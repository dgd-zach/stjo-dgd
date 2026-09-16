<?php

/**
 * Template part: generosity-band — the site-wide "Your Generosity" band (photo
 * cards, icon tiles, Donate CTA). Single source: the pre-footer template part
 * includes it on every inner page, and the stjo/generosity-band block renders
 * it wherever a page carries the band in its own content (Home, Support Us).
 * Copy and destinations come from theme-config.json (give.*). Rendered directly via get_template_part (never parsed by the
 * block editor or do_blocks), so it's plain HTML: the wp:* block comments the
 * generator emitted are inert and have been dropped.
 *
 * @package stjo
 */

$stjo_give_once     = stjo_config_get( 'give.once_url', 'https://give.stjo.org/site/Donation2?df_id=6740&6740.donation=form1' );
$stjo_give_monthly  = stjo_config_get( 'give.monthly_url', $stjo_give_once );
$stjo_daf  = stjo_config_get( 'give.daf_url', $stjo_give_once );
$stjo_memorial  = stjo_config_get( 'give.memorial_url', $stjo_give_once );
$stjo_give_planned  = stjo_config_get( 'give.planned_url', '/support-us/planned-giving/' );
$stjo_give_ira      = stjo_config_get( 'give.ira_url', 'https://plannedgiving.stjo.org/give-from-your-ira' );
$stjo_give_shop     = stjo_config_get( 'give.shop_url', '/support-us/our-shop/' );
$stjo_give_wishlist = stjo_config_get( 'give.wishlists_url', '/support-us/wishlists-gift-cards/' );
?>
<div class="wp-block-group alignfull stjo-generosity">
    <div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>

    <h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Your Generosity <strong>Changes Everything</strong></h2>

    <p class="has-text-align-center has-light-color has-text-color">Every gift, no matter the size or how you give, brings hope, culture and opportunity to a Lakota child at St. Joseph's.</p>

    <div style="height:var(--wp--preset--spacing--small)" aria-hidden="true" class="wp-block-spacer"></div>

    <div class="wp-block-columns alignwide stjo-give-row">
        <div class="wp-block-column">
            <div class="wp-block-cover stjo-card" style="min-height:340px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'card-4.png' ) ); ?>" data-object-fit="cover" /><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-50 has-background-dim"></span>
                <div class="wp-block-cover__inner-container">
                    <h3 class="wp-block-heading has-light-color has-text-color">Monthly giving</h3>
                    <p class="has-light-color has-text-color stjo-card__reveal">Be a DreamMaker with a recurring gift.</p>
                    <div class="wp-block-buttons">
                        <div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="<?php echo esc_url( $stjo_give_monthly ); ?>">Give Monthly Now</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wp-block-column">
            <div class="wp-block-cover stjo-card" style="min-height:340px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'card-5.png' ) ); ?>" data-object-fit="cover" /><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-50 has-background-dim"></span>
                <div class="wp-block-cover__inner-container">
                    <h3 class="wp-block-heading has-light-color has-text-color">One-Time Gift</h3>
                    <p class="has-light-color has-text-color stjo-card__reveal">Make an immediate impact today.</p>
                    <div class="wp-block-buttons">
                        <div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="<?php echo esc_url( $stjo_give_once ); ?>">Give Now</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wp-block-column">
            <div class="wp-block-cover stjo-card" style="min-height:340px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'card-6.png' ) ); ?>" data-object-fit="cover" /><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-50 has-background-dim"></span>
                <div class="wp-block-cover__inner-container">
                    <h3 class="wp-block-heading has-light-color has-text-color">Donor Advised Fund</h3>
                    <p class="has-light-color has-text-color stjo-card__reveal">Give through your DAF account.</p>
                    <div class="wp-block-buttons">
                        <div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="<?php echo esc_url( $stjo_daf ); ?>">Give Now</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wp-block-column">
            <div class="wp-block-cover stjo-card" style="min-height:340px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( stjo_asset( 'card-7.png' ) ); ?>" data-object-fit="cover" /><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-50 has-background-dim"></span>
                <div class="wp-block-cover__inner-container">
                    <h3 class="wp-block-heading has-light-color has-text-color">Memorial Gift</h3>
                    <p class="has-light-color has-text-color stjo-card__reveal">Honor a loved one's memory.</p>
                    <div class="wp-block-buttons">
                        <div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-white-color has-text-color wp-element-button" href="<?php echo esc_url( $stjo_memorial ); ?>">Give Now</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wp-block-columns alignwide stjo-give-row">
        <div class="wp-block-column"><a class="stjo-give-tile" href="<?php echo esc_url( $stjo_give_planned ); ?>"><img src="<?php echo esc_url( stjo_asset( 'article-person.png' ) ); ?>" alt="" aria-hidden="true" class="skip-lazy"><span class="stjo-give-tile__title">Estate Legacy Giving</span><span class="stjo-give-tile__sub">Leave a lasting legacy.</span></a></div>
        <div class="wp-block-column"><a class="stjo-give-tile" href="<?php echo esc_url( $stjo_give_ira ); ?>"><img src="<?php echo esc_url( stjo_asset( 'savings.png' ) ); ?>" alt="" aria-hidden="true" class="skip-lazy"><span class="stjo-give-tile__title">Individual Retirement Account</span><span class="stjo-give-tile__sub">Give a tax-smart IRA gift.</span></a></div>
        <div class="wp-block-column"><a class="stjo-give-tile" href="<?php echo esc_url( $stjo_give_shop ); ?>"><img src="<?php echo esc_url( stjo_asset( 'local-mall.png' ) ); ?>" alt="" aria-hidden="true" class="skip-lazy"><span class="stjo-give-tile__title">Our Shop</span><span class="stjo-give-tile__sub">Shop Lakota crafts.</span></a></div>
        <div class="wp-block-column"><a class="stjo-give-tile" href="<?php echo esc_url( $stjo_give_wishlist ); ?>"><img src="<?php echo esc_url( stjo_asset( 'redeem.png' ) ); ?>" alt="" aria-hidden="true" class="skip-lazy"><span class="stjo-give-tile__title">Wishlists &amp; Gift Cards</span><span class="stjo-give-tile__sub">Give specific items students need.</span></a></div>
    </div>

    <div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>

    <div class="wp-block-buttons is-layout-flex is-content-justification-center">
        <div class="wp-block-button"><a class="wp-block-button__link has-blue-900-color has-yellow-background-color has-text-color has-background has-medium-font-size has-custom-font-size wp-element-button" href="<?php echo esc_url( $stjo_give_once ); ?>">Donate Now</a></div>
    </div>
    <div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
</div>
