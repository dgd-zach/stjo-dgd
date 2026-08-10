/**
 * Blog archive filtering + pagination, in-page.
 *
 * Progressive enhancement: the category pills and pagination are real links
 * (server renders the right posts for /blog/?category=slug and its /page/N/).
 * This intercepts those clicks, fetches the target URL, and swaps the fresh
 * #stjo-blog-results fragment in — same fetch + DOMParser approach as the
 * stories carousel, so there's no separate AJAX endpoint. Falls back to a full
 * navigation if the fetch fails.
 */
(function () {
	'use strict';

	var results   = document.querySelector('[data-blog-results]');
	var filterbar = document.querySelector('[data-blog-filter]');
	if (!results || !filterbar) {
		return;
	}
	var status = document.querySelector('[data-blog-status]');
	var busy   = false;

	function announce(doc) {
		if (!status) {
			return;
		}
		var count = doc.querySelectorAll('[data-blog-results] .stjo-story-card').length;
		var pill  = doc.querySelector('[data-blog-filter] .stjo-pills a[aria-current="true"]');
		var label = pill ? pill.textContent.trim() : '';
		status.textContent = count + ' ' + (1 === count ? 'post' : 'posts') +
			(label && 'All' !== label ? ' in ' + label : '') + ' shown.';
	}

	// Mirror the active pill from the fetched document onto the live pills.
	function syncActivePill(freshFilter) {
		var activeEl = freshFilter.querySelector('.stjo-pills a[aria-current="true"]');
		var activeCat = activeEl ? (activeEl.getAttribute('data-category') || '') : '';
		filterbar.querySelectorAll('.stjo-pills a').forEach(function (a) {
			if ((a.getAttribute('data-category') || '') === activeCat) {
				a.setAttribute('aria-current', 'true');
			} else {
				a.removeAttribute('aria-current');
			}
		});
	}

	function load(url, push, focusResults) {
		if (busy) {
			return;
		}
		busy = true;
		results.classList.add('is-loading');
		results.setAttribute('aria-busy', 'true');

		fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return r.text(); })
			.then(function (html) {
				var doc          = new DOMParser().parseFromString(html, 'text/html');
				var freshResults = doc.querySelector('[data-blog-results]');
				var freshFilter  = doc.querySelector('[data-blog-filter]');
				if (!freshResults) {
					window.location.href = url;
					return;
				}
				results.innerHTML = freshResults.innerHTML;
				if (freshFilter) {
					syncActivePill(freshFilter);
				}
				if (push) {
					window.history.pushState({ stjoBlog: true }, '', url);
				}
				announce(doc);
				if (focusResults) {
					results.focus();
				}
			})
			.catch(function () { window.location.href = url; })
			.then(function () {
				results.classList.remove('is-loading');
				results.removeAttribute('aria-busy');
				busy = false;
			});
	}

	// Pills live in the filter bar (stable).
	filterbar.addEventListener('click', function (e) {
		var a = e.target.closest('.stjo-pills a');
		if (!a) {
			return;
		}
		e.preventDefault();
		load(a.href, true, false);
	});

	// Pagination is inside the swapped region, so delegate from the container.
	results.addEventListener('click', function (e) {
		var a = e.target.closest('.pagination a.page-numbers');
		if (!a) {
			return;
		}
		e.preventDefault();
		load(a.href, true, true);
	});

	window.addEventListener('popstate', function () {
		load(window.location.href, false, false);
	});
})();
