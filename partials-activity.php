<?php
/** Small drawings for the "inside the book" tiles. */
switch ( $args['icon'] ?? '' ) {
	case 'numbers':
		echo '<svg viewBox="0 0 120 90" aria-hidden="true"><g font-family="Josefin Sans,sans-serif" font-weight="700" font-size="30" fill="#fff"><text x="14" y="38">2</text><text x="44" y="38">+</text><text x="72" y="38">3</text></g><rect x="14" y="48" width="86" height="5" rx="2.5" fill="#fff"/><rect x="42" y="60" width="30" height="24" rx="5" fill="#FFC800"/></svg>';
		break;
	case 'maze':
		echo '<svg viewBox="0 0 120 90" aria-hidden="true"><path fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" d="M14 10h92v70H52M14 10v70h24V30h48v30H52V44"/><circle cx="66" cy="44" r="6" fill="#FFC800"/></svg>';
		break;
	case 'colour':
		echo '<svg viewBox="0 0 120 90" aria-hidden="true"><circle cx="38" cy="34" r="20" fill="#E2391F"/><circle cx="66" cy="28" r="20" fill="#FFC800"/><circle cx="58" cy="54" r="20" fill="#00AEEF" fill-opacity=".9"/><path d="M84 70L108 22l6 3-24 48-8 5z" fill="#fff"/></svg>';
		break;
	case 'letters':
		echo '<svg viewBox="0 0 120 90" aria-hidden="true"><g font-family="Playfair Display,serif" font-weight="700" font-size="34"><rect x="8" y="24" width="32" height="40" rx="6" fill="#fff"/><text x="14" y="56" fill="#0F172A">A</text><rect x="44" y="24" width="32" height="40" rx="6" fill="#FFC800"/><text x="51" y="56" fill="#0F172A">B</text><rect x="80" y="24" width="32" height="40" rx="6" fill="#fff"/><text x="87" y="56" fill="#0F172A">C</text></g></svg>';
		break;
	case 'shapes':
		echo '<svg viewBox="0 0 120 90" aria-hidden="true"><path d="M30 12l22 38H8z" fill="#FFC800"/><rect x="62" y="12" width="40" height="38" rx="4" fill="#fff"/><circle cx="60" cy="68" r="16" fill="#E2391F"/></svg>';
		break;
	default:
		echo '<svg viewBox="0 0 120 90" aria-hidden="true"><path d="M60 8a24 24 0 0 1 24 24c0 16-20 18-20 32h-8c0-20 20-22 20-32a16 16 0 0 0-32 0h-8A24 24 0 0 1 60 8z" fill="#fff"/><circle cx="60" cy="78" r="6" fill="#FFC800"/></svg>';
}
