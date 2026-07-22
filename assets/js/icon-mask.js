/**
 * Icon recolor via mask: paints each matched icon <img> as a background-color
 * box masked by its own bitmap, so hover color changes can transition (CSS
 * filter chains interpolate through junk hues and have to snap). The CSS
 * hangs off .is-masked (sections.css); without JS, or before an image loads,
 * the plain <img> + filter fallback applies. Add .stjo-icon-mask on a
 * wrapper to opt other icons in.
 */
(function () {
	'use strict';

	document.querySelectorAll('a.stjo-give-tile img, .stjo-icon-mask img').forEach(function (img) {
		function apply() {
			if (!img.naturalWidth) {
				return;
			}
			img.style.setProperty('--icon', 'url("' + (img.currentSrc || img.src) + '")');
			img.classList.add('is-masked');
		}
		if (img.complete) {
			apply();
		} else {
			img.addEventListener('load', apply, { once: true });
		}
	});
})();
