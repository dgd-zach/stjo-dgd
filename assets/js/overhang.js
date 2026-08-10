/**
 * Content-overhang sizing: makes the overhang exactly half the overhanging
 * block's own height, so the band edge always meets that block at its
 * vertical midpoint — whatever block is placed there, at any width.
 *
 * The CSS mechanism (main.css) drives both the band's reserve and the child's
 * pull from one --overhang length; this just sets that length to half the
 * child's measured height. A block with an author-set --overhang (inline
 * style) is left alone. ResizeObserver keeps it correct as the block reflows
 * (e.g. a 16:9 embed changing height with the viewport) or loads late.
 */
(function () {
	'use strict';

	var groups = document.querySelectorAll('.is-style-overhang-first-child, .is-style-overhang-last-child');
	if (!groups.length) {
		return;
	}

	Array.prototype.forEach.call(groups, function (group) {
		// Respect an explicit author override.
		if (group.style && group.style.getPropertyValue('--overhang')) {
			return;
		}
		var isFirst = group.classList.contains('is-style-overhang-first-child');
		var child = isFirst ? group.firstElementChild : group.lastElementChild;
		if (!child) {
			return;
		}
		function apply() {
			var h = child.getBoundingClientRect().height;
			if (h > 0) {
				group.style.setProperty('--overhang', (h / 2) + 'px');
			}
		}
		apply();
		if ('ResizeObserver' in window) {
			new ResizeObserver(apply).observe(child);
		} else {
			window.addEventListener('resize', apply);
		}
	});
} )();
