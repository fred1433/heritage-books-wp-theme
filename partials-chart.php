<?php
/** Chart band: a winding road through the eras. */
$eras = hb_site( 'eras', array() );
$pts  = array( array( 62, 130 ), array( 215, 130 ), array( 368, 130 ), array( 368, 300 ), array( 215, 300 ), array( 62, 300 ) );
?>
<section class="chart-band">
	<div class="chart-copy">
		<p class="rule-label"><?php echo esc_html( hb_site( 'chart.kicker' ) ); ?></p>
		<h2><?php echo hb_text( hb_site( 'chart.title' ) ); ?></h2>
		<p class="chart-note"><?php echo esc_html( hb_site( 'chart.note' ) ); ?></p>
	</div>
	<figure class="chart-sheet">
		<svg viewBox="0 0 480 420" role="img" aria-label="<?php echo esc_attr( hb_site( 'chart.sheet_title' ) ); ?>">
			<text x="240" y="44" text-anchor="middle" class="ch-title"><?php echo esc_html( hb_site( 'chart.sheet_title' ) ); ?></text>
			<path d="M62 130 H368 a85 85 0 0 1 0 170 H62" class="ch-road"/>
			<path d="M62 130 H368 a85 85 0 0 1 0 170 H62" class="ch-dash"/>
			<?php foreach ( array_slice( $eras, 0, 6 ) as $i => $era ) : ?>
				<circle cx="<?php echo (int) $pts[ $i ][0]; ?>" cy="<?php echo (int) $pts[ $i ][1]; ?>" r="9" class="ch-dot"/>
				<text x="<?php echo (int) $pts[ $i ][0]; ?>" y="<?php echo (int) $pts[ $i ][1] + ( $i < 3 ? -28 : 40 ); ?>" text-anchor="middle" class="ch-era"><?php echo esc_html( $era['short'] ?? $era['name'] ); ?></text>
			<?php endforeach; ?>
		</svg>
		<?php echo hb_sample_tag( hb_site( 'labels.sample_chart', 'Sample chart' ) ); ?>
	</figure>
</section>
