/**
 * Count-up stats: when a stat figure scrolls into view, its number animates
 * from zero to the value in the markup. Targets .stjo-stat__figure (impact
 * band). The real value stays in the markup (no-JS/SEO safe); prefix and suffix
 * around the number ($, +, %) and comma grouping are preserved while
 * counting. prefers-reduced-motion leaves the final values in place.
 */
(function () {
	'use strict';

	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var DURATION = 1400;
	var els = document.querySelectorAll('.stjo-stat__figure');
	if (!els.length) {
		return;
	}

	// cubic-bezier(0.16, 1, 0.3, 1): a strong ease-out so the number decelerates
	// noticeably as it nears the final value. Newton-Raphson to solve x → t.
	function cubicBezier(x1, y1, x2, y2) {
		function ax(a, b) { return 1 - 3 * b + 3 * a; }
		function bx(a, b) { return 3 * b - 6 * a; }
		function cx(a) { return 3 * a; }
		function calc(t, a, b) { return ((ax(a, b) * t + bx(a, b)) * t + cx(a)) * t; }
		function slope(t, a, b) { return 3 * ax(a, b) * t * t + 2 * bx(a, b) * t + cx(a); }
		return function (x) {
			if (x <= 0) { return 0; }
			if (x >= 1) { return 1; }
			var t = x;
			for (var i = 0; i < 5; i++) {
				var s = slope(t, x1, x2);
				if (0 === s) { break; }
				t -= (calc(t, x1, x2) - x) / s;
			}
			return calc(t, y1, y2);
		};
	}
	var ease = cubicBezier(.06,.01,0,1);

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
			var eased = ease(progress);
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
