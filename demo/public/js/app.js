/**
 * AgriExpress front-end interactions
 * Vanilla JS — no build step required.
 */
document.addEventListener('DOMContentLoaded', function () {
  initMobileMenu();
  initDropdowns();
  initCarousels();
  initSimpleCarousels();
  initQtyControls();
  initGallery();
  initTabs();
  initSwatches();
  initCoupon();
  initPasswordToggle();
  initAuthTabs();
  initWishlistToggle();
  updateCartBadge();
});

/* ----------------------------------------------------
 * Mobile menu (hamburger) toggle
 * -------------------------------------------------- */
function initMobileMenu() {
  const btn = document.querySelector('[data-mobile-menu-btn]');
  const panel = document.querySelector('[data-mobile-menu-panel]');
  if (!btn || !panel) return;

  btn.addEventListener('click', function () {
    const isOpen = panel.classList.toggle('hidden') === false;
    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    const openIcon = btn.querySelector('.icon-open');
    const closeIcon = btn.querySelector('.icon-close');
    if (openIcon && closeIcon) {
      openIcon.classList.toggle('hidden', isOpen);
      closeIcon.classList.toggle('hidden', !isOpen);
    }
  });
}

/* ----------------------------------------------------
 * Nav dropdowns (Shop / Categories) — click to open,
 * closes on outside click or Escape
 * -------------------------------------------------- */
function initDropdowns() {
  const dropdowns = document.querySelectorAll('[data-dropdown]');

  dropdowns.forEach(function (dropdown) {
    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
    const menu = dropdown.querySelector('[data-dropdown-menu]');
    if (!trigger || !menu) return;

    trigger.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = !menu.classList.contains('hidden');
      closeAllDropdowns();
      if (!isOpen) {
        menu.classList.remove('hidden');
        trigger.querySelector('.chevron')?.classList.add('rotate-180');
      }
    });
  });

  function closeAllDropdowns() {
    dropdowns.forEach(function (dropdown) {
      const menu = dropdown.querySelector('[data-dropdown-menu]');
      const trigger = dropdown.querySelector('[data-dropdown-trigger]');
      menu?.classList.add('hidden');
      trigger?.querySelector('.chevron')?.classList.remove('rotate-180');
    });
  }

  document.addEventListener('click', closeAllDropdowns);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAllDropdowns();
  });
}

/* ----------------------------------------------------
 * Generic carousel: used for Hero slider & Testimonials
 * Markup contract:
 * <div data-carousel data-autoplay="5000">
 *   <div data-carousel-track> ...slides (data-carousel-slide)... </div>
 *   <button data-carousel-prev></button>
 *   <button data-carousel-next></button>
 *   <div data-carousel-dots><button data-dot-index="0">...</button></div>
 * </div>
 * -------------------------------------------------- */
