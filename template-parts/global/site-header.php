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

		<button class="site-header__menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
			<span class="site-header__menu-icon" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'stjo' ); ?></span>
		</button>

		<nav id="site-nav" class="site-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'stjo' ); ?>">
			<?php stjo_mega_nav(); ?>
		</nav>

		<div class="site-header__actions">
			<?php if ( stjo_config_get( 'header.show_search', true ) ) : ?>
				<button class="site-header__search" type="button" aria-haspopup="dialog" aria-label="<?php esc_attr_e( 'Search', 'stjo' ); ?>" data-search-open>
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
						<line x1="16.5" y1="16.5" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</button>
			<?php endif; ?>
			<?php
			// style → [wrapper block-style class, link color classes]. Only three
			// block styles exist; variants come from WP color settings.
			$style_classes = array(
				'primary'     => array( 'is-style-fill', '' ),
				'fill'        => array( 'is-style-fill', '' ),
				'ghost'       => array( 'is-style-outline', '' ),
				'outline'     => array( 'is-style-outline', '' ),
				'ghost-light' => array( 'is-style-outline', 'has-white-color has-text-color has-white-background-color has-background' ),
				'yellow'      => array( 'is-style-fill', 'has-blue-900-color has-text-color has-yellow-background-color has-background' ),
				'light'       => array( 'is-style-fill', 'has-brand-dark-color has-text-color has-white-background-color has-background' ),
			);
			foreach ( (array) stjo_config_get( 'header.ctas', array() ) as $cta ) {
				if ( empty( $cta['label'] ) || empty( $cta['url'] ) ) {
					continue;
				}
				list( $style, $link_colors ) = $style_classes[ $cta['style'] ?? 'primary' ] ?? array( 'is-style-fill', '' );
				printf(
					'<div class="wp-block-button %1$s"><a class="wp-block-button__link %4$s wp-element-button" href="%2$s">%3$s</a></div>',
					esc_attr( $style ),
					esc_url( 0 === strpos( $cta['url'], 'http' ) ? $cta['url'] : home_url( $cta['url'] ) ),
					esc_html( $cta['label'] ),
					esc_attr( $link_colors )
				);
			}
			?>
		</div>
	</div>
	<?php
	// Desktop panel band: nav.js moves each section's .mega-panel in here so
	// the band opens IN FLOW (pushing content down, nothing floats over the
	// hero) and section switches crossfade inside the one band. In the
	// drawer, panels move back under their triggers as accordions.
	?>
	<div class="mega-panels" data-mega-panels></div>

	<?php if ( stjo_config_get( 'header.show_search', true ) ) : ?>
	<dialog class="search-modal" id="site-search-modal" aria-label="<?php esc_attr_e( 'Search this site', 'stjo' ); ?>" data-search-modal>
		<div class="search-modal__card">
			<form class="search-modal__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="site-search-input"><?php esc_html_e( 'Search this site', 'stjo' ); ?></label>
				<input id="site-search-input" class="search-modal__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'stjo' ); ?>" required>
				<div class="wp-block-button is-style-fill search-modal__submit">
					<button class="wp-block-button__link wp-element-button" type="submit"><?php esc_html_e( 'Search', 'stjo' ); ?></button>
				</div>
			</form>
			<button class="search-modal__close" type="button" data-search-close>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">
					<path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
				<span class="screen-reader-text"><?php esc_html_e( 'Close search', 'stjo' ); ?></span>
			</button>
		</div>
	</dialog>
	<?php endif; ?>
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
