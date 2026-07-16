/**
 * Mega menu behavior. Top-level section items are disclosure buttons:
 *   click / Enter / Space toggles the panel (native button activation)
 *   ArrowDown opens and focuses the first link, ArrowUp closes
 *   Escape closes and restores focus to the trigger
 *   Tab from an open trigger enters the panel; Tab from the last panel item
 *   moves on to the next parent item (and the panel closes); Shift+Tab
 *   mirrors both jumps
 *
 * On desktop every section's panel is moved into the single .mega-panels
 * band under the header. The band opens IN FLOW: its height animates and
 * pushes the page down. Switching sections keeps the band open (a pointer
 * flag stops the focusout close that would otherwise bounce it shut) and
 * the contents fade out then in. In the drawer (<=1024px) panels move back
 * under their triggers and behave as plain accordions; body scroll locks
 * while the drawer is open.
 */
(function () {
	'use strict';

	var nav = document.querySelector('[data-mega-nav]');
	var host = document.querySelector('[data-mega-panels]');
	var header = document.querySelector('.site-header');
	if (!nav) {
		return;
	}

	var drawerMq = window.matchMedia('(max-width: 1024px)');
	var items = Array.prototype.slice.call(nav.querySelectorAll('[data-mega-trigger]')).map(function (trigger) {
		return {
			trigger: trigger,
			li: trigger.closest('li'),
			panel: document.getElementById(trigger.getAttribute('aria-controls'))
		};
	}).filter(function (it) {
		return it.panel;
	});
	var active = null;
	var pointerSwitch = false;

	function usingHost() {
		return !!host && !drawerMq.matches;
	}

	// Panels live in the band on desktop, under their triggers in the drawer.
	function placePanels() {
		items.forEach(function (it) {
			if (usingHost()) {
				host.appendChild(it.panel);
			} else {
				it.li.appendChild(it.panel);
			}
		});
	}

	function syncHeight() {
		if (!host) {
			return;
		}
		if (active && usingHost()) {
			host.classList.add('is-open');
			host.style.height = active.panel.offsetHeight + 'px';
		} else {
			host.classList.remove('is-open');
			host.style.height = '';
		}
	}

	function setExpanded(it, open) {
		it.trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
		it.panel.classList.toggle('is-open', open);
		it.li.classList.toggle('is-open', open);
	}

	function openItem(it) {
		if (active && active !== it) {
			setExpanded(active, false);
		}
		setExpanded(it, true);
		active = it;
		syncHeight();
	}

	function closeItem(it) {
		setExpanded(it, false);
		if (active === it) {
			active = null;
		}
		syncHeight();
	}

	function closeAll() {
		items.forEach(function (it) {
			setExpanded(it, false);
		});
		active = null;
		syncHeight();
	}

	function isOpen(it) {
		return it.trigger.getAttribute('aria-expanded') === 'true';
	}

	function panelFocusables(panel) {
		return Array.prototype.slice.call(
			panel.querySelectorAll('a[href], button:not([disabled])')
		);
	}

	// Header tab stops outside the panels (triggers, plain links, actions).
	function headerFocusables() {
		return Array.prototype.slice.call(
			header.querySelectorAll('a[href], button:not([disabled])')
		).filter(function (el) {
			return !el.closest('.mega-panel') && null !== el.offsetParent;
		});
	}

	function afterTrigger(trigger) {
		var stops = headerFocusables();
		return stops[stops.indexOf(trigger) + 1] || null;
	}

	function inAnyUnit(el) {
		return !!el && items.some(function (it) {
			return it.li.contains(el) || it.panel.contains(el);
		});
	}

	items.forEach(function (it) {
		it.trigger.addEventListener('click', function () {
			pointerSwitch = false;
			if (isOpen(it)) {
				closeItem(it);
			} else {
				openItem(it);
			}
		});

		it.trigger.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowDown') {
				event.preventDefault();
				openItem(it);
				var first = panelFocusables(it.panel)[0];
				if (first) {
					first.focus();
				}
			} else if (event.key === 'ArrowUp') {
				event.preventDefault();
				closeItem(it);
			} else if (event.key === 'Tab' && !event.shiftKey && isOpen(it) && !drawerMq.matches) {
				// Into the panel instead of on to the next parent.
				var target = panelFocusables(it.panel)[0];
				if (target) {
					event.preventDefault();
					target.focus();
				}
			}
		});

		it.panel.addEventListener('keydown', function (event) {
			if (event.key !== 'Tab' || drawerMq.matches) {
				return;
			}
			var stops = panelFocusables(it.panel);
			if (!stops.length) {
				return;
			}
			if (!event.shiftKey && document.activeElement === stops[stops.length - 1]) {
				// Off the end of the panel: close and resume at the next parent.
				event.preventDefault();
				closeItem(it);
				var next = afterTrigger(it.trigger);
				if (next) {
					next.focus();
				}
			} else if (event.shiftKey && document.activeElement === stops[0]) {
				// Back out of the panel onto its trigger (stays open).
				event.preventDefault();
				it.trigger.focus();
			}
		});

		// The trigger's li and its (possibly relocated) panel act as one
		// disclosure unit for Escape and focus tracking.
		function onKeydown(event) {
			if (event.key === 'Escape' && isOpen(it)) {
				event.stopPropagation();
				closeItem(it);
				it.trigger.focus();
			}
		}
		function onFocusout(event) {
			if (drawerMq.matches || !isOpen(it)) {
				return;
			}
			var next = event.relatedTarget;
			if (it.li.contains(next) || it.panel.contains(next)) {
				return;
			}
			// A pointer press on another trigger moves focus before its click
			// lands; closing here would bounce the band shut and back open.
			// Let the click's openItem() swap contents with the band open.
			if (pointerSwitch && inAnyUnit(next)) {
				return;
			}
			closeItem(it);
		}
		it.li.addEventListener('keydown', onKeydown);
		it.panel.addEventListener('keydown', onKeydown);
		it.li.addEventListener('focusout', onFocusout);
		it.panel.addEventListener('focusout', onFocusout);
	});

	// Shift+Tab from the tab stop after an open trigger mirrors the forward
	// jump: back into the last item of the open panel.
	if (header) {
		header.addEventListener('keydown', function (event) {
			if (event.key !== 'Tab' || !event.shiftKey || !active || drawerMq.matches) {
				return;
			}
			if (document.activeElement === afterTrigger(active.trigger)) {
				var stops = panelFocusables(active.panel);
				if (stops.length) {
					event.preventDefault();
					stops[stops.length - 1].focus();
				}
			}
		});
		header.addEventListener('pointerdown', function (event) {
			pointerSwitch = !!event.target.closest('[data-mega-trigger]');
			if (pointerSwitch) {
				window.setTimeout(function () {
					pointerSwitch = false;
				}, 400);
			}
		});
	}

	// Click outside the nav and the band closes everything.
	document.addEventListener('click', function (event) {
		if (!nav.contains(event.target) && !(host && host.contains(event.target))) {
			closeAll();
		}
	});

	// Keep the band height in step with the open panel.
	window.addEventListener('resize', function () {
		if (active) {
			syncHeight();
		}
	});

	// Drawer toggle.
	var toggle = document.querySelector('[data-nav-toggle]');

	function setDrawer(open) {
		if (!toggle) {
			return;
		}
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		document.body.classList.toggle('nav-open', open);
		if (header) {
			header.classList.toggle('nav-open', open);
		}
		if (!open) {
			closeAll();
		}
	}

	if (toggle) {
		toggle.addEventListener('click', function () {
			setDrawer(toggle.getAttribute('aria-expanded') !== 'true');
		});
		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && document.body.classList.contains('nav-open')) {
				setDrawer(false);
				toggle.focus();
			}
		});
	}

	// Crossing the breakpoint resets state and re-homes the panels.
	function onBreakpointChange() {
		closeAll();
		setDrawer(false);
		placePanels();
	}
	if (drawerMq.addEventListener) {
		drawerMq.addEventListener('change', onBreakpointChange);
	}

	placePanels();

	// ── Search modal ─────────────────────────────────────────────────────
	// Native <dialog>: showModal() gives real modality (background inert,
	// Tab stays inside, Escape closes via the cancel event). We add: focus
	// into the input on open, focus restored to the opener on close, body
	// scroll lock, and backdrop-click close (a click whose target is the
	// dialog itself can only land on the backdrop, the card covers the rest).
	var searchOpen = document.querySelector('[data-search-open]');
	var searchModal = document.querySelector('[data-search-modal]');

	if (searchOpen && searchModal && typeof searchModal.showModal === 'function') {
		searchOpen.addEventListener('click', function () {
			closeAll();
			searchModal.showModal();
			document.body.classList.add('modal-open');
			var input = searchModal.querySelector('input[type="search"]');
			if (input) {
				input.focus();
			}
		});
		searchModal.addEventListener('close', function () {
			document.body.classList.remove('modal-open');
			searchOpen.focus();
		});
		searchModal.addEventListener('click', function (event) {
			if (event.target === searchModal) {
				searchModal.close();
			}
		});
		var searchClose = searchModal.querySelector('[data-search-close]');
		if (searchClose) {
			searchClose.addEventListener('click', function () {
				searchModal.close();
			});
		}
	}
})();
