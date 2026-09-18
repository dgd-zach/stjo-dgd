<?php
/**
 * Lightbox content: Houseparents and Pets In Homes (opened from Residential
 * Living). Copy is verbatim from stjo.org/programs/residential-living/hapi-
 * homes-program/. "Read more about The Dog" goes to Important Animals (its Dog
 * sub-page is not in this sitemap).
 *
 * This file reproduces post 1826 byte for byte as the lead dev arranged it on
 * 2026-09-18: title band, then the FAQ accordion, then the dog cards band.
 * Seed source only, so keep it in step with the page after editor changes.
 *
 * The five Q&As are one Yoast FAQ block in the Accordion style, the same
 * treatment faq.php, about.php and residential-living.php use. A Yoast answer
 * is a single inline rich-text field, so a paragraph break inside an answer is
 * <br><br> and the story list is line-separated links (faq.php hit the same
 * limit). jsonAnswer stays plain text: Yoast feeds it to the FAQPage schema.
 *
 * Whitespace here is load-bearing. Yoast's own save puts a space after every
 * .schema-faq-section, and the editor writes each column and cover on its own
 * line, so the builders below reproduce that exactly and a re-seed is a no-op
 * against the live page.
 *
 * Inside the lightbox stjo_lightbox_demote_headings() shifts by 3 minus the
 * shallowest level. The band's H1 makes that a 2-level shift: H1 becomes H3
 * and the card H4s become H6, under the dialog's own H2.
 *
 * @package stjo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * One Yoast FAQ block in the Accordion style. faq-accordion.js supplies the
 * toggle behavior on the front end, and view.js fires stjo:lightbox-open so
 * the same script also wires the copy the lightbox clones into its dialog.
 * serialize_block_attributes() writes the JSON the editor would, so the block
 * round-trips instead of being flagged on reopen.
 */
$faq = function ( array $items ) {
	$questions = array();
	$sections  = '';
	foreach ( $items as $i => $item ) {
		$id          = 'faq-hapi-homes-' . ( $i + 1 );
		$questions[] = array(
			'id'           => $id,
			'question'     => $item[0],
			'answer'       => $item[1],
			'jsonQuestion' => html_entity_decode( wp_strip_all_tags( $item[0] ) ),
			'jsonAnswer'   => html_entity_decode( wp_strip_all_tags( str_replace( '<br>', ' ', $item[1] ) ) ),
			'images'       => array(),
		);
		// Trailing space after the section div: Yoast's own save emits it.
		$sections .= '<div class="schema-faq-section" id="' . $id . '"><strong class="schema-faq-question">' . $item[0] . '</strong> <p class="schema-faq-answer">' . $item[1] . '</p> </div> ';
	}
	$attrs = serialize_block_attributes( array( 'questions' => $questions, 'className' => 'is-style-accordion' ) );
	return '<!-- wp:yoast/faq-block ' . $attrs . " -->\n"
		. '<div class="schema-faq wp-block-yoast-faq-block is-style-accordion">' . $sections . "</div>\n"
		. '<!-- /wp:yoast/faq-block -->';
};

