/* Timeline block — decade sliders with year-chip tabs, read-more expanders,
 * scroll reveals. Progressive enhancement over the stacked server markup:
 * without this file everything is visible and readable. */
( function () {
	'use strict';

	function initTimeline( root ) {
		if ( root.dataset.stjoTlInit ) {
			return;
		}
		root.dataset.stjoTlInit = '1';
		root.classList.add( 'is-js' );

		var revealables = root.querySelectorAll( '[data-reveal]' );
		if ( 'IntersectionObserver' in window ) {
			var io = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-revealed' );
						io.unobserve( entry.target );
					}
				} );
			}, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 } );
			revealables.forEach( function ( el ) {
				io.observe( el );
			} );
		} else {
			revealables.forEach( function ( el ) {
				el.classList.add( 'is-revealed' );
			} );
		}

		root.querySelectorAll( '.stjo-timeline-card' ).forEach( initReadMore );
		root.querySelectorAll( '.stjo-timeline__decade' ).forEach( initDecade );
		initScrollFx( root );
	}

	/* Scroll-linked effects: the navy line fill tracks the viewport center,
	 * and each decade's watermark + node fade/de-blur in around it (both
	 * directions). Under reduced motion only the line fill runs. */
	function initScrollFx( root ) {
		var inner = root.querySelector( '.stjo-timeline__inner' );
		if ( ! inner ) {
			return;
		}
		var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		var decades = Array.prototype.slice.call( root.querySelectorAll( '.stjo-timeline__decade' ) );
		var ticking = false;

		function update() {
			ticking = false;
			var rect = inner.getBoundingClientRect();
			var focusY = window.innerHeight / 2;
			var fill = Math.max( 0, Math.min( focusY - rect.top, rect.height ) );
			inner.style.setProperty( '--stjo-tl-progress', fill.toFixed( 1 ) + 'px' );

			var span = window.innerHeight * 0.45;
			decades.forEach( function ( section ) {
				var label = section.querySelector( '.stjo-timeline__decade-label' );
				if ( ! label ) {
					return;
				}
				if ( reduce.matches ) {
					section.style.setProperty( '--stjo-tl-vis', '1' );
					section.style.setProperty( '--stjo-tl-blur', '0px' );
					return;
				}
				// One-sided: ramp in while approaching the viewport center from
				// below, stay at full strength once passed. Scrolling back up
				// walks the same ramp in reverse.
				var r = label.getBoundingClientRect();
				var delta = r.top + r.height / 2 - focusY;
				var vis = delta <= 0 ? 1 : 1 - Math.min( delta / span, 1 );
				vis = Math.pow( vis, 0.75 );
				section.style.setProperty( '--stjo-tl-vis', vis.toFixed( 3 ) );
				section.style.setProperty( '--stjo-tl-blur', ( ( 1 - vis ) * 5 ).toFixed( 2 ) + 'px' );
			} );
		}

		function requestUpdate() {
			if ( ! ticking ) {
				ticking = true;
				requestAnimationFrame( update );
			}
		}

		window.addEventListener( 'scroll', requestUpdate, { passive: true } );
		window.addEventListener( 'resize', requestUpdate );
		update();
	}

	function initDecade( section ) {
		var cardsWrap = section.querySelector( '.stjo-timeline__cards' );
		var viewport = section.querySelector( '.stjo-timeline__viewport' );
		var track = section.querySelector( '.stjo-timeline__track' );
		var chipsWrap = section.querySelector( '.stjo-timeline__chips' );
		if ( ! cardsWrap || ! viewport || ! track || ! chipsWrap ) {
			return; // Single-event decade: keep the plain stacked card.
		}

		var cards = Array.prototype.slice.call( track.children );
		var chips = Array.prototype.slice.call( chipsWrap.querySelectorAll( '.stjo-timeline__chip' ) );
		if ( cards.length < 2 || chips.length !== cards.length ) {
			return;
		}

		var active = 0;
		var resizeObserver = null;

		cardsWrap.classList.add( 'is-slider' );
		// Shadow bleed padding on the viewport (see style.css) — part of its
		// border-box height, so it joins the height math below.
		var bleed = parseFloat( window.getComputedStyle( viewport ).paddingTop ) || 0;
		chipsWrap.setAttribute( 'role', 'tablist' );
		chipsWrap.setAttribute( 'aria-label', ( chipsWrap.dataset.decadeLabel || '' ) + ' milestones' );
		chips.forEach( function ( chip, i ) {
			chip.setAttribute( 'role', 'tab' );
			chip.setAttribute( 'aria-controls', chip.dataset.card );
			cards[ i ].setAttribute( 'role', 'tabpanel' );
			cards[ i ].setAttribute( 'aria-labelledby', chip.id );
			chip.addEventListener( 'click', function () {
				setActive( i, false );
			} );
		} );

		chipsWrap.addEventListener( 'keydown', function ( e ) {
			var next = null;
			if ( 'ArrowRight' === e.key ) {
				next = ( active + 1 ) % cards.length;
			} else if ( 'ArrowLeft' === e.key ) {
				next = ( active - 1 + cards.length ) % cards.length;
			} else if ( 'Home' === e.key ) {
				next = 0;
			} else if ( 'End' === e.key ) {
				next = cards.length - 1;
			}
			if ( null !== next ) {
				e.preventDefault();
				setActive( next, true );
			}
		} );

		function gap() {
			var g = parseFloat( window.getComputedStyle( track ).columnGap );
			return isNaN( g ) ? 0 : g;
		}

		function position() {
			track.style.transform = 'translateX(-' + active * ( cards[ active ].offsetWidth + gap() ) + 'px)';
		}

		function setHeight() {
			viewport.style.height = ( cards[ active ].offsetHeight + bleed * 2 ) + 'px';
		}

		function watchActiveCard() {
			if ( ! ( 'ResizeObserver' in window ) ) {
				return;
			}
			if ( resizeObserver ) {
				resizeObserver.disconnect();
			}
			resizeObserver = new ResizeObserver( setHeight );
			resizeObserver.observe( cards[ active ] );
		}

		function setActive( i, focusChip ) {
			active = i;
			chips.forEach( function ( chip, j ) {
				var selected = j === i;
				chip.setAttribute( 'aria-selected', selected ? 'true' : 'false' );
				chip.setAttribute( 'tabindex', selected ? '0' : '-1' );
				chip.classList.toggle( 'is-active', selected );
			} );
			cards.forEach( function ( card, j ) {
				if ( j === i ) {
					card.removeAttribute( 'aria-hidden' );
					card.inert = false;
				} else {
					card.setAttribute( 'aria-hidden', 'true' );
					card.inert = true;
				}
			} );
			position();
			setHeight();
			watchActiveCard();
			if ( focusChip ) {
				chips[ i ].focus();
			}
		}

		var resizeRaf = 0;
		window.addEventListener( 'resize', function () {
			cancelAnimationFrame( resizeRaf );
			resizeRaf = requestAnimationFrame( function () {
				position();
				setHeight();
			} );
		} );

		setActive( 0, false );
	}

	function initReadMore( card ) {
		var text = card.querySelector( '.stjo-timeline-card__text' );
		var btn = card.querySelector( '.stjo-timeline-card__more' );
		if ( ! text || ! btn ) {
			return;
		}
		var label = btn.querySelector( '.stjo-timeline-card__more-label' );
		var expanded = false;

		// Clamp only when the text actually overflows three lines; a text that
		// fits stays unclamped so the fade mask never dims complete copy.
		function evaluate() {
			if ( expanded ) {
				text.style.maxHeight = text.scrollHeight + 'px'; // re-fit after resize
				return;
			}
			text.classList.add( 'is-clamped' );
			var overflowing = text.scrollHeight > text.clientHeight + 1;
			btn.hidden = ! overflowing;
			if ( ! overflowing ) {
				text.classList.remove( 'is-clamped' );
			}
		}
		evaluate();
		if ( document.fonts && document.fonts.ready ) {
			document.fonts.ready.then( evaluate );
		}
		var evalRaf = 0;
		window.addEventListener( 'resize', function () {
			cancelAnimationFrame( evalRaf );
			evalRaf = requestAnimationFrame( evaluate );
		} );

		btn.addEventListener( 'click', function () {
			expanded = ! expanded;
			if ( expanded ) {
				text.style.maxHeight = text.scrollHeight + 'px';
				text.classList.add( 'is-expanded' );
			} else {
				text.style.maxHeight = '';
				text.classList.remove( 'is-expanded' );
			}
			btn.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );
			if ( label ) {
				label.textContent = expanded ? ( btn.dataset.labelLess || 'Show Less' ) : ( btn.dataset.labelMore || 'Read More' );
			}
		} );
	}

	document.querySelectorAll( '.stjo-timeline' ).forEach( initTimeline );
} )();
