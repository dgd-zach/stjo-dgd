/**
 * Mega menu behavior. Top-level section items are a link + a chevron
 * disclosure button (data-mega-trigger):
 *   desktop hover (hover-capable pointers only) opens the panel with a short
 *   intent delay; moving off the item AND its panel closes it
 *   clicking the link navigates to the section landing page (desktop); in
 *   the drawer the link click is intercepted and toggles the accordion, so
 *   the whole row keeps working as before on mobile
 *   the button: click / Enter / Space toggles, ArrowDown opens and focuses
 *   the first link, ArrowUp closes, Escape closes and restores focus
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
	var hoverMq = window.matchMedia('(hover: hover) and (pointer: fine)');
	var items = Array.prototype.slice.call(nav.querySelectorAll('[data-mega-trigger]')).map(function (trigger) {
		var li = trigger.closest('li');
		return {
			trigger: trigger,
			li: li,
			link: li ? li.querySelector('[data-mega-link]') : null,
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

	// Hover intent: on hover-capable desktop the band opens on pointer hover
	// and closes when the pointer has left both the item and its (relocated)
	// panel. Timers give a short grace period for the trigger-to-panel hop and
	// never close a panel that holds keyboard focus.
	var openTimer = null;
	var closeTimer = null;

	// Arriving on a page with the cursor already parked on the item you just
	// clicked would pop its panel straight back open (the browser hit-tests
	// the element under the cursor on the first pointer event after load).
	// Hover-open stays disarmed until the pointer is seen OUTSIDE a section
	// item once, so the panel only opens after a real leave-and-return.
	var hoverArmed = false;
	function armHover(event) {
		if (!event.target.closest || !event.target.closest('.menu-item--section, .mega-panel')) {
			hoverArmed = true;
			document.removeEventListener('pointermove', armHover);
		}
	}
	document.addEventListener('pointermove', armHover);

	function hovering() {
		return hoverArmed && hoverMq.matches && usingHost();
	}

	function scheduleClose(it) {
		window.clearTimeout(closeTimer);
		closeTimer = window.setTimeout(function () {
			if (active !== it) {
				return;
			}
			var focused = document.activeElement;
			if (it.li.contains(focused) || it.panel.contains(focused)) {
				return;
			}
			closeItem(it);
		}, 220);
	}

	items.forEach(function (it) {
		it.li.addEventListener('pointerenter', function (event) {
			if (!hovering() || 'touch' === event.pointerType) {
				return;
			}
			window.clearTimeout(closeTimer);
			window.clearTimeout(openTimer);
			openTimer = window.setTimeout(function () {
				openItem(it);
			}, 80);
		});
		it.li.addEventListener('pointerleave', function (event) {
			if (!hovering() || 'touch' === event.pointerType) {
				return;
			}
			window.clearTimeout(openTimer);
			scheduleClose(it);
		});
		it.panel.addEventListener('pointerenter', function (event) {
			if (!hovering() || 'touch' === event.pointerType) {
				return;
			}
			window.clearTimeout(closeTimer);
		});
		it.panel.addEventListener('pointerleave', function (event) {
			if (!hovering() || 'touch' === event.pointerType) {
				return;
			}
			scheduleClose(it);
		});

		// The section link navigates on desktop; in the drawer the row acts as
		// the accordion toggle it always was, so intercept and toggle instead.
		if (it.link) {
			it.link.addEventListener('click', function (event) {
				if (drawerMq.matches) {
					event.preventDefault();
					if (isOpen(it)) {
						closeItem(it);
					} else {
						openItem(it);
					}
				}
			});
			it.link.addEventListener('keydown', function (event) {
				if (event.key === 'ArrowDown') {
					event.preventDefault();
					openItem(it);
					var first = panelFocusables(it.panel)[0];
					if (first) {
						first.focus();
					}
				}
			});
		}

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

		// Focus trap for the open drawer. (The search + lightbox modals use a
		// native <dialog> showModal(), which traps focus on its own; the drawer
		// is a plain overlay, so contain Tab within the header manually.)
		function drawerFocusables() {
			var sel = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
			return Array.prototype.filter.call(header.querySelectorAll(sel), function (el) {
				return !el.closest('[inert]') && (el.offsetWidth > 0 || el.offsetHeight > 0 || el.getClientRects().length > 0);
			});
		}
		document.addEventListener('keydown', function (event) {
			if (event.key !== 'Tab' || !drawerMq.matches || !document.body.classList.contains('nav-open')) {
				return;
			}
			var focusables = drawerFocusables();
			if (!focusables.length) {
				return;
			}
			var first = focusables[0];
			var last = focusables[focusables.length - 1];
			var current = document.activeElement;
			if (!header.contains(current)) {
				event.preventDefault();
				first.focus();
			} else if (event.shiftKey && current === first) {
				event.preventDefault();
				last.focus();
			} else if (!event.shiftKey && current === last) {
				event.preventDefault();
				first.focus();
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
	// Escape closes via the cancel event). We add: a wrapping Tab focus trap
	// (showModal alone lets Tab step out to the browser UI at the ends), focus
	// into the input on open, focus restored to the opener on close, body
	// scroll lock, and backdrop-click close (a click whose target is the
	// dialog itself can only land on the backdrop, the card covers the rest).
	var searchOpen = document.querySelector('[data-search-open]');
	var searchModal = document.querySelector('[data-search-modal]');

	if (searchOpen && searchModal && typeof searchModal.showModal === 'function') {
		searchOpen.addEventListener('click', function () {
			closeAll();
			searchModal.showModal();
			// Measure the scrollbar BEFORE locking scroll so body.modal-open can
			// pad by it and the page doesn't shift sideways when it disappears.
			var scrollbar = window.innerWidth - document.documentElement.clientWidth;
			document.documentElement.style.setProperty('--stjo-scrollbar-comp', scrollbar + 'px');
			document.body.classList.add('modal-open');
			var input = searchModal.querySelector('input[type="search"]');
			if (input) {
				input.focus();
			}
		});
		searchModal.addEventListener('close', function () {
			document.body.classList.remove('modal-open');
			document.documentElement.style.removeProperty('--stjo-scrollbar-comp');
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
		// Native showModal() inerts the page but lets Tab step out to the browser
		// UI at the ends — wrap it so focus stays inside the dialog.
		searchModal.addEventListener('keydown', function (event) {
			if (event.key !== 'Tab') {
				return;
			}
			var focusables = Array.prototype.filter.call(
				searchModal.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'),
				function (el) { return el.offsetWidth > 0 || el.offsetHeight > 0 || el.getClientRects().length > 0; }
			);
			if (!focusables.length) {
				return;
			}
			var first = focusables[0];
			var last = focusables[focusables.length - 1];
			if (event.shiftKey && document.activeElement === first) {
				event.preventDefault();
				last.focus();
			} else if (!event.shiftKey && document.activeElement === last) {
				event.preventDefault();
				first.focus();
			}
		});
	}
})();
