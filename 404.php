<?php
get_header();
?>
<main id="main" class="plain-page"><div class="plain-inner">
	<h1><?php echo esc_html( hb_site( 'labels.not_found', 'This page does not exist.' ) ); ?></h1>
	<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( hb_site( 'labels.back_home', 'Back to the home page' ) ); ?></a></p>
</div></main>
<?php
get_footer();
