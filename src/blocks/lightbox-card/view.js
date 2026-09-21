/* Lightbox cards: each card ships its prescribed lightbox (hero, heading,
   content, link) in an inert inline <template>; activating the card clones
   that into one shared <dialog>. Native showModal() supplies modality
   (Escape, inert background, focus restore on close); this adds a wrapping
   Tab focus trap (showModal alone lets focus step out to the browser UI at
   the ends), the body scroll lock with scrollbar-width compensation (no
   layout jump), and backdrop close. Open/close easing lives in style.css. */
( function () {
	'use strict';

	var dialog = null;
	var contentEl = null;
	// data-stjo-lightbox-id of the lightbox currently open, so the close
	// handler knows which fragment to strip and never clobbers an unrelated one.
	var currentId = null;

	/* ---- URL fragment sync (#<page-slug>) ------------------------------- */
	// Reflect the open lightbox into the URL so it can be linked to and reopened
	// on load. replaceState (not location.hash =) is used throughout: it changes
	// the URL without firing hashchange (no feedback loop with our own writes)
	// and without the native jump-to-anchor scroll. No history entry is pushed,
	// so the Back button leaves the page rather than closing the lightbox.
	function hashId() {
		return location.hash ? decodeURIComponent( location.hash.slice( 1 ) ) : '';
	}
	function setHash( id ) {
		if ( ! window.history || ! history.replaceState || hashId() === id ) {
			return;
		}
		try {
			history.replaceState( history.state, '', '#' + id );
		} catch ( e ) {}
	}
	function clearHash() {
		if ( ! window.history || ! history.replaceState ) {
			return;
		}
		try {
			history.replaceState( history.state, '', location.pathname + location.search );
		} catch ( e ) {}
	}
	function triggerById( id ) {
		if ( ! id ) {
			return null;
		}
		var esc = ( window.CSS && CSS.escape ) ? CSS.escape( id ) : id.replace( /["\\]/g, '\\$&' );
		return document.querySelector( 'button[data-stjo-lightbox][data-stjo-lightbox-id="' + esc + '"]' );
	}

	function buildDialog() {
		dialog = document.createElement( 'dialog' );
		dialog.className = 'stjo-lightbox';
		dialog.innerHTML =
			'<button type="button" class="stjo-lightbox__close" aria-label="Close dialog">' +
				'<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>' +
			'</button>' +
			'<div class="stjo-lightbox__content"></div>';
		contentEl = dialog.querySelector( '.stjo-lightbox__content' );
		// One handler covers the corner X and the template's Close button.
		dialog.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( '.stjo-lightbox__close, [data-stjo-lightbox-close]' ) ) {
				dialog.close();
			}
		} );
		// Only the backdrop registers the dialog element itself as the target.
		dialog.addEventListener( 'mousedown', function ( e ) {
			if ( e.target === dialog ) {
				dialog.close();
			}
		} );
		dialog.addEventListener( 'close', function () {
			document.body.classList.remove( 'modal-open' );
			document.documentElement.style.removeProperty( '--stjo-scrollbar-comp' );
			dialog.classList.remove( 'is-scrollable' );
			// Drop the fragment only if it is still this lightbox's, so a hash
			// left by something else on the page is untouched.
			if ( currentId && hashId() === currentId ) {
				clearHash();
			}
			currentId = null;
		} );
		// Classic (non-overlay) scrollbars take their width out of the dialog's
		// box, so the moment long content made the dialog scroll (an FAQ answer
		// opening) the text reflowed narrower and jumped left. Flag a scrolling
		// dialog and measure its bar; style.css widens the dialog by that much
		// so the content box keeps its size. ResizeObserver runs before paint,
		// so the widening lands in the same frame as the scrollbar.
		if ( window.ResizeObserver ) {
			var observer = new ResizeObserver( syncScrollbar );
			observer.observe( dialog );
			observer.observe( contentEl );
		}
		// showModal() inerts the page but lets Tab step out to the browser UI at
		// the ends — wrap it so focus stays inside the dialog.
		dialog.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Tab' ) {
				return;
			}
			var focusables = Array.prototype.filter.call(
				dialog.querySelectorAll( 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])' ),
				function ( el ) { return el.offsetWidth > 0 || el.offsetHeight > 0 || el.getClientRects().length > 0; }
			);
			if ( ! focusables.length ) {
				return;
			}
			var first = focusables[ 0 ];
			var last = focusables[ focusables.length - 1 ];
			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		} );
		document.body.appendChild( dialog );
	}

	function syncScrollbar() {
		if ( ! dialog || ! dialog.open ) {
			return;
		}
		var scrollable = dialog.scrollHeight > dialog.clientHeight;
		if ( scrollable && ! dialog.classList.contains( 'is-scrollable' ) ) {
			var bar = dialog.offsetWidth - dialog.clientWidth; // 0 for overlay scrollbars
			if ( bar > 0 ) {
				dialog.style.setProperty( '--stjo-lightbox-scrollbar', bar + 'px' );
			}
		}
		dialog.classList.toggle( 'is-scrollable', scrollable );
	}

	/* The template sits next to the trigger inside whatever card markup hosts
	   them (lightbox-card block, story card, ...), so walk up to the nearest
	   ancestor that contains one. */
	function findTemplate( trigger ) {
		var node = trigger.parentElement;
		while ( node && node !== document.body ) {
			var tpl = node.querySelector( 'template[data-stjo-lightbox-template]' );
			if ( tpl ) {
				return tpl;
			}
			node = node.parentElement;
		}
		return null;
	}

	function open( trigger ) {
		var template = findTemplate( trigger );
		if ( ! template ) {
			return;
		}
		if ( ! dialog ) {
			buildDialog();
		}
		contentEl.innerHTML = '';
		contentEl.appendChild( template.content.cloneNode( true ) );
		// Freshly cloned content has never been seen by the page's enhancers
		// (template content is inert, and clones carry no listeners). Announce
		// the fill so they can wire what just arrived — faq-accordion.js
		// listens for this; anything else content pages need can hook it too.
		contentEl.dispatchEvent( new CustomEvent( 'stjo:lightbox-open', { bubbles: true } ) );
		// The heading can be hidden per block (content pages that open with their
		// own); the template still carries the title so the dialog keeps its name.
		var title = contentEl.querySelector( '.stjo-lightbox__title' );
		var name  = title ? title.textContent : ( template.getAttribute( 'data-stjo-lightbox-title' ) || trigger.textContent.trim() );
		dialog.setAttribute( 'aria-label', name );
		// Reserve the scrollbar's width before overflow:hidden removes it, so
		// the page doesn't shift sideways (body.modal-open pads by this var).
		var scrollbar = window.innerWidth - document.documentElement.clientWidth;
		document.documentElement.style.setProperty( '--stjo-scrollbar-comp', scrollbar + 'px' );
		document.body.classList.add( 'modal-open' );
		dialog.showModal();
		syncScrollbar();
		// Reflect this lightbox into the URL so it can be shared / reopened.
		currentId = trigger.getAttribute( 'data-stjo-lightbox-id' ) || null;
		if ( currentId ) {
			setHash( currentId );
		}
	}

	document.addEventListener( 'click', function ( e ) {
		var trigger = e.target.closest( 'button[data-stjo-lightbox]' );
		if ( ! trigger || ! window.HTMLDialogElement ) {
			return;
		}
		open( trigger );
	} );

	// Deep link: open the lightbox named by the URL fragment — on load, and if
	// the fragment later changes to one (an in-page link, Back/Forward). A
	// fragment matching no lightbox closes any that is open.
	function syncFromHash() {
		if ( ! window.HTMLDialogElement ) {
			return;
		}
		var id = hashId();
		var trigger = triggerById( id );
		if ( trigger ) {
			if ( dialog && dialog.open && currentId === id ) {
				return;
			}
			if ( dialog && dialog.open ) {
				dialog.close();
			}
			open( trigger );
		} else if ( dialog && dialog.open ) {
			dialog.close();
		}
	}
	window.addEventListener( 'hashchange', syncFromHash );
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', syncFromHash );
	} else {
		syncFromHash();
	}
} )();
