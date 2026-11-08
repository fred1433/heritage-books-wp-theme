<?php
/** Small helpers shared by the templates. */

/** A value from the site settings, with dots for nesting. */
function hb_site( $path, $default = '' ) {
	$value = get_option( 'hb_site', array() );
	foreach ( explode( '.', $path ) as $part ) {
		if ( ! is_array( $value ) || ! array_key_exists( $part, $value ) ) {
			return $default;
		}
		$value = $value[ $part ];
	}
	return $value;
}

/** Escape text, then turn *words* into emphasised words. */
function hb_text( $text ) {
	return preg_replace( '/\*(\S(?:[^*]*\S)?)\*/', '<em>$1</em>', esc_html( (string) $text ) );
}

function hb_meta( $key, $post = null ) {
	return get_post_meta( $post ? $post->ID : get_the_ID(), $key, true );
}

/** A file by name: one loaded by the seed, or a Media Library item with that title. */
function hb_media( $name ) {
	if ( ! $name ) {
		return '';
	}
	$map = get_option( 'hb_media', array() );
	if ( ! empty( $map[ $name ] ) ) {
		return wp_get_attachment_url( $map[ $name ] );
	}
	$found = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'title' => $name, 'numberposts' => 1 ) );
	return $found ? wp_get_attachment_url( $found[0]->ID ) : '';
}

/** A title with its first match of $accent emphasised, whatever the punctuation. */
function hb_accent( $title, $accent ) {
	$at = '' === (string) $accent ? false : stripos( $title, $accent );
	if ( false === $at ) {
		return esc_html( $title );
	}
	$len = strlen( $accent );
	return esc_html( substr( $title, 0, $at ) ) . '<em>' . esc_html( substr( $title, $at, $len ) ) . '</em>' . esc_html( substr( $title, $at + $len ) );
}

/** The film that can really play a sample, if any. */
function hb_sample_film() {
	$film = hb_open_film();
	return $film && hb_media( hb_meta( 'sample_video', $film ) ) ? $film : null;
}

function hb_img( $name, $alt = '', $class = '', $extra = 'loading="lazy"' ) {
	$url = hb_media( $name );
	if ( ! $url ) {
		return '<span class="media-empty ' . esc_attr( $class ) . '" aria-hidden="true"></span>';
	}
	return sprintf( '<img src="%s" alt="%s" class="%s" decoding="async" %s>', esc_url( $url ), esc_attr( $alt ), esc_attr( $class ), $extra );
}

function hb_sample_tag( $label = '' ) {
	$label = $label ?: hb_site( 'labels.sample_image', 'Sample' );
	return '<span class="sample-tag">' . esc_html( $label ) . '</span>';
}

