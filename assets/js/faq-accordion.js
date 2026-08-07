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
			// Everything after the question is the answer; wrap it so one id
			// controls the whole panel regardless of how many blocks it holds.
			var panel = document.createElement( 'div' );
			panel.className = 'schema-faq-answer-wrap';
			panel.id = 'stjo-faq-panel-' + ( ++uid );
			var node = question.nextSibling;
			while ( node ) {
				var next = node.nextSibling;
				panel.appendChild( node );
				node = next;
			}
			section.appendChild( panel );

			question.setAttribute( 'role', 'button' );
			question.setAttribute( 'tabindex', '0' );
			question.setAttribute( 'aria-expanded', 'false' );
			question.setAttribute( 'aria-controls', panel.id );

			function toggle() {
				var open = ! section.classList.contains( 'is-open' );
				section.classList.toggle( 'is-open', open );
				question.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
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

	document.querySelectorAll( '.wp-block-yoast-faq-block.is-style-accordion' ).forEach( initBlock );
} )();
