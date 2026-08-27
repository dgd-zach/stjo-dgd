/* Turns a Yoast FAQ block with the "Accordion" style into a real disclosure
 * accordion. Yoast renders <div.schema-faq-section> containing a
 * <strong.schema-faq-question> then answer block(s) — not a native <details> —
 * so this wires up the toggle + ARIA. Progressive enhancement: the block only
 * collapses once .is-enhanced is set (CSS), so with no JS every answer stays
 * visible and readable. */
( function () {
	'use strict';

	var uid = 0;

	function initBlock( block ) {
		var sections = block.querySelectorAll( '.schema-faq-section' );
		if ( ! sections.length ) {
			return;
		}
		Array.prototype.forEach.call( sections, function ( section ) {
			var question = section.querySelector( '.schema-faq-question' );
			if ( ! question ) {
				return;
			}
			// Everything after the question is the answer. Two nested wrappers:
			// the outer .wrap is the grid whose row track animates 0fr↔1fr; the
			// inner clips its overflow so the height transition reads smoothly.
			var panel = document.createElement( 'div' );
			panel.className = 'schema-faq-answer-wrap';
			panel.id = 'stjo-faq-panel-' + ( ++uid );
			var inner = document.createElement( 'div' );
			inner.className = 'schema-faq-answer-inner';
			var node = question.nextSibling;
			while ( node ) {
				var next = node.nextSibling;
				inner.appendChild( node );
				node = next;
			}
			panel.appendChild( inner );
			section.appendChild( panel );

			question.setAttribute( 'role', 'button' );
			question.setAttribute( 'tabindex', '0' );
			question.setAttribute( 'aria-expanded', 'false' );
			question.setAttribute( 'aria-controls', panel.id );
			panel.inert = true; // collapsed: out of tab order + hidden from AT

			function toggle() {
				var open = ! section.classList.contains( 'is-open' );
				section.classList.toggle( 'is-open', open );
				question.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
				panel.inert = ! open;
			}
			question.addEventListener( 'click', toggle );
			question.addEventListener( 'keydown', function ( e ) {
				if ( 'Enter' === e.key || ' ' === e.key || 'Spacebar' === e.key ) {
					e.preventDefault();
					toggle();
				}
			} );
		} );
		block.classList.add( 'is-enhanced' );
	}

	function scan( root ) {
		root.querySelectorAll( '.wp-block-yoast-faq-block.is-style-accordion:not(.is-enhanced)' ).forEach( initBlock );
	}

	scan( document );

	// Lightbox content lives in an inert <template> until view.js clones it
	// into the dialog, so the load-time scan above never sees it — and clones
	// carry no listeners even when it did. view.js announces each fill with
	// this event; scanning the dialog wires any FAQ that arrived in it. The
	// :not(.is-enhanced) guard makes repeat opens idempotent.
	document.addEventListener( 'stjo:lightbox-open', function ( e ) {
		if ( e.target && e.target.querySelectorAll ) {
			scan( e.target );
		}
	} );
} )();
