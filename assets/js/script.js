(() => {
  "use strict";

  const CSS = `
:root{--subspace-loader-z:2147483647;--subspace-loader-bg:rgba(255,255,255,.75);--subspace-loader-fg:#0d6efd;}
@media (prefers-color-scheme: dark){
  :root{--subspace-loader-bg:rgba(0,0,0,.55);--subspace-loader-fg:#9ec5fe;}
}
@media (prefers-reduced-motion: reduce){
  .subspace-loader__spinner{animation:none!important;}
  .subspace-lazy{transition:none!important;}
}
.subspace-loader{
  position:fixed;inset:0;display:grid;place-items:center;
  background:var(--subspace-loader-bg);backdrop-filter:saturate(1.2) blur(2px);
  opacity:0;pointer-events:none;transition:opacity .15s ease;
  z-index:var(--subspace-loader-z);
}
.subspace-loader.is-active{opacity:1;pointer-events:auto;}
.subspace-loader__spinner{
  width:44px;height:44px;border-radius:50%;
  border:4px solid rgba(0,0,0,.12);border-top-color:var(--subspace-loader-fg);
  animation:subspace-spin .8s linear infinite;
}
@keyframes subspace-spin{to{transform:rotate(360deg)}}

.subspace-lazy{opacity:0;transition:opacity .25s ease;}
.subspace-lazy.is-loaded{opacity:1;}
`;

  // Injecteer CSS in de pagina
  function injectStyles(cssText) {
    const style = document.createElement("style");
    style.setAttribute("data-subspace", "runtime-styles");
    style.textContent = cssText;
    document.head.appendChild(style);
  }

  // Controleer of een URL dezelfde origin heeft
  function sameOrigin(url) {
    try {
      const u = new URL(url, window.location.href);
      return u.origin === window.location.origin;
    } catch {
      return false;
    }
  }

  // Controleer of het alleen een hash navigatie is
  function isHashOnlyNav(anchor) {
    const href = anchor.getAttribute("href") || "";
    if (!href.startsWith("#")) return false;
    // behandel "#", "#section" als alleen-hash
    return true;
  }

  // Maak een laad-indicator voor pagina navigatie
  function createPageLoader() {
    const el = document.createElement("div");
    el.className = "subspace-loader";
    el.setAttribute("aria-hidden", "true");
    el.innerHTML = `<div class="subspace-loader__spinner" role="status" aria-label="Loading"></div>`;
    document.body.appendChild(el);

    let active = false;
    const show = () => {
      if (active) return;
      active = true;
      el.classList.add("is-active");
    };
    const hide = () => {
      active = false;
      el.classList.remove("is-active");
    };

    return { show, hide };
  }

  // Setup navigatie loader voor interne links
  function setupNavigationLoader(loader) {
    // Eerste laden: toon snel, verberg bij load/pageshow
    loader.show();
    window.addEventListener("load", () => loader.hide(), { once: true });
    window.addEventListener("pageshow", () => loader.hide()); // BFCache herstel

    // Toon bij navigaties binnen de site
    document.addEventListener(
      "click",
      (e) => {
        const a = e.target && e.target.closest ? e.target.closest("a") : null;
        if (!a) return;
        if (a.target && a.target !== "_self") return;
        if (a.hasAttribute("download")) return;

        const href = a.getAttribute("href");
        if (!href) return;

        if (isHashOnlyNav(a)) return;
        if (!sameOrigin(href)) return;

        loader.show();
      },
      { capture: true }
    );

    // Toon loader bij formulier verzendingen
    document.addEventListener(
      "submit",
      (e) => {
        const form = e.target;
        if (!form || form.method?.toLowerCase() === "dialog") return;
        loader.show();
      },
      { capture: true }
    );

    window.addEventListener("beforeunload", () => loader.show());
  }

  // Markeer afbeeldingen voor eager of lazy loading
  function markEagerOrLazy(el) {
    if (el.hasAttribute("data-eager")) return; // opt-out
    // Alleen instellen als niet al opgegeven door auteur
    if (!el.getAttribute("loading")) el.setAttribute("loading", "lazy");
    if (!el.getAttribute("decoding")) el.setAttribute("decoding", "async");
  }

  // Laad een afbeelding met fade-in effect
  function loadImg(img) {
    const dataSrc = img.getAttribute("data-src");
    const dataSrcset = img.getAttribute("data-srcset");

    if (dataSrcset && !img.getAttribute("srcset")) img.setAttribute("srcset", dataSrcset);
    if (dataSrc && img.getAttribute("src") !== dataSrc) img.setAttribute("src", dataSrc);

    img.addEventListener(
      "load",
      () => {
        img.classList.add("is-loaded");
        img.removeAttribute("data-src");
        img.removeAttribute("data-srcset");
      },
      { once: true }
    );

    img.addEventListener(
      "error",
      () => {
        // Voorkom eeuwig onzichtbaar blijven bij fouten
        img.classList.add("is-loaded");
      },
      { once: true }
    );
  }

  // Laad achtergrond afbeelding
  function loadBg(el) {
    const url = el.getAttribute("data-bg");
    if (!url) return;
    el.style.backgroundImage = `url("${url.replace(/"/g, '\\"')}")`;
    el.removeAttribute("data-bg");
    el.classList.add("is-loaded");
  }

  // Setup lazy loading voor afbeeldingen en iframes
  function setupLazyLoading() {
    const imgs = Array.from(document.querySelectorAll("img"));
    const iframes = Array.from(document.querySelectorAll("iframe"));
    const lazyImgs = [];
    const lazyBgs = [];

    imgs.forEach((img) => {
      markEagerOrLazy(img);

      // Pas consistente "loading -> loaded" animatie toe voor alle afbeeldingen (opt-out via data-eager)
      if (!img.hasAttribute("data-eager")) {
        img.classList.add("subspace-lazy");

        if (img.complete) {
          // Gecachet / al geladen
          img.classList.add("is-loaded");
        } else {
          img.addEventListener(
            "load",
            () => img.classList.add("is-loaded"),
            { once: true }
          );
          img.addEventListener(
            "error",
            () => img.classList.add("is-loaded"),
            { once: true }
          );
        }
      }

      const wantsManagedLazy = img.hasAttribute("data-src") || img.hasAttribute("data-srcset");
      if (wantsManagedLazy) {
        // beheerde lazy: alleen echte src/srcset instellen wanneer we besluiten te laden
        lazyImgs.push(img);
      }
    });

    iframes.forEach((f) => {
      if (f.hasAttribute("data-eager")) return;
      if (!f.getAttribute("loading")) f.setAttribute("loading", "lazy");
    });

    document.querySelectorAll("[data-bg]").forEach((el) => {
      el.classList.add("subspace-lazy");
      lazyBgs.push(el);
    });

    // Als native lazy-loading bestaat, kunnen we src/srcset direct instellen en de browser laat plannen
    const hasNativeLazyImg = "loading" in HTMLImageElement.prototype;

    if (hasNativeLazyImg) {
      lazyImgs.forEach(loadImg);
      // Achtergrond afbeeldingen hebben nog steeds IO nodig
    }

    const needsIO = !hasNativeLazyImg || lazyBgs.length > 0;
    if (!needsIO) return;

    if (!("IntersectionObserver" in window)) {
      // Fallback: laad alles in één keer
      lazyImgs.forEach(loadImg);
      lazyBgs.forEach(loadBg);
      return;
    }

    // Observeer elementen en laad ze wanneer ze in beeld komen
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const el = entry.target;

          if (el.tagName === "IMG") loadImg(el);
          else loadBg(el);

          io.unobserve(el);
        });
      },
      { root: null, rootMargin: "200px 0px", threshold: 0.01 }
    );

    // Observeer alleen elementen die nog niet door native eager scheduling zijn afgehandeld
    if (!hasNativeLazyImg) lazyImgs.forEach((img) => io.observe(img));
    lazyBgs.forEach((el) => io.observe(el));
  }

  // Oneindige scroll voor spaces pagina
  function setupInfiniteScroll() {
    const container = document.querySelector('[data-infinite-scroll]');
    if (!container) return;

    const loadMoreUrl = container.getAttribute('data-load-more-url');
    if (!loadMoreUrl) return;

    let loading = false;
    let page = 1;
    let hasMore = true;

    // Laad meer content via AJAX
    const loadMore = async () => {
      if (loading || !hasMore) return;
      loading = true;

      // Toon laad indicator
      const loadingIndicator = document.createElement('div');
      loadingIndicator.className = 'text-center py-4';
      loadingIndicator.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
      container.appendChild(loadingIndicator);

      try {
        page++;
        const url = new URL(loadMoreUrl, window.location.origin);
        url.searchParams.set('page', page);
        url.searchParams.set('ajax', '1');

        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');

        const html = await response.text();
        loadingIndicator.remove();

        // Controleer of er nog meer content is
        if (html.trim() === '' || html.trim() === '[]') {
          hasMore = false;
          return;
        }

        // Voeg nieuwe content toe
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        while (tempDiv.firstChild) {
          container.appendChild(tempDiv.firstChild);
        }

        // Setup lazy loading opnieuw voor nieuwe afbeeldingen
        setupLazyLoading();
      } catch (error) {
        console.error('Error loading more content:', error);
        loadingIndicator.remove();
      } finally {
        loading = false;
      }
    };

    // Intersection Observer voor automatisch laden
    const sentinel = document.createElement('div');
    sentinel.className = 'infinite-scroll-sentinel';
    sentinel.style.height = '1px';
    container.parentElement.appendChild(sentinel);

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && hasMore) {
            loadMore();
          }
        });
      },
      { root: null, rootMargin: '400px', threshold: 0.01 }
    );

    observer.observe(sentinel);
  }

  // Initialiseer alle functionaliteit bij DOM ready
  document.addEventListener("DOMContentLoaded", () => {
    injectStyles(CSS);

    const loader = createPageLoader();
    setupNavigationLoader(loader);

    setupLazyLoading();
    setupInfiniteScroll();
  });
})();
