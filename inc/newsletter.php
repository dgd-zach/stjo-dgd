<?php
/**
 * Footer newsletter — server-side relay into Luminate Online.
 *
 * The footer form posts to admin-post.php and this relays it into LO as a
 * real survey 3720 response (client-mode API: getLoginUrl issues a session +
 * auth token, submitSurvey rides them). That keeps the client's survey
 * reporting intact, registers the constituent, and opts them into email.
 *
 * Only the API key is needed, in wp-config.php, never in the repo:
 *
 *   define( 'STJO_LO_API_KEY', '...' );
 *
 * Without it the form falls back to posting directly at LO (degrades, not
 * breaks). Server-mode (login_name/login_password) was tried first and is
 * IP-gated on this instance; client mode needs no whitelist BUT is subject
 * to the survey's own protections — it works because reCAPTCHA is turned
 * OFF on survey 3720. If someone re-enables it there, submissions fail with
 * questionInError 5241 in the logged response body. Also learned the hard
 * way: LO rejects @example.com addresses behind the generic error 1726.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is the LO API key defined?
 */
function stjo_newsletter_api_ready() {
	return defined( 'STJO_LO_API_KEY' );
}

/**
 * Handle the footer form POST (logged-in and anonymous visitors alike).
 */
/**
 * Finish the request: JSON for the AJAX path (assets/js/newsletter.js), a
 * redirect back to the referring page for the no-JS fallback, where the
 * footer renders the same message from the query arg.
 */
function stjo_newsletter_finish( $ok ) {
	if ( ! empty( $_POST['stjo_ajax'] ) ) {
		wp_send_json( array( 'ok' => (bool) $ok ) );
	}
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	wp_safe_redirect( add_query_arg( 'newsletter', $ok ? 'thanks' : 'error', $back ) . '#footer-newsletter' );
	exit;
}

function stjo_newsletter_submit() {
	// Honeypot: humans leave it empty. Bots that fill it get a success
	// response and nothing else, so they learn nothing from it.
	if ( ! empty( $_POST['denySubmit'] ) ) {
		stjo_newsletter_finish( true );
	}

	$email = isset( $_POST['cons_email'] ) ? sanitize_email( wp_unslash( $_POST['cons_email'] ) ) : '';
	$first = isset( $_POST['cons_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cons_first_name'] ) ) : '';
	$last  = isset( $_POST['cons_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cons_last_name'] ) ) : '';
	if ( ! is_email( $email ) || ! stjo_newsletter_api_ready() ) {
		stjo_newsletter_finish( false );
	}

	// Soft rate limit so the public form cannot be used to hammer the
	// client's LO instance: a handful of submissions per address per hour.
	$bucket = 'stjo_nl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$count  = (int) get_transient( $bucket );
	if ( $count >= 5 ) {
		stjo_newsletter_finish( false );
	}
	set_transient( $bucket, $count + 1, HOUR_IN_SECONDS );

	$api_base = (string) stjo_config_get( 'footer.newsletter.api_base', 'https://give.stjo.org/site' );

	// Step 1: a client session + auth token. getLoginUrl needs only the key.
	$login = wp_remote_post( $api_base . '/CRConsAPI', array(
		'timeout' => 10,
		'body'    => array(
			'method'          => 'getLoginUrl',
			'api_key'         => STJO_LO_API_KEY,
			'v'               => '1.0',
			'response_format' => 'json',
		),
	) );
	$auth  = '';
	$jsess = '';
	if ( ! is_wp_error( $login ) ) {
		$data  = json_decode( wp_remote_retrieve_body( $login ), true );
		$auth  = (string) ( $data['getLoginUrlResponse']['token'] ?? '' );
		$jsess = (string) ( $data['getLoginUrlResponse']['JSESSIONID'] ?? '' );
	}
	if ( '' === $auth ) {
		error_log( 'stjo newsletter: getLoginUrl failed: ' . ( is_wp_error( $login ) ? $login->get_error_message() : wp_remote_retrieve_body( $login ) ) ); // phpcs:ignore
		stjo_newsletter_finish( false );
	}

	// Step 2: submit as a survey response on that session. The jsessionid has
	// to ride the URL and the cookie or LO drops the auth token's session.
	$body = array(
		'method'              => 'submitSurvey',
		'api_key'             => STJO_LO_API_KEY,
		'v'                   => '1.0',
		'response_format'     => 'json',
		'auth'                => $auth,
		'survey_id'           => (string) stjo_config_get( 'footer.newsletter.survey_id', '' ),
		'cons_info_component' => 't',
		'cons_email'          => $email,
	);
	if ( '' !== $first ) {
		$body['cons_first_name'] = $first;
	}
	if ( '' !== $last ) {
		$body['cons_last_name'] = $last;
	}
	if ( stjo_config_get( 'footer.newsletter.s_src' ) ) {
		$body['source'] = (string) stjo_config_get( 'footer.newsletter.s_src' );
	}

	$response = wp_remote_post( $api_base . '/CRSurveyAPI;jsessionid=' . rawurlencode( $jsess ), array(
		'timeout' => 10,
		'headers' => array( 'Cookie' => 'JSESSIONID=' . $jsess ),
		'body'    => $body,
	) );

	$ok = false;
	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		$ok   = isset( $data['submitSurveyResponse']['success'] )
			&& filter_var( $data['submitSurveyResponse']['success'], FILTER_VALIDATE_BOOLEAN );
		if ( ! $ok ) {
			// Surfaced in the PHP error log only — the visitor just sees the
			// error notice. Body includes LO's errorField/errorMessage details.
			error_log( 'stjo newsletter: LO rejected submitSurvey: ' . wp_remote_retrieve_body( $response ) ); // phpcs:ignore
		}
	} elseif ( is_wp_error( $response ) ) {
		error_log( 'stjo newsletter: relay failed: ' . $response->get_error_message() ); // phpcs:ignore
	}

	stjo_newsletter_finish( $ok );
}
add_action( 'admin_post_stjo_newsletter', 'stjo_newsletter_submit' );
add_action( 'admin_post_nopriv_stjo_newsletter', 'stjo_newsletter_submit' );
