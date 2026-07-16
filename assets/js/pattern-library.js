/**
 * Pattern Library interactions: each pattern section is focusable and acts
 * as a copy button. Click (or Enter/Space) anywhere on a pattern, except
 * the overlay's real links, copies the raw block markup from the section's
 * text/plain script node to the clipboard, with an announced "Copied"
 * confirmation. Clipboard API first, execCommand fallback for plain-http
 * dev sites.
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

	document.querySelectorAll('[data-plib-item]').forEach(function (item) {
		var markupEl = item.querySelector('.stjo-plib__markup');
		var hint = item.querySelector('[data-plib-hint]');
		if (!markupEl) {
			return;
		}
		var title = item.getAttribute('data-plib-title') || 'pattern';
		var hintDefault = hint ? hint.textContent : '';
		var resetTimer = 0;

		item.setAttribute('tabindex', '0');
		item.setAttribute('role', 'button');
		item.setAttribute('aria-label', 'Copy the ' + title + ' pattern markup');

		function feedback(message) {
			if (hint) {
				hint.textContent = message;
				item.classList.add('is-copied');
				window.clearTimeout(resetTimer);
				resetTimer = window.setTimeout(function () {
					hint.textContent = hintDefault;
					item.classList.remove('is-copied');
				}, 1800);
			}
			if (live) {
				live.textContent = message + ': ' + title;
			}
		}

		function copy() {
			copyText(markupEl.textContent.trim()).then(
				function () {
					feedback('Copied!');
				},
				function () {
					feedback('Copy failed');
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