function hb_films( $era = '' ) {
	$args = array( 'post_type' => 'film', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' );
	if ( $era ) {
		$args['tax_query'] = array( array( 'taxonomy' => 'era', 'field' => 'slug', 'terms' => $era ) );
	}
	return get_posts( $args );
}

function hb_featured_films() {
	return array_values( array_filter( hb_films(), function ( $f ) {
		return ! hb_meta( 'is_sample', $f );
	} ) );
}

function hb_open_film() {
	foreach ( hb_films() as $f ) {
		if ( hb_meta( 'open', $f ) ) {
			return $f;
		}
	}
	return null;
}

function hb_books( $shelf ) {
	return get_posts( array(
		'post_type'   => 'book',
		'numberposts' => -1,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
		'meta_query'  => array( array( 'key' => 'shelf', 'value' => $shelf, 'compare' => 'LIKE' ) ),
	) );
}

function hb_open_book() {
	$found = get_posts( array( 'post_type' => 'book', 'numberposts' => 1, 'meta_key' => 'open', 'meta_value' => '1', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	return $found ? $found[0] : null;
}

/** Lines of "A|B" into pairs. */
function hb_pairs( $text ) {
	$out = array();
	foreach ( preg_split( '/\R/', (string) $text ) as $line ) {
		if ( false !== strpos( $line, '|' ) ) {
			$out[] = array_map( 'trim', explode( '|', $line, 2 ) );
		}
	}
	return $out;
}

function hb_film_poster( $film ) {
	$image = get_the_post_thumbnail_url( $film, 'full' );
	$pos   = hb_meta( 'card_pos', $film );
	$zoom  = (float) hb_meta( 'card_zoom', $film );
	$style = $pos ? ' style="transform-origin:' . esc_attr( $pos ) . ';transform:scale(' . ( $zoom ?: 1 ) . ')"' : '';
	ob_start();
	?>
	<span class="poster">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" decoding="async"<?php echo $style; ?>>
		<?php else : ?>
			<span class="media-empty"></span>
		<?php endif; ?>
		<?php if ( hb_meta( 'poster_title', $film ) ) : ?>
			<span class="poster-lockup">
				<span class="poster-title"><?php echo esc_html( hb_meta( 'poster_title', $film ) ); ?></span>
				<?php if ( hb_meta( 'poster_sub', $film ) ) : ?>
					<span class="poster-sub"><?php echo esc_html( hb_meta( 'poster_sub', $film ) ); ?></span>
				<?php endif; ?>
			</span>
		<?php endif; ?>
		<?php echo hb_sample_tag(); ?>
	</span>
	<?php
	return ob_get_clean();
}

function hb_film_card( $film, $show_tagline = true ) {
	$open = (bool) hb_meta( 'open', $film );
	$tag  = $open ? 'a' : 'div';
	$attr = $open ? ' href="' . esc_url( get_permalink( $film ) ) . '" data-film-link' : '';
	ob_start();
	?>
	<<?php echo $tag . $attr; ?> class="film-card<?php echo $open ? ' is-open' : ''; ?>">
		<?php echo hb_film_poster( $film ); ?>
		<?php if ( ! hb_meta( 'is_sample', $film ) ) : ?>
			<span class="film-card-title"><?php echo esc_html( get_the_title( $film ) ); ?></span>
		<?php endif; ?>
		<?php if ( $show_tagline && hb_meta( 'tagline', $film ) ) : ?>
			<span class="film-card-line"><?php echo esc_html( hb_meta( 'tagline', $film ) ); ?></span>
		<?php endif; ?>
		<?php if ( $open && hb_media( hb_meta( 'sample_video', $film ) ) ) : ?>
			<span class="film-card-play" data-card-play><?php echo esc_html( hb_site( 'labels.play_sample', 'Play sample' ) ); ?></span>
		<?php endif; ?>
		<?php if ( $open && hb_meta( 'tagline', $film ) ) : ?>
			<span class="film-pop" aria-hidden="true">
				<strong><?php echo esc_html( get_the_title( $film ) ); ?></strong>
				<?php echo esc_html( hb_meta( 'tagline', $film ) ); ?>
			</span>
		<?php endif; ?>
	</<?php echo $tag; ?>>
	<?php
	return ob_get_clean();
}

function hb_book_object( $book, $size = '' ) {
	$art = hb_media( hb_meta( 'art', $book ) );
	ob_start();
	?>
	<span class="book-object <?php echo esc_attr( $size ); ?>">
		<span class="book-3d">
			<span class="book-face">
				<?php if ( $art ) : ?>
					<img src="<?php echo esc_url( $art ); ?>" alt="" loading="lazy" decoding="async">
				<?php endif; ?>
				<span class="book-title"<?php echo hb_meta( 'ink', $book ) ? ' style="color:' . esc_attr( hb_meta( 'ink', $book ) ) . '"' : ''; ?>><?php echo esc_html( get_the_title( $book ) ); ?></span>
				<span class="book-sample"><?php echo esc_html( hb_site( 'labels.sample_cover', 'Sample cover' ) ); ?></span>
			</span>
		</span>
		<?php if ( hb_meta( 'age', $book ) ) : ?>
			<span class="age-badge"><small><?php echo esc_html( hb_site( 'labels.ages', 'Ages' ) ); ?></small><?php echo esc_html( hb_meta( 'age', $book ) ); ?></span>
		<?php endif; ?>
	</span>
	<?php
	return ob_get_clean();
}

function hb_book_card( $book ) {
	$open = (bool) hb_meta( 'open', $book );
	ob_start();
	?>
	<article class="book-card<?php echo $open ? ' is-open' : ''; ?>">
		<?php if ( $open ) : ?>
			<a href="<?php echo esc_url( get_permalink( $book ) ); ?>" class="book-card-link">
				<?php echo hb_book_object( $book ); ?>
				<h3><?php echo esc_html( get_the_title( $book ) ); ?></h3>
			</a>
			<div class="store-row">
				<?php foreach ( hb_pairs( hb_meta( 'stores', $book ) ) as $store ) : ?>
					<a class="store" href="<?php echo esc_url( $store[1] ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $store[0] ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<?php echo hb_book_object( $book ); ?>
			<h3><?php echo esc_html( get_the_title( $book ) ); ?></h3>
		<?php endif; ?>
	</article>
	<?php
	return ob_get_clean();
}

/** The non-clickable subscribe control. */
function hb_subscribe_locked( $variant = '' ) {
	ob_start();
	?>
	<span class="locked <?php echo esc_attr( $variant ); ?>" role="note">
		<span class="locked-label"><?php echo esc_html( hb_site( 'labels.subscribe', 'Subscribe' ) ); ?></span>
		<span class="locked-why"><?php echo esc_html( hb_site( 'labels.needs_account', 'Needs your account' ) ); ?></span>
	</span>
	<?php
	return ob_get_clean();
}