function initCarousels() {
  document.querySelectorAll('[data-carousel]').forEach(function (root) {
    // Guard: never initialise the same carousel twice (double-included
    // script, Turbo/livewire re-render, etc.) — that's what leaves behind
    // duplicate dots and two autoplay timers fighting each other.
    if (root.dataset.carouselReady === '1') return;
    root.dataset.carouselReady = '1';

    const track = root.querySelector('[data-carousel-track]');
    const slides = Array.from(root.querySelectorAll('[data-carousel-slide]'));
    const dotsWrap = root.querySelector('[data-carousel-dots]');
    const prevBtn = root.querySelector('[data-carousel-prev]');
    const nextBtn = root.querySelector('[data-carousel-next]');
    if (!track || slides.length === 0) return;

    let index = 0;
    const autoplayMs = parseInt(root.dataset.autoplay || '0', 10);
    let timer = null;

    // How many slides fit in the viewport at the current breakpoint.
    // Derived from measured widths so it works for 1-per-view (hero) and
    // 3-per-view (testimonials) without any config.
    function perView() {
      const viewport = track.parentElement;
      if (!viewport) return 1;
      const slideW = slides[0].getBoundingClientRect().width;
      if (!slideW) return 1;
      return Math.max(1, Math.round(viewport.getBoundingClientRect().width / slideW));
    }

    // Last index we can scroll to without leaving a gap at the end.
    function maxIndex() {
      return Math.max(0, slides.length - perView());
    }

    // Dots represent scrollable positions, not raw slides. With 4 cards at
    // 3-per-view there are only 2 positions, so only 2 dots. Always rebuilt
    // from scratch so a stale DOM can never leave an extra dot behind.
    function buildDots() {
      if (!dotsWrap) return;
      dotsWrap.innerHTML = '';
      const count = maxIndex() + 1;
      if (count < 2) return; // nothing to page through
      for (let i = 0; i < count; i++) {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.setAttribute('data-dot-index', i);
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        dot.className = 'h-2 rounded-full transition-all ' + (i === index ? 'bg-brand-700 w-5' : 'bg-gray-300 w-2');
        dotsWrap.appendChild(dot);
      }
    }

    function render() {
      // Step by the slide's real offset rather than a flat 100% per index,
      // so a 3-up slider advances one card at a time.
      const offset = slides[index].offsetLeft - slides[0].offsetLeft;
      track.style.transform = 'translateX(-' + offset + 'px)';

      if (dotsWrap) {
        Array.from(dotsWrap.children).forEach(function (dot, i) {
          const active = i === index;
          dot.classList.toggle('bg-brand-700', active);
          dot.classList.toggle('w-5', active);
          dot.classList.toggle('bg-gray-300', !active);
          dot.classList.toggle('w-2', !active);
        });
      }
    }

    function goTo(i) {
      const max = maxIndex();
      // Wrap around the scrollable range, not the raw slide count.
      if (i > max) i = 0;
      if (i < 0) i = max;
      index = i;
      render();
      restartAutoplay();
    }

    function next() { goTo(index + 1); }
    function prev() { goTo(index - 1); }

    prevBtn?.addEventListener('click', prev);
    nextBtn?.addEventListener('click', next);
    dotsWrap?.addEventListener('click', function (e) {
      const dot = e.target.closest('[data-dot-index]');
      if (dot) goTo(parseInt(dot.dataset.dotIndex, 10));
    });

    function restartAutoplay() {
      if (!autoplayMs) return;
      clearInterval(timer);
      timer = setInterval(next, autoplayMs);
    }

    // Pause on hover
    root.addEventListener('mouseenter', function () { clearInterval(timer); });
    root.addEventListener('mouseleave', restartAutoplay);

    // Breakpoint changes alter perView, slide offsets and the dot count.
    let resizeTimer = null;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (index > maxIndex()) index = maxIndex();
        buildDots();
        render();
      }, 150);
    });

    buildDots();
    render();
    restartAutoplay();

    // Images loading can change slide offsets — re-measure once settled.
    window.addEventListener('load', function () {
      buildDots();
      render();
    });
  });
}

/* ----------------------------------------------------
 * Simple carousel — shows/hides one slide at a time via
 * a plain class toggle. No transform math, no flex-width
 * assumptions — just classList swaps, so it can't silently
 * fail to move even if some CSS elsewhere doesn't load.
 * Markup contract:
 * <div data-simple-carousel data-autoplay="5000">
 *   <div data-simple-slide>...</div>
 *   <div data-simple-slide class="hidden">...</div>
 *   <button data-simple-prev></button>
 *   <button data-simple-next></button>
 *   <div data-simple-dots></div>
 * </div>
 * -------------------------------------------------- */
function initSimpleCarousels() {
  document.querySelectorAll('[data-simple-carousel]').forEach(function (root) {
    const slides = Array.from(root.querySelectorAll('[data-simple-slide]'));
    const dotsWrap = root.querySelector('[data-simple-dots]');
    const prevBtn = root.querySelector('[data-simple-prev]');
    const nextBtn = root.querySelector('[data-simple-next]');
    if (slides.length === 0) return;

    let index = slides.findIndex(function (s) { return !s.classList.contains('hidden'); });
    if (index === -1) index = 0;

    const autoplayMs = parseInt(root.dataset.autoplay || '0', 10);
    let timer = null;

    if (dotsWrap && dotsWrap.children.length === 0) {
      slides.forEach(function (_, i) {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.setAttribute('data-simple-dot-index', i);
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        dot.className = 'w-2 h-2 rounded-full transition-all ' + (i === index ? 'bg-brand-700 w-5' : 'bg-gray-300');
        dotsWrap.appendChild(dot);
      });
    }

    function render() {
      slides.forEach(function (slide, i) {
        slide.classList.toggle('hidden', i !== index);
      });
      if (dotsWrap) {
        Array.from(dotsWrap.children).forEach(function (dot, i) {
          dot.classList.toggle('bg-brand-700', i === index);
          dot.classList.toggle('w-5', i === index);
          dot.classList.toggle('bg-gray-300', i !== index);
          dot.classList.toggle('w-2', i !== index);
        });
      }
    }

    function goTo(i) {
      index = (i + slides.length) % slides.length;
      render();
      restartAutoplay();
    }

    function next() { goTo(index + 1); }
    function prev() { goTo(index - 1); }

    prevBtn?.addEventListener('click', prev);
    nextBtn?.addEventListener('click', next);
    dotsWrap?.addEventListener('click', function (e) {
      const dot = e.target.closest('[data-simple-dot-index]');
      if (dot) goTo(parseInt(dot.dataset.simpleDotIndex, 10));
    });

    function restartAutoplay() {
      if (!autoplayMs) return;
      clearInterval(timer);
      timer = setInterval(next, autoplayMs);
    }

    root.addEventListener('mouseenter', function () { clearInterval(timer); });
    root.addEventListener('mouseleave', restartAutoplay);

    render();
    restartAutoplay();
  });
}

