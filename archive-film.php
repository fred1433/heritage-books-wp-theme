<?php
get_header();
$a        = hb_site( 'films_page', array() );
$eras     = hb_site( 'eras', array() );
$featured = hb_featured_films();
?>
<main id="main">
	<section class="film-carousel" data-carousel aria-roledescription="carousel">
		<?php foreach ( $featured as $i => $f ) : ?>
			<article class="slide" data-slide<?php echo 0 === $i ? '' : ' hidden'; ?>>
				<?php $slide_img = get_the_post_thumbnail_url( $f, 'full' ); ?>
				<?php if ( $slide_img ) : ?>
					<img src="<?php echo esc_url( $slide_img ); ?>" alt="" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
				<?php endif; ?>
				<div class="slide-copy">
					<h1><?php echo esc_html( get_the_title( $f ) ); ?><?php if ( hb_meta( 'subtitle', $f ) ) : ?> <span><?php echo hb_text( hb_meta( 'subtitle', $f ) ); ?></span><?php endif; ?></h1>
					<?php if ( hb_meta( 'tagline', $f ) ) : ?><p><?php echo esc_html( hb_meta( 'tagline', $f ) ); ?></p><?php endif; ?>
					<?php if ( hb_meta( 'open', $f ) ) : ?>
						<a class="btn btn-yellow btn-play" href="<?php echo esc_url( get_permalink( $f ) ); ?>" data-film-link><?php echo esc_html( $a['details'] ?? '' ); ?><i aria-hidden="true"></i></a>
					<?php else : ?>
						<p class="slide-closed"><?php echo esc_html( $a['closed'] ?? '' ); ?></p>
					<?php endif; ?>
				</div>
				<?php echo hb_sample_tag(); ?>
			</article>
		<?php endforeach; ?>
		<?php if ( count( $featured ) > 1 ) : ?>
			<div class="carousel-controls">
				<button type="button" data-prev aria-label="Previous film"></button>
				<?php foreach ( $featured as $i => $f ) : ?>
					<button type="button" class="dot" data-dot="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( get_the_title( $f ) ); ?>" aria-current="<?php echo 0 === $i ? 'true' : 'false'; ?>"></button>
				<?php endforeach; ?>
				<button type="button" data-next aria-label="Next film"></button>
			</div>
		<?php endif; ?>
	</section>

	<section class="subscribe-band">
		<h2><?php echo hb_text( $a['subscribe_title'] ?? '' ); ?></h2>
		<p class="subscribe-script"><?php echo hb_text( $a['subscribe_sub'] ?? '' ); ?></p>
		<?php echo hb_subscribe_locked(); ?>
		<ol class="era-line">
			<?php foreach ( $eras as $era ) : ?>
				<li>
					<a href="#era-<?php echo esc_attr( $era['slug'] ); ?>">
						<b><?php echo esc_html( $era['name'] ); ?></b>
						<span class="era-line-object"><?php echo hb_img( $era['object'] ?? '' ); ?></span>
						<small><?php echo esc_html( $era['items'] ); ?></small>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<?php foreach ( $eras as $era ) : ?>
		<section class="era-row" id="era-<?php echo esc_attr( $era['slug'] ); ?>">
			<h2 class="section-title"><?php echo hb_text( $era['title'] ?? $era['name'] ); ?></h2>
			<p class="era-row-items"><?php echo esc_html( $era['items'] ); ?></p>
			<div class="film-grid">
				<?php foreach ( hb_films( $era['slug'] ) as $f ) { echo hb_film_card( $f, false ); } ?>
			</div>
		</section>
	<?php endforeach; ?>
</main>
<?php
get_footer();
