<?php
/**
 * Lightbox card. The lightbox is a prescribed template built from the
 * block's own fields (hero image, heading, content, optional link) and
 * ships in an inert inline <template>; view.js clones it into a shared
 * modal <dialog> when the card is activated. The card's excerpt is an
 * abbreviated version of the full lightbox content. In the editor canvas
 * the trigger renders as a non-interactive span (preview lives behind the
 * sidebar's Preview Lightbox button instead).
 *
 * @package stjo
 */

$stjo_lb_title   = trim( $attributes['title'] ?? '' );
$stjo_lb_content = trim( $attributes['content'] ?? '' );

// Lightbox body: a chosen page wins over the Content field. Sourcing from a
// page lets editors maintain long-form modal content (headings, images,
// revisions) like any other page instead of a plain-text sidebar box.
// Published pages only — this prints into public markup, so a draft or
// private page must never leak through a modal.
$stjo_lb_page_id = absint( $attributes['contentPageId'] ?? 0 );
$stjo_lb_page    = $stjo_lb_page_id ? get_post( $stjo_lb_page_id ) : null;
if ( ! $stjo_lb_page || 'publish' !== $stjo_lb_page->post_status || 'page' !== $stjo_lb_page->post_type ) {
	$stjo_lb_page = null;
}
$stjo_lb_body_html = '';
if ( $stjo_lb_page ) {
	$stjo_lb_body_html = apply_filters( 'the_content', $stjo_lb_page->post_content );
} elseif ( $stjo_lb_content ) {
	$stjo_lb_body_html = wpautop( esc_html( $stjo_lb_content ) );
}

$stjo_lb_excerpt = $stjo_lb_content ? wp_trim_words( $stjo_lb_content, 20, '…' ) : '';
if ( '' === $stjo_lb_excerpt && $stjo_lb_page ) {
	$stjo_lb_excerpt = wp_trim_words( wp_strip_all_tags( $stjo_lb_body_html ), 20, '…' );
}
$stjo_lb_label   = trim( $attributes['linkLabel'] ?? '' );
$stjo_lb_label   = '' !== $stjo_lb_label ? $stjo_lb_label : __( 'Explore', 'stjo' );
$stjo_lb_is_text  = false !== strpos( $attributes['className'] ?? '', 'is-style-text' );
// Arrow Link style: the link label is the whole block — no card chrome, no
// image, no heading, no excerpt. For dropping a lightbox trigger into running
// content (media-text columns, paragraphs) where a card would be too heavy.
$stjo_lb_is_arrow = false !== strpos( $attributes['className'] ?? '', 'is-style-arrow-link' );
$stjo_lb_media   = ! empty( $attributes['mediaUrl'] ) ? $attributes['mediaUrl'] : '';
$stjo_lb_media_alt = (string) ( $attributes['mediaAlt'] ?? '' );

// Lightbox hero: the block's own image wins; otherwise a content page's
// featured image steps in, so a purpose-made lightbox page carries its own
// hero without the block needing anything set.
$stjo_lb_hero     = $stjo_lb_media;
$stjo_lb_hero_alt = $stjo_lb_media_alt;
if ( ! $stjo_lb_hero && $stjo_lb_page && has_post_thumbnail( $stjo_lb_page ) ) {
	$stjo_lb_thumb_id = get_post_thumbnail_id( $stjo_lb_page );
	$stjo_lb_hero     = (string) wp_get_attachment_image_url( $stjo_lb_thumb_id, 'large' );
	$stjo_lb_hero_alt = (string) get_post_meta( $stjo_lb_thumb_id, '_wp_attachment_image_alt', true );
}
$stjo_lb_link    = trim( $attributes['linkUrl'] ?? '' );
// A bare "#" is a placeholder, not a destination (the pattern demos shipped
// with it and pages built from them carry it) — treat it as no link so the
// lightbox CTA hides instead of rendering a dead button. QA: Youth Programs.
if ( '#' === $stjo_lb_link ) {
	$stjo_lb_link = '';
}
$stjo_lb_new_tab = ! empty( $attributes['linkNewTab'] );

