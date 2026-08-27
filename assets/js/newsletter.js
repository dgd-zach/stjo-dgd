/* Footer newsletter: submit over fetch and confirm inline — the thank-you
 * message takes the Sign Up button's place, so nobody leaves the page.
 * Progressive: with no JS (or when the relay is unconfigured and the form
 * posts straight at Luminate) the plain POST still works, and the no-JS
 * relay path renders the same messages server-side from ?newsletter=. */
( function () {
	'use strict';

	var form = document.querySelector( '.site-footer__form' );
	if ( ! form || ! window.fetch ) {
		return;
	}
	// Only the relay speaks JSON; the LO-direct fallback keeps its full-page POST.
	var action = form.getAttribute( 'action' ) || '';
	if ( -1 === action.indexOf( 'admin-post.php' ) ) {
		return;
	}

	var busy = false;

	function clearError() {
		var old = document.querySelector( '#footer-newsletter .site-footer__form-error' );
		if ( old ) {
			old.remove();
		}
	}

	function showError() {
		clearError();
		var p = document.createElement( 'p' );
		p.className = 'site-footer__form-error';
		p.setAttribute( 'role', 'alert' );
		p.textContent = form.dataset.msgError || 'Something went wrong with your signup. Please check your email address and try again.';
		form.parentNode.insertBefore( p, form );
	}

	function showThanks( button ) {
		clearError();
		var msg = document.createElement( 'p' );
		msg.className = 'site-footer__form-thanks has-yellow-color';
		msg.setAttribute( 'role', 'status' );
		msg.setAttribute( 'tabindex', '-1' );
		msg.textContent = form.dataset.msgThanks || 'Thank you for subscribing!';
		button.replaceWith( msg );
		// The control the user just activated is gone; move focus onto the
		// confirmation so keyboard and screen-reader users land on the news.
		msg.focus();
		form.querySelectorAll( 'input:not([type="hidden"])' ).forEach( function ( input ) {
			input.value = '';
			input.disabled = true;
		} );
	}

	// Console helper: preview the inline states without submitting anything
	// to Luminate. stjoNewsletterPreview() shows the thank-you swap,
	// stjoNewsletterPreview('error') the failure alert.
	window.stjoNewsletterPreview = function ( kind ) {
		if ( 'error' === kind ) {
			showError();
			return;
		}
		var button = form.querySelector( 'button[type="submit"]' );
		if ( button ) {
			showThanks( button );
		}
	};

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();
		if ( busy ) {
			return;
		}
		var button = form.querySelector( 'button[type="submit"]' );
		var data = new FormData( form );
		data.append( 'stjo_ajax', '1' );

		busy = true;
		button.disabled = true;
		var label = button.textContent;
		button.textContent = form.dataset.msgSending || 'Signing up…';

		fetch( action, { method: 'POST', body: data } )
			.then( function ( res ) { return res.json(); } )
			.then( function ( out ) {
				if ( out && out.ok ) {
					showThanks( button );
					return;
				}
				throw new Error( 'rejected' );
			} )
			.catch( function () {
				showError();
				button.disabled = false;
				button.textContent = label;
			} )
			.finally( function () {
				busy = false;
			} );
	} );
} )();
