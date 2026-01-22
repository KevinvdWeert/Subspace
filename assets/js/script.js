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

  function injectStyles(cssText) {
    const style = document.createElement("style");
    style.setAttribute("data-subspace", "runtime-styles");
    style.textContent = cssText;
    document.head.appendChild(style);
  }

  function sameOrigin(url) {
    try {
      const u = new URL(url, window.location.href);
      return u.origin === window.location.origin;
    } catch {
      return false;
    }
  }

  function isHashOnlyNav(anchor) {
    const href = anchor.getAttribute("href") || "";
    if (!href.startsWith("#")) return false;
    // treat "#", "#section" as hash-only
    return true;
  }

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

  function setupNavigationLoader(loader) {
    // Initial load: show quickly, then hide on load/pageshow.
    loader.show();
    window.addEventListener("load", () => loader.hide(), { once: true });
    window.addEventListener("pageshow", () => loader.hide()); // BFCache restore

    // Show on in-site navigations.
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

  function markEagerOrLazy(el) {
    if (el.hasAttribute("data-eager")) return; // opt-out
    // Only set if not already specified by author.
    if (!el.getAttribute("loading")) el.setAttribute("loading", "lazy");
    if (!el.getAttribute("decoding")) el.setAttribute("decoding", "async");
  }

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
        // Avoid staying invisible forever if it fails.
        img.classList.add("is-loaded");
      },
      { once: true }
    );
  }

  function loadBg(el) {
    const url = el.getAttribute("data-bg");
    if (!url) return;
    el.style.backgroundImage = `url("${url.replace(/"/g, '\\"')}")`;
    el.removeAttribute("data-bg");
    el.classList.add("is-loaded");
  }

  function setupLazyLoading() {
    const imgs = Array.from(document.querySelectorAll("img"));
    const iframes = Array.from(document.querySelectorAll("iframe"));
    const lazyImgs = [];
    const lazyBgs = [];

    imgs.forEach((img) => {
      markEagerOrLazy(img);

      // Apply a consistent "loading -> loaded" animation for all images (opt-out via data-eager)
      if (!img.hasAttribute("data-eager")) {
        img.classList.add("subspace-lazy");

        if (img.complete) {
          // Cached / already loaded
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
        // managed lazy: only set real src/srcset when we decide to load
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

    // If native lazy-loading exists, we can set src/srcset immediately and let the browser schedule it.
    const hasNativeLazyImg = "loading" in HTMLImageElement.prototype;

    if (hasNativeLazyImg) {
      lazyImgs.forEach(loadImg);
      // Background images still need IO.
    }

    const needsIO = !hasNativeLazyImg || lazyBgs.length > 0;
    if (!needsIO) return;

    if (!("IntersectionObserver" in window)) {
      // Fallback: load everything at once.
      lazyImgs.forEach(loadImg);
      lazyBgs.forEach(loadBg);
      return;
    }

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

    // Only observe those not already handled by native eager scheduling.
    if (!hasNativeLazyImg) lazyImgs.forEach((img) => io.observe(img));
    lazyBgs.forEach((el) => io.observe(el));
  }

  // Infinite scroll for spaces
  function setupInfiniteScroll() {
    const container = document.querySelector('[data-infinite-scroll]');
    if (!container) return;

    const loadMoreUrl = container.getAttribute('data-load-more-url');
    if (!loadMoreUrl) return;

    let loading = false;
    let page = 1;
    let hasMore = true;

    const loadMore = async () => {
      if (loading || !hasMore) return;
      loading = true;

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

        if (html.trim() === '' || html.trim() === '[]') {
          hasMore = false;
          return;
        }

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        while (tempDiv.firstChild) {
          container.appendChild(tempDiv.firstChild);
        }

        // Re-setup lazy loading for new images
        setupLazyLoading();
      } catch (error) {
        console.error('Error loading more content:', error);
        loadingIndicator.remove();
      } finally {
        loading = false;
      }
    };

    // Intersection Observer for automatic loading
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

  document.addEventListener("DOMContentLoaded", () => {
    injectStyles(CSS);

    const loader = createPageLoader();
    setupNavigationLoader(loader);

    setupLazyLoading();
    setupInfiniteScroll();
  });
})();
