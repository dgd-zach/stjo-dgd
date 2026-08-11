<?php
/**
 * general-content-section — the "General Content Section" reference page
 * (Figma 5531:5016). Composed from the curated inc/patterns/* sections in the
 * design's order. Accordions use the Yoast FAQ block (is-style-accordion),
 * per spec, instead of the native-details accordion pattern. The header,
 * breadcrumbs, generosity pre-footer, and footer are template parts and are
 * not part of this content.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$P    = get_template_directory() . '/inc/patterns/';
$ladv = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
$sp   = function ( $size ) {
	return '<!-- wp:spacer {"height":"var:preset|spacing|' . $size . '"} --><div style="height:var(--wp--preset--spacing--' . $size . ')" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer -->';
};

// A Yoast FAQ block styled as an accordion (5 questions). wp_json_encode keeps
// the block attributes valid; the saved schema-faq markup renders on the front
// end and faq-accordion.js upgrades it to a collapsible accordion.
$faq = function ( $prefix ) use ( $ladv ) {
	$q         = 'Lorem ipsum dolor sit amet?';
	$questions = array();
	$sections  = '';
	for ( $i = 1; $i <= 5; $i++ ) {
		$id          = 'faq-' . $prefix . '-' . $i;
		$questions[] = array(
			'id'           => $id,
			'question'     => $q,
			'answer'       => $ladv,
			'jsonQuestion' => $q,
			'jsonAnswer'   => $ladv,
			'images'       => array(),
		);
		$sections .= '<div class="schema-faq-section" id="' . $id . '"><strong class="schema-faq-question">' . $q . '</strong> <p class="schema-faq-answer">' . $ladv . '</p> </div>';
	}
	$attrs = wp_json_encode(
		array(
			'questions' => $questions,
			'className' => 'is-style-accordion',
		)
	);
	return '<!-- wp:yoast/faq-block ' . $attrs . " -->\n"
		. '<div class="schema-faq wp-block-yoast-faq-block is-style-accordion">' . $sections . "</div>\n"
		. '<!-- /wp:yoast/faq-block -->';
};

// ── Hero + intro ─────────────────────────────────────────────────────────
include $P . 'page-title-band.php';       // eyebrow + "General Content Section"
include $P . 'divider-zigzag.php';
include $P . 'two-column-intro.php';       // Section Intro Title + text
include $P . 'share-bar.php';
include $P . 'heading-video.php';          // Option Video Title + video

// ── FAQ (Yoast accordions) ─────────────────────────────────────────────────
include $P . 'eyebrow-heading-band.php';   // Eyebrow + "Frequently Asked Questions"

echo '<!-- wp:group {"metadata":{"name":"FAQ Accordion"},"layout":{"type":"constrained","contentSize":"600px"}} -->'
	. '<div class="wp-block-group">' . $sp( 'medium' ) . $faq( 'gcs-a' ) . $sp( 'medium' ) . '</div>'
	. "<!-- /wp:group -->\n";

// Two-column: text beside a Yoast FAQ accordion.
echo '<!-- wp:group {"metadata":{"name":"Two-Column Text + FAQ"},"layout":{"type":"constrained"}} -->'
	. '<div class="wp-block-group">' . $sp( 'medium' )
	. '<!-- wp:columns --><div class="wp-block-columns">'
	. '<!-- wp:column --><div class="wp-block-column">'
	. '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Questions + Answers</h2><!-- /wp:heading -->'
	. '<!-- wp:paragraph --><p>' . $ladv . '</p><!-- /wp:paragraph -->'
	. '</div><!-- /wp:column -->'
	. '<!-- wp:column --><div class="wp-block-column">' . $faq( 'gcs-b' ) . '</div><!-- /wp:column -->'
	. '</div><!-- /wp:columns -->'
	. $sp( 'medium' ) . '</div><!-- /wp:group -->' . "\n";

// ── Body-copy variations ───────────────────────────────────────────────────
include $P . 'two-column-text-list.php';   // Text + Bullet List
include $P . 'heading-centered-text.php';  // + Text

include $P . 'title-text.php';                   // Title + Text
include $P . 'title-two-column-text.php';       // Title + 2 Column Text
include $P . 'two-column-subsection-reversed.php'; // Subsection: text left, image right
include $P . 'two-column-subsection.php';        // Subsection: image left, text right
include $P . 'heading-two-column-text.php';      // Title + Two Column Text
include $P . 'heading-three-column-text.php';    // Title + Three Column Text

// ── Quote, CTA, media, related ─────────────────────────────────────────────
include $P . 'pull-quote.php';
include $P . 'band-cover-cta.php';               // DreamMaker CTA
include $P . 'full-width-image.php';
include $P . 'related-content-cards-manual.php'; // Keep Reading + 4 cards
// (No trailing zigzag — the pre-footer template part now carries it.)
