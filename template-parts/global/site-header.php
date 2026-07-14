<?php
/**
 * Global site header: accent bar + brand + primary navigation + action CTAs.
 * Client content comes from theme-config.json (see inc/theme-config.php).
 *
 * @package stjo
 */
?>
<div class="site-accent-bar" aria-hidden="true"></div>

<header class="site-header" role="banner">
	<div class="site-header__inner">
		<div class="site-header__brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				printf(
					'<a class="site-header__title" href="%1$s">%2$s</a>',
					esc_url( home_url( '/' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
			}
			?>
		</div>

		<nav class="site-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'stjo' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'primary-menu',
				'depth'          => 2,
				'fallback_cb'    => 'stjo_primary_menu_fallback',
			) );
			?>
		</nav>

		<div class="site-header__actions">
			<?php if ( stjo_config_get( 'header.show_search', true ) ) : ?>
				<button class="site-header__search" type="button" aria-label="<?php esc_attr_e( 'Search', 'stjo' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
						<line x1="16.5" y1="16.5" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</button>
			<?php endif; ?>
			<?php
			foreach ( (array) stjo_config_get( 'header.ctas', array() ) as $cta ) {
				if ( empty( $cta['label'] ) || empty( $cta['url'] ) ) {
					continue;
				}
				$style = isset( $cta['style'] ) ? $cta['style'] : 'primary';
				printf(
					'<a class="btn btn--%1$s" href="%2$s">%3$s</a>',
					esc_attr( $style ),
					esc_url( 0 === strpos( $cta['url'], 'http' ) ? $cta['url'] : home_url( $cta['url'] ) ),
					esc_html( $cta['label'] )
				);
			}
			?>
		</div>
	</div>
</header>
<?php
/**
 * Fallback primary menu (used until a menu is assigned to the "primary" location).
 * Items come from theme-config.json → header.menu_fallback.
 */
function stjo_primary_menu_fallback() {
	$items = (array) stjo_config_get( 'header.menu_fallback', array() );
	if ( ! $items ) {
		return;
	}
	echo '<ul class="primary-menu">';
	foreach ( $items as $item ) {
		if ( empty( $item['label'] ) || empty( $item['url'] ) ) {
			continue;
		}
		printf(
			'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
			esc_url( home_url( $item['url'] ) ),
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}
