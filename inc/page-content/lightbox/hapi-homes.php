<?php
/**
 * Lightbox content: Houseparents and Pets In Homes (opened from Residential
 * Living). Verbatim from stjo.org/programs/residential-living/hapi-homes-
 * program/, including the dogs' portraits and bios. "Read more about The Dog"
 * goes to Important Animals (its Dog sub-page is not in this sitemap).
 *
 * @package stjo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$dog = function ( $file, $name, $bio ) {
	$img = stjo_seeded_image( $file );
	$fig = '';
	if ( $img['id'] ) {
		$fig = '<!-- wp:image {"id":' . (int) $img['id'] . ',"width":"200px","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->'
			. '<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $name ) . '" class="wp-image-' . (int) $img['id'] . '" style="width:200px"/></figure>'
			. '<!-- /wp:image -->';
	}
	return '<!-- wp:column --><div class="wp-block-column">' . $fig
		. '<!-- wp:heading {"level":4} --><h4 class="wp-block-heading">' . $name . '</h4><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>' . $bio . '</p><!-- /wp:paragraph -->'
		. '</div><!-- /wp:column -->';
};
$dogs = array(
	array( 'Sadie-Mae.jpg', 'Sadie Mae', 'Hey, there! I’m Sadie Mae and I am a Staffordshire Terrier who loves belly rubs and giving kisses. I’m so excited to meet new people that my whole body wiggles!' ),
	array( 'Andy.jpg', 'Andy', 'Hey, y’all! Andy here! I’m a Yorkshire Terrier who likes to cuddle and play fetch. I may be the next contestant on “Dancing With the Stars” because I’ve got moves!' ),
	array( 'Zoe.jpg', 'Zoe', 'Hello everyone, my name is Zoe! I have many relatives at St. Joseph’s. I love to play fetch, cuddle and eat treats.' ),
	array( 'Frankie.jpg', 'Frankie', 'My name is Frankie and I’m a girl who lived as a street dog for over five months! We don’t know how my story started, but I sure know how it’s going now! I love playing, herding, belly rubs and food. I love it here!' ),
	array( 'Dakota.jpg', 'Dakota', 'Hi! I’m Dakota, the Mini Australia Shepherd. I have the superpower of knowing when kids need attention. I like to run with the track students — I always win! But I also like slower days of cuddles and snuggles to sooth anyone who has anxiety. My favorite thing is when students want to play fetch!' ),
	array( 'Cowboy.jpg', 'Cowboy', 'Hi! My name is Cowboy. You might find me hanging out at Donations/ Home and Office. I am a Markiesje Spaniel (Dutch Tulip Dog). I’m typically shy and quiet. I love camping, going on walks and riding in the car!' ),
	array( 'Ruby.jpg', 'Ruby', 'I’m Ruby, a brown-eyed mini Australian shepherd with a flair for attention, a love for food, fellow furry friends and shadowing my humans wherever they go — except if they take the stairs. While my vet calls me “round,” I proudly prefer “fluffy” and insist post-grooming slimness is simply an optical illusion!' ),
	array( 'Zoey.jpg', 'Zoey', 'I’m Zoey, and after being rescued from a hard start in life, I’ve spent my time happily coming to work and comforting the boys at St. Joseph’s who need me most. I can always tell when one of them needs a friend to sit beside them!' ),
	array( 'Setauket.jpg', 'Setauket', 'My name is Setauket, and I know what it’s like to need a safe place. After my first owner was hurt in an accident, I spent time in doggy foster care. I was adopted and now bring comfort and understanding to students. I’m happiest when I’m getting belly rubs from anyone needing extra attention.' ),
);
?>
<!-- wp:paragraph -->
<p>Homes are always happier when one of the members of the family walks on four legs, right? We sure think so!</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>St. Joseph’s is proud to have the Houseparents and Pets In (HAPI) Homes program on campus. The program launched in 2017 with three dogs. We are happy to say the program gets a little “HAPI-er” every year, and keeps expanding over time.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Why are the dogs here?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Research shows dogs support psychological growth while increasing social skills and self-esteem in children. They provide emotional support and may decrease anxiety, which in turn has the potential to increase overall academic achievement.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>“We’ve seen students who have a hard time speaking to adults or other children open-up to a dog,” said Maija, the HAPI Homes program coordinator. “Over time, that communication the student has with the dog spills over to others in the home and classroom. Before you know it, that quiet kid you worried about is a leader in his or her home and classroom … and it started with a dog.”</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>HAPI Homes also teaches students the responsibility that goes into caring for an animal.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Can any dog come to St. Joseph’s?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Not just any four-legged friend gets to come to St. Joseph’s Indian School. Houseparents or other staff members personally own the dogs at St. Joseph’s. The dogs must meet strict guidelines to test their temperament and have documentation to prove they are up-to-date on their shots. Dogs in the program are all Canine Good Citizen (CGC) certified by the AKC and tested by a professional trainer before they can be around children. If they are declared a “good citizen”, they can come to campus and visit homes and classrooms on a leash. Dogs and students are never alone together.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Are the dogs really making a difference?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Let us demonstrate, with an example of a situation that happened right here on campus.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>After venturing downstairs in his pajamas, a St. Joseph’s student explained to his houseparent that he could not sleep. Every time he closed his eyes, he said he could see scary red eyes on the side of his closet.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>After taking the student back up to his room, the houseparent returned shortly after with Sarge, the resident HAPI Homes dog, to look around. After Sarge deemed the room safe, the young boy was able to relax and slept soundly for the rest of the night.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>It can be difficult for little ones to be away from home — especially when they first arrive at St. Joseph’s. Having a comforting presence that comes from a dog can be truly beneficial.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>“The transformations are incredible,” said Maija. “We’re so happy to have more dogs on campus now. It’s a lot of fun to see the kids interact with them.”</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Meet Our Furry Friends</h3>
<!-- /wp:heading -->

<?php foreach ( array_chunk( $dogs, 3 ) as $row ) : ?>
<!-- wp:columns -->
<div class="wp-block-columns"><?php foreach ( $row as $d ) { echo $dog( $d[0], $d[1], $d[2] ); } ?></div>
<!-- /wp:columns -->

<?php endforeach; ?>
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Are there historical ties between Native Americans and dogs?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Yes! The &scaron;&uacute;&#331;ka — dog — has long played an important role in Lakota society and culture. Before the Spanish introduced horses in the 1700’s, the Lakota (Sioux) relied heavily on dogs for a variety of tasks.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="/lakota-culture/important-animals/">Read more about The Dog.</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Tell me more about how dogs are incorporated on campus!</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Creating positive experiences for students that include animals is important. Along with the HAPI Homes program, St. Joseph’s Indian School has opportunities to take part in animal rescues. While not an official program, it gives the students a tremendous sense of pride to help an animal in need. You can read more about these experiences through the following stories.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="https://blog.stjo.org/animal-blessing-blessing-the-four-legged-who-bless-st-josephs/">Animal Blessing: Blessing the Four-Legged Who Bless St. Joseph’s</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://blog.stjo.org/student-animal-rescue-team-gives-old-dog-new-life/">Student Animal Rescue Team Gives Old Dog New Life</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://blog.stjo.org/supplies-needed-for-student-group-that-rescues-unwanted-animals/">Supplies Needed for Student Group that Rescues Unwanted Animals</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://blog.stjo.org/student-reflection-how-i-changed-a-dogs-life/">Student Reflection: How I Changed a Dog’s Life</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
