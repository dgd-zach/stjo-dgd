/**
 * Carousel behavior for any group with .is-style-carousel: direct-child
 * Cover blocks are slides. Injects the controls pill (prev / dots / next),
 * ARIA per the APG carousel pattern, and pointer swipe. Slides fade
 * via .is-active-slide (CSS); inactive slides are visibility: hidden, so
 * their links drop out of the tab order on their own.
 *
 * Auto-rotate: every 5s (override with data-carousel-interval, ms). There is
 * no visible pause control (per design), so the stop semantics carry the
 * accessibility weight: rotation never starts under prefers-reduced-motion,
 * hovering holds it, and moving focus into the carousel or using any
 * control/swipe stops it permanently. aria-live is off while rotating and
 * polite once stopped, so slide changes are only announced when the user is
 * driving them.
 */
(function () {
	'use strict';

	var reducedMq = window.matchMedia('(prefers-reduced-motion: reduce)');

	function init(root) {
		var slides = Array.prototype.slice.call(root.children).filter(function (el) {
			return el.classList.contains('wp-block-cover');
		});
		if (!slides.length) {
			return;
		}

		var current = 0;
		var label = root.getAttribute('data-carousel-label') || 'Highlights';
		root.setAttribute('role', 'group');
		root.setAttribute('aria-roledescription', 'carousel');
		root.setAttribute('aria-label', label);

		slides.forEach(function (slide, i) {
			slide.setAttribute('role', 'group');
			slide.setAttribute('aria-roledescription', 'slide');
			slide.setAttribute('aria-label', (i + 1) + ' of ' + slides.length);
		});

		if (slides.length < 2) {
			slides[0].classList.add('is-active-slide');
			root.setAttribute('aria-live', 'polite');
			return;
		}

		var interval = parseInt(root.getAttribute('data-carousel-interval'), 10) || 5000;
		var stopped = reducedMq.matches;
		var hoverHold = false;
		var timer = 0;

		var controls = document.createElement('div');
		controls.className = 'stjo-carousel-controls';

		var icons = {
			prev: '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M11 4 6 9l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			next: '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
		};

		function makeButton(className, aria, svg) {
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'stjo-carousel-controls__arrow ' + className;
			b.setAttribute('aria-label', aria);
			b.innerHTML = svg;
			return b;
		}

		var prev = makeButton('stjo-carousel-controls__prev', 'Previous slide', icons.prev);
		var next = makeButton('stjo-carousel-controls__next', 'Next slide', icons.next);

		var dotsWrap = document.createElement('div');
		dotsWrap.className = 'stjo-carousel-controls__dots';
		var dots = slides.map(function (slide, i) {
			var dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'stjo-carousel-controls__dot';
			dot.setAttribute('aria-label', 'Go to slide ' + (i + 1) + ' of ' + slides.length);
			dot.addEventListener('click', function () {
				stopRotation();
				show(i);
			});
			dotsWrap.appendChild(dot);
			return dot;
		});

		controls.appendChild(prev);
		controls.appendChild(dotsWrap);
		controls.appendChild(next);
		root.appendChild(controls);

		function show(i) {
			current = (i + slides.length) % slides.length;
			slides.forEach(function (slide, n) {
				slide.classList.toggle('is-active-slide', n === current);
			});
			dots.forEach(function (dot, n) {
				if (n === current) {
					dot.setAttribute('aria-current', 'true');
				} else {
					dot.removeAttribute('aria-current');
				}
			});
		}

		function running() {
			return !stopped && !hoverHold;
		}

		function sync() {
			window.clearInterval(timer);
			timer = 0;
			if (running()) {
				timer = window.setInterval(function () {
					show(current + 1);
				}, interval);
			}
			// Announce slide changes only when the user is driving them.
			root.setAttribute('aria-live', stopped ? 'polite' : 'off');
		}

		function stopRotation() {
			if (!stopped) {
				stopped = true;
				sync();
			}
		}

		prev.addEventListener('click', function () {
			stopRotation();
			show(current - 1);
		});
		next.addEventListener('click', function () {
			stopRotation();
			show(current + 1);
		});
		controls.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft') {
				event.preventDefault();
				stopRotation();
				show(current - 1);
			} else if (event.key === 'ArrowRight') {
				event.preventDefault();
				stopRotation();
				show(current + 1);
			}
		});

		// Hovering holds rotation; it resumes on pointer leave.
		root.addEventListener('pointerenter', function () {
			hoverHold = true;
			sync();
		});
		root.addEventListener('pointerleave', function () {
			hoverHold = false;
			sync();
		});
		// Keyboard/AT users get a durable stop: focus into the carousel ends
		// rotation for good (there is no visible pause control).
		root.addEventListener('focusin', stopRotation);

		// Pointer swipe: horizontal drags beyond the threshold change slides;
		// taps and vertical scrolls fall through untouched.
		var startX = null;
		var startY = null;
		root.addEventListener('pointerdown', function (event) {
			startX = event.clientX;
			startY = event.clientY;
		});
		root.addEventListener('pointerup', function (event) {
			if (startX === null) {
				return;
			}
			var dx = event.clientX - startX;
			var dy = event.clientY - startY;
			startX = startY = null;
			if (Math.abs(dx) > 44 && Math.abs(dx) > Math.abs(dy)) {
				stopRotation();
				show(dx < 0 ? current + 1 : current - 1);
			}
		});

		if (reducedMq.addEventListener) {
			reducedMq.addEventListener('change', function () {
				if (reducedMq.matches) {
					stopRotation();
				}
			});
		}

		show(0);
		sync();
	}

	document.querySelectorAll('.wp-block-group.is-style-carousel').forEach(init);
})();
