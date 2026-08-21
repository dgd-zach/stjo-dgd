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

	// Default when a figure carries no data-count-duration (hand-written
	// .stjo-stat__figure paragraphs). The Stat Figure block sets its own.
	var DEFAULT_DURATION = 4000;

	var els = document.querySelectorAll('.stjo-stat__figure');
	if (!els.length) {
		return;
	}

	// Split a figure into what comes before the number, the number, and what
	// comes after it. Anything non-numeric on either side is carried through
	// untouched, so $275,000, £500, 98%, 1,300+, ~200 and "12,000 hours" all
	// work. The number itself may carry comma grouping and a decimal part.
	//
	// A figure with two numbers in it (24/7) deliberately fails to parse and is
	// left alone — there is nothing sensible to count to.
	function parseFigure(text) {
		var m = text.match(/^(\D*?)(\d[\d,]*(?:\.\d+)?)(\D*)$/);
		if (!m) {
			return null;
		}
		var digits   = m[2];
		var dot      = digits.indexOf('.');
		var decimals = -1 === dot ? 0 : digits.length - dot - 1;
		var scale    = Math.pow(10, decimals);

		return {
			prefix: m[1],
			suffix: m[3],
			// Count in the smallest unit the figure actually shows — whole
			// numbers for 22,610, tenths for 1.2, cents for $9.99 — so the
			// one-by-one tail steps through values the reader can see change.
			units: Math.round(parseFloat(digits.replace(/,/g, '')) * scale),
			scale: scale,
			decimals: decimals,
			grouped: -1 !== digits.indexOf(',')
		};
	}

	// Rebuild the figure from a count in smallest units, keeping the original
	// grouping and decimal places.
	function format(spec, units) {
		var n = units / spec.scale;
		var body = spec.grouped
			? n.toLocaleString('en-US', {
				minimumFractionDigits: spec.decimals,
				maximumFractionDigits: spec.decimals
			})
			: n.toFixed(spec.decimals);
		return spec.prefix + body + spec.suffix;
	}

	// Per-block duration, set by the Stat Figure block's sidebar control.
	function readDuration(el) {
		var raw = parseInt(el.getAttribute('data-count-duration'), 10);
		return (raw >= 500 && raw <= 20000) ? raw : DEFAULT_DURATION;
	}

	function animate(el, spec, duration) {
		var lastText  = null;
		var startTime = null;

		// One continuous law, no phases: the distance still to travel halves
		// every `halfLife` ms. Closing a single unit therefore takes
		// halfLife / (remaining * ln2) — so each successive number takes a
		// constant ratio longer than the one before it. That constant ratio is
		// what reads as an exponential slowdown, and because it is a ratio the
		// deceleration is smooth all the way in rather than switching gear.
		//
		// halfLife comes from the figure's own size, so the behaviour is
		// proportional rather than tuned to particular values: 3,707 and
		// 275,000 both run through log2(target) halvings, show the same gap
		// ratios at the same points, and land at the same moment.
		var halvings = Math.log(spec.units) / Math.LN2;
		var halfLife = duration / halvings;

		function paint(units) {
			var text = format(spec, units);
			if (text !== lastText) {
				el.textContent = text;
				lastText = text;
			}
		}

		function frame(now) {
			if (startTime === null) {
				startTime = now;
			}
			var remaining = spec.units * Math.pow(2, -(now - startTime) / halfLife);
			// Flooring `remaining` means the shown value rises by exactly one
			// each time it crosses an integer, so the last stretch is one by one
			// with nothing skipped. Early on it crosses many per frame, which is
			// fine — it is unreadable at that speed anyway.
			var value = remaining < 1 ? spec.units : spec.units - Math.floor(remaining);
			paint(value);
			if (value < spec.units) {
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
			if (spec && spec.units > 1) {
				animate(entry.target, spec, readDuration(entry.target));
			}
		});
	}, { threshold: 0.5 });

	els.forEach(function (el) {
		observer.observe(el);
	});
})();