/* ----------------------------------------------------
 * Quantity steppers (cart, product page)
 * Markup: <div data-qty><button data-qty-decrease>-</button>
 *   <span data-qty-value>1</span><button data-qty-increase>+</button></div>
 * -------------------------------------------------- */
function initQtyControls() {
  document.querySelectorAll('[data-qty]').forEach(function (control) {
    const valueEl = control.querySelector('[data-qty-value]');
    const decreaseBtn = control.querySelector('[data-qty-decrease]');
    const increaseBtn = control.querySelector('[data-qty-increase]');
    const min = parseInt(control.dataset.min || '1', 10);
    const max = parseInt(control.dataset.max || '99', 10);
    if (!valueEl) return;

    function setValue(v) {
      v = Math.min(max, Math.max(min, v));
      valueEl.textContent = v;
      control.dispatchEvent(new CustomEvent('qtychange', { bubbles: true, detail: { value: v } }));
    }

    decreaseBtn?.addEventListener('click', function () {
      setValue(parseInt(valueEl.textContent, 10) - 1);
    });
    increaseBtn?.addEventListener('click', function () {
      setValue(parseInt(valueEl.textContent, 10) + 1);
    });
  });

  // Recalculate cart row + order summary totals when any qty changes on the cart page
  const cartTotalsRoot = document.querySelector('[data-cart-totals]');
  if (cartTotalsRoot) {
    document.querySelectorAll('[data-cart-row]').forEach(function (row) {
      row.addEventListener('qtychange', function (e) {
        const price = parseFloat(row.dataset.price);
        const lineTotalEl = row.querySelector('[data-line-total]');
        if (lineTotalEl) lineTotalEl.textContent = '$' + (price * e.detail.value).toFixed(2);
        recalcCartTotals();
      });
    });
    recalcCartTotals();
  }

  function recalcCartTotals() {
    let subtotal = 0;
    document.querySelectorAll('[data-cart-row]').forEach(function (row) {
      const price = parseFloat(row.dataset.price);
      const qty = parseInt(row.querySelector('[data-qty-value]').textContent, 10);
      subtotal += price * qty;
    });
    const discountPct = parseFloat(cartTotalsRoot.dataset.discountPct || '0');
    const taxPct = parseFloat(cartTotalsRoot.dataset.taxPct || '0');
    const discount = subtotal * (discountPct / 100);
    const taxable = subtotal - discount;
    const tax = taxable * (taxPct / 100);
    const total = taxable + tax;

    setText('[data-summary-subtotal]', '$' + subtotal.toFixed(2));
    setText('[data-summary-discount]', '-$' + discount.toFixed(2));
    setText('[data-summary-tax]', '$' + tax.toFixed(2));
    setText('[data-summary-total]', '$' + total.toFixed(2));
  }

  function setText(selector, text) {
    document.querySelectorAll(selector).forEach(function (el) { el.textContent = text; });
  }
}

/* ----------------------------------------------------
 * Product gallery — click thumbnail to swap main image
 * -------------------------------------------------- */
function initGallery() {
  const mainImg = document.querySelector('[data-gallery-main]');
  const thumbs = document.querySelectorAll('[data-gallery-thumb]');
  if (!mainImg || thumbs.length === 0) return;

  thumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      mainImg.src = thumb.dataset.fullImage || thumb.querySelector('img')?.src;
      thumbs.forEach(function (t) { t.classList.remove('border-brand-700'); t.classList.add('border-gray-200'); });
      thumb.classList.add('border-brand-700');
      thumb.classList.remove('border-gray-200');
    });
  });
}

