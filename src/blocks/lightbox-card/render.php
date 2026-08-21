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
$stjo_lb_excerpt = $stjo_lb_content ? wp_trim_words( $stjo_lb_content, 20, '…' ) : '';
$stjo_lb_label   = trim( $attributes['linkLabel'] ?? '' );
$stjo_lb_label   = '' !== $stjo_lb_label ? $stjo_lb_label : __( 'Explore', 'stjo' );
$stjo_lb_is_text = false !== strpos( $attributes['className'] ?? '', 'is-style-text' );
$stjo_lb_media   = ! empty( $attributes['mediaUrl'] ) ? $attributes['mediaUrl'] : '';
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
	<?php if ( $stjo_lb_media && ! $stjo_lb_is_text ) : ?>
		<figure class="stjo-lightbox-card__media">
			<img src="<?php echo esc_url( $stjo_lb_media ); ?>" alt="<?php echo esc_attr( $attributes['mediaAlt'] ?? '' ); ?>" loading="lazy" />
		</figure>
	<?php endif; ?>
	<div class="stjo-lightbox-card__body">
		<?php if ( $stjo_lb_title ) : ?>
			<h3 class="stjo-lightbox-card__title"><?php echo esc_html( $stjo_lb_title ); ?></h3>
		<?php endif; ?>
		<?php if ( $stjo_lb_excerpt ) : ?>
			<p class="stjo-lightbox-card__text"><?php echo esc_html( $stjo_lb_excerpt ); ?></p>
		<?php endif; ?>
		<?php if ( $stjo_lb_in_editor ) : ?>
			<span class="stjo-lightbox-card__link" aria-hidden="true"><?php echo esc_html( $stjo_lb_label ) . $stjo_lb_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			<?php if ( ! $stjo_lb_content ) : ?>
				<p class="stjo-lightbox-card__notice"><?php esc_html_e( 'Add lightbox content in the block settings to power this card.', 'stjo' ); ?></p>
			<?php endif; ?>
		<?php elseif ( $stjo_lb_content ) : ?>
			<button type="button" class="stjo-lightbox-card__link" data-stjo-lightbox>
				<?php echo esc_html( $stjo_lb_label ) . $stjo_lb_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
			</button>
			<template data-stjo-lightbox-template>
				<?php if ( $stjo_lb_media ) : ?>
					<figure class="stjo-lightbox__hero">
						<img src="<?php echo esc_url( $stjo_lb_media ); ?>" alt="<?php echo esc_attr( $attributes['mediaAlt'] ?? '' ); ?>" />
					</figure>
				<?php endif; ?>
				<div class="stjo-lightbox__inner">
					<?php if ( $stjo_lb_title ) : ?>
						<h2 class="stjo-lightbox__title"><?php echo esc_html( $stjo_lb_title ); ?></h2>
					<?php endif; ?>
					<div class="stjo-lightbox__body">
						<?php echo wpautop( esc_html( $stjo_lb_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped then paragraph-wrapped. ?>
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
