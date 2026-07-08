(function () {
  var sidebarToggle = document.getElementById('sidebarToggle');
  var sidebarCollapsible = document.getElementById('sidebarCollapsible');
  var navLinks = document.querySelector('.sidebar-nav');

  if (sidebarToggle && sidebarCollapsible) {
    sidebarToggle.addEventListener('click', function () {
      var isOpen = sidebarCollapsible.classList.toggle('open');
      sidebarToggle.setAttribute('aria-expanded', String(isOpen));
    });
  }

  var sections = document.querySelectorAll('main section[id]');
  var navAnchors = navLinks ? navLinks.querySelectorAll('a') : [];

  if ('IntersectionObserver' in window && sections.length) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var id = entry.target.getAttribute('id');
          navAnchors.forEach(function (a) {
            a.classList.toggle('active', a.getAttribute('href') === '#' + id);
          });
        }
      });
    }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

    sections.forEach(function (section) {
      observer.observe(section);
    });
  }
})();