/* ----------------------------------------------------
 * Tabs — Description / Specifications / Reviews
 * -------------------------------------------------- */
function initTabs() {
  document.querySelectorAll('[data-tabs]').forEach(function (root) {
    const buttons = root.querySelectorAll('[data-tab-btn]');
    const panels = root.querySelectorAll('[data-tab-panel]');

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const target = btn.dataset.tabBtn;

        buttons.forEach(function (b) {
          b.classList.remove('border-brand-700', 'text-brand-700');
          b.classList.add('text-gray-500', 'border-transparent');
        });
        btn.classList.add('border-brand-700', 'text-brand-700');
        btn.classList.remove('text-gray-500', 'border-transparent');

        panels.forEach(function (p) {
          p.classList.toggle('hidden', p.dataset.tabPanel !== target);
        });
      });
    });
  });
}

/* ----------------------------------------------------
 * Color / variant swatches — selectable state
 * -------------------------------------------------- */
function initSwatches() {
  document.querySelectorAll('[data-swatch-group]').forEach(function (group) {
    const swatches = group.querySelectorAll('[data-swatch]');
    const label = group.closest('[data-swatch-wrap]')?.querySelector('[data-swatch-label]');

    swatches.forEach(function (swatch) {
      swatch.addEventListener('click', function () {
        swatches.forEach(function (s) { s.classList.remove('ring-brand-700'); s.classList.add('ring-transparent'); });
        swatch.classList.add('ring-brand-700');
        swatch.classList.remove('ring-transparent');
        if (label) label.textContent = swatch.dataset.swatch;
      });
    });
  });
}

/* ----------------------------------------------------
 * Coupon apply (cart page) — mock feedback
 * -------------------------------------------------- */
function initCoupon() {
  const form = document.querySelector('[data-coupon-form]');
  if (!form) return;
  const input = form.querySelector('input');
  const banner = document.querySelector('[data-coupon-banner]');
  const dismissBtn = banner?.querySelector('[data-coupon-dismiss]');

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!input.value.trim()) return;
    banner?.classList.remove('hidden');
    banner.querySelector('[data-coupon-code]').textContent = input.value.trim().toUpperCase();
    input.value = '';
  });

  dismissBtn?.addEventListener('click', function () {
    banner?.classList.add('hidden');
  });
}

/* ----------------------------------------------------
 * Password show/hide toggle (login page)
 * -------------------------------------------------- */
function initPasswordToggle() {
  document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const input = document.querySelector(btn.dataset.togglePassword);
      if (!input) return;
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.querySelector('.icon-eye')?.classList.toggle('hidden', isHidden);
      btn.querySelector('.icon-eye-slash')?.classList.toggle('hidden', !isHidden);
    });
  });
}

/* ----------------------------------------------------
 * Login / Register tab switch
 * -------------------------------------------------- */
function initAuthTabs() {
  const tabs = document.querySelectorAll('[data-auth-tab]');
  const panels = document.querySelectorAll('[data-auth-panel]');
  if (tabs.length === 0) return;

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      tabs.forEach(function (t) { t.classList.remove('bg-white', 'text-brand-700', 'shadow-sm'); t.classList.add('text-gray-500'); });
      tab.classList.add('bg-white', 'text-brand-700', 'shadow-sm');
      tab.classList.remove('text-gray-500');
      panels.forEach(function (p) { p.classList.toggle('hidden', p.dataset.authPanel !== tab.dataset.authTab); });
    });
  });
}

/* ----------------------------------------------------
 * Wishlist heart toggle (fills in on click)
 * -------------------------------------------------- */
function initWishlistToggle() {
  document.querySelectorAll('[data-wishlist-btn]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const icon = btn.querySelector('i');
      icon.classList.toggle('fa-regular');
      icon.classList.toggle('fa-solid');
      icon.classList.toggle('text-rose-500');
    });
  });
}

/* ----------------------------------------------------
 * Cart badge count (reads data-cart-count on body, or
 * falls back to counting cart rows on the cart page)
 * -------------------------------------------------- */
function updateCartBadge() {
  const badges = document.querySelectorAll('[data-cart-badge]');
  const rows = document.querySelectorAll('[data-cart-row]').length;
  if (rows > 0) {
    badges.forEach(function (b) { b.textContent = rows; });
  }
}