// Question, answer. Verbatim from the live HAPI Homes page; the only change is
// that paragraph breaks became <br><br> and the story list became links on
// their own lines, because a Yoast answer is a single rich-text field.
$faq_items = array(
	array(
		'Why are the dogs here?',
		'Research shows dogs support psychological growth while increasing social skills and self-esteem in children. They provide emotional support and may decrease anxiety, which in turn has the potential to increase overall academic achievement.'
		. '<br><br>“We’ve seen students who have a hard time speaking to adults or other children open-up to a dog,” said Maija, the HAPI Homes program coordinator. “Over time, that communication the student has with the dog spills over to others in the home and classroom. Before you know it, that quiet kid you worried about is a leader in his or her home and classroom … and it started with a dog.”'
		. '<br><br>HAPI Homes also teaches students the responsibility that goes into caring for an animal.',
	),
	array(
		'Can any dog come to St. Joseph’s?',
		'Not just any four-legged friend gets to come to St. Joseph’s Indian School. Houseparents or other staff members personally own the dogs at St. Joseph’s. The dogs must meet strict guidelines to test their temperament and have documentation to prove they are up-to-date on their shots. Dogs in the program are all Canine Good Citizen (CGC) certified by the AKC and tested by a professional trainer before they can be around children. If they are declared a “good citizen”, they can come to campus and visit homes and classrooms on a leash. Dogs and students are never alone together.',
	),
	array(
		'Are the dogs really making a difference?',
		'Let us demonstrate, with an example of a situation that happened right here on campus.'
		. '<br><br>After venturing downstairs in his pajamas, a St. Joseph’s student explained to his houseparent that he could not sleep. Every time he closed his eyes, he said he could see scary red eyes on the side of his closet.'
		. '<br><br>After taking the student back up to his room, the houseparent returned shortly after with Sarge, the resident HAPI Homes dog, to look around. After Sarge deemed the room safe, the young boy was able to relax and slept soundly for the rest of the night.'
		. '<br><br>It can be difficult for little ones to be away from home — especially when they first arrive at St. Joseph’s. Having a comforting presence that comes from a dog can be truly beneficial.'
		. '<br><br>“The transformations are incredible,” said Maija. “We’re so happy to have more dogs on campus now. It’s a lot of fun to see the kids interact with them.”',
	),
	array(
		'Are there historical ties between Native Americans and dogs?',
		'Yes! The šúŋka — dog — has long played an important role in Lakota society and culture. Before the Spanish introduced horses in the 1700’s, the Lakota (Sioux) relied heavily on dogs for a variety of tasks.'
		. '<br><br><a href="/lakota-culture/important-animals/">Read more about The Dog.</a>',
	),
	array(
		'Tell me more about how dogs are incorporated on campus!',
		'Creating positive experiences for students that include animals is important. Along with the HAPI Homes program, St. Joseph’s Indian School has opportunities to take part in animal rescues. While not an official program, it gives the students a tremendous sense of pride to help an animal in need. You can read more about these experiences through the following stories.'
		. '<br><br><a href="https://blog.stjo.org/animal-blessing-blessing-the-four-legged-who-bless-st-josephs/">Animal Blessing: Blessing the Four-Legged Who Bless St. Joseph’s</a>'
		. '<br><a href="https://blog.stjo.org/student-animal-rescue-team-gives-old-dog-new-life/">Student Animal Rescue Team Gives Old Dog New Life</a>'
		. '<br><a href="https://blog.stjo.org/supplies-needed-for-student-group-that-rescues-unwanted-animals/">Supplies Needed for Student Group that Rescues Unwanted Animals</a>'
		. '<br><a href="https://blog.stjo.org/student-reflection-how-i-changed-a-dogs-life/">Student Reflection: How I Changed a Dog’s Life</a>',
	),
);

/**
 * One dog card: a cover block over the dog's photo, with the name and bio
 * inside. $dim is the overlay percentage that keeps the light text readable.
 */
$dog = function ( $file, $name, $bio, $dim ) {
	$img = stjo_seeded_image( $file );
	$url = $img['url'];
	$id  = (int) $img['id'];
	$dim = (int) $dim;
	return '<!-- wp:column -->' . "\n"
		. '<div class="wp-block-column"><!-- wp:cover {"url":"' . $url . '","id":' . $id . ',"dimRatio":' . $dim . ',"overlayColor":"black","minHeight":340,"className":"stjo-card"} -->' . "\n"
		. '<div class="wp-block-cover stjo-card" style="min-height:340px"><img class="wp-block-cover__image-background wp-image-' . $id . '" alt="" src="' . $url . '" data-object-fit="cover"/>'
		. '<span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-' . $dim . ' has-background-dim"></span>'
		. '<div class="wp-block-cover__inner-container"><!-- wp:heading {"level":4,"textColor":"light"} -->' . "\n"
		. '<h4 class="wp-block-heading has-light-color has-text-color">' . $name . '</h4>' . "\n"
		. '<!-- /wp:heading -->' . "\n\n"
		. '<!-- wp:paragraph {"className":"stjo-card__reveal","textColor":"light"} -->' . "\n"
		. '<p class="stjo-card__reveal has-light-color has-text-color">' . $bio . '</p>' . "\n"
		. '<!-- /wp:paragraph --></div></div>' . "\n"
		. '<!-- /wp:cover --></div>' . "\n"
		. '<!-- /wp:column -->';
};

