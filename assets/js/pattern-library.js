/**
 * Pattern Library interactions: each pattern section is focusable and acts
 * as a copy button. Click (or Enter/Space) anywhere on a pattern copies the
 * raw block markup from the section's text/plain script node to the
 * clipboard; the corner HUD's name line briefly reads "Copied to clipboard."
 * and the live region announces the result. Clipboard API first, execCommand
 * fallback for plain-http dev sites.
 */
(function () {
	'use strict';

	var live = document.querySelector('[data-plib-live]');

	function copyText(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		}
		return new Promise(function (resolve, reject) {
			var area = document.createElement('textarea');
			area.value = text;
			area.setAttribute('readonly', '');
			area.style.position = 'fixed';
			area.style.left = '-9999px';
			document.body.appendChild(area);
			area.select();
			try {
				if (document.execCommand('copy')) {
					resolve();
				} else {
					reject(new Error('execCommand failed'));
				}
			} catch (err) {
				reject(err);
			} finally {
				area.remove();
			}
		});
	}

	var items = Array.prototype.slice.call(document.querySelectorAll('[data-plib-item]'));

	// Short blocks (dividers etc.) get a compact one-row HUD; the CSS also
	// clips the HUD to the block, so this keeps name + icon inside view.
	function markShort() {
		items.forEach(function (item) {
			item.classList.toggle('stjo-plib__item--short', item.offsetHeight < 120);
		});
	}
	markShort();
	window.addEventListener('load', markShort);
	var shortRaf = 0;
	window.addEventListener('resize', function () {
		cancelAnimationFrame(shortRaf);
		shortRaf = requestAnimationFrame(markShort);
	});

	items.forEach(function (item) {
		var markupEl = item.querySelector('.stjo-plib__markup');
		if (!markupEl) {
			return;
		}
		var title = item.getAttribute('data-plib-title') || 'pattern';
		var hudTitle = item.querySelector('.stjo-plib__hud-title');
		var hudDefault = hudTitle ? hudTitle.innerHTML : ''; // keeps the <strong> around the name on restore
		var resetTimer = 0;

		item.setAttribute('tabindex', '0');
		item.setAttribute('role', 'button');
		item.setAttribute('aria-label', 'Copy the ' + title + ' pattern markup');

		function feedback(message) {
			if (hudTitle) {
				hudTitle.textContent = message;
				window.clearTimeout(resetTimer);
				resetTimer = window.setTimeout(function () {
					hudTitle.innerHTML = hudDefault;
				}, 1800);
			}
			if (live) {
				live.textContent = message + ' ' + title;
			}
		}

		function copy() {
			copyText(markupEl.textContent.trim()).then(
				function () {
					feedback('Copied to clipboard.');
				},
				function () {
					feedback('Copy failed.');
				}
			);
		}

		item.addEventListener('click', function (event) {
			if (event.target.closest('a[href]')) {
				return; // Isolate / Back links behave normally.
			}
			event.preventDefault();
			copy();
		});
		item.addEventListener('keydown', function (event) {
			if ((event.key === 'Enter' || event.key === ' ') && event.target === item) {
				event.preventDefault();
				copy();
			}
		});
	});
})();
