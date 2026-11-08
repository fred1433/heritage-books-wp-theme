<footer class="site-footer">
	<div class="footer-top">
		<p class="footer-line"><?php echo esc_html( hb_site( 'footer.line' ) ); ?></p>
		<div class="footer-col">
			<h2><?php echo esc_html( hb_site( 'footer.heading', 'Browse' ) ); ?></h2>
			<?php $book = hb_open_book(); ?>
			<?php if ( $book ) : ?><a href="<?php echo esc_url( get_permalink( $book ) ); ?>"><?php echo esc_html( hb_site( 'labels.nav_books', 'Books' ) ); ?></a><?php endif; ?>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'film' ) ); ?>"><?php echo esc_html( hb_site( 'labels.nav_movies', 'Movies' ) ); ?></a>
		</div>
	</div>
	<?php if ( hb_site( 'footer.credit' ) ) : ?><p class="footer-credit"><?php echo esc_html( hb_site( 'footer.credit' ) ); ?></p><?php endif; ?>
	<?php if ( hb_site( 'footer.call_url' ) ) : ?>
		<p class="footer-call"><?php echo esc_html( hb_site( 'footer.call_text' ) ); ?> <a href="<?php echo esc_url( hb_site( 'footer.call_url' ) ); ?>"><?php echo esc_html( hb_site( 'footer.call_label' ) ); ?></a></p>
	<?php endif; ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>
