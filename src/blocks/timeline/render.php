<?php
/**
 * History timeline — renders every published timeline-event grouped by decade,
 * sorted (year, menu_order, date). Server output is the no-JS fallback: all
 * cards stacked and fully readable, chips hidden, text unclamped. view.js
 * upgrades each decade into a slider with year-chip tabs and Read More
 * expanders.
 *
 * @package stjo
 */

$stjo_tl_posts = get_posts( array(
	'post_type'      => 'timeline-event',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	'order'          => 'ASC',
) );

$stjo_tl_events  = array();
$stjo_tl_skipped = 0;
foreach ( $stjo_tl_posts as $stjo_tl_post ) {
	$stjo_tl_year = absint( get_post_meta( $stjo_tl_post->ID, 'stjo_timeline_year', true ) );
	if ( ! $stjo_tl_year ) {
		$stjo_tl_skipped++;
		continue;
	}
	$stjo_tl_events[] = array(
		'post' => $stjo_tl_post,
		'year' => $stjo_tl_year,
	);
}

usort( $stjo_tl_events, function ( $a, $b ) {
	if ( $a['year'] !== $b['year'] ) {
		return $a['year'] <=> $b['year'];
	}
	if ( $a['post']->menu_order !== $b['post']->menu_order ) {
		return $a['post']->menu_order <=> $b['post']->menu_order;
	}
	return strcmp( $a['post']->post_date, $b['post']->post_date );
} );

$stjo_tl_decades = array();
foreach ( $stjo_tl_events as $stjo_tl_event ) {
	$stjo_tl_decade = (string) ( (int) floor( $stjo_tl_event['year'] / 10 ) * 10 ) . 's';
	$stjo_tl_decades[ $stjo_tl_decade ][] = $stjo_tl_event;
}

$stjo_tl_is_editor_preview = defined( 'REST_REQUEST' ) && REST_REQUEST;

if ( ! $stjo_tl_decades ) {
	if ( $stjo_tl_is_editor_preview ) {
		echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'stjo-timeline stjo-timeline--empty' ) ) . '><p>' // phpcs:ignore WordPress.Security.EscapeOutput
			. esc_html__( 'History Timeline: no Timeline events with a Year yet. Add them under Timeline in the dashboard.', 'stjo' ) . '</p></div>';
	}
	return;
}

