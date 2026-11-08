<?php
/** Films, books and the fields the templates read. */

function hb_film_fields() {
	return array(
		'subtitle'      => 'Subtitle (wrap words in *stars* to colour them)',
		'tagline'       => 'One-line description',
		'meta_line'     => 'Line above the title; replaces the four fields below when filled',
		'year'          => 'Year',
		'rating'        => 'Rating',
		'runtime'       => 'Runtime',
		'language'      => 'Language',
		'genres'        => 'Genres (separate with |)',
		'poster_title'  => 'Poster title',
		'poster_sub'    => 'Poster subtitle',
		'open'          => 'Has its own page (1 or 0)',
		'is_sample'     => 'Placeholder card (1 or 0)',
		'card_pos'      => 'Card image focus, for example 30% 50%',
		'card_zoom'     => 'Card image zoom, for example 1.8',
		'sample_video'  => 'Sample video name',
		'sample_poster' => 'Sample video poster name',
		'sample_label'  => 'Label shown before playing',
		'sample_note'   => 'Note under the player',
		'side_kicker'   => 'Related item: small heading',
		'side_title'    => 'Related item: title',
		'side_detail'   => 'Related item: detail line',
		'side_label'    => 'Related item: button label',
		'side_url'      => 'Related item: button link',
	);
}

function hb_book_fields() {
	return array(
		'accent'     => 'Words of the title to colour',
		'kicker'     => 'Small heading',
		'age'        => 'Age badge',
		'art'        => 'Cover art name',
		'ink'        => 'Cover title colour',
		'specs'      => 'Details, one per line: Label|Value',
		'stores'     => 'Stores, one per line: Button label|Link',
		'store_note' => 'Note under the stores',
		'series'     => 'Series name',
		'open'       => 'Has its own page (1 or 0)',
		'shelf'      => 'Shelf: home, also, main',
	);
}

add_action( 'init', function () {
	register_post_type( 'film', array(
		'label'        => 'Films',
		'public'       => true,
		'has_archive'  => 'films',
		'rewrite'      => array( 'slug' => 'films', 'with_front' => false ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-video-alt2',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );
	register_post_type( 'book', array(
		'label'        => 'Books',
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'books', 'with_front' => false ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-book-alt',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );
	register_taxonomy( 'era', array( 'film' ), array(
		'label'        => 'Eras',
		'public'       => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => false,
	) );
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'hb_film', 'Film details', 'hb_render_fields', 'film', 'normal', 'high', array( 'fields' => hb_film_fields() ) );
	add_meta_box( 'hb_book', 'Book details', 'hb_render_fields', 'book', 'normal', 'high', array( 'fields' => hb_book_fields() ) );
} );

function hb_render_fields( $post, $box ) {
	wp_nonce_field( 'hb_save', 'hb_nonce' );
	echo '<table class="form-table">';
	foreach ( $box['args']['fields'] as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<tr><th><label for="hb_%1$s">%2$s</label></th><td><textarea class="large-text" rows="%4$d" id="hb_%1$s" name="hb[%1$s]">%3$s</textarea></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_textarea( (string) $value ),
			false !== strpos( (string) $value, "\n" ) ? 4 : 1
		);
	}
	echo '</table>';
}

add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['hb_nonce'] ) || ! wp_verify_nonce( $_POST['hb_nonce'], 'hb_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( (array) ( $_POST['hb'] ?? array() ) as $key => $value ) {
		update_post_meta( $post_id, sanitize_key( $key ), sanitize_textarea_field( wp_unslash( $value ) ) );
	}
} );
