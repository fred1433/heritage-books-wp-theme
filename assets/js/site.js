/* Heritage Books: only the interactions the pages need. Everything renders without this file. */
(function () {
  'use strict';
  var store = {
    get: function (k) { try { return sessionStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { sessionStorage.setItem(k, v); } catch (e) {} },
    del: function (k) { try { sessionStorage.removeItem(k); } catch (e) {} }
  };

  /* Era switcher on the home page. */
  document.querySelectorAll('[data-eras]').forEach(function (root) {
    var buttons = root.querySelectorAll('[data-era]');
    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        buttons.forEach(function (b) { b.setAttribute('aria-pressed', String(b === btn)); });
        root.querySelectorAll('[data-era-panel]').forEach(function (p) {
          p.hidden = p.getAttribute('data-era-panel') !== btn.getAttribute('data-era');
        });
      });
    });
  });

  /* Film carousel: moves only when asked. */
  document.querySelectorAll('[data-carousel]').forEach(function (root) {
    var slides = Array.prototype.slice.call(root.querySelectorAll('[data-slide]'));
    var dots = Array.prototype.slice.call(root.querySelectorAll('[data-dot]'));
    var at = 0;
    function show(i) {
      at = (i + slides.length) % slides.length;
      slides.forEach(function (s, n) { s.hidden = n !== at; });
      dots.forEach(function (d, n) { d.setAttribute('aria-current', String(n === at)); });
    }
    var prev = root.querySelector('[data-prev]'), next = root.querySelector('[data-next]');
    if (prev) prev.addEventListener('click', function () { show(at - 1); });
    if (next) next.addEventListener('click', function () { show(at + 1); });
    dots.forEach(function (d, n) { d.addEventListener('click', function () { show(n); }); });
  });

  /* Book gallery. */
  document.querySelectorAll('[data-gallery]').forEach(function (root) {
    var thumbs = root.querySelectorAll('[data-gallery-thumb]');
    thumbs.forEach(function (t) {
      t.addEventListener('click', function () {
        thumbs.forEach(function (o) { o.setAttribute('aria-pressed', String(o === t)); });
        root.querySelectorAll('[data-gallery-panel]').forEach(function (p) {
          p.hidden = p.getAttribute('data-gallery-panel') !== t.getAttribute('data-gallery-thumb');
        });
      });
    });
  });

  /* Sample player. */
  var player = document.querySelector('[data-player]');
  if (player) {
    var video = player.querySelector('video');
    var start = function () {
      player.setAttribute('data-state', 'playing');
      video.controls = true;
      var p = video.play();
      if (p && p.catch) p.catch(function () { player.setAttribute('data-state', 'idle'); video.controls = false; });
    };
    player.querySelector('[data-player-go]').addEventListener('click', function () {
      video.currentTime = 0;
      start();
    });
    video.addEventListener('ended', function () {
      player.setAttribute('data-state', 'ended');
      video.controls = false;
      player.querySelector('[data-idle-label]').hidden = true;
      player.querySelector('[data-replay-label]').hidden = false;
    });
    document.querySelectorAll('[data-play-sample]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        player.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'center' });
        start();
      });
    });
    /* Arriving with #player from another page: play, and leave no extra step in the history. */
    if (location.hash === '#player') {
      history.replaceState(history.state, '', location.pathname + location.search);
      player.scrollIntoView({ block: 'center' });
      start();
    }
  }

  /* Coming back to the list lands on the same card. */
  var isList = document.querySelector('.era-row');
  if (isList) {
    if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
    document.querySelectorAll('[data-film-link], .nav-cta').forEach(function (a) {
      a.addEventListener('click', function (e) {
        store.set('hb-list-y', String(window.scrollY));
        store.set('hb-from-list', '1');
        if (e.target.closest && e.target.closest('[data-card-play]')) {
          e.preventDefault();
          location.href = a.href + '#player';
        }
      });
    });
    window.addEventListener('pageshow', function (e) {
      var entry = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
      var back = e.persisted || (entry && entry.type === 'back_forward');
      var y = store.get('hb-list-y');
      if (back && y !== null) window.scrollTo(0, parseInt(y, 10));
    });
  }
  document.querySelectorAll('[data-back-to-list]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      if (store.get('hb-from-list') === '1' && history.length > 1) {
        e.preventDefault();
        history.back();
      }
    });
  });
  if (!isList && document.querySelector('[data-back-to-list]') && document.referrer.indexOf('/films/') === -1) {
    store.del('hb-from-list');
  }
})();
