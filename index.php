<?php
/**
 * Fallback template — used for the blog index and any unmatched query.
 *
 * @package stjo
 */

get_header();
?>
<div class="entry-content">
	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
		<div class="post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'post-list__item' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="post-list__thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
					<?php endif; ?>
					<h2 class="post-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="post-list__excerpt"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'stjo' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
