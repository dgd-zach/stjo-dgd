/**
 * Count-up stats: when a stat figure scrolls into view, its number animates
 * from zero to the value in the markup. Targets .stjo-stat__figure (impact
 * band) and anything with the "Count Up" block style (.is-style-count-up).
 * The real value stays in the markup (no-JS/SEO safe); prefix and suffix
 * around the number ($, +, %) and comma grouping are preserved while
 * counting. prefers-reduced-motion leaves the final values in place.
 */
(function () {
	'use strict';

	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var DURATION = 1400;
	var els = document.querySelectorAll('.stjo-stat__figure, .is-style-count-up');
	if (!els.length) {
		return;
	}

	function parseFigure(text) {
		var m = text.match(/^([^0-9]*)([0-9][0-9,]*)([^0-9]*)$/);
		if (!m) {
			return null;
		}
		return {
			prefix: m[1],
			target: parseInt(m[2].replace(/,/g, ''), 10),
			grouped: m[2].indexOf(',') !== -1,
			suffix: m[3]
		};
	}

	function animate(el, spec) {
		var startTime = null;
		function frame(now) {
			if (startTime === null) {
				startTime = now;
			}
			var progress = Math.min((now - startTime) / DURATION, 1);
			var eased = 1 - Math.pow(1 - progress, 3);
			var value = Math.round(eased * spec.target);
			el.textContent = spec.prefix + (spec.grouped ? value.toLocaleString('en-US') : String(value)) + spec.suffix;
			if (progress < 1) {
				window.requestAnimationFrame(frame);
			}
		}
		window.requestAnimationFrame(frame);
	}

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (!entry.isIntersecting) {
				return;
			}
			observer.unobserve(entry.target);
			var spec = parseFigure(entry.target.textContent.trim());
			if (spec && spec.target > 0) {
				animate(entry.target, spec);
			}
		});
	}, { threshold: 0.5 });

	els.forEach(function (el) {
		observer.observe(el);
	});
})();
