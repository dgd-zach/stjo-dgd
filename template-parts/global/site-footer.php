<?php
/**
 * Global site footer. Client content comes from theme-config.json
 * (see inc/theme-config.php): contact, links, social, newsletter, partners, legal.
 *
 * @package stjo
 */

$contact_icons = array(
	'address' => 'fa-solid fa-location-dot',
	'phone'   => 'fa-solid fa-phone',
	'email'   => 'fa-solid fa-envelope',
);

// Font Awesome brand classes keyed by the config's network name (lowercased).
$social_icons = array(
	'facebook'  => 'fa-facebook-f',
	'instagram' => 'fa-instagram',
	'youtube'   => 'fa-youtube',
	'tiktok'    => 'fa-tiktok',
	'x'         => 'fa-x-twitter',
	'twitter'   => 'fa-x-twitter',
	'flickr'    => 'fa-flickr',
	'linkedin'  => 'fa-linkedin-in',
	'pinterest' => 'fa-pinterest-p',
	'threads'   => 'fa-threads',
	'vimeo'     => 'fa-vimeo-v',
);
?>
<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<div class="site-footer__grid">

			<div class="site-footer__col site-footer__about">
				<p class="site-footer__org"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
				<?php if ( stjo_config_get( 'footer.tagline' ) ) : ?>
					<p class="site-footer__tagline"><?php echo esc_html( stjo_config_get( 'footer.tagline' ) ); ?></p>
				<?php endif; ?>
				<?php $contact = (array) stjo_config_get( 'footer.contact', array() ); ?>
				<?php if ( $contact ) : ?>
					<ul class="site-footer__contact">
						<?php foreach ( $contact as $row ) : ?>
							<li>
								<span class="site-footer__icon" aria-hidden="true"><i class="<?php echo esc_attr( $contact_icons[ $row['icon'] ?? '' ] ?? 'fa-solid fa-location-dot' ); ?>"></i></span>
								<?php if ( ! empty( $row['url'] ) ) : ?>
									<a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo wp_kses( $row['text'] ?? '', array( 'br' => array() ) ); ?></a>
								<?php else : ?>
									<span><?php echo wp_kses( $row['text'] ?? '', array( 'br' => array() ) ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="site-footer__col site-footer__links">
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'site-footer__menu',
						'depth'          => 1,
					) );
				} else {
					$links = (array) stjo_config_get( 'footer.links', array() );
					if ( $links ) {
						echo '<ul class="site-footer__menu">';
						foreach ( $links as $link ) {
							if ( empty( $link['label'] ) || empty( $link['url'] ) ) {
								continue;
							}
							printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( home_url( $link['url'] ) ), esc_html( $link['label'] ) );
						}
						echo '</ul>';
					}
				}
				?>
				<?php $social = (array) stjo_config_get( 'footer.social', array() ); ?>
				<?php if ( $social ) : ?>
					<h2 class="site-footer__heading"><?php esc_html_e( 'Connect With Us', 'stjo' ); ?></h2>
					<ul class="site-footer__social" aria-label="<?php esc_attr_e( 'Social media', 'stjo' ); ?>">
						<?php foreach ( $social as $network ) : ?>
							<?php $brand = $social_icons[ strtolower( $network['network'] ?? '' ) ] ?? ''; ?>
							<li>
								<a href="<?php echo esc_url( $network['url'] ?? '#' ); ?>" aria-label="<?php echo esc_attr( $network['network'] ?? '' ); ?>">
									<?php if ( ! empty( $network['icon'] ) ) : ?>
										<img src="<?php echo esc_url( $network['icon'] ); ?>" alt="" width="24" height="24" loading="lazy">
									<?php elseif ( $brand ) : ?>
										<i class="fa-brands <?php echo esc_attr( $brand ); ?>" aria-hidden="true"></i>
									<?php else : ?>
										<span class="site-footer__social-dot" aria-hidden="true"></span>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="site-footer__col site-footer__newsletter">
				<?php if ( stjo_config_get( 'footer.newsletter.heading' ) ) : ?>
					<h2 class="site-footer__heading site-footer__heading--lg"><?php echo esc_html( stjo_config_get( 'footer.newsletter.heading' ) ); ?></h2>
					<?php if ( stjo_config_get( 'footer.newsletter.body' ) ) : ?>
						<p><?php echo esc_html( stjo_config_get( 'footer.newsletter.body' ) ); ?></p>
					<?php endif; ?>
					<form class="site-footer__form" action="<?php echo esc_url( stjo_config_get( 'footer.newsletter.action', '#' ) ); ?>" method="post">
						<div class="site-footer__form-row">
							<label class="screen-reader-text" for="nl-first"><?php esc_html_e( 'First name', 'stjo' ); ?></label>
							<input id="nl-first" type="text" name="first_name" placeholder="<?php esc_attr_e( 'First name', 'stjo' ); ?>">
							<label class="screen-reader-text" for="nl-last"><?php esc_attr_e( 'Last name', 'stjo' ); ?></label>
							<input id="nl-last" type="text" name="last_name" placeholder="<?php esc_attr_e( 'Last name', 'stjo' ); ?>">
						</div>
						<label class="screen-reader-text" for="nl-email"><?php esc_html_e( 'Email', 'stjo' ); ?></label>
						<input id="nl-email" type="email" name="email" placeholder="<?php esc_attr_e( 'Email address', 'stjo' ); ?>">
						<button class="wp-block-button__link wp-element-button stjo-form-button-outline" type="submit"><?php esc_html_e( 'Sign Up', 'stjo' ); ?></button>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<?php $partners = (array) stjo_config_get( 'footer.partners', array() ); ?>
		<?php if ( $partners ) : ?>
			<div class="site-footer__partners" aria-label="<?php esc_attr_e( 'Accreditations and partners', 'stjo' ); ?>">
				<?php foreach ( $partners as $partner ) : ?>
					<?php if ( ! empty( $partner['image'] ) ) : ?>
						<img class="site-footer__partner-logo" src="<?php echo esc_url( $partner['image'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ?? '' ); ?>" loading="lazy">
					<?php else : ?>
						<span class="site-footer__partner" aria-hidden="true"></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="site-footer__legal">
			<p class="site-footer__copyright">
				<?php
				$legal = stjo_config_get( 'footer.legal' );
				if ( $legal ) {
					echo esc_html( str_replace( '{year}', gmdate( 'Y' ), $legal ) );
				} else {
					printf(
						/* translators: 1: year, 2: site name */
						esc_html__( '© %1$s %2$s. All rights reserved.', 'stjo' ),
						esc_html( gmdate( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
				}
				?>
			</p>
			<a class="site-footer__privacy" href="<?php echo esc_url( home_url( stjo_config_get( 'footer.privacy_url', '/privacy-policy/' ) ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'stjo' ); ?></a>
		</div>
	</div>
</footer>
