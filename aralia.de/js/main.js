(function(){
  "use strict";

  var yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  var header = document.getElementById("siteHeader");
  if (header) {
    var onScroll = function(){
      header.classList.toggle("scrolled", window.scrollY > 20);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  var navToggle = document.getElementById("navToggle");
  var mainNav = document.getElementById("mainNav");
  if (navToggle && mainNav) {
    navToggle.addEventListener("click", function(){
      var open = mainNav.classList.toggle("open");
      navToggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
    mainNav.querySelectorAll("a").forEach(function(link){
      link.addEventListener("click", function(){
        mainNav.classList.remove("open");
        navToggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  var form = document.getElementById("contactForm");
  var formNote = document.getElementById("formNote");
  if (form) {
    form.addEventListener("submit", function(e){
      e.preventDefault();
      var name = form.name.value.trim();
      var email = form.email.value.trim();
      var anlass = form.anlass.value;
      var datum = form.datum.value.trim();
      var nachricht = form.nachricht.value.trim();

      var subject = "Anfrage über aralia.de: " + anlass;
      var body =
        "Name: " + name + "\n" +
        "E-Mail: " + email + "\n" +
        "Anlass: " + anlass + "\n" +
        "Gewünschtes Datum: " + (datum || "-") + "\n\n" +
        nachricht;

      var mailto = "mailto:info@aralia.de" +
        "?subject=" + encodeURIComponent(subject) +
        "&body=" + encodeURIComponent(body);

      window.location.href = mailto;
      if (formNote) {
        formNote.textContent = "Ihr E-Mail-Programm öffnet sich mit der vorausgefüllten Anfrage an info@aralia.de.";
      }
    });
  }
})();
