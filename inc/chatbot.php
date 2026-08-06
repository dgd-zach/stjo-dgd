<?php
/**
 * NICE CXone (inContact) chat widget — the client's existing chat provider,
 * embed per Asana task 1215191045199661. The loader snippet is theirs
 * verbatim except sitelocation: their old CMS filled a per-page merge tag
 * ("{FAQ}"); here it reports the current document title. Disable via
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
	$sitelocation = wp_json_encode( wp_get_document_title() );
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
