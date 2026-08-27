<?php
/**
 * NICE CXone (inContact) chat widget — the client's existing chat provider,
 * embed per Asana task 1215191045199661. The loader snippet is theirs
 * verbatim except sitelocation: their old CMS filled a per-page merge tag
 * ("{FAQ}"); here it reports the plain page name (post title, archive term,
 * front page's site-facing name) so agents see "FAQ", not
 * "FAQ - St. Joseph's Indian School". Disable via
 * add_filter( 'stjo_enable_chatbot', '__return_false' ).
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stjo_chatbot_embed() {
	if ( ! apply_filters( 'stjo_enable_chatbot', true ) ) {
		return;
	}
	if ( is_front_page() ) {
		$stjo_location = 'Home';
	} elseif ( is_singular() ) {
		$stjo_location = get_the_title();
	} elseif ( is_archive() ) {
		$stjo_location = get_the_archive_title();
	} elseif ( is_search() ) {
		$stjo_location = 'Search';
	} elseif ( is_404() ) {
		$stjo_location = '404';
	} else {
		$stjo_location = wp_get_document_title();
	}
	$sitelocation = wp_json_encode( wp_strip_all_tags( (string) $stjo_location ) );
	var_dump($sitelocation);
	?>
	<!-- NICE CXone chat -->
	<script>
	(function(n,u){
	window.CXoneDfo=n,
	window[n]=window[n]||function(){(window[n].q=window[n].q||[]).push(arguments)},window[n].u=u,
	e=document.createElement("script"),e.type="module",e.src=u+"?"+Math.round(Date.now()/1e3/3600),
	document.head.appendChild(e)
	})('cxone','https://web-modules-de-na1.niceincontact.com/loader/1/loader.js');

	cxone('init', '5542');
	cxone('guide','init');
	cxone('guide', 'setButtonSize', '100px');
	cxone('guide', 'setOffsetY', '4em');
	cxone('chat','setCustomerCustomField', 'sitelocation', <?php echo $sitelocation; // phpcs:ignore WordPress.Security.EscapeOutput -- wp_json_encode output. ?>);
	</script>
	<?php
}
add_action( 'wp_footer', 'stjo_chatbot_embed', 20 );
