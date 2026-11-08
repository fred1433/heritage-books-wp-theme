<?php
/** Heritage Books theme bootstrap. */
require_once __DIR__ . '/inc/content-types.php';
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/admin.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
} );

add_action( 'wp_enqueue_scripts', function () {
	$v = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'heritage-books', get_template_directory_uri() . '/assets/css/site.css', array(), $v );
	wp_enqueue_script( 'heritage-books', get_template_directory_uri() . '/assets/js/site.js', array(), $v, array( 'strategy' => 'defer', 'in_footer' => true ) );
	foreach ( array( 'wp-block-library', 'global-styles', 'classic-theme-styles' ) as $h ) {
		wp_dequeue_style( $h );
	}
}, 20 );

add_action( 'wp_head', function () {
	foreach ( array( 'josefin-sans-latin-700-normal', 'playfair-display-latin-700-normal', 'poppins-latin-400-normal' ) as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( get_template_directory_uri() . '/assets/fonts/' . $font . '.woff2' ) );
	}
}, 1 );

/* Keep the front end free of anything the pages do not use. */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_print_auto_sizes_contain_css_fix', 1 );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
remove_action( 'wp_head', 'wp_resource_hints', 2 );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
add_filter( 'should_load_separate_core_block_assets', '__return_true' );
add_filter( 'wp_speculation_rules_configuration', '__return_null' );
add_filter( 'show_admin_bar', function ( $show ) { return is_admin() ? $show : false; } );

/* A site can ask to stay out of search engines from its seed data. */
add_filter( 'wp_robots', function ( $robots ) {
	if ( hb_site( 'noindex' ) ) {
		return array( 'noindex' => true, 'nofollow' => true, 'noarchive' => true );
	}
	return $robots;
} );

add_filter( 'document_title_parts', function ( $parts ) {
	unset( $parts['tagline'] );
	return $parts;
} );
