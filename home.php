<?php
/**
 * Blog archive (the posts page, /blog/).
 *
 * Blue title hero, a category pill filter bar, then the two-up card grid and
 * carousel-style pagination. Pills/pagination are real links; blog-filter.js
 * upgrades them to an in-page fetch + swap of #stjo-blog-results. See
 * inc/blog.php.
 *
 * @package stjo
 */

get_header();

$stjo_active   = stjo_blog_active_category();
$stjo_cats     = stjo_blog_categories();
$stjo_blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
// Keep the active category on the pagination links.
$stjo_page_args = $stjo_active ? array( 'add_args' => array( 'category' => $stjo_active ) ) : array();

stjo_page_hero( (int) get_option( 'page_for_posts' ) );
?>
<div class="entry-content">
	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>

	<nav class="stjo-blog-filterbar" aria-label="<?php esc_attr_e( 'Filter posts by category', 'stjo' ); ?>" data-blog-filter>
		<div class="stjo-pills">
			<a href="<?php echo esc_url( $stjo_blog_url ); ?>" data-category=""<?php echo '' === $stjo_active ? ' aria-current="true"' : ''; ?>><?php esc_html_e( 'All', 'stjo' ); ?></a>
			<?php foreach ( $stjo_cats as $stjo_cat ) : ?>
				<a
					href="<?php echo esc_url( add_query_arg( 'category', $stjo_cat->slug, $stjo_blog_url ) ); ?>"
					data-category="<?php echo esc_attr( $stjo_cat->slug ); ?>"
					<?php echo $stjo_active === $stjo_cat->slug ? ' aria-current="true"' : ''; ?>
				><?php echo esc_html( $stjo_cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
	</nav>
	<p class="screen-reader-text" aria-live="polite" data-blog-status></p>

	<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>

	<div id="stjo-blog-results" data-blog-results tabindex="-1">
		<?php if ( have_posts() ) : ?>
			<div class="wp-block-columns stjo-story-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					stjo_post_card( get_post() );
				endwhile;
				?>
			</div>
			<?php stjo_posts_pagination( $stjo_page_args ); ?>
		<?php else : ?>
			<p class="stjo-blog-empty"><?php esc_html_e( 'No posts found in this category yet.', 'stjo' ); ?></p>
		<?php endif; ?>
	</div>

	<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
<?php
get_footer();
