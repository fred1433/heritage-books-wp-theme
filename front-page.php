<?php
get_header();
$h     = hb_site( 'home', array() );
$eras  = hb_site( 'eras', array() );
$film  = hb_open_film();
$book  = hb_open_book();
?>
<main id="main">
	<section class="home-hero">
		<?php echo hb_img( $h['hero_image'] ?? '', '', 'home-hero-img', 'fetchpriority="high"' ); ?>
		<div class="home-hero-copy">
			<h1><?php echo hb_text( $h['hero_title'] ?? '' ); ?></h1>
			<div class="hero-actions">
				<a class="btn btn-yellow" href="<?php echo esc_url( get_post_type_archive_link( 'film' ) ); ?>"><?php echo esc_html( $h['cta_films'] ?? '' ); ?></a>
				<?php if ( $book ) : ?><a class="btn btn-white" href="<?php echo esc_url( get_permalink( $book ) ); ?>"><?php echo esc_html( $h['cta_books'] ?? '' ); ?></a><?php endif; ?>
				<?php echo hb_subscribe_locked( 'on-dark' ); ?>
			</div>
			<?php if ( ! empty( $h['stats'] ) ) : ?>
				<dl class="hero-stats">
					<?php foreach ( $h['stats'] as $stat ) : ?>
						<div><dd><?php echo esc_html( $stat['value'] ); ?></dd><dt><?php echo esc_html( $stat['label'] ); ?></dt></div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>
		</div>
		<?php echo hb_sample_tag( hb_site( 'labels.sample_image', 'Sample' ) ); ?>
	</section>

	<section class="unique">
		<div class="unique-copy">
			<p class="rule-label"><?php echo esc_html( $h['unique_label'] ?? '' ); ?></p>
			<p class="unique-text"><?php echo hb_text( $h['unique_text'] ?? '' ); ?></p>
		</div>
		<div class="unique-media">
			<p class="rule-label rule-both"><?php echo esc_html( $h['why_label'] ?? '' ); ?></p>
			<div class="unique-stage">
				<?php if ( $book ) { echo hb_book_object( $book, 'book-lg' ); } ?>
				<span class="tablet"><?php echo hb_img( $h['tablet_image'] ?? '', '', 'tablet-img' ); ?></span>
			</div>
		</div>
	</section>

	<section class="voices">
		<h2 class="section-title"><?php echo hb_text( $h['voices_title'] ?? '' ); ?></h2>
		<div class="voice-grid">
			<?php foreach ( ( $h['voices'] ?? array() ) as $v ) : ?>
				<figure class="voice">
					<figcaption><span class="voice-mark" aria-hidden="true"><?php echo esc_html( mb_substr( $v['name'], 0, 1 ) ); ?></span><span><b><?php echo esc_html( $v['name'] ); ?></b><small><?php echo esc_html( $v['place'] ); ?></small></span></figcaption>
					<blockquote><?php echo esc_html( $v['text'] ); ?></blockquote>
				</figure>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="journeys" data-eras>
		<div class="journeys-copy">
			<h2><?php echo hb_text( $h['journeys_title'] ?? '' ); ?></h2>
			<h3><?php echo hb_text( $h['subscribe_title'] ?? '' ); ?></h3>
			<?php echo hb_subscribe_locked(); ?>
		</div>
		<ol class="era-steps">
			<?php foreach ( $eras as $i => $era ) : ?>
				<li>
					<button type="button" data-era="<?php echo (int) $i; ?>" aria-pressed="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<b><?php echo esc_html( $era['name'] ); ?></b>
						<small><?php echo esc_html( $era['items'] ); ?></small>
					</button>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="era-object">
			<?php foreach ( $eras as $i => $era ) : ?>
				<figure data-era-panel="<?php echo (int) $i; ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
					<?php echo hb_img( $era['object'] ?? '', '', '', '' ); ?>
				</figure>
			<?php endforeach; ?>
			<?php echo hb_sample_tag(); ?>
		</div>
	</section>

	<section class="movies">
		<div class="section-head">
			<h2 class="section-title"><?php echo hb_text( $h['movies_title'] ?? '' ); ?></h2>
			<a class="view-all" href="<?php echo esc_url( get_post_type_archive_link( 'film' ) ); ?>"><?php echo esc_html( $h['view_all'] ?? '' ); ?></a>
		</div>
		<div class="film-grid">
			<?php foreach ( hb_featured_films() as $f ) { echo hb_film_card( $f ); } ?>
		</div>
		<p class="scope-note"><?php echo esc_html( $h['movies_note'] ?? '' ); ?></p>
	</section>

	<section class="stories">
		<h2 class="section-title"><?php echo hb_text( $h['books_title'] ?? '' ); ?></h2>
		<div class="book-grid">
			<?php foreach ( hb_books( 'home' ) as $b ) { echo hb_book_card( $b ); } ?>
		</div>
		<p class="scope-note"><?php echo esc_html( $h['books_note'] ?? '' ); ?></p>
	</section>

	<?php get_template_part( 'partials-chart' ); ?>
</main>
<?php
get_footer();
