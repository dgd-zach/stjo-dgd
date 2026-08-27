<?php
/**
 * Footer newsletter — server-side relay into Luminate Online.
 *
 * Survey 3720 enforces reCAPTCHA v3 on its public form endpoint, so a
 * cross-domain browser POST cannot succeed. The authenticated survey API
 * (CRSurveyAPI submitSurvey) is captcha-free by design, so the footer form
 * posts to admin-post.php and this relays it: the response is recorded as a
 * survey 3720 submission, which keeps the client's LO survey reporting
 * intact, registers the constituent, and opts them into email.
 *
 * Credentials live in wp-config.php, never in the repo or theme-config:
 *
 *   define( 'STJO_LO_API_KEY',  '...' );
 *   define( 'STJO_LO_API_USER', '...' );  // an LO API user's login_name
 *   define( 'STJO_LO_API_PASS', '...' );
 *
 * With any of them missing, the form falls back to posting directly to the
 * LO action from theme-config (the pre-relay behavior), so an environment
 * without credentials degrades instead of breaking.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Are all three LO API constants defined?
 */
function stjo_newsletter_api_ready() {
	return defined( 'STJO_LO_API_KEY' ) && defined( 'STJO_LO_API_USER' ) && defined( 'STJO_LO_API_PASS' );
}

/**
 * Handle the footer form POST (logged-in and anonymous visitors alike).
 */
function stjo_newsletter_submit() {
	$thanks = home_url( (string) stjo_config_get( 'footer.newsletter.next_url', '/' ) );
	$back   = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back   = add_query_arg( 'newsletter', 'error', $back ) . '#footer-newsletter';

	// Honeypot: humans leave it empty. Bots that fill it get the thank-you
	// page and nothing else, so they learn nothing from the response.
	if ( ! empty( $_POST['denySubmit'] ) ) {
		wp_safe_redirect( $thanks );
		exit;
	}

	$email = isset( $_POST['cons_email'] ) ? sanitize_email( wp_unslash( $_POST['cons_email'] ) ) : '';
	$first = isset( $_POST['cons_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cons_first_name'] ) ) : '';
	$last  = isset( $_POST['cons_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cons_last_name'] ) ) : '';
	if ( ! is_email( $email ) || ! stjo_newsletter_api_ready() ) {
		wp_safe_redirect( $back );
		exit;
	}

	// Soft rate limit so the public form cannot be used to hammer the
	// client's LO instance: a handful of submissions per address per hour.
	$bucket = 'stjo_nl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$count  = (int) get_transient( $bucket );
	if ( $count >= 5 ) {
		wp_safe_redirect( $back );
		exit;
	}
	set_transient( $bucket, $count + 1, HOUR_IN_SECONDS );

	$endpoint = (string) stjo_config_get( 'footer.newsletter.api_endpoint', 'https://give.stjo.org/site/CRSurveyAPI' );
	$body     = array(
		'method'          => 'submitSurvey',
		'api_key'         => STJO_LO_API_KEY,
		'v'               => '1.0',
		'response_format' => 'json',
		'login_name'      => STJO_LO_API_USER,
		'login_password'  => STJO_LO_API_PASS,
		'survey_id'       => (string) stjo_config_get( 'footer.newsletter.survey_id', '' ),
		'cons_email'      => $email,
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

	$response = wp_remote_post( $endpoint, array(
		'timeout' => 10,
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

	wp_safe_redirect( $ok ? $thanks : $back );
	exit;
}
add_action( 'admin_post_stjo_newsletter', 'stjo_newsletter_submit' );
add_action( 'admin_post_nopriv_stjo_newsletter', 'stjo_newsletter_submit' );
