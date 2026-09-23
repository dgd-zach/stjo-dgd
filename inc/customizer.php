<?php
/**
 * Site Settings: Appearance > Customize > Site Settings.
 *
 * Lets the team edit the client-specific content that lives in
 * theme-config.json (header buttons, footer details, social links, partner
 * logos) without touching the file. The JSON stays the source of defaults;
 * whatever is saved here is layered on top by stjo_config_apply_mods(), which
 * stjo_config() calls, so every template that reads stjo_config_get() picks
 * the edits up unchanged.
 *
 * Giving links (give.*) are deliberately NOT here: the Your Generosity band
 * is an editable block pattern (inc/patterns/band-cards-tiles-cta.php) where
 * wording, art and links are edited in place, and the automatic pre-footer
 * band keeps reading give.* from the JSON.
 *
 * Everything is stored in ONE theme mod, `stjo_config`, as a nested array
 * mirroring the JSON paths (multidimensional Customizer setting ids). A field
 * that was never saved falls through to the JSON value. Field semantics,
 * spelled out under each field by a "Leave blank to..." hint that shows only
 * while the field is empty (stjo_customize_blank_hint + customize-controls.js):
 *   - standing copy (tagline, newsletter, legal, button links): blank falls
 *     back to the theme-config.json default, never empty
 *   - removable items (contact rows, social links, partner logos, a button
 *     label): blank hides that item
 *
 * Lists are fixed slots (three contact rows, one field per network, five
 * partner logos): the Customizer has no native add/remove control, and the
 * site needs no more than that today.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------ vocabulary -- */

/** Header button styles offered in the dropdown (keys from stjo_header_ctas()). */
function stjo_customize_cta_styles() {
	return array(
		'primary'     => __( 'Blue (filled)', 'stjo' ),
		'ghost'       => __( 'Outline', 'stjo' ),
		'light'       => __( 'White (filled)', 'stjo' ),
		'yellow'      => __( 'Yellow (filled)', 'stjo' ),
		'ghost-light' => __( 'White outline', 'stjo' ),
	);
}

/** Social slot key for a network name from the JSON list ("YouTube" => "youtube"). */
function stjo_customize_social_key( $network ) {
	return sanitize_title( (string) $network );
}

/** Number of partner logo slots offered. */
function stjo_customize_partner_slots() {
	return max( 5, count( (array) stjo_config_get_base( 'footer.partners', array() ) ) );
}

/* ------------------------------------------------------------ sanitizers -- */

function stjo_sanitize_text( $value ) {
	return sanitize_text_field( (string) $value );
}
function stjo_sanitize_multiline( $value ) {
	return trim( wp_kses( (string) $value, array( 'br' => array() ) ) );
}
function stjo_sanitize_url_field( $value ) {
	return esc_url_raw( trim( (string) $value ) );
}
function stjo_sanitize_checkbox( $value ) {
	return ! empty( $value ) && 'false' !== $value;
}
function stjo_sanitize_cta_style( $value ) {
	return array_key_exists( $value, stjo_customize_cta_styles() ) ? $value : 'primary';
}
function stjo_sanitize_digits( $value ) {
	return preg_replace( '/\D+/', '', (string) $value );
}

/* ------------------------------------------------------------ blank hint -- */

/**
 * "Leave blank to..." note shown under a field only while it is empty.
 * customize-controls.js moves it below the input and toggles it live.
 *
 * @param string $mode  'fallback' (blank uses the JSON default) or 'hide'.
 * @param string $value The JSON default, shown for the fallback case.
 * @param string $hide  Optional custom wording for the hide case.
 * @return string HTML span, appended to a control's description.
 */
