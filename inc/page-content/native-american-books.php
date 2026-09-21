<?php
/**
 * native-american-books — "Native American Books" (Lakota Culture).
 *
 * Built from stjo.org/native-american-culture/best-native-american-books/.
 * Each staff pick is a media-text: cover beside title, author, the Akta Lakota
 * blurb, and a link to buy it at the Akta Lakota Museum & Cultural Center.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$book = function ( $cover, $title, $author, $blurb, $url, $flip ) {
	$i = stjo_seeded_image( $cover );
	$id = (int) $i['id'];
	$alt = esc_attr( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
	$attrs = array( 'mediaId' => $id, 'mediaType' => 'image', 'mediaWidth' => 28, 'verticalAlignment' => 'top' );
	if ( $flip ) { $attrs['mediaPosition'] = 'right'; }
	$cols = $flip ? 'auto 28%' : '28% auto';
	$cls = 'wp-block-media-text' . ( $flip ? ' has-media-on-the-right' : '' ) . ' is-stacked-on-mobile is-vertically-aligned-top';
	$fig = '<figure class="wp-block-media-text__media"><img src="' . esc_url( $i['url'] ) . '" alt="' . $alt . '" class="wp-image-' . $id . ' size-full"/></figure>';
	$body = '<div class="wp-block-media-text__content">'
		. '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . $title . '</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph {"className":"stjo-subhead"} --><p class="stjo-subhead">' . $author . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph --><p>' . $blurb . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"textColor":"blue-900","className":"is-style-arrow-link"} -->'
		. '<div class="wp-block-button is-style-arrow-link"><a class="wp-block-button__link has-blue-900-color has-text-color wp-element-button" href="' . esc_url( $url ) . '">Find it at the Akta Lakota Museum</a></div>'
		. '<!-- /wp:button --></div><!-- /wp:buttons -->'
		. '</div>';
	return '<!-- wp:media-text ' . serialize_block_attributes( $attrs ) . ' -->' . "\n"
		. '<div class="' . $cls . '" style="grid-template-columns:' . $cols . '">' . ( $flip ? $body . $fig : $fig . $body ) . '</div>' . "\n"
		. '<!-- /wp:media-text -->';
};

echo $title_band( 'Lakota Culture', 'Native American Books' );
?>
<!-- wp:group {"metadata":{"name":"Intro"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>

<!-- wp:quote {"className":"is-style-plain"} -->
<blockquote class="wp-block-quote is-style-plain"><!-- wp:paragraph -->
<p>&#8220;Does that mean, Grandfather &hellip; that the more you know in your mind the less you can carry in your hand?&#8221;</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>At St. Joseph’s Indian School, we’ve partnered with Native American youth and families to educate for life — mind, body, heart and spirit — since 1927. Preserving and sharing the <a href="/lakota-culture/">Lakota (Sioux) culture</a> is a core part of our mission and being well informed on Native American books and authors is a key part of that effort.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>In addition to having cultural experts on staff, we regularly take the time to educate ourselves through different texts and books about the Native American people and culture.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Here are some of our top picks for Native American books and authors.</p>
<!-- /wp:paragraph -->

<?php echo $sp( 'medium' ); ?></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Book List"},"layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-group"><?php
$books = array(
	array( 'cover' => 'The-Girl-Who-Sang-to-the-Buffalo.jpg', 'title' => 'The Girl Who Sang to the Buffalo: A Child, an Elder and the Light from an Ancient Sky', 'author' => 'by Kent Nerburn', 'blurb' => 'A haunting dream that will not relent pulls author Kent Nerburn back into the hidden world of Native America, where dreams have meaning, animals are teachers and the old ones still have powers beyond our understanding. In this moving narrative, we travel through the lands of the Lakota and the Ojibwe, where we encounter a strange little girl with an unnerving connection to the past, a forgotten asylum that history has tried to hide and the complex, unforgettable characters we have come to know from Neither Wolf nor Dog and The Wolf at Twilight. Part history, part mystery, part spiritual journey and teaching story, The Girl Who Sang to the Buffalo is filled with profound insight into humanity and Native American culture. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/girl-who-sang-to-the-buffalo.html' ),
	array( 'cover' => 'The-Sioux-Life-and-Customs-of-a-Warrior-Society.jpg', 'title' => 'The Sioux: Life and Customs of a Warrior Society', 'author' => 'by Royal B. Hassrick', 'blurb' => 'For many people, the Sioux — as warriors and as buffalo hunters — have become the symbol of all that is American Indian … colorful figures endowed with great fortitude and powerful vision. They were the heroes of the Great Plains, and they were the villains, too. Hassrick eloquently describe the ways of the people, the patterns of their behavior, and the concepts of their imagination. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/the-sioux.html' ),
	array( 'cover' => 'The-Wolf-at-Twilight.jpg', 'title' => 'The Wolf at Twilight: An Indian Elder’s Journey through a Land of Ghosts and Shadows', 'author' => 'by Kent Nerburn', 'blurb' => 'A note is left on a car windshield, an old dog dies, and Kent Nerburn finds himself back on the Lakota reservation where he traveled more than a decade before with a tribal elder named Dan. The touching, funny and haunting journey that ensues goes deep into reservation boarding-school mysteries, the dark confines of sweat lodges and isolated Native homesteads far back in the Dakota hills in search of ghosts that have haunted Dan since childhood. In this fictionalized account of actual events, Nerburn brings the land of the northern High Plains alive and reveals the Native American way of teaching and learning with a depth that few outsiders have ever captured. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/the-wolf-at-twilight.html' ),
	array( 'cover' => 'The-Lakota-Way-Stories-and-Lessons-for-Living.jpg', 'title' => 'The Lakota Way: Stories and Lessons for Living', 'author' => 'by Joseph M. Marshall', 'blurb' => 'Rich with storytelling, history and folklore, The Lakota Way expresses the heart of Native American philosophy and imparts the path to a fulfilling and meaningful life. In this book, Marshall focuses on the twelve core qualities that are crucial to the Lakota way of living: bravery, fortitude, generosity, wisdom, respect, honor, perseverance, love, humility, sacrifice, truth and compassion. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/the-lakota-way.html' ),
	array( 'cover' => 'The-Journey-of-Crazy-Horse.jpg', 'title' => 'The Journey of Crazy Horse: A Lakota History', 'author' => 'by Joseph M. Marshall', 'blurb' => 'As the peerless warrior who brought the U.S. Army to its knees at the Battle of Little Bighorn, Crazy Horse remains one of the most perennially fascinating figures of the American West. Drawing on extensive research and a rich oral tradition that is rarely shared outside the Native American community, Marshall gives us a uniquely complete portrait of Crazy Horse. The Journey of Crazy Horse celebrates an enduring culture and gives vibrant life to its most trusted and revered hero. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/the-journey-of-crazy-horse.html' ),
	array( 'cover' => 'Witness.jpg', 'title' => 'Witness: A Hunkpapha Historian’s Strong-Heart Song of the Lakotas', 'author' => 'by Josephine Waggoner', 'blurb' => 'Witness offers a rare participant’s perspective on 19th and early 20th century Lakota and Dakota life. This work includes extraordinary firsthand and as-told-to historical stories by tribal members — such as accounts of life in the Powder River camps and at the agencies in the 1870s, the experiences of a mixed-blood girl at the first off-reservation boarding school, and descriptions of traditional beliefs — in addition to sixty biographies of Lakota and Dakota chiefs and headmen. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/catalog/product/view/id/2535/s/witnessa-hunkpapha-historian/category/298/' ),
	array( 'cover' => 'The-Long-Death-The-Last-Days-of-the-Plains-Indians.jpg', 'title' => 'The Long Death: The Last Days of the Plains Indians', 'author' => 'by Ralph K Andrist', 'blurb' => 'This compelling narrative explains how Native Americans found themselves time and again betrayed by the ever-expanding white nation of the East, fighting for lands on the edge of the shrinking frontier. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/catalog/product/view/id/1485/s/the-long-death/category/298/' ),
	array( 'cover' => 'The-Sacred-Pipe.jpg', 'title' => 'The Sacred Pipe', 'author' => 'by Joseph Epes Brown', 'blurb' => 'Black Elk was the only qualified priest of the older Oglala Sioux still living when The Sacred Pipe was written. This is his book, orally translated by Joseph Epes Brown during his time on the Pine Ridge Reservation. Beginning with the story of White Buffalo Cow Woman’s first visit to the Sioux to give them the sacred pipe, Black Elk describes and discusses the details and meanings of the seven rites, which were disclosed — one by one — to the Sioux through visions. He takes the reader through the sun dance, the purification rite, the keeping of the soul and other rites, showing how the Sioux have come to terms with God and nature and their fellow men through a rare spirit of sacrifice and determination. — Akta Lakota Museum &amp; Cultural Center', 'url' => 'https://shopping.aktalakota.org/the-sacred-pipe.html' ),
);
$last = count( $books ) - 1;
foreach ( $books as $n => $bk ) {
	echo $book( $bk['cover'], $bk['title'], $bk['author'], $bk['blurb'], $bk['url'], 1 === ( $n % 2 ) );
	if ( $n < $last ) { echo $sp( 'large' ); }
}
?></div>
<!-- /wp:group -->
