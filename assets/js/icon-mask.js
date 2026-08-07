/**
 * Icon recolor via mask: paints each matched icon <img> as a background-color
 * box masked by its own bitmap, so hover color changes can transition (CSS
 * filter chains interpolate through junk hues and have to snap). The CSS
 * hangs off .is-masked (sections.css); without JS the plain <img> + filter
 * fallback applies. Add .stjo-icon-mask on a wrapper to opt other icons in.
 *
 * GOTCHA: Smush lazy-loads images, so at DOMContentLoaded the <img> src is a
 * base64 SVG placeholder and the real URL lives in data-src. Masking with the
 * placeholder yields a blank/invisible icon, so resolve the real URL first
 * (data-src wins), and re-run when the lazy image finally swaps in.
 */
(function () {
	'use strict';

	document.querySelectorAll('a.stjo-give-tile img, .stjo-icon-mask img').forEach(function (img) {
		function realSrc() {
			var ds = img.getAttribute('data-src');
			if (ds && 0 !== ds.indexOf('data:')) {
				return ds;
			}
			var s = img.currentSrc || img.src;
			return s && 0 !== s.indexOf('data:') ? s : '';
		}
		function apply() {
			var url = realSrc();
			if (!url) {
				return; // real image not ready yet (lazy placeholder still in src)
			}
			img.style.setProperty('--icon', 'url("' + url + '")');
			img.classList.add('is-masked');
		}
		apply();
		if (!img.classList.contains('is-masked')) {
			// Placeholder still in place: recompute when the real image loads.
			img.addEventListener('load', apply);
			img.addEventListener('lazyloaded', apply); // lazysizes (Smush) custom event
		}
	});
})();
