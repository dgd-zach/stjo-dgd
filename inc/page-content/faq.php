<?php
/**
 * faq — "Frequently Asked Questions" (About child, /about/faq/).
 *
 * Client QA (About page): only three questions show on About, so the full
 * catalogue lives here and About links to it. Content is the live
 * stjo.org/about/faq/ page verbatim: 6 sections, 35 questions, each section a
 * Yoast FAQ block in the accordion style. Old-site links were remapped to this
 * sitemap (financial report + charity rating -> Accountability & Reports,
 * alumni -> Student Stories, powwow -> Attend a Powwow, religious education ->
 * its Education child); two links to old FAQ sub-pages (Wikipedia response,
 * dreamcatcher response) have no home here and were dropped, text kept
 * (flagged). Bulleted lists inside answers became line breaks because Yoast
 * answers are inline rich text.
 *
 * Seed source only — edit live content in the WP editor after seeding.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__ . '/_helpers.php';

$faq_json = <<<'JSON'
[
 {
  "section": "General Questions",
  "slug": "general-questions",
  "qa": [
   {
    "q": "How many children attend your school?",
    "a": "Our campus includes 20 homes that house over 200 Native American children. We educate boys and girls in grades one through eight in our elementary school on campus. Our older students attend grades 9-12 at Chamberlain High School."
   },
   {
    "q": "Where is St. Joseph’s Indian School located?",
    "a": "We are located in central South Dakota, where Interstate 90 crosses the Missouri River. Our community, Chamberlain, has a population of approximately 2,500 people."
   },
   {
    "q": "How big is your campus?",
    "a": "St. Joseph’s campus is approximately 55 acres. Our campus includes 20 homes that house over 200 Native American children. Other structures include our Business Office, the Akta Lakota Museum &amp; Cultural Center, Our Lady of the Sioux Chapel, the Health &amp; Family Services Center, Tipi Press Printing, our elementary school and plant maintenance buildings."
   },
   {
    "q": "Is St. Joseph’s Indian School a religious organization?",
    "a": "Yes, St. Joseph’s mission is under Catholic auspices and serves Native American children of all religious affiliations. The root and foundation of our religious teaching is based on the teachings of the Church. However, we also teach our children their Native American traditions and how they closely correspond to the message of Christ’s Gospel."
   },
   {
    "q": "Do you have a pen pal program?",
    "a": "Students participate in projects like these through their classrooms, but currently have all the projects they can handle."
   },
   {
    "q": "How are students selected to attend St. Joseph’s?",
    "a": "The children attending St. Joseph’s are here because their families want them to be here. Parents or guardians complete an application process with our admissions staff, which includes personal interviews with the student and their family."
   },
   {
    "q": "Why is your campus not on the reservation?",
    "a": "When Fr. Henry Hogebach, SCJ was searching for a place to establish St. Joseph’s Indian School, the Columbus College campus in Chamberlain became available. Because it was already a school, it had all the buildings Fr. Hogebach needed. He purchased the property for $40,000 in April of 1927 and school began that fall. St. Joseph’s Indian School has remained at this location ever since."
   },
   {
    "q": "Why are the majority of St. Joseph’s teachers non-native?",
    "a": "Recruiting qualified staff — teachers and otherwise — for a nonprofit school in a rural area is an ever-present challenge. St. Joseph’s most recent employment update indicated that just over 10% of all St. Joseph’s employees are Native American. When considering staff who work in St. Joseph’s Child Services departments (houseparents, counselors, teachers), it increases to 13%. Our goal is to continue increasing this figure."
   },
   {
    "q": "How does St. Joseph’s help the families of students?",
    "a": "St. Joseph’s Family Service Counselors work to support communication between the organization, students and their families. They travel to meet with families, often bringing samples of schoolwork from their child. They seek to understand family needs and connect families to available resources, both through St. Joseph’s and in their communities. Some of the immediate needs we’re able to help meet include energy and rent assistance, gas vouchers, food boxes, bedding, transportation and support at funerals."
   },
   {
    "q": "Your Wikipedia page seems incomplete, why is that?",
    "a": "Wikipedia content is derived from non-St. Joseph’s Indian School sources that can be inaccurate and misleading. St. Joseph’s is not allowed to individually correct these inaccuracies and is reliant on other parties to help correct them. This is a constant work in progress. Read more about this topic here."
   }
  ]
 },
 {
  "section": "Finances &amp; Donations",
  "slug": "finances-donations",
  "qa": [
   {
    "q": "What percentage of my donation goes towards the children’s needs?",
    "a": "Of each dollar raised, 68 cents goes to the children in our care and for future planned program growth. Our <a href=\"/about/accountability-reports/\">annual financial report</a> is available online."
   },
   {
    "q": "Why do you send so much mail?",
    "a": "Currently, direct mail is our primary source of funding. St. Joseph’s is located in a rural community — Chamberlain, South Dakota. Our Title 1 program is the only program that receives regular federal support and we don’t receive large gifts from huge corporations. Instead, we rely on caring individuals from across the country to support our work through tax-deductible donations, one gift at a time."
   },
   {
    "q": "Can I send books?",
    "a": "Yes, we are always happy to accept books for children and adults."
   },
   {
    "q": "Why aren’t other Native American tribes helping you?",
    "a": "St. Joseph’s Indian School is not located on a reservation or affiliated with a specific tribe, though we do occasionally receive gifts from tribes. However, most tribal governments are focused on providing necessary resources in their own communities, such as hospitals, nursing homes and community programs."
   },
   {
    "q": "Why isn’t the government helping you more?",
    "a": "Federal funding is received for the Title I program, which assists students who need extra attention in completing their regular schoolwork. Although the school receives no other ongoing support from the federal government, we have been awarded funding for special projects on a very limited basis. Private donations are our main source of funding."
   },
   {
    "q": "Why don’t you receive more help from casinos?",
    "a": "St. Joseph’s Indian School is not located on a reservation or affiliated with a specific tribe. We do receive gifts from some of the tribally run casinos in South Dakota and also from casinos in other states. Most tribal casinos support their own tribes and reservations by building hospitals, clinics, homes and other necessities."
   },
   {
    "q": "How did you get my name and number?",
    "a": "Names and addresses come from a variety of sources. As we work to share the news of our mission, we follow the <a href=\"https://tnpa.org/ethics/\" target=\"_blank\" rel=\"noreferrer noopener\">The Nonprofit Alliance’s code of standards and ethics</a> regarding the exchange of this information. For more specific information, please contact us at 1-800-341-2235."
   },
   {
    "q": "Why are you not rated by the Better Business Bureau?",
    "a": "St. Joseph’s Indian School is accredited by the Council on Accreditation (COA) regarding our finances, and is an accredited educational facility by the State of South Dakota. Through the Council on Accreditation’s extensive audit process, we receive evaluation by a panel of experts in social service fields. The evaluation, including site visits and interviews with St. Joseph’s personnel and families served, assesses much more than just finances. <a href=\"/about/accountability-reports/\">Read more about why we have chosen the Council on Accreditation</a>."
   },
   {
    "q": "When you send me some items in the mail I notice they are made in China. Can you please explain this?",
    "a": "The majority of the items we send in the mail are made in the USA. However, some items are made in China. Having items made overseas is an important issue for St. Joseph’s Indian School, and not a decision we take lightly. Read more about this topic here."
   }
  ]
 },
 {
  "section": "Education",
  "slug": "education",
  "qa": [
   {
    "q": "Do you offer sports and extracurricular activities?",
    "a": "Yes, St. Joseph’s offers all the same sports as a typical junior high school, including football, volleyball, basketball and track. Younger students can participate in introductory wrestling and gymnastics. Students of all ages can participate in archery through the National Archery in the Schools program (NASP). St. Joseph’s high school students attend Chamberlain High School, and can participate in all the activities offered there."
   },
   {
    "q": "What do St. Joseph’s students do after high school?",
    "a": "St. Joseph’s graduates often choose military service after high school. Some join the work force or attend college or technical school. St. Joseph’s alumni have gone on to become teachers, counselors, nurses and many other noteworthy occupations. <a href=\"/student-stories/\">Read more</a>."
   },
   {
    "q": "What does the college graduation rate look like for students who attend St Joseph’s?",
    "a": "Tracking college graduation information has always been a challenge, but we are working to find a way to stay in touch with students. Each year, St. Joseph’s gives over $100,000 in college scholarships to alumni, alumni family members, and other Native American students. <a href=\"/student-stories/\">Read more about St. Joseph’s alumni</a>."
   }
  ]
 },
 {
  "section": "Cultural &amp; Social Issues for Native Americans",
  "slug": "cultural-social-issues-for-native-americans",
  "qa": [
   {
    "q": "Why do you use the term “Indian” in your name?",
    "a": "The topic of changing the name of our organization has come up in the past. We are grateful to have the input and support of the families and communities we serve in not changing our name at this time."
   },
   {
    "q": "Why is there a need for St. Joseph’s Indian School?",
    "a": "Many of our students are in the custody of grandparents who — like many — are having a hard time meeting their own needs. They are facing poverty and unsafe living conditions. Since 1927, St. Joseph’s has provided a solid education and a safe, loving home-away-from-home to Native American children from families who seek our assistance."
   },
   {
    "q": "Are the children orphans?",
    "a": "No. St. Joseph’s Indian School isn’t an adoption agency or an orphanage, nor do we have the right to place children in foster homes. Although some of our children are placed here by social services, most have family or foster parents who care for them during breaks and the summer months."
   },
   {
    "q": "What is the government doing to help people on the reservation?",
    "a": "Each tribe, and each community within a reservation, has different opportunities for programs and different needs. You can contact a specific tribal office for more information, or refer to the national newspaper <em>Indian Country Today</em> for a broader overview."
   },
   {
    "q": "How are you teaching and preserving the students’ knowledge of their Lakota culture?",
    "a": "St. Joseph’s has a Native American studies program to teach the children the Lakota language, culture and traditions. We work with elders to provide opportunities for students to participate in ceremonies such as smudging, Inípi and Wiping of the Tears. Students can join traditional dance and drum groups. St. Joseph’s also hosts an annual American Indian Day powwow for students, which is open to the public. <a href=\"/lakota-culture/powwow-dance/attend-a-powwow/\">Read more about our powwow</a>."
   },
   {
    "q": "Are the children taught the traditional ways of hunting and fishing?",
    "a": "St. Joseph’s students learn about these traditions in Native American Studies class. While life in this era no longer necessitates hunting for survival, students take part in archery and experience fishing in the Missouri River that flows to the west of our campus. There are also opportunities for special activities and camps to further develop their skills and connection to their heritage."
   }
  ]
 },
 {
  "section": "Religion &amp; the Catholic Church",
  "slug": "religion-the-catholic-church",
  "qa": [
   {
    "q": "Are all the children at your school Catholic?",
    "a": "No. Though St. Joseph’s Indian School is affiliated with the Catholic Church through the Priests of the Sacred Heart, we welcome Native American children of all faiths, recognizing the dignity of each human person created in God’s image. Students are not required to be Catholic and we respect each child’s individual family beliefs. <a href=\"/youth-programs/education-cultural-awareness/religious-education/\">Read more about our Religious Studies program</a>."
   },
   {
    "q": "What does SCJ stand for?",
    "a": "SCJ is an abbreviation for the Latin <em>Sacerdotes Cordis Jesu</em> (Priests of the Heart of Jesus)."
   },
   {
    "q": "If you are a Catholic organization, don’t you receive financial support from the Catholic Church?",
    "a": "As a Catholic mission organization, we have always raised our own funds. Our Sacred Heart Fathers and Brothers opened the doors of St. Joseph’s Indian School in 1927 with the local Bishop’s blessing, but were entrusted with financing our programs, as we are to this day."
   },
   {
    "q": "What Diocese are you in?",
    "a": "St. Joseph’s Indian School is part of the Sioux Falls Diocese in South Dakota."
   },
   {
    "q": "In the Lakota culture do the people believe in Jesus Christ?",
    "a": "There isn’t one particular way that all Lakota families view Christianity in terms of believing or not believing. Many Lakota people have expressed that “I carry my Bible in one hand and my <strong>canupa</strong> <em>(pipe)</em> in the other.” Children are not required to be Catholic to attend St. Joseph’s; we welcome Native American children of all faiths, recognizing the dignity of each human person created in God’s image."
   }
  ]
 },
 {
  "section": "Volunteering Opportunities",
  "slug": "volunteering-opportunities",
  "qa": [
   {
    "q": "Can I Volunteer at St. Joseph’s Indian School?",
    "a": "St. Joseph’s Indian School is deeply grateful for the many individuals and groups who wish to support the Lakota (Sioux) children in our care. At this time, <strong>we are not accepting volunteer applications</strong>. Our on campus volunteer program is extremely limited due to the unique nature of our residential setting and the extensive safety and federal requirements needed to protect our students."
   },
   {
    "q": "Other Ways to Help",
    "a": "Although we cannot host volunteers at this time, there are meaningful ways to support Native American children and families in South Dakota:<br><br><strong>Cheyenne River Youth Project</strong> — Eagle Butte, SD — 605-964-8200 — <a href=\"https://lakotayouth.org/\" target=\"_blank\" rel=\"noreferrer noopener\">lakotayouth.org</a><br><strong>Habitat for Humanity (Ft. Thompson)</strong> — Eagle Butte, SD — 605-245-2450<br><strong>Habitat for Humanity (Rosebud)</strong> — Eagle Butte, SD — 605-856-2665<br><strong>Lower Brule Boys &amp; Girls Club</strong> — Eagle Butte, SD — 605-473-0652 — <a href=\"https://lbyouth.tripod.com/\" target=\"_blank\" rel=\"noreferrer noopener\">lbyouth.tripod.com</a> Serving in these reservation communities supports some of the families of St. Joseph’s students."
   }
  ]
 }
]
JSON;
$faq_sections = json_decode( $faq_json, true );

// One Yoast FAQ block (accordion style) per section. serialize_block_attributes()
// escapes the answer HTML the way the editor does, so the block validates.
$faq_block = function ( $slug, array $qa ) {
	$questions = array();
	$sections  = '';
	foreach ( $qa as $i => $item ) {
		$id          = 'faq-' . $slug . '-' . ( $i + 1 );
		$questions[] = array(
			'id'           => $id,
			'question'     => $item['q'],
			'answer'       => $item['a'],
			'jsonQuestion' => html_entity_decode( wp_strip_all_tags( $item['q'] ) ),
			'jsonAnswer'   => html_entity_decode( wp_strip_all_tags( str_replace( '<br>', ' ', $item['a'] ) ) ),
			'images'       => array(),
		);
		$sections .= '<div class="schema-faq-section" id="' . $id . '"><strong class="schema-faq-question">' . $item['q'] . '</strong> <p class="schema-faq-answer">' . $item['a'] . '</p> </div>';
	}
	$attrs = serialize_block_attributes( array( 'questions' => $questions, 'className' => 'is-style-accordion' ) );
	return '<!-- wp:yoast/faq-block ' . $attrs . " -->\n" . '<div class="schema-faq wp-block-yoast-faq-block is-style-accordion">' . $sections . "</div>\n" . '<!-- /wp:yoast/faq-block -->';
};

echo $title_band( 'About', 'Frequently Asked Questions' );
?>
<!-- wp:group {"metadata":{"name":"FAQ"},"layout":{"type":"constrained","contentSize":"768px"}} -->
<div class="wp-block-group"><?php echo $sp( 'medium' ); ?>
<?php foreach ( $faq_sections as $n => $section ) : ?>
<?php if ( $n > 0 ) { echo $sp( 'medium' ); } ?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo $section['section']; ?></h2>
<!-- /wp:heading -->

<?php echo $faq_block( $section['slug'], $section['qa'] ); ?>

<?php endforeach; ?>
<?php echo $sp( 'large' ); ?></div>
<!-- /wp:group -->