$stjo_tl_uid = wp_unique_id( 'stjo-tl-' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'stjo-timeline' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<?php if ( $stjo_tl_is_editor_preview && $stjo_tl_skipped ) : ?>
		<p class="stjo-timeline__editor-note">
			<?php
			/* translators: %d: number of events missing a year */
			echo esc_html( sprintf( _n( '%d timeline event is missing a Year and is not shown.', '%d timeline events are missing a Year and are not shown.', $stjo_tl_skipped, 'stjo' ), $stjo_tl_skipped ) );
			?>
		</p>
	<?php endif; ?>
	<div class="stjo-timeline__inner">
		<?php foreach ( $stjo_tl_decades as $stjo_tl_decade => $stjo_tl_group ) : ?>
			<?php
			// role="group" (not a landmark) keeps 11 decades from flooding the
			// screen-reader rotor; the giant watermark numeral is decorative,
			// so the decade name is carried by the group label and year pills.
			?>
			<section class="stjo-timeline__decade" role="group" aria-label="<?php echo esc_attr( $stjo_tl_decade ); ?>">
				<span class="stjo-timeline__node" aria-hidden="true"></span>
				<span class="stjo-timeline__decade-label" aria-hidden="true"><?php echo esc_html( $stjo_tl_decade ); ?></span>
				<div class="stjo-timeline__cards">
					<div class="stjo-timeline__viewport">
						<div class="stjo-timeline__track">
							<?php foreach ( $stjo_tl_group as $stjo_tl_i => $stjo_tl_event ) : ?>
								<?php
								$stjo_tl_post    = $stjo_tl_event['post'];
								$stjo_tl_card_id = $stjo_tl_uid . '-card-' . $stjo_tl_post->ID;
								$stjo_tl_text_id = $stjo_tl_uid . '-text-' . $stjo_tl_post->ID;
								$stjo_tl_layout  = stjo_timeline_sanitize_layout( get_post_meta( $stjo_tl_post->ID, 'stjo_timeline_image_layout', true ) );
								$stjo_tl_focus   = stjo_timeline_sanitize_focus( (string) get_post_meta( $stjo_tl_post->ID, 'stjo_timeline_image_focus', true ) );
								$stjo_tl_img_att = array( 'class' => 'stjo-timeline-card__img' );
								if ( ! in_array( $stjo_tl_focus, array( 'center center', '50% 50%' ), true ) ) {
									$stjo_tl_img_att['style'] = 'object-position:' . $stjo_tl_focus;
								}
								$stjo_tl_image   = get_the_post_thumbnail( $stjo_tl_post, 'large', $stjo_tl_img_att );
								$stjo_tl_classes = 'stjo-timeline-card';
								$stjo_tl_classes .= $stjo_tl_image ? ' stjo-timeline-card--' . $stjo_tl_layout : ' stjo-timeline-card--bare';
								?>
								<article class="<?php echo esc_attr( $stjo_tl_classes ); ?>" id="<?php echo esc_attr( $stjo_tl_card_id ); ?>" data-reveal data-index="<?php echo esc_attr( $stjo_tl_i ); ?>">
									<?php
									// Editor preview only: jump straight to this event's edit screen.
									if ( $stjo_tl_is_editor_preview ) :
										$stjo_tl_edit_link = get_edit_post_link( $stjo_tl_post->ID, 'raw' );
										if ( $stjo_tl_edit_link ) :
											?>
											<a class="stjo-timeline-card__edit" href="<?php echo esc_url( $stjo_tl_edit_link ); ?>" target="_blank" rel="noopener">
												<?php echo esc_html( sprintf( /* translators: %s: event year */ __( 'Edit %s', 'stjo' ), $stjo_tl_event['year'] ) ); ?> ↗
											</a>
											<?php
										endif;
									endif;
									?>
									<?php if ( $stjo_tl_image ) : ?>
										<figure class="stjo-timeline-card__media"><?php echo $stjo_tl_image; // phpcs:ignore WordPress.Security.EscapeOutput ?></figure>
									<?php endif; ?>
									<div class="stjo-timeline-card__body">
										<span class="stjo-timeline-card__year"><?php echo esc_html( $stjo_tl_event['year'] ); ?></span>
										<h3 class="stjo-timeline-card__title"><?php echo esc_html( get_the_title( $stjo_tl_post ) ); ?></h3>
										<div class="stjo-timeline-card__text" id="<?php echo esc_attr( $stjo_tl_text_id ); ?>">
											<?php echo apply_filters( 'the_content', $stjo_tl_post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										</div>
										<button type="button" class="stjo-timeline-card__more" aria-expanded="false" aria-controls="<?php echo esc_attr( $stjo_tl_text_id ); ?>"
											data-label-more="<?php esc_attr_e( 'Read More', 'stjo' ); ?>" data-label-less="<?php esc_attr_e( 'Show Less', 'stjo' ); ?>">
											<span class="stjo-timeline-card__more-label"><?php esc_html_e( 'Read More', 'stjo' ); ?></span>
											<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: event title */ __( 'about %s', 'stjo' ), get_the_title( $stjo_tl_post ) ) ); ?></span>
											<svg class="stjo-timeline-card__more-icon" aria-hidden="true" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M4 2l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
										</button>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
					<?php if ( count( $stjo_tl_group ) > 1 ) : ?>
						<div class="stjo-timeline__chips" data-decade-label="<?php echo esc_attr( $stjo_tl_decade ); ?>">
							<?php foreach ( $stjo_tl_group as $stjo_tl_i => $stjo_tl_event ) : ?>
								<button type="button"
									class="stjo-timeline__chip<?php echo 0 === $stjo_tl_i ? ' is-active' : ''; ?>"
									id="<?php echo esc_attr( $stjo_tl_uid . '-tab-' . $stjo_tl_event['post']->ID ); ?>"
									data-index="<?php echo esc_attr( $stjo_tl_i ); ?>"
									data-card="<?php echo esc_attr( $stjo_tl_uid . '-card-' . $stjo_tl_event['post']->ID ); ?>">
									<?php echo esc_html( $stjo_tl_event['year'] ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endforeach; ?>
		<span class="stjo-timeline__end" data-reveal aria-hidden="true"></span>
	</div>
</div>
