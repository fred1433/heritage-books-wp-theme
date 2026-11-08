<?php
get_header();
the_post();
$l      = hb_site( 'film_page', array() );
$meta   = hb_meta( 'meta_line' ) ? array( hb_meta( 'meta_line' ) ) : array_filter( array( hb_meta( 'year' ), hb_meta( 'rating' ), hb_meta( 'runtime' ), hb_meta( 'language' ) ) );
$genres = array_filter( array_map( 'trim', explode( '|', (string) hb_meta( 'genres' ) ) ) );
$video  = hb_media( hb_meta( 'sample_video' ) );
$poster = hb_media( hb_meta( 'sample_poster' ) );
$back   = get_post_type_archive_link( 'film' );
?>
<main id="main" class="film-page">
	<section class="film-hero">
		<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full', array( 'class' => 'film-hero-img', 'alt' => '', 'fetchpriority' => 'high', 'loading' => false ) ); } ?>
		<span class="film-hero-lockup" aria-hidden="true">
			<span class="poster-title"><?php echo esc_html( hb_meta( 'poster_title' ) ); ?></span>
			<span class="poster-sub"><?php echo esc_html( hb_meta( 'poster_sub' ) ); ?></span>
		</span>
		<div class="film-hero-copy">
			<a class="back-link" href="<?php echo esc_url( $back ); ?>" data-back-to-list><?php echo esc_html( $l['back'] ?? '' ); ?></a>
			<ul class="meta-row"><?php foreach ( $meta as $m ) { echo '<li>' . esc_html( $m ) . '</li>'; } ?></ul>
			<h1><?php the_title(); ?><?php if ( hb_meta( 'subtitle' ) ) : ?> <span><?php echo hb_text( hb_meta( 'subtitle' ) ); ?></span><?php endif; ?></h1>
			<?php if ( hb_meta( 'tagline' ) ) : ?><p class="film-tagline"><?php echo esc_html( hb_meta( 'tagline' ) ); ?></p><?php endif; ?>
			<ul class="genre-row"><?php foreach ( $genres as $g ) { echo '<li>' . esc_html( $g ) . '</li>'; } ?></ul>
			<div class="hero-actions">
				<?php if ( $video ) : ?><a class="btn btn-yellow btn-play" href="#player" data-play-sample><?php echo esc_html( hb_site( 'labels.play_sample', 'Play sample' ) ); ?><i aria-hidden="true"></i></a><?php endif; ?>
				<?php echo hb_subscribe_locked( 'on-dark' ); ?>
			</div>
		</div>
		<?php echo hb_sample_tag(); ?>
	</section>

	<section class="film-body">
		<h2 class="film-body-title"><?php the_title(); ?><?php if ( hb_meta( 'subtitle' ) ) : ?> <span class="sep">&middot;</span> <span><?php echo hb_text( hb_meta( 'subtitle' ) ); ?></span><?php endif; ?></h2>
		<ul class="meta-row meta-grey"><?php foreach ( $meta as $m ) { echo '<li>' . esc_html( $m ) . '</li>'; } ?></ul>

		<?php if ( $video ) : ?>
			<div class="player" id="player" data-player data-state="idle">
				<video src="<?php echo esc_url( $video ); ?>" poster="<?php echo esc_url( $poster ); ?>" preload="none" playsinline muted width="1280" height="720"></video>
				<span class="player-label"><?php echo esc_html( hb_meta( 'sample_label' ) ); ?></span>
				<button type="button" class="btn btn-yellow btn-play player-go" data-player-go>
					<span data-idle-label><?php echo esc_html( hb_site( 'labels.play_sample', 'Play sample' ) ); ?></span>
					<span data-replay-label hidden><?php echo esc_html( $l['replay'] ?? '' ); ?></span>
					<i aria-hidden="true"></i>
				</button>
			</div>
			<p class="player-note"><?php echo esc_html( hb_meta( 'sample_note' ) ); ?></p>
		<?php endif; ?>

		<?php if ( get_the_content() ) : ?><div class="film-synopsis"><?php the_content(); ?></div><?php endif; ?>

		<?php if ( hb_meta( 'side_url' ) ) : ?>
			<aside class="side-item">
				<div>
					<p class="rule-label"><?php echo esc_html( hb_meta( 'side_kicker' ) ); ?></p>
					<h3><?php echo esc_html( hb_meta( 'side_title' ) ); ?></h3>
					<p><?php echo esc_html( hb_meta( 'side_detail' ) ); ?></p>
				</div>
				<a class="store" href="<?php echo esc_url( hb_meta( 'side_url' ) ); ?>" rel="noopener" target="_blank"><?php echo esc_html( hb_meta( 'side_label' ) ); ?></a>
			</aside>
		<?php endif; ?>

		<p><a class="back-link back-dark" href="<?php echo esc_url( $back ); ?>" data-back-to-list><?php echo esc_html( $l['back'] ?? '' ); ?></a></p>
	</section>
</main>
<?php
get_footer();
