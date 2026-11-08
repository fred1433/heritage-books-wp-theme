<?php
get_header();
the_post();
$l     = hb_site( 'book_page', array() );
$book  = get_post();
$title = hb_accent( $book->post_title, hb_meta( 'accent' ) );
$activities = $l['activities'] ?? array();
?>
<main id="main" class="book-page">
	<nav class="crumbs" aria-label="Breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $l['crumb_home'] ?? 'Home' ); ?></a>
		<span><?php echo esc_html( hb_meta( 'series' ) ); ?></span>
		<span aria-current="page"><?php the_title(); ?></span>
	</nav>
	<section class="book-top" data-gallery>
		<div class="gallery">
			<div class="gallery-main">
				<figure data-gallery-panel="0"><?php echo hb_book_object( $book, 'book-xl' ); ?></figure>
				<?php foreach ( $activities as $i => $act ) : if ( $i > 2 ) { break; } ?>
					<figure data-gallery-panel="<?php echo (int) $i + 1; ?>" hidden><span class="activity-art art-<?php echo esc_attr( $act['icon'] ); ?> art-big"><?php get_template_part( 'partials-activity', null, array( 'icon' => $act['icon'] ) ); ?></span><figcaption><?php echo esc_html( $l['sample_page'] ?? '' ); ?></figcaption></figure>
				<?php endforeach; ?>
			</div>
			<div class="gallery-thumbs">
				<button type="button" data-gallery-thumb="0" aria-pressed="true" aria-label="Cover"><?php echo hb_book_object( $book, 'book-xs' ); ?></button>
				<?php foreach ( $activities as $i => $act ) : if ( $i > 2 ) { break; } ?>
					<button type="button" data-gallery-thumb="<?php echo (int) $i + 1; ?>" aria-pressed="false" aria-label="<?php echo esc_attr( $act['label'] ); ?>"><span class="activity-art art-<?php echo esc_attr( $act['icon'] ); ?>"><?php get_template_part( 'partials-activity', null, array( 'icon' => $act['icon'] ) ); ?></span></button>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="book-info">
			<p class="rule-label"><?php echo esc_html( hb_meta( 'kicker' ) ); ?></p>
			<h1><?php echo $title; ?></h1>
			<?php if ( get_the_content() ) : ?><div class="book-text"><?php the_content(); ?></div><?php endif; ?>
			<dl class="spec-row">
				<?php foreach ( hb_pairs( hb_meta( 'specs' ) ) as $spec ) : ?>
					<div><dt><?php echo esc_html( $spec[0] ); ?></dt><dd><?php echo esc_html( $spec[1] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<h2><?php echo esc_html( $l['buy_title'] ?? '' ); ?></h2>
			<div class="store-row store-row-lg">
				<?php foreach ( hb_pairs( hb_meta( 'stores' ) ) as $store ) : ?>
					<a class="store" href="<?php echo esc_url( $store[1] ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $store[0] ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php if ( hb_meta( 'store_note' ) ) : ?><p class="store-note"><?php echo esc_html( hb_meta( 'store_note' ) ); ?></p><?php endif; ?>
		</div>
	</section>

	<section class="inside">
		<div class="inside-copy">
			<p class="rule-label"><?php echo esc_html( $l['inside_kicker'] ?? '' ); ?></p>
			<h2><?php echo hb_text( $l['inside_title'] ?? '' ); ?></h2>
		</div>
		<ul class="activity-grid">
			<?php foreach ( $activities as $act ) : ?>
				<li><span class="activity-art art-<?php echo esc_attr( $act['icon'] ); ?>"><?php get_template_part( 'partials-activity', null, array( 'icon' => $act['icon'] ) ); ?></span><b><?php echo esc_html( $act['label'] ); ?></b></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="final-quiz">
		<svg class="medal" viewBox="0 0 120 150" aria-hidden="true">
			<path d="M38 4h18l10 44H48zM82 4H64L54 48h18z" fill="#00AEEF"/>
			<circle cx="60" cy="94" r="46" fill="#FFC800"/><circle cx="60" cy="94" r="36" fill="none" stroke="#B98900" stroke-width="3"/>
			<path d="M60 68l8 17 18 2-13 13 4 18-17-9-17 9 4-18-13-13 18-2z" fill="#B98900"/>
		</svg>
		<h2><?php echo hb_text( $l['quiz_title'] ?? '' ); ?></h2>
	</section>

	<section class="stories also">
		<h2 class="section-title"><?php echo hb_text( $l['also_title'] ?? '' ); ?></h2>
		<div class="book-grid">
			<?php foreach ( hb_books( 'also' ) as $b ) { echo hb_book_card( $b ); } ?>
		</div>
	</section>

	<?php get_template_part( 'partials-chart' ); ?>
</main>
<?php
get_footer();
