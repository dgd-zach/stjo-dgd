/* Donation selector — builds the Luminate Online deep link from the chosen
 * frequency + amount. Params are placeholders until the real LO form contract
 * is confirmed: df_id (form id), FREQ (monthly|once), AMOUNT (dollars). */
( function () {
	function init( form ) {
		var choices = form.querySelector( 'input[name="choices"]' );
		var other = form.querySelector( '.js-donsel-other, [class$="__other"]' );
		var freq = function () {
			var f = form.querySelector( 'input[value="monthly"], input[value="once"]' );
			var checked = form.querySelector( 'fieldset:first-of-type input:checked' );
			return checked ? checked.value : 'monthly';
		};
		var amount = function () {
			var otherVal = ( other && other.value.replace( /[^0-9.]/g, '' ) ) || '';
			if ( otherVal ) return otherVal;
			var checked = form.querySelector( '[class*="__amounts"] input:checked' );
			return checked ? checked.value : '';
		};
		var update = function () {
			if ( choices ) choices.value = 'freq=' + freq() + ';amount=' + amount();
		};
		form.addEventListener( 'change', update );
		if ( other ) {
			other.addEventListener( 'input', function () {
				if ( other.value.trim() ) {
					form.querySelectorAll( '[class*="__amounts"] input' ).forEach( function ( r ) { r.checked = false; } );
				}
				update();
			} );
			form.querySelectorAll( '[class*="__amounts"] input' ).forEach( function ( r ) {
				r.addEventListener( 'change', function () { other.value = ''; } );
			} );
		}
		// Each frequency can point at its own LO form; fall back to the
		// generic id (data-lo-form-id) when a per-frequency one is blank.
		var formId = function () {
			var f = freq();
			var perFreq = 'once' === f ? form.dataset.loFormIdOnce : form.dataset.loFormIdMonthly;
			return perFreq || form.dataset.loFormId || '';
		};
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			update();
			var base = form.dataset.baseUrl || '';
			var params = new URLSearchParams( {
				df_id: formId(),
				setAmount: amount()
			} );
			// A dedicated monthly form already implies the frequency, so only
			// send setFreq when one shared form handles both.
			if ( '1' !== form.dataset.monthlyForm ) {
				params.set( 'setFreq', freq() );
			}
			var url = base + ( base.indexOf( '?' ) === -1 ? '?' : '&' ) + params.toString();
			// The giving form lives on give.stjo.org, so it opens in a new tab
			// like every other off-site link. Plain window.open (not the
			// 'noopener' feature, which returns null even on success) so a
			// blocked popup can be detected and the visitor still gets there.
			var win = window.open( url, '_blank' );
			if ( win ) {
				win.opener = null;
			} else {
				window.location.assign( url );
			}
		} );
		update();
	}
	document.querySelectorAll( '[class*="donation-selector__form"]' ).forEach( init );
} )();
