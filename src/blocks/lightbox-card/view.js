/* Lightbox cards: each card ships its prescribed lightbox (hero, heading,
   content, link) in an inert inline <template>; activating the card clones
   that into one shared <dialog>. Native showModal() supplies modality
   (focus trap, Escape, inert background, focus restore on close); this adds
   the body scroll lock with scrollbar-width compensation (no layout jump)
   and backdrop close. Open/close easing lives in style.css. */
( function () {
	'use strict';

	var dialog = null;
	var contentEl = null;

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
		} );
		document.body.appendChild( dialog );
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
		var title = contentEl.querySelector( '.stjo-lightbox__title' );
		dialog.setAttribute( 'aria-label', title ? title.textContent : trigger.textContent.trim() );
		// Reserve the scrollbar's width before overflow:hidden removes it, so
		// the page doesn't shift sideways (body.modal-open pads by this var).
		var scrollbar = window.innerWidth - document.documentElement.clientWidth;
		document.documentElement.style.setProperty( '--stjo-scrollbar-comp', scrollbar + 'px' );
		document.body.classList.add( 'modal-open' );
		dialog.showModal();
	}

	document.addEventListener( 'click', function ( e ) {
		var trigger = e.target.closest( 'button[data-stjo-lightbox]' );
		if ( ! trigger || ! window.HTMLDialogElement ) {
			return;
		}
		open( trigger );
	} );
} )();
