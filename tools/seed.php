<?php
/**
 * Loads a content file into a fresh WordPress install: site settings, films, eras, books.
 * Called from the blueprint. hb_seed( $json_path, $media_dir ). Safe to run twice: it updates by slug.
 */
function hb_seed_post( $type, $item, $order ) {
	$slug = $item['slug'] ?? sanitize_title( $item['title'] );
	$args = array(
		'post_type'    => $type,
		'post_status'  => 'publish',
		'post_title'   => $item['title'],
		'post_name'    => $slug,
		'post_content' => $item['body'] ?? '',
		'menu_order'   => $order,
	);
	$existing = get_page_by_path( $slug, OBJECT, $type );
	if ( $existing ) {
		$args['ID'] = $existing->ID;
		return wp_update_post( $args );
	}
	return wp_insert_post( $args );
}

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

	/* Media: a file already loaded under this name is kept. */
	$map = get_option( 'hb_media', array() );
	if ( $media_dir && is_dir( $media_dir ) ) {
		foreach ( glob( $media_dir . '/*' ) as $file ) {
			$name = pathinfo( $file, PATHINFO_FILENAME );
			if ( ! empty( $map[ $name ] ) && get_post( $map[ $name ] ) ) {
				continue;
			}
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
		$id = hb_seed_post( 'film', $film, $order );
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
		$id = hb_seed_post( 'book', $book, $order );
		foreach ( $book['fields'] ?? array() as $key => $value ) {
			update_post_meta( $id, $key, $value );
		}
	}

	/* Pages */
	foreach ( $data['pages'] ?? array() as $page ) {
		hb_seed_post( 'page', $page, 0 );
	}

	flush_rewrite_rules( false );
}
