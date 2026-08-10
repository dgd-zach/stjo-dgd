/**
 * Custom play button for video blocks (styled in sections.css: the design's
 * play-box icon, white → yellow on hover/focus). Two cases:
 *
 *   • native core/video — a real <button> is overlaid on the <video>; clicking
 *     plays it and hides the overlay so the browser's own controls show.
 *   • YouTube embed — inc/video-facade.php already rendered a poster + button
 *     (a cross-origin iframe can't be overlaid); clicking swaps in the real
 *     player with autoplay. Poster falls back to hqdefault if maxres 404s.
 */
(function () {
	'use strict';

	// Native core/video.
	Array.prototype.forEach.call(document.querySelectorAll('.wp-block-video'), function (figure) {
		var video = figure.querySelector('video');
		if (!video || figure.querySelector('.stjo-play')) {
			return;
		}

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'stjo-play';
		btn.setAttribute('aria-label', 'Play video');
		figure.appendChild(btn);

		btn.addEventListener('click', function () {
			figure.classList.add('is-playing');
			var p = video.play();
			if (p && typeof p.catch === 'function') {
				// Interaction guard: if play is rejected, restore the overlay.
				p.catch(function () { figure.classList.remove('is-playing'); });
			}
			video.focus();
		});

		// Playback started via native controls: drop the overlay too.
		video.addEventListener('play', function () {
			figure.classList.add('is-playing');
		});
	});

	// YouTube facades.
	Array.prototype.forEach.call(document.querySelectorAll('.stjo-video-facade'), function (facade) {
		var poster = facade.querySelector('.stjo-video-facade__poster');
		if (poster) {
			poster.addEventListener('error', function swap() {
				poster.removeEventListener('error', swap);
				poster.src = poster.src.replace('maxresdefault', 'hqdefault');
			});
		}

		var btn = facade.querySelector('.stjo-play');
		if (!btn) {
			return;
		}
		btn.addEventListener('click', function () {
			var iframe = document.createElement('iframe');
			iframe.src = facade.getAttribute('data-embed-src');
			iframe.title = facade.getAttribute('data-title') || 'Video player';
			iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
			iframe.setAttribute('allowfullscreen', '');
			iframe.setAttribute('style', 'position:absolute;inset:0;width:100%;height:100%;border:0;');
			facade.replaceWith(iframe);
			iframe.focus();
		});
	});
})();
