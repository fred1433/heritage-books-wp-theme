<?php
get_header();
echo '<main id="main" class="plain-page"><div class="plain-inner">';
while ( have_posts() ) {
	the_post();
	echo '<h1>' . esc_html( get_the_title() ) . '</h1>';
	the_content();
}
echo '</div></main>';
get_footer();
