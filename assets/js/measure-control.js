/**
 * "Text width" block control — a tactile max-width slider in the inspector.
 *
 * Adds a Text width panel (toggle + range slider) to paragraph / heading /
 * list / group blocks. Setting it stamps the block with the `stjo-measure`
 * class + an inline `--stjo-measure` custom property; utilities.css caps the
 * block at that width on desktop and releases it to full width once columns
 * stack (<=768). Editor canvas previews the cap live via wrapperProps.
 *
 * No build step — classic wp.* globals + hooks filters (mirrors unregister.js).
 */
( function ( wp ) {
	'use strict';

	var addFilter                   = wp.hooks.addFilter;
	var el                          = wp.element.createElement;
	var Fragment                    = wp.element.Fragment;
	var InspectorControls           = wp.blockEditor.InspectorControls;
	var PanelBody                   = wp.components.PanelBody;
	var RangeControl                = wp.components.RangeControl;
	var ToggleControl               = wp.components.ToggleControl;
	var createHigherOrderComponent  = wp.compose.createHigherOrderComponent;
	var __                          = wp.i18n.__;

	var BLOCKS  = [ 'core/paragraph', 'core/heading', 'core/list', 'core/group' ];
	var MIN     = 240;
	var MAX     = 800;
	var DEFAULT = 400;

	function supported( name ) {
		return BLOCKS.indexOf( name ) !== -1;
	}

	// 1. Register the attribute on the supported blocks.
	addFilter( 'blocks.registerBlockType', 'stjo/measure/attribute', function ( settings, name ) {
		if ( ! supported( name ) ) {
			return settings;
		}
		settings.attributes = Object.assign( {}, settings.attributes, {
			stjoMeasure: { type: 'number', 'default': 0 },
		} );
		return settings;
	} );

	// 2. Inspector panel: toggle + range slider.
	var withControl = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( ! supported( props.name ) || ! props.isSelected ) {
				return el( BlockEdit, props );
			}
			var value = props.attributes.stjoMeasure || 0;
			return el(
				Fragment,
				{},
				el( BlockEdit, props ),
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'Text width', 'stjo' ), initialOpen: false },
						el( ToggleControl, {
							label: __( 'Limit width', 'stjo' ),
							help: __( 'Caps the text to a readable width on desktop and releases to full width once columns stack on mobile.', 'stjo' ),
							checked: value > 0,
							onChange: function ( on ) {
								props.setAttributes( { stjoMeasure: on ? DEFAULT : 0 } );
							},
						} ),
						value > 0 && el( RangeControl, {
							label: __( 'Max width', 'stjo' ),
							value: value,
							min: MIN,
							max: MAX,
							step: 10,
							marks: true,
							onChange: function ( n ) {
								props.setAttributes( { stjoMeasure: n || 0 } );
							},
						} )
					)
				)
			);
		};
	}, 'withStjoMeasureControl' );
	addFilter( 'editor.BlockEdit', 'stjo/measure/control', withControl );

	// 3. Preview the cap in the editor canvas.
	var withPreview = createHigherOrderComponent( function ( BlockListBlock ) {
		return function ( props ) {
			var value = props.attributes && props.attributes.stjoMeasure;
			if ( ! supported( props.name ) || ! value ) {
				return el( BlockListBlock, props );
			}
			var wrapperProps = Object.assign( {}, props.wrapperProps );
			wrapperProps.style = Object.assign( {}, wrapperProps.style, {
				'--stjo-measure': value + 'px',
				maxWidth: 'var(--stjo-measure)',
			} );
			return el( BlockListBlock, Object.assign( {}, props, { wrapperProps: wrapperProps } ) );
		};
	}, 'withStjoMeasurePreview' );
	addFilter( 'editor.BlockListBlock', 'stjo/measure/preview', withPreview );

	// 4. Stamp the saved markup so the front end gets the class + custom prop.
	addFilter( 'blocks.getSaveContent.extraProps', 'stjo/measure/save', function ( extraProps, blockType, attributes ) {
		if ( ! supported( blockType.name ) || ! attributes.stjoMeasure ) {
			return extraProps;
		}
		extraProps.className = ( extraProps.className ? extraProps.className + ' ' : '' ) + 'stjo-measure';
		extraProps.style = Object.assign( {}, extraProps.style, {
			'--stjo-measure': attributes.stjoMeasure + 'px',
		} );
		return extraProps;
	} );
} )( window.wp );
