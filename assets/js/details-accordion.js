/* Animate the native <details> accordion (.stjo-accordion) open/close with the
 * same grid-rows technique the Yoast FAQ accordion uses, so it animates in every
 * browser. A native <details> toggles instantly, and the CSS-only alternative
 * (::details-content + block-size:auto) only animates in Chromium via
 * interpolate-size — so this JS takes over the toggle instead.
 *
 * Progressive enhancement: the panels only collapse once .is-enhanced is on the
 * group (see sections.css), so with no JS every <details> works natively and
 * every panel stays reachable. Mirrors faq-accordion.js, lightbox re-scan and all. */
( function () {
	'use strict';

	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var DURATION = 250; // keep in sync with the grid-template-rows transition in sections.css

	function initDetails( details ) {
		if ( details.dataset.stjoAccordion ) {
			return;
		}
		var summary = details.querySelector( ':scope > summary' );
		if ( ! summary ) {
			return;
		}

		// Wrap everything after the summary: the outer .panel is the grid whose
		// single row animates 0fr↔1fr; the inner clips overflow so the height
		// reads smoothly and a collapsed panel truly measures 0.
		var panel = document.createElement( 'div' );
		panel.className = 'stjo-accordion__panel';
		var inner = document.createElement( 'div' );
		inner.className = 'stjo-accordion__panel-inner';
		var node = summary.nextSibling;
		while ( node ) {
			var next = node.nextSibling;
			inner.appendChild( node );
			node = next;
		}
		panel.appendChild( inner );
		details.appendChild( panel );
		details.dataset.stjoAccordion = '1';

		var isOpen = details.open;
		details.classList.toggle( 'is-open', isOpen );
		panel.inert = ! isOpen; // collapsed: out of tab order + hidden from AT

		var timer = 0;

		function openIt() {
			window.clearTimeout( timer );
			details.open = true; // put the panel in flow so it can be measured/painted
			panel.inert = false;
			if ( reduce ) {
				details.classList.add( 'is-open' );
				return;
			}
			// Two frames so the 0fr starting point is committed before the flip to 1fr.
			requestAnimationFrame( function () {
				requestAnimationFrame( function () {
					details.classList.add( 'is-open' );
				} );
			} );
		}

		function close() {
			window.clearTimeout( timer );
			panel.inert = true;
			details.classList.remove( 'is-open' ); // grid → 0fr
			if ( reduce ) {
				details.open = false;
				return;
			}
			// Keep the element open (content painted) until the collapse finishes,
			// then drop the attribute. A timeout, not transitionend, so an
			// unmeasurable/zero-height panel can never leave it stuck open.
			timer = window.setTimeout( function () {
				details.open = false;
			}, DURATION + 50 );
		}

		summary.addEventListener( 'click', function ( e ) {
			e.preventDefault(); // take over the native instant toggle (keyboard Enter/Space routes through click too)
			if ( details.classList.contains( 'is-open' ) ) {
				close();
			} else {
				openIt();
			}
		} );
	}

	function scan( root ) {
		root.querySelectorAll( '.stjo-accordion:not(.is-enhanced)' ).forEach( function ( group ) {
			group.querySelectorAll( '.wp-block-details' ).forEach( initDetails );
			group.classList.add( 'is-enhanced' );
		} );
	}

	scan( document );

	// Lightbox content is cloned in from an inert <template> after load; view.js
	// fires this so any accordion that arrives in the dialog gets wired too. The
	// :not(.is-enhanced) guard keeps repeat opens idempotent.
	document.addEventListener( 'stjo:lightbox-open', function ( e ) {
		if ( e.target && e.target.querySelectorAll ) {
			scan( e.target );
		}
	} );
} )();
