<?php
/**
 * Loads a content file into a fresh WordPress install: site settings, films, eras, books.
 * Called from the blueprint. hb_seed( $json_path, $media_dir ).
 */
function hb_seed( $json_path, $media_dir = '' ) {
	wp_set_current_user( 1 );
	$theme_dir = dirname( __DIR__ );
	$slug      = basename( $theme_dir );
	if ( get_stylesheet() !== $slug ) {
		switch_theme( $slug );
	}
	if ( ! post_type_exists( 'film' ) ) {
		require_once $theme_dir . '/inc/helpers.php';
		require_once $theme_dir . '/inc/content-types.php';
		do_action( 'init' );
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
	add_filter( 'big_image_size_threshold', '__return_false' );

	$data = json_decode( file_get_contents( $json_path ), true );
	update_option( 'blogname', $data['site']['brand'] ?? 'Heritage Books' );
	update_option( 'blogdescription', '' );
	update_option( 'hb_site', $data['site'] );
	update_option( 'permalink_structure', '/%postname%/' );
	update_option( 'show_on_front', 'posts' );

	/* Media */
	$map = array();
	if ( $media_dir && is_dir( $media_dir ) ) {
		foreach ( glob( $media_dir . '/*' ) as $file ) {
			$name = pathinfo( $file, PATHINFO_FILENAME );
			$copy = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) );
			if ( ! empty( $copy['error'] ) ) {
				continue;
			}
			$type = wp_check_filetype( $copy['file'] );
			$id   = wp_insert_attachment( array( 'post_title' => $name, 'post_mime_type' => $type['type'], 'post_status' => 'inherit' ), $copy['file'] );
			$map[ $name ] = $id;
		}
	}
	update_option( 'hb_media', $map );

	/* Eras */
	foreach ( $data['site']['eras'] as $era ) {
		if ( ! term_exists( $era['slug'], 'era' ) ) {
			wp_insert_term( $era['name'], 'era', array( 'slug' => $era['slug'] ) );
		}
	}

	/* Films */
	foreach ( $data['films'] as $order => $film ) {
		$id = wp_insert_post( array(
			'post_type'    => 'film',
			'post_status'  => 'publish',
			'post_title'   => $film['title'],
			'post_name'    => $film['slug'] ?? '',
			'post_content' => $film['body'] ?? '',
			'menu_order'   => $order,
		) );
		wp_set_object_terms( $id, $film['era'], 'era' );
		foreach ( $film['fields'] ?? array() as $key => $value ) {
			update_post_meta( $id, $key, $value );
		}
		if ( ! empty( $film['image'] ) && isset( $map[ $film['image'] ] ) ) {
			set_post_thumbnail( $id, $map[ $film['image'] ] );
		}
	}

	/* Books */
	foreach ( $data['books'] as $order => $book ) {
		$id = wp_insert_post( array(
			'post_type'    => 'book',
			'post_status'  => 'publish',
			'post_title'   => $book['title'],
			'post_name'    => $book['slug'] ?? '',
			'post_content' => $book['body'] ?? '',
			'menu_order'   => $order,
		) );
		foreach ( $book['fields'] ?? array() as $key => $value ) {
			update_post_meta( $id, $key, $value );
		}
	}

	/* Pages */
	foreach ( $data['pages'] ?? array() as $page ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $page['title'],
			'post_name'    => $page['slug'],
			'post_content' => $page['body'],
		) );
	}

	flush_rewrite_rules( false );
}
