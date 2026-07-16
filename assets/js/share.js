/**
 * Share Bar links: pattern markup ships the bare sharer endpoints (the URL
 * parameter empty, since a static pattern cannot know its page). Fill in the
 * canonical page URL at runtime so the buttons share the right page.
 */
(function () {
	'use strict';

	var canonical = document.querySelector('link[rel="canonical"]');
	var url = encodeURIComponent(canonical ? canonical.href : window.location.href);

	document.querySelectorAll('.stjo-share-btn a[href]').forEach(function (link) {
		var href = link.getAttribute('href');
		if (/[?&](u|url)=$/.test(href)) {
			link.setAttribute('href', href + url);
		}
	});
})();