// File, name, bio, overlay percentage.
$dogs = array(
	array( 'Sadie-Mae.jpg', 'Sadie Mae', 'Hey, there! I’m Sadie Mae and I am a Staffordshire Terrier who loves belly rubs and giving kisses. I’m so excited to meet new people that my whole body wiggles!', 60 ),
	array( 'Andy.jpg', 'Andy', 'Hey, y’all! Andy here! I’m a Yorkshire Terrier who likes to cuddle and play fetch. I may be the next contestant on “Dancing With the Stars” because I’ve got moves!', 60 ),
	array( 'Zoe.jpg', 'Zoe', 'Hello everyone, my name is Zoe! I have many relatives at St. Joseph’s. I love to play fetch, cuddle and eat treats.', 60 ),
	array( 'Frankie.jpg', 'Frankie', 'My name is Frankie and I’m a girl who lived as a street dog for over five months! We don’t know how my story started, but I sure know how it’s going now! I love playing, herding, belly rubs and food. I love it here!', 60 ),
	array( 'Dakota.jpg', 'Dakota', 'Hi! I’m Dakota, the Mini Australia Shepherd. I have the superpower of knowing when kids need attention. I like to run with the track students — I always win! But I also like slower days of cuddles and snuggles to sooth anyone who has anxiety. My favorite thing is when students want to play fetch!', 60 ),
	array( 'Cowboy.jpg', 'Cowboy', 'Hi! My name is Cowboy. You might find me hanging out at Donations/ Home and Office. I am a Markiesje Spaniel (Dutch Tulip Dog). I’m typically shy and quiet. I love camping, going on walks and riding in the car!', 60 ),
	array( 'Ruby.jpg', 'Ruby', 'I’m Ruby, a brown-eyed mini Australian shepherd with a flair for attention, a love for food, fellow furry friends and shadowing my humans wherever they go — except if they take the stairs. While my vet calls me “round,” I proudly prefer “fluffy” and insist post-grooming slimness is simply an optical illusion!', 60 ),
	array( 'Zoey.jpg', 'Zoey', 'I’m Zoey, and after being rescued from a hard start in life, I’ve spent my time happily coming to work and comforting the boys at St. Joseph’s who need me most. I can always tell when one of them needs a friend to sit beside them!', 60 ),
	// Setauket's overlay is 0 on the live page, so its light text sits on the
	// bare photo. Flagged for the lead dev, reproduced here as-is.
	array( 'Setauket.jpg', 'Setauket', 'My name is Setauket, and I know what it’s like to need a safe place. After my first owner was hurt in an accident, I spent time in doggy foster care. I was adopted and now bring comfort and understanding to students. I’m happiest when I’m getting belly rubs from anyone needing extra attention.', 0 ),
);

$dog_rows = array();
foreach ( array_chunk( $dogs, 3 ) as $row ) {
	$cols = array();
	foreach ( $row as $d ) {
		$cols[] = $dog( $d[0], $d[1], $d[2], $d[3] );
	}
	$dog_rows[] = '<!-- wp:columns -->' . "\n" . '<div class="wp-block-columns">' . implode( "\n\n", $cols ) . '</div>' . "\n" . '<!-- /wp:columns -->';
}
?>
<!-- wp:group {"metadata":{"name":"Page Title Band","categories":["stjo-heroes"],"patternName":"stjo/page-title-band"},"align":"full","className":"stjo-page-title-band","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-page-title-band has-white-color has-text-color"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"className":"is-style-eyebrow","style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<p class="has-text-align-center is-style-eyebrow has-white-color has-text-color">About</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">HAPI Homes FAQ</h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:spacer -->
<div style="height:var(--wp--preset--spacing--medium)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<?php
// PHP swallows the newline that follows a closing tag, so these echoes carry
// their own blank line. The seed has to match the page byte for byte.
echo $faq( $faq_items ), "\n\n";
?>
<!-- wp:group {"align":"full","className":"stjo-cards-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull stjo-cards-band"><!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center">Meet Our Furry Friends</h2>
<!-- /wp:heading -->

<?php echo implode( "\n\n", $dog_rows ), "\n\n"; ?>
<!-- wp:spacer {"height":"var:preset|spacing|large"} -->
<div style="height:var(--wp--preset--spacing--large)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
