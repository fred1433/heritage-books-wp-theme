<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<?php $overlay = is_front_page() || is_singular( 'film' ) || is_post_type_archive( 'film' ); ?>
<body <?php body_class( $overlay ? 'header-over' : 'header-solid' ); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php echo esc_html( hb_site( 'labels.skip', 'Skip to content' ) ); ?></a>
<?php if ( hb_site( 'banner.text' ) ) : ?>
	<p class="preview-banner"><span><?php echo esc_html( hb_site( 'banner.text' ) ); ?></span>
	<?php if ( hb_site( 'banner.link_label' ) ) : ?>
		<a href="<?php echo esc_url( home_url( hb_site( 'banner.link_path', '/' ) ) ); ?>"><?php echo esc_html( hb_site( 'banner.link_label' ) ); ?></a>
	<?php endif; ?></p>
<?php endif; ?>
<header class="site-header">
	<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<span><?php echo esc_html( hb_site( 'brand', get_bloginfo( 'name' ) ) ); ?></span>
		<?php if ( hb_site( 'brand_chip' ) ) : ?><i><?php echo esc_html( hb_site( 'brand_chip' ) ); ?></i><?php endif; ?>
	</a>
	<nav class="nav-pill" aria-label="Main">
		<?php
		$book    = hb_open_book();
		$film    = hb_sample_film();
		$on_film = $film && is_singular( 'film' ) && get_queried_object_id() === $film->ID;
		?>
		<?php if ( $book ) : ?>
			<a href="<?php echo esc_url( get_permalink( $book ) ); ?>"<?php echo is_singular( 'book' ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( hb_site( 'labels.nav_books', 'Books' ) ); ?></a>
		<?php endif; ?>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'film' ) ); ?>"<?php echo is_post_type_archive( 'film' ) || is_singular( 'film' ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( hb_site( 'labels.nav_films', 'Films' ) ); ?></a>
		<?php if ( $film ) : ?>
			<a class="nav-cta" href="<?php echo esc_url( $on_film ? '#player' : get_permalink( $film ) . '#player' ); ?>"<?php echo $on_film ? ' data-play-sample' : ''; ?>><?php echo esc_html( hb_site( 'labels.play_sample', 'Play sample' ) ); ?></a>
		<?php endif; ?>
	</nav>
</header>