// SSR previews come through the REST block renderer; that plus wp-admin
// covers every editor context.
$stjo_lb_in_editor = is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );

$stjo_lb_arrow = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$stjo_lb_wrapper = get_block_wrapper_attributes( array( 'class' => 'stjo-lightbox-card' ) );
?>
<article <?php echo $stjo_lb_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput -- core-built attribute string. ?>>
	<?php if ( $stjo_lb_media && ! $stjo_lb_is_text && ! $stjo_lb_is_arrow ) : ?>
		<figure class="stjo-lightbox-card__media">
			<img src="<?php echo esc_url( $stjo_lb_media ); ?>" alt="<?php echo esc_attr( $attributes['mediaAlt'] ?? '' ); ?>" loading="lazy" />
		</figure>
	<?php endif; ?>
	<div class="stjo-lightbox-card__body">
		<?php if ( $stjo_lb_title && ! $stjo_lb_is_arrow ) : ?>
			<h3 class="stjo-lightbox-card__title"><?php echo esc_html( $stjo_lb_title ); ?></h3>
		<?php endif; ?>
		<?php if ( $stjo_lb_excerpt && ! $stjo_lb_is_arrow ) : ?>
			<p class="stjo-lightbox-card__text"><?php echo esc_html( $stjo_lb_excerpt ); ?></p>
		<?php endif; ?>
		<?php if ( $stjo_lb_in_editor ) : ?>
			<span class="stjo-lightbox-card__link" aria-hidden="true"><?php echo esc_html( $stjo_lb_label ) . $stjo_lb_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			<?php if ( ! $stjo_lb_body_html ) : ?>
				<p class="stjo-lightbox-card__notice"><?php esc_html_e( 'Add lightbox content, or choose a content page, in the block settings to power this link.', 'stjo' ); ?></p>
			<?php elseif ( $stjo_lb_page ) : ?>
				<p class="stjo-lightbox-card__notice"><?php echo esc_html( sprintf( /* translators: %s: page title */ __( 'Lightbox content comes from the page “%s”.', 'stjo' ), get_the_title( $stjo_lb_page ) ) ); ?></p>
			<?php endif; ?>
		<?php elseif ( $stjo_lb_body_html ) : ?>
			<button type="button" class="stjo-lightbox-card__link" data-stjo-lightbox>
				<?php echo esc_html( $stjo_lb_label ) . $stjo_lb_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
			</button>
			<template data-stjo-lightbox-template>
				<?php if ( $stjo_lb_hero ) : ?>
					<figure class="stjo-lightbox__hero">
						<img src="<?php echo esc_url( $stjo_lb_hero ); ?>" alt="<?php echo esc_attr( $stjo_lb_hero_alt ); ?>" />
					</figure>
				<?php endif; ?>
				<div class="stjo-lightbox__inner">
					<?php if ( $stjo_lb_title ) : ?>
						<h3 class="stjo-lightbox__title"><?php echo esc_html( $stjo_lb_title ); ?></h3>
					<?php endif; ?>
					<div class="stjo-lightbox__body">
						<?php echo $stjo_lb_body_html; // phpcs:ignore WordPress.Security.EscapeOutput -- page path is the_content (post content, filtered like any page render); field path was esc_html'd + wpautop'd above. ?>
					</div>
					<div class="stjo-lightbox__actions">
						<?php if ( $stjo_lb_link ) : ?>
							<a class="stjo-lightbox__cta" href="<?php echo esc_url( $stjo_lb_link ); ?>"<?php echo $stjo_lb_new_tab ? ' target="_blank" rel="noopener"' : ''; ?>>
								<?php echo esc_html( trim( $attributes['linkText'] ?? '' ) ?: __( 'Learn More', 'stjo' ) ); ?>
								<?php if ( $stjo_lb_new_tab ) : ?>
									<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'stjo' ); ?></span>
								<?php endif; ?>
							</a>
						<?php endif; ?>
						<button type="button" class="stjo-lightbox__dismiss" data-stjo-lightbox-close><?php esc_html_e( 'Close', 'stjo' ); ?></button>
					</div>
				</div>
			</template>
		<?php endif; ?>
	</div>
</article>
