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
	 * each decade's watermark + node fade/de-blur in around it, and flagged
	 * card images pan their crop with the scroll (parallax). Under reduced
	 * motion only the line fill runs. */
	function initScrollFx( root ) {
		var inner = root.querySelector( '.stjo-timeline__inner' );
		if ( ! inner ) {
			return;
		}
		var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		var mobile = window.matchMedia( '(max-width: 900px)' );
		var decades = Array.prototype.slice.call( root.querySelectorAll( '.stjo-timeline__decade' ) ).map( function ( section ) {
			return {
				section: section,
				label: section.querySelector( '.stjo-timeline__decade-label' ),
				latched: 0
			};
		} );
		// "Image motion" images (block-level toggle, imageMotion attribute).
		// Horizontal cards crop, so scroll pans object-position Y (focal point
		// keeps X). Vertical cards show the whole photo, so they scale from
		// 1.3x down to 1x instead. Geometry is measured on the media figure:
		// the img's own rect grows while scaled, which would feed back into
		// the math. `rest` is the inline focus style to put back under
		// reduced motion.
		var parallax = Array.prototype.slice.call( root.querySelectorAll( 'img[data-stjo-parallax]' ) ).map( function ( img ) {
			return {
				img: img,
				box: img.closest( '.stjo-timeline-card__media' ) || img,
				zoom: !! img.closest( '.stjo-timeline-card--vertical' ),
				x: window.getComputedStyle( img ).objectPosition.split( /\s+/ )[ 0 ] || '50%',
				rest: img.style.objectPosition
			};
		} );
		var ticking = false;

		function update() {
			ticking = false;
			var rect = inner.getBoundingClientRect();
			var focusY = window.innerHeight / 2;
			var fill = Math.max( 0, Math.min( focusY - rect.top, rect.height ) );
			inner.style.setProperty( '--stjo-tl-progress', fill.toFixed( 1 ) + 'px' );

			var span = window.innerHeight * 0.45;
			decades.forEach( function ( d ) {
				if ( ! d.label ) {
					return;
				}
				if ( reduce.matches ) {
					d.section.style.setProperty( '--stjo-tl-vis', '1' );
					d.section.style.setProperty( '--stjo-tl-blur', '0px' );
					return;
				}
				// One-sided: ramp in while approaching the viewport center from
				// below, stay at full strength once passed. On desktop, scrolling
				// back up walks the same ramp in reverse; on mobile the value
				// latches so headings fade in once and stay in.
				var r = d.label.getBoundingClientRect();
				var delta = r.top + r.height / 2 - focusY;
				var vis = delta <= 0 ? 1 : 1 - Math.min( delta / span, 1 );
				vis = Math.pow( vis, 0.75 );
				d.latched = Math.max( d.latched, vis );
				if ( mobile.matches ) {
					vis = d.latched;
				}
				d.section.style.setProperty( '--stjo-tl-vis', vis.toFixed( 3 ) );
				d.section.style.setProperty( '--stjo-tl-blur', ( ( 1 - vis ) * 5 ).toFixed( 2 ) + 'px' );
			} );

			// Image motion. Travel t runs 0 (entering at the bottom of the
			// screen) to 1 (leaving at the top). Horizontal: t maps onto
			// object-position Y, 100% down to 0%, the same physics as a fixed
			// background; the pan distance is exactly the hidden crop.
			// Vertical: the photo starts at 1.3x and settles softly to 1x by
			// the time the card reaches the viewport center (reversible on
			// back-scroll).
			parallax.forEach( function ( p ) {
				if ( reduce.matches ) {
					if ( p.img.style.objectPosition !== p.rest ) {
						p.img.style.objectPosition = p.rest;
					}
					if ( p.img.style.transform ) {
						p.img.style.transform = '';
					}
					return;
				}
				var pr = p.box.getBoundingClientRect();
				if ( ! pr.height || pr.bottom < 0 || pr.top > window.innerHeight ) {
					return;
				}
				var t = ( window.innerHeight - pr.top ) / ( window.innerHeight + pr.height );
				t = Math.max( 0, Math.min( 1, t ) );
				if ( p.zoom ) {
					var settle = Math.min( t / 0.5, 1 );
					p.img.style.transform = 'scale(' + ( 1 + 0.3 * Math.pow( 1 - settle, 2 ) ).toFixed( 4 ) + ')';
				} else {
					p.img.style.objectPosition = p.x + ' ' + ( ( 1 - t ) * 100 ).toFixed( 2 ) + '%';
				}
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

		// How much has to be hidden before clamping earns its keep, in lines.
		// Clamping on any overflow at all read badly: the fade mask starts at
		// 55%, so half the copy dimmed and a Read More appeared to hide one or
		// two short lines, on a card with visible room to spare. With a 4-line
		// clamp this lets anything up to 6 lines render in full, and reserves
		// the control for entries long enough that hiding them buys something.
		var WORTH_CLAMPING_LINES = 2;

		function evaluate() {
			if ( expanded ) {
				text.style.maxHeight = text.scrollHeight + 'px'; // re-fit after resize
				return;
			}
			// Measure unclamped first: scrollHeight on a clamped element already
			// reports the full content, but reading it while unclamped also
			// survives any future change to how the clamp is applied.
			text.classList.remove( 'is-clamped' );
			var full = text.scrollHeight;
			text.classList.add( 'is-clamped' );
			var visible = text.clientHeight;

			var line = parseFloat( window.getComputedStyle( text ).lineHeight );
			if ( ! line || isNaN( line ) ) {
				line = 24; // 16px base at the 1.5 line-height the clamp assumes
			}
			var worthIt = ( full - visible ) > line * WORTH_CLAMPING_LINES;

			btn.hidden = ! worthIt;
			if ( ! worthIt ) {
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
