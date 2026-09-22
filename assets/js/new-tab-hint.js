/**
 * Announce new-tab links to screen reader users.
 *
 * Every <a target="_blank"> on the page gets a visually hidden
 * "(opens in a new tab)" appended to its accessible name, unless it already
 * says so (the lightbox CTA prints its own). Links whose name is an
 * aria-label (icon-only social links) get the hint appended to that label
 * instead, since inner text is ignored there. Also guarantees rel=noopener.
 *
 * Progressive enhancement: without JavaScript the links still open in a new
 * tab, they just are not announced.
 */
(function () {
	'use strict';
	var text = (window.stjoNewTabHint && window.stjoNewTabHint.text) || '(opens in a new tab)';
	var needle = text.replace(/[()]/g, '').toLowerCase();

	function enhance(root) {
		var links = (root || document).querySelectorAll('a[target="_blank"]');
		for (var i = 0; i < links.length; i++) {
			var a = links[i];
			if (a.hasAttribute('data-stjo-newtab')) { continue; }
			a.setAttribute('data-stjo-newtab', '');

			var rel = (a.getAttribute('rel') || '').split(/\s+/).filter(Boolean);
			if (rel.indexOf('noopener') === -1) { rel.push('noopener'); a.setAttribute('rel', rel.join(' ')); }

			var label = a.getAttribute('aria-label');
			if (label !== null) {
				if (label.toLowerCase().indexOf(needle) === -1) { a.setAttribute('aria-label', label + ' ' + text); }
				continue;
			}
			if ((a.textContent || '').toLowerCase().indexOf(needle) !== -1) { continue; }
			var span = document.createElement('span');
			span.className = 'screen-reader-text';
			span.textContent = ' ' + text;
			a.appendChild(span);
		}
	}

	enhance(document);
	// Lightbox bodies are cloned into the dialog when opened; catch those too.
	if (window.MutationObserver) {
		new MutationObserver(function (records) {
			for (var i = 0; i < records.length; i++) {
				for (var j = 0; j < records[i].addedNodes.length; j++) {
					var n = records[i].addedNodes[j];
					if (n.nodeType === 1) { enhance(n); }
				}
			}
		}).observe(document.body, { childList: true, subtree: true });
	}
})();
