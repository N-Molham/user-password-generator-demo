/**
 * Password Generator
 */
( function( w ) {
	jQuery( function( $ ) {
		// reference inputs for later use
		var $user_special = $( '#generator-use-special' ),
			$use_extra_special = $( '#generator-use-extra' ),
			$password_length = $( '#generator-length' ),
			$password_result = $( '#generator-result' ),
			$password_inputs = $( '#pass1, #pass2' );

		// random string picker function
		var random_string = function ( length, user_spcial, user_extra_special ) {
			// insure length be at least 12 chars and an Integer
			length = parseInt( length );
			if ( isNaN( length ) || length < 12 )
				length = 12;

			// string to pick from
			var pick_from = upgd.password.chars;

			// use special characters
			if ( user_spcial )
				pick_from += upgd.password.special_chars;
			
			// use EXTAR special characters
			if ( user_extra_special )
				pick_from += upgd.password.extra_special_chars;

			// begin selection
			var result = '';
			for ( var i = length; i > 0; --i ) {
				result += pick_from[ Math.round( Math.random() * ( pick_from.length - 1 ) ) ];
			}

			return result;
		};

		// button clicked
		$( '#generator-button' ).on( 'click', function( e ) {
			e.preventDefault();

			// new password
			var new_password = random_string( $password_length.val(), $user_special.is( ':checked' ), $use_extra_special.is( ':checked' ) );

			// update result holder for later user
			$password_result.val( new_password );

			// update password inputs
			$password_inputs.val( new_password )
							// trigger keyup event so password strength indicator refresh it's status
							.trigger( 'keyup' );
		} );

		// auto-select generated password
		$password_result.on( 'click', function() {
			this.select();
		} );
	} );
} )( window );