(function () {
  var root = document.documentElement;
  var header = document.getElementById('siteHeader');
  var toggle = document.getElementById('menuToggle');
  var nav = document.getElementById('mainNav');
  function setOpen(open) {
    nav.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', String(open));
    root.classList.toggle('nav-open', open);
  }
  if (toggle && nav) {
    toggle.addEventListener('click', function () { setOpen(!nav.classList.contains('open')); });
    nav.addEventListener('click', function (e) { if (e.target.closest('a')) { setOpen(false); } });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { setOpen(false); } });
  }
  function onScroll() { if (header) { header.classList.toggle('scrolled', window.scrollY > 40); } }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
})();
