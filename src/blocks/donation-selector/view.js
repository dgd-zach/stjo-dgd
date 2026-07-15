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
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			update();
			var base = form.dataset.baseUrl || '';
			var params = new URLSearchParams( {
				df_id: form.dataset.loFormId || '',
				FREQ: freq(),
				AMOUNT: amount()
			} );
			window.location.assign( base + ( base.indexOf( '?' ) === -1 ? '?' : '&' ) + params.toString() );
		} );
		update();
	}
	document.querySelectorAll( '[class*="donation-selector__form"]' ).forEach( init );
} )();