function stjo_customize_blank_hint( $mode, $value = '', $hide = '' ) {
	if ( 'hide' === $mode ) {
		$text = $hide ? $hide : __( 'Leave blank to hide this.', 'stjo' );
		return '<span class="stjo-blank-hint" style="display:none">' . esc_html( $text ) . '</span>';
	}
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '<span class="stjo-blank-hint" style="display:none">' . esc_html__( 'Leave blank to use the standard value.', 'stjo' ) . '</span>';
	}
	$short = mb_strlen( $value ) > 52 ? mb_substr( $value, 0, 52 ) . '…' : $value;
	return '<span class="stjo-blank-hint" style="display:none">'
		. esc_html__( 'Leave blank to use:', 'stjo' )
		. ' <code title="' . esc_attr( $value ) . '">' . esc_html( $short ) . '</code></span>';
}

/** Show the blank hint only while its field is empty, positioned under the field. */
function stjo_customize_controls_js() {
	wp_enqueue_script(
		'stjo-customize-controls',
		get_template_directory_uri() . '/assets/js/customize-controls.js',
		array( 'customize-controls', 'jquery' ),
		(string) filemtime( get_template_directory() . '/assets/js/customize-controls.js' ),
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'stjo_customize_controls_js' );

/** Muted styling for the blank hints. */
function stjo_customize_controls_css() {
	echo '<style id="stjo-customize-controls-css">
		.stjo-blank-hint { display: block; margin: 6px 0 0; color: #646970; font-size: 12px; line-height: 1.5; }
		.stjo-blank-hint code { font-size: 11px; word-break: break-all; background: #f0f0f1; padding: 1px 4px; border-radius: 2px; }
		#customize-control-stjo_generosity_band_note { padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #dcdcde; }
		#customize-control-stjo_generosity_band_note .stjo-note-action { margin: 10px 0 0; }
		#customize-control-stjo_generosity_band_note .dashicons-external { font-size: 16px; line-height: 1.6; margin-left: 4px; vertical-align: text-bottom; }
	</style>';
}
add_action( 'customize_controls_print_styles', 'stjo_customize_controls_css' );

/* ---------------------------------------------------------- registration -- */

function stjo_customize_register( $wp_customize ) {
	$base = stjo_config_base();

	if ( ! class_exists( 'Stjo_Customize_Note_Control' ) ) {
		/**
		 * Display-only control: a title, a description and a button link, no
		 * setting behind it. Used to point editors from the Customizer to
		 * things that live elsewhere (the Your Generosity band pattern).
		 */
		class Stjo_Customize_Note_Control extends WP_Customize_Control {
			public $type       = 'stjo_note';
			public $link_url   = '';
			public $link_label = '';

			public function render_content() {
				if ( $this->label ) {
					printf( '<span class="customize-control-title">%s</span>', esc_html( $this->label ) );
				}
				if ( $this->description ) {
					printf( '<span class="description customize-control-description">%s</span>', wp_kses_post( $this->description ) );
				}
				if ( $this->link_url && $this->link_label ) {
					printf(
						'<p class="stjo-note-action"><a class="button button-secondary" href="%s" target="_blank" rel="noopener">%s<span class="screen-reader-text"> %s</span><span class="dashicons dashicons-external" aria-hidden="true"></span></a></p>',
						esc_url( $this->link_url ),
						esc_html( $this->link_label ),
						esc_html__( '(opens in a new tab)', 'stjo' )
					);
				}
			}
		}
	}

	$wp_customize->add_panel( 'stjo_site_settings', array(
		'title'       => __( 'Custom Site Settings', 'stjo' ),
		'description' => __( 'The details that appear on every page: header buttons, footer, social links and partner logos. Each field notes what happens when you leave it blank, either falling back to the standard value or hiding that item.', 'stjo' ),
		'priority'    => 5,
	) );

	$add = function ( $id, $default, $sanitize, $control ) use ( $wp_customize ) {
		$wp_customize->add_setting( 'stjo_config' . $id, array(
			'type'              => 'theme_mod',
			'capability'        => $control['capability'] ?? 'edit_theme_options',
			'default'           => $default,
			'sanitize_callback' => $sanitize,
			'transport'         => 'refresh',
		) );
		unset( $control['capability'] );
		// A `stjo_hint` (from stjo_customize_blank_hint) rides along in the
		// description; JS shows it only while the field is empty.
		if ( ! empty( $control['stjo_hint'] ) ) {
			$control['description'] = trim( ( $control['description'] ?? '' ) . ' ' . $control['stjo_hint'] );
		}
		unset( $control['stjo_hint'] );
		$class = $control['class'] ?? 'WP_Customize_Control';
		unset( $control['class'] );
		$wp_customize->add_control( new $class( $wp_customize, 'stjo_config' . $id, $control ) );
	};

	/* Header buttons ------------------------------------------------------- */
	$wp_customize->add_section( 'stjo_header', array(
		'panel'       => 'stjo_site_settings',
		'title'       => __( 'Header buttons', 'stjo' ),
		'description' => __( 'The two buttons at the top right of every page. Clear a label to hide that button.', 'stjo' ),
	) );
	foreach ( array( 0, 1 ) as $i ) {
		$cta = $base['header']['ctas'][ $i ] ?? array( 'label' => '', 'url' => '', 'style' => 'primary' );
		$n   = $i + 1;
		$add( "[header][ctas][$i][label]", $cta['label'] ?? '', 'stjo_sanitize_text', array(
			'section'   => 'stjo_header',
			'label'     => sprintf( __( 'Button %d label', 'stjo' ), $n ),
			'type'      => 'text',
			'stjo_hint' => stjo_customize_blank_hint( 'hide', '', __( 'Leave blank to hide this button.', 'stjo' ) ),
		) );
		$add( "[header][ctas][$i][url]", $cta['url'] ?? '', 'stjo_sanitize_url_field', array(
			'section'   => 'stjo_header',
			'label'     => sprintf( __( 'Button %d link', 'stjo' ), $n ),
			'type'      => 'url',
			'stjo_hint' => stjo_customize_blank_hint( 'fallback', $cta['url'] ?? '' ),
		) );
		$add( "[header][ctas][$i][style]", $cta['style'] ?? 'primary', 'stjo_sanitize_cta_style', array(
			'section' => 'stjo_header',
			'label'   => sprintf( __( 'Button %d style', 'stjo' ), $n ),
			'type'    => 'select',
			'choices' => stjo_customize_cta_styles(),
		) );
	}
	$add( '[header][show_search]', ! empty( $base['header']['show_search'] ), 'stjo_sanitize_checkbox', array(
		'section' => 'stjo_header',
		'label'   => __( 'Show the search icon', 'stjo' ),
		'type'    => 'checkbox',
	) );

	/* Footer --------------------------------------------------------------- */
	$wp_customize->add_section( 'stjo_footer', array(
		'panel'       => 'stjo_site_settings',
		'title'       => __( 'Footer', 'stjo' ),
		'description' => __( 'Contact details, tagline, newsletter wording and the legal line at the bottom of every page.', 'stjo' ),
	) );
	// The giving band sits just above the footer, so this is where people look
	// for it. It is a synced pattern, edited in the Pattern editor; this note
	// (first in the section) sends them straight to that pattern's editor, or
	// to the Patterns screen if the pattern has not been created yet.
	$band_pattern = function_exists( 'stjo_generosity_band_pattern' ) ? stjo_generosity_band_pattern() : null;
	$wp_customize->add_control( new Stjo_Customize_Note_Control( $wp_customize, 'stjo_generosity_band_note', array(
		'section'     => 'stjo_footer',
		'priority'    => 1,
		'settings'    => array(),
		'label'       => __( 'Your Generosity band', 'stjo' ),
		'description' => __( 'The blue giving band above the footer, with its photo cards, icon tiles and Donate button, is one pattern shared by every page. Its wording, photos and giving links are edited in the Pattern editor, not here. Save or discard your Customizer changes first; the link opens in a new tab.', 'stjo' ),
		'link_url'    => $band_pattern ? admin_url( 'post.php?post=' . (int) $band_pattern->ID . '&action=edit' ) : admin_url( 'site-editor.php?p=/pattern' ),
		'link_label'  => $band_pattern ? __( 'Edit the Your Generosity band', 'stjo' ) : __( 'Open Patterns', 'stjo' ),
	) ) );

	$contact = array();
	foreach ( (array) ( $base['footer']['contact'] ?? array() ) as $row ) {
		$contact[ $row['icon'] ?? '' ] = $row['text'] ?? '';
	}
	$add( '[footer][tagline]', $base['footer']['tagline'] ?? '', 'stjo_sanitize_text', array(
		'section'   => 'stjo_footer',
		'label'     => __( 'Tagline', 'stjo' ),
		'type'      => 'text',
		'stjo_hint' => stjo_customize_blank_hint( 'fallback', $base['footer']['tagline'] ?? '' ),
	) );
	$add( '[footer][contact][address]', $contact['address'] ?? '', 'stjo_sanitize_multiline', array(
		'section'     => 'stjo_footer',
		'label'       => __( 'Address', 'stjo' ),
		'type'        => 'textarea',
		'description' => __( 'Use &lt;br&gt; for a line break.', 'stjo' ),
		'stjo_hint'   => stjo_customize_blank_hint( 'hide', '', __( 'Leave blank to hide the address.', 'stjo' ) ),
	) );
	$add( '[footer][contact][phone]', $contact['phone'] ?? '', 'stjo_sanitize_text', array(
		'section'   => 'stjo_footer',
		'label'     => __( 'Phone', 'stjo' ),
		'type'      => 'text',
		'stjo_hint' => stjo_customize_blank_hint( 'hide', '', __( 'Leave blank to hide the phone number.', 'stjo' ) ),
	) );
	$add( '[footer][contact][email]', $contact['email'] ?? '', 'stjo_sanitize_text', array(
		'section'   => 'stjo_footer',
		'label'     => __( 'Email', 'stjo' ),
		'type'      => 'email',
		'stjo_hint' => stjo_customize_blank_hint( 'hide', '', __( 'Leave blank to hide the email address.', 'stjo' ) ),
	) );
	$add( '[footer][newsletter][heading]', $base['footer']['newsletter']['heading'] ?? '', 'stjo_sanitize_text', array(
		'section'   => 'stjo_footer',
		'label'     => __( 'Newsletter heading', 'stjo' ),
		'type'      => 'text',
		'stjo_hint' => stjo_customize_blank_hint( 'fallback', $base['footer']['newsletter']['heading'] ?? '' ),
	) );
	$add( '[footer][newsletter][body]', $base['footer']['newsletter']['body'] ?? '', 'stjo_sanitize_multiline', array(
		'section'   => 'stjo_footer',
		'label'     => __( 'Newsletter text', 'stjo' ),
		'type'      => 'textarea',
		'stjo_hint' => stjo_customize_blank_hint( 'fallback', $base['footer']['newsletter']['body'] ?? '' ),
	) );
	// Where the sign-up form submits. With the giving system's API connected
	// (wp-config credentials) the form posts through this site and the Survey
	// ID decides which Luminate survey receives it; without the API it posts
	// straight to the submission URL. Both are exposed so either setup can be
	// re-pointed without a code change.
	$add( '[footer][newsletter][survey_id]', $base['footer']['newsletter']['survey_id'] ?? '', 'stjo_sanitize_digits', array(
		'section'     => 'stjo_footer',
		'label'       => __( 'Newsletter survey ID', 'stjo' ),
		'type'        => 'text',
		'description' => __( 'The Luminate Online survey that receives sign-ups (SURVEY_ID in the form address). Digits only.', 'stjo' ),
		'stjo_hint'   => stjo_customize_blank_hint( 'fallback', $base['footer']['newsletter']['survey_id'] ?? '' ),
	) );
	$add( '[footer][newsletter][action]', $base['footer']['newsletter']['action'] ?? '', 'stjo_sanitize_url_field', array(
		'section'     => 'stjo_footer',
		'label'       => __( 'Newsletter submission URL', 'stjo' ),
		'type'        => 'url',
		'description' => __( 'The Luminate Online survey address the form posts to. Keep the survey ID above and this address pointing at the same survey.', 'stjo' ),
		'stjo_hint'   => stjo_customize_blank_hint( 'fallback', $base['footer']['newsletter']['action'] ?? '' ),
	) );
	$add( '[footer][newsletter][s_src]', $base['footer']['newsletter']['s_src'] ?? '', 'stjo_sanitize_text', array(
		'section'     => 'stjo_footer',
		'label'       => __( 'Newsletter source code', 'stjo' ),
		'type'        => 'text',
		'description' => __( 'Sent with each sign-up (s_src) so Luminate reports where it came from.', 'stjo' ),
		'stjo_hint'   => stjo_customize_blank_hint( 'fallback', $base['footer']['newsletter']['s_src'] ?? '' ),
	) );
	$add( '[footer][legal]', $base['footer']['legal'] ?? '', 'stjo_sanitize_multiline', array(
		'section'     => 'stjo_footer',
		'label'       => __( 'Legal line', 'stjo' ),
		'type'        => 'textarea',
		'description' => __( '{year} becomes the current year.', 'stjo' ),
		'stjo_hint'   => stjo_customize_blank_hint( 'fallback', $base['footer']['legal'] ?? '' ),
	) );
	$privacy_default = 0;
	$privacy_path    = trim( (string) ( $base['footer']['privacy_url'] ?? '' ), '/' );
	if ( $privacy_path ) {
		$privacy_page    = get_page_by_path( $privacy_path );
		$privacy_default = $privacy_page ? (int) $privacy_page->ID : 0;
	}
	$add( '[footer][privacy_page]', $privacy_default, 'absint', array(
		'section'        => 'stjo_footer',
		'label'          => __( 'Privacy policy page', 'stjo' ),
		'type'           => 'dropdown-pages',
		'allow_addition' => false,
	) );
	$status_default = 0;
	$status_path    = trim( (string) ( $base['footer']['status_url'] ?? '' ), '/' );
	if ( $status_path ) {
		$status_page    = get_page_by_path( $status_path );
		$status_default = $status_page ? (int) $status_page->ID : 0;
	}
	$add( '[footer][status_page]', $status_default, 'absint', array(
		'section'        => 'stjo_footer',
		'label'          => __( '501(c)(3) status page', 'stjo' ),
		'type'           => 'dropdown-pages',
		'allow_addition' => false,
		'description'    => __( 'The words "501(c)(3)" in the legal line link to this page. Choose "Select" at the top of the list to leave them plain text.', 'stjo' ),
	) );

	/* Social links --------------------------------------------------------- */
	$wp_customize->add_section( 'stjo_social', array(
		'panel'       => 'stjo_site_settings',
		'title'       => __( 'Social links', 'stjo' ),
		'description' => __( 'The icons under "Connect With Us" in the footer. Clear a link to hide that network.', 'stjo' ),
	) );
	foreach ( (array) ( $base['footer']['social'] ?? array() ) as $network ) {
		$key = stjo_customize_social_key( $network['network'] ?? '' );
		if ( ! $key ) {
			continue;
		}
		$add( "[footer][social][$key]", $network['url'] ?? '', 'stjo_sanitize_url_field', array(
			'section'   => 'stjo_social',
			'label'     => $network['network'],
			'type'      => 'url',
			'stjo_hint' => stjo_customize_blank_hint( 'hide', '', __( 'Leave blank to hide this icon.', 'stjo' ) ),
		) );
	}

	/* Partner logos -------------------------------------------------------- */
	$wp_customize->add_section( 'stjo_partners', array(
		'panel'       => 'stjo_site_settings',
		'title'       => __( 'Partner logos', 'stjo' ),
		'description' => __( 'The accreditation and partner logos at the bottom of the footer. Remove an image to hide that slot. The name is read out to screen readers.', 'stjo' ),
	) );
	$partners = array_values( (array) ( $base['footer']['partners'] ?? array() ) );
	for ( $i = 0; $i < stjo_customize_partner_slots(); $i++ ) {
		$partner    = $partners[ $i ] ?? array( 'name' => '', 'image' => '' );
		$default_id = ! empty( $partner['image'] ) ? (int) attachment_url_to_postid( home_url( $partner['image'] ) ) : 0;
		$add( "[footer][partners][$i][image]", $default_id, 'absint', array(
			'class'     => 'WP_Customize_Media_Control',
			'section'   => 'stjo_partners',
			'label'     => sprintf( __( 'Logo %d', 'stjo' ), $i + 1 ),
			'mime_type' => 'image',
		) );
		$add( "[footer][partners][$i][name]", $partner['name'] ?? '', 'stjo_sanitize_text', array(
			'section' => 'stjo_partners',
			'label'   => sprintf( __( 'Logo %d name', 'stjo' ), $i + 1 ),
			'type'    => 'text',
		) );
	}

}
add_action( 'customize_register', 'stjo_customize_register' );

/* ---------------------------------------------------------------- merge -- */

/**
 * Layer the saved Customizer values over the JSON config. Called by
 * stjo_config(); $mods is the `stjo_config` theme mod.
 *
 * @param array $config Defaults + JSON.
 * @param array $mods   Saved theme mod (nested).
 * @return array
 */
function stjo_config_apply_mods( array $config, array $mods ) {
	// [exists, value] for a dotted path inside $mods.
	$mod = function ( $path ) use ( $mods ) {
		$value = $mods;
		foreach ( explode( '.', $path ) as $key ) {
			if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) ) {
				return array( false, null );
			}
			$value = $value[ $key ];
		}
		return array( true, $value );
	};

	// Header buttons: label blank hides; link blank keeps the default.
	$ctas = array();
	foreach ( array_values( (array) ( $config['header']['ctas'] ?? array() ) ) as $i => $cta ) {
		list( $has_label, $label ) = $mod( "header.ctas.$i.label" );
		list( $has_url, $url )     = $mod( "header.ctas.$i.url" );
		list( $has_style, $style ) = $mod( "header.ctas.$i.style" );
		if ( $has_label ) {
			if ( '' === trim( (string) $label ) ) {
				continue;
			}
			$cta['label'] = $label;
		}
		if ( $has_url && '' !== trim( (string) $url ) ) {
			$cta['url'] = $url;
		}
		if ( $has_style && '' !== $style ) {
			$cta['style'] = $style;
		}
		$ctas[] = $cta;
	}
	$config['header']['ctas'] = $ctas;
	list( $has, $value ) = $mod( 'header.show_search' );
	if ( $has ) {
		$config['header']['show_search'] = (bool) $value;
	}

	// Footer standing copy: the edited value shows; blank falls back to the
	// theme-config.json default (this copy should never just vanish).
	foreach ( array(
		'footer.tagline'              => array( 'footer', 'tagline' ),
		'footer.legal'                => array( 'footer', 'legal' ),
		'footer.newsletter.heading'   => array( 'footer', 'newsletter', 'heading' ),
		'footer.newsletter.body'      => array( 'footer', 'newsletter', 'body' ),
		'footer.newsletter.action'    => array( 'footer', 'newsletter', 'action' ),
		'footer.newsletter.survey_id' => array( 'footer', 'newsletter', 'survey_id' ),
		'footer.newsletter.s_src'     => array( 'footer', 'newsletter', 's_src' ),
	) as $path => $keys ) {
		list( $has, $value ) = $mod( $path );
		if ( $has && '' !== trim( (string) $value ) ) {
			$ref = &$config;
			foreach ( $keys as $k ) {
				$ref = &$ref[ $k ];
			}
			$ref = (string) $value;
			unset( $ref );
		}
	}

	// Contact rows, keyed by icon; blank removes the row.
	$rows = array();
	foreach ( (array) ( $config['footer']['contact'] ?? array() ) as $row ) {
		$rows[ $row['icon'] ?? count( $rows ) ] = $row;
	}
	foreach ( array( 'address', 'phone', 'email' ) as $icon ) {
		list( $has, $value ) = $mod( "footer.contact.$icon" );
		if ( ! $has ) {
			continue;
		}
		$value = trim( (string) $value );
		if ( '' === $value ) {
			unset( $rows[ $icon ] );
			continue;
		}
		$row = array( 'icon' => $icon, 'text' => $value );
		if ( 'phone' === $icon ) {
			$row['url'] = 'tel:' . preg_replace( '/\D+/', '', $value );
		} elseif ( 'email' === $icon ) {
			$row['url'] = 'mailto:' . $value;
		}
		$rows[ $icon ] = $row;
	}
	$config['footer']['contact'] = array_values( $rows );

	// Social: one URL per network; blank hides it.
	$social = array();
	foreach ( (array) ( $config['footer']['social'] ?? array() ) as $network ) {
		list( $has, $value ) = $mod( 'footer.social.' . stjo_customize_social_key( $network['network'] ?? '' ) );
		if ( $has ) {
			if ( '' === trim( (string) $value ) ) {
				continue;
			}
			$network['url'] = $value;
		}
		$social[] = $network;
	}
	$config['footer']['social'] = $social;

	// Partner logos: slot image 0 hides the slot; an attachment id becomes its URL.
	$partners = array_values( (array) ( $config['footer']['partners'] ?? array() ) );
	$out      = array();
	for ( $i = 0; $i < max( count( $partners ), stjo_customize_partner_slots() ); $i++ ) {
		$partner = $partners[ $i ] ?? null;
		list( $has_image, $image ) = $mod( "footer.partners.$i.image" );
		list( $has_name, $name )   = $mod( "footer.partners.$i.name" );
		if ( $has_image ) {
			$image_url = $image ? wp_get_attachment_image_url( (int) $image, 'medium' ) : '';
			if ( ! $image_url ) {
				continue; // cleared, or the attachment is gone
			}
			$partner = array( 'name' => $partner['name'] ?? '', 'image' => wp_make_link_relative( $image_url ) );
		}
		if ( ! $partner ) {
			continue;
		}
		if ( $has_name ) {
			$partner['name'] = (string) $name;
		}
		$out[] = $partner;
	}
	$config['footer']['partners'] = $out;

	// Privacy page: a chosen page wins over the JSON path.
	// get_page_uri(), not get_permalink(): the permalink filters include the
	// link-out redirect, which reads the config and would loop back here.
	list( $has, $value ) = $mod( 'footer.privacy_page' );
	if ( $has && (int) $value > 0 && 'publish' === get_post_status( (int) $value ) ) {
		$uri = get_page_uri( (int) $value );
		if ( $uri ) {
			$config['footer']['privacy_url'] = '/' . trim( $uri, '/' ) . '/';
		}
	}

	// 501(c)(3) status page: a chosen page wins; explicitly choosing none
	// (0) drops the link so the legal line reads as plain text.
	list( $has, $value ) = $mod( 'footer.status_page' );
	if ( $has ) {
		$config['footer']['status_url'] = '';
		if ( (int) $value > 0 && 'publish' === get_post_status( (int) $value ) ) {
			$uri = get_page_uri( (int) $value );
			if ( $uri ) {
				$config['footer']['status_url'] = '/' . trim( $uri, '/' ) . '/';
			}
		}
	}

	return $config;
}
