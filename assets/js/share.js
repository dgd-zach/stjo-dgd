/**
 * Share Bar links: pattern markup ships the bare sharer endpoints (the URL
 * parameter empty, since a static pattern cannot know its page). Fill in the
 * canonical page URL at runtime so the buttons share the right page, and
 * predraft the post text where the platform allows it (X only — Facebook and
 * LinkedIn ignore prefilled text and build the preview from the page's own
 * Open Graph tags, which Yoast provides).
 */
(function () {
	'use strict';

	var canonical = document.querySelector('link[rel="canonical"]');
	var url = encodeURIComponent(canonical ? canonical.href : window.location.href);
	var title = encodeURIComponent(document.title);

	document.querySelectorAll('.stjo-share-btn a[href]').forEach(function (link) {
		var href = link.getAttribute('href');
		if (!/[?&](u|url)=$/.test(href)) {
			return;
		}
		href += url;
		// X supports a predrafted post body alongside the URL.
		if (/(^|\.)(x|twitter)\.com\//.test(link.hostname + link.pathname) || /https?:\/\/(www\.)?(x|twitter)\.com\//.test(href)) {
			href += '&text=' + title;
		}
		link.setAttribute('href', href);
	});
})();
