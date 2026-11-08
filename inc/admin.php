<?php
/** "Site content" screen: every text on the home page as a plain form. */

function hb_flatten( $data, $prefix = '' ) {
	$out = array();
	foreach ( $data as $key => $value ) {
		$path = '' === $prefix ? (string) $key : $prefix . '.' . $key;
		if ( is_array( $value ) ) {
			$out += hb_flatten( $value, $path );
		} else {
			$out[ $path ] = $value;
		}
	}
	return $out;
}

function hb_unflatten( $flat ) {
	$out = array();
	foreach ( $flat as $path => $value ) {
		$ref = &$out;
		foreach ( explode( '.', $path ) as $part ) {
			if ( ! isset( $ref[ $part ] ) ) {
				$ref[ $part ] = array();
			}
			$ref = &$ref[ $part ];
		}
		$ref = $value;
		unset( $ref );
	}
	return $out;
}

add_action( 'admin_menu', function () {
	add_menu_page( 'Site content', 'Site content', 'edit_theme_options', 'hb-site-content', 'hb_site_screen', 'dashicons-edit-page', 4 );
} );

function hb_site_screen() {
	$site = get_option( 'hb_site', array() );
	if ( isset( $_POST['hb_site_nonce'] ) && wp_verify_nonce( $_POST['hb_site_nonce'], 'hb_site_save' ) ) {
		$flat = hb_flatten( $site );
		foreach ( (array) wp_unslash( $_POST['site'] ?? array() ) as $key => $value ) {
			$path = str_replace( '__', '.', $key );
			if ( array_key_exists( $path, $flat ) ) {
				$flat[ $path ] = sanitize_textarea_field( $value );
			}
		}
		$site = hb_unflatten( $flat );
		update_option( 'hb_site', $site );
		echo '<div class="updated"><p>Saved.</p></div>';
	}
	echo '<div class="wrap"><h1>Site content</h1><form method="post">';
	wp_nonce_field( 'hb_site_save', 'hb_site_nonce' );
	echo '<table class="form-table">';
	foreach ( hb_flatten( $site ) as $path => $value ) {
		printf(
			'<tr><th>%s</th><td><textarea class="large-text" rows="2" name="site[%s]">%s</textarea></td></tr>',
			esc_html( ucfirst( str_replace( array( '.', '_' ), ' ', $path ) ) ),
			esc_attr( str_replace( '.', '__', $path ) ),
			esc_textarea( (string) $value )
		);
	}
	echo '</table>';
	submit_button( 'Save changes' );
	echo '</form></div>';
}
