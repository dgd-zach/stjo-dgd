/**
 * Stories carousel + AJAX year filters for the Student Stories archive.
 *
 * Carousel: any [data-stories-carousel] whose track holds more than one
 * .stjo-stories-carousel__page becomes a swipeable pager (the archive chunks
 * 6 cards per page). Progressive enhancement over stacked pages: without JS
 * everything is visible. Controls follow the hero carousel's APG shape
 * (prev / dots / next, ARIA roles, pointer swipe, arrow keys on the
 * controls) minus auto-rotation, so there is nothing to pause. Ends clamp
 * (no wrap): arrows disable at the first and last page. Hidden pages are
 * inert so their links drop out of the tab order.
 *
 * Filters: clicks on a section's year pills fetch the pill's own href (the
 * server renders the filtered page anyway), swap in that section's fresh
 * markup, re-init its carousel, and push the URL into history — same result
 * as the full navigation, minus the reload. Back/forward re-syncs every
 * section from the history URL. Without JS the pills stay plain links.
 */
(function () {
	'use strict';

	function init(root) {
		var viewport = root.querySelector('.stjo-stories-carousel__viewport');
		var track = root.querySelector('.stjo-stories-carousel__track');
		if (!viewport || !track) {
			return;
		}
		var pages = Array.prototype.slice.call(track.children);
		if (pages.length < 2) {
			return;
		}

		root.classList.add('is-carousel');
		var label = root.getAttribute('data-carousel-label') || 'Stories';
		var current = 0;
		// Shadow-bleed padding on the viewport (style.css) joins the height math.
		var bleed = parseFloat(window.getComputedStyle(viewport).paddingTop) || 0;

		root.setAttribute('role', 'group');
		root.setAttribute('aria-roledescription', 'carousel');
		root.setAttribute('aria-label', label);
		pages.forEach(function (page, i) {
			page.setAttribute('role', 'group');
			page.setAttribute('aria-roledescription', 'slide');
			page.setAttribute('aria-label', (i + 1) + ' of ' + pages.length);
		});

		var icons = {
			prev: '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M11 4 6 9l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			next: '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
		};

		function makeArrow(cls, aria, svg) {
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'stjo-stories-nav__arrow ' + cls;
			b.setAttribute('aria-label', aria);
			b.innerHTML = svg;
			return b;
		}

		var nav = document.createElement('div');
		nav.className = 'stjo-stories-nav';
		var prev = makeArrow('stjo-stories-nav__prev', 'Previous page of stories', icons.prev);
		var next = makeArrow('stjo-stories-nav__next', 'Next page of stories', icons.next);

		var dotsWrap = document.createElement('div');
		dotsWrap.className = 'stjo-stories-nav__dots';
		var dots = pages.map(function (page, i) {
			var dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'stjo-stories-nav__dot';
			dot.setAttribute('aria-label', 'Go to page ' + (i + 1) + ' of ' + pages.length);
			dot.addEventListener('click', function () {
				show(i);
			});
			dotsWrap.appendChild(dot);
			return dot;
		});

		nav.appendChild(prev);
		nav.appendChild(dotsWrap);
		nav.appendChild(next);
		root.appendChild(nav);

		function gap() {
			var g = parseFloat(window.getComputedStyle(track).columnGap);
			return isNaN(g) ? 0 : g;
		}

		function position() {
			track.style.transform = 'translateX(-' + current * (pages[current].offsetWidth + gap()) + 'px)';
		}

		function setHeight() {
			viewport.style.height = (pages[current].offsetHeight + bleed * 2) + 'px';
		}

		var resizeObserver = null;
		function watchActivePage() {
			if (!('ResizeObserver' in window)) {
				return;
			}
			if (resizeObserver) {
				resizeObserver.disconnect();
			}
			resizeObserver = new ResizeObserver(setHeight);
			resizeObserver.observe(pages[current]);
		}

		function show(i) {
			current = Math.max(0, Math.min(pages.length - 1, i));
			pages.forEach(function (page, n) {
				if (n === current) {
					page.removeAttribute('aria-hidden');
					page.inert = false;
				} else {
					page.setAttribute('aria-hidden', 'true');
					page.inert = true;
				}
			});
			dots.forEach(function (dot, n) {
				if (n === current) {
					dot.setAttribute('aria-current', 'true');
				} else {
					dot.removeAttribute('aria-current');
				}
			});
			prev.disabled = 0 === current;
			next.disabled = current === pages.length - 1;
			position();
			setHeight();
			watchActivePage();
		}

		prev.addEventListener('click', function () {
			show(current - 1);
		});
		next.addEventListener('click', function () {
			show(current + 1);
		});
		nav.addEventListener('keydown', function (event) {
			if ('ArrowLeft' === event.key) {
				event.preventDefault();
				show(current - 1);
			} else if ('ArrowRight' === event.key) {
				event.preventDefault();
				show(current + 1);
			}
		});

		// Pointer swipe: horizontal drags beyond the threshold change pages;
		// taps and vertical scrolls fall through untouched.
		var startX = null;
		var startY = null;
		viewport.addEventListener('pointerdown', function (event) {
			startX = event.clientX;
			startY = event.clientY;
		});
		viewport.addEventListener('pointerup', function (event) {
			if (null === startX) {
				return;
			}
			var dx = event.clientX - startX;
			var dy = event.clientY - startY;
			startX = startY = null;
			if (Math.abs(dx) > 44 && Math.abs(dx) > Math.abs(dy)) {
				show(dx < 0 ? current + 1 : current - 1);
			}
		});

		var resizeRaf = 0;
		window.addEventListener('resize', function () {
			cancelAnimationFrame(resizeRaf);
			resizeRaf = requestAnimationFrame(function () {
				position();
				setHeight();
			});
		});

		show(0);
	}

	document.querySelectorAll('[data-stories-carousel]').forEach(init);

	// ── AJAX year filters ──

	var live = null;
	function announce(message) {
		if (!live) {
			live = document.createElement('div');
			live.className = 'screen-reader-text';
			live.setAttribute('aria-live', 'polite');
			document.body.appendChild(live);
		}
		live.textContent = message;
	}

	function swapSection(section, url, focusYear) {
		section.setAttribute('aria-busy', 'true');
		return fetch(url, { credentials: 'same-origin' })
			.then(function (res) {
				if (!res.ok) {
					throw new Error(res.status);
				}
				return res.text();
			})
			.then(function (html) {
				var doc = new DOMParser().parseFromString(html, 'text/html');
				var fresh = doc.getElementById(section.id);
				if (!fresh) {
					throw new Error('section missing in response');
				}
				section.replaceWith(fresh);
				var carousel = fresh.querySelector('[data-stories-carousel]');
				if (carousel) {
					init(carousel);
				}
				var cards = fresh.querySelectorAll('.stjo-story-card').length;
				var heading = fresh.querySelector('.stjo-stories-band__head h3, .stjo-stories-band__head h2');
				var title = heading ? heading.textContent : '';
				announce(cards + ' stories shown in ' + title);
				if (focusYear) {
					var pill = Array.prototype.slice.call(fresh.querySelectorAll('.stjo-pills a')).filter(function (a) {
						return a.textContent.trim() === focusYear;
					})[0];
					if (pill) {
						pill.focus();
					}
				}
				return fresh;
			});
	}

	document.addEventListener('click', function (event) {
		var pill = event.target.closest('.stjo-stories-band .stjo-pills a');
		if (!pill || event.metaKey || event.ctrlKey || event.shiftKey || event.button) {
			return; // modified clicks keep native behavior (new tab etc.)
		}
		var section = pill.closest('.stjo-stories-band');
		event.preventDefault();
		var url = pill.href;
		swapSection(section, url, pill.textContent.trim()).then(function () {
			window.history.pushState({ stjoStories: true }, '', url);
		}).catch(function () {
			window.location.href = url; // graceful fallback: normal navigation
		});
	});

	// Back/forward: re-sync every stories section from the history URL.
	window.addEventListener('popstate', function () {
		var sections = document.querySelectorAll('.stjo-stories-band');
		if (!sections.length) {
			return;
		}
		fetch(window.location.href, { credentials: 'same-origin' })
			.then(function (res) {
				return res.text();
			})
			.then(function (html) {
				var doc = new DOMParser().parseFromString(html, 'text/html');
				sections.forEach(function (section) {
					var fresh = doc.getElementById(section.id);
					if (fresh) {
						section.replaceWith(fresh);
						var carousel = fresh.querySelector('[data-stories-carousel]');
						if (carousel) {
							init(carousel);
						}
					}
				});
			})
			.catch(function () {
				window.location.reload();
			});
	});
})();
