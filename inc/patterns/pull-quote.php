<?php
/**
 * Title: Pull Quote
 * Categories: quotes
 * Description: Structure: pull-quote. Large quote with attribution between yellow rules. Source: general-content.
 *
 * Uses core/pullquote. It was previously a group wrapping a core/quote, which
 * meant the yellow rules lived on the wrapper and editors got a plain quote's
 * toolbar. core/pullquote is a <figure> with the citation built in, so the
 * wrapper is gone and stjo-pull-quote sits on the figure itself.
 *
 * @package stjo
 */
?>
<!-- wp:pullquote {"align":"wide","className":"stjo-pull-quote"} -->
<figure class="wp-block-pullquote alignwide stjo-pull-quote"><blockquote><p>&#8220;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.&#8221;</p><cite>-Name Lastname</cite></blockquote></figure>
<!-- /wp:pullquote -->
