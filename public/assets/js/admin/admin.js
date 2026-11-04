/* AgriExpress Admin — shared JS behavior only (sidebar, theme, toast, bulk
   checkboxes). Anything used by a single page (image previews, the
   dashboard charts, etc.) lives in that page's own @push('scripts')
   block instead — see resources/views/admin/*.blade.php. */

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('-translate-x-full');
  document.getElementById('overlay').classList.toggle('hidden');
}

function toggleTheme() {
  const cur = document.documentElement.getAttribute('data-theme');
  const next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  try { localStorage.setItem('se-theme', next); } catch (e) { }

  // Pages that draw theme-aware canvases (e.g. the dashboard's charts)
  // listen for this instead of admin.js knowing about any specific page.
  document.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: next } }));
}

try {
  const saved = localStorage.getItem('se-theme');
  if (saved) document.documentElement.setAttribute('data-theme', saved);
} catch (e) { }

function toast(msg) {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg; t.style.opacity = 1;
  clearTimeout(t._h); t._h = setTimeout(() => t.style.opacity = 0, 1800);
}

// Used by the "select all" checkbox in the header of any table that has
// per-row checkboxes with a shared class (products, categories, ...).
function toggleAll(box, cls) {
  document.querySelectorAll('.' + cls).forEach(c => c.checked = box.checked);
}

// Filter and sort admin lists without a full page reload. The form still
// submits normally when JavaScript or the request fails.
document.addEventListener('DOMContentLoaded', function () {
  initConfirmModal();
  initFrontendValidation();
  document.querySelectorAll('[data-admin-filter]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      refreshAdminList(form);
    });

    form.querySelectorAll('select').forEach(function (select) {
      select.addEventListener('change', function () { refreshAdminList(form); });
    });
  });
});

function initFrontendValidation() {
  document.querySelectorAll('form').forEach(function (form) {
    form.setAttribute('novalidate', 'novalidate');
    const fields = form.querySelectorAll('input, select, textarea');

    fields.forEach(function (field) {
      if (form.matches('[data-custom-validation]') && field.type === 'date') return;
      field.addEventListener('input', function () { validateField(field); });
      field.addEventListener('change', function () { validateField(field); });
      field.addEventListener('blur', function () { validateField(field); });
    });

    form.addEventListener('submit', function (event) {
      let firstInvalid = null;
      fields.forEach(function (field) {
        if (form.matches('[data-custom-validation]') && field.type === 'date') return;
        if (!validateField(field) && !firstInvalid) firstInvalid = field;
      });

      if (firstInvalid) {
        event.preventDefault();
        firstInvalid.focus();
      }
    });
  });
}

function validateField(field) {
  if (field.disabled || field.type === 'hidden' || field.type === 'submit' || field.type === 'button') return true;

  const value = field.type === 'checkbox' || field.type === 'radio' ? (field.checked ? field.value : '') : field.value.trim();
  let message = '';

  if (field.required && !value) {
    message = 'This field is required.';
  } else if (value && field.type === 'email' && !field.validity.valid) {
    message = 'Enter a valid email address.';
  } else if (value && field.type === 'url' && !field.validity.valid) {
    message = 'Enter a valid URL.';
  } else if (value && field.type === 'number' && field.validity.rangeUnderflow) {
    message = `Value must be at least ${field.min}.`;
  } else if (value && field.type === 'number' && field.validity.rangeOverflow) {
    message = `Value must be no more than ${field.max}.`;
  } else if (value && field.type === 'date' && field.validity.rangeUnderflow) {
    message = `Date must be ${field.min} or later.`;
  } else if (value && field.type === 'date' && field.validity.rangeOverflow) {
    message = `Date must be ${field.max} or earlier.`;
  } else if (value && field.validity.badInput) {
    message = 'Enter a valid value.';
  } else if (value && field.validity.stepMismatch) {
    message = 'Enter a valid value.';
  } else if (value && field.validity.patternMismatch) {
    message = 'Enter a valid value.';
  }

  let error = field.nextElementSibling;
  if (!error || !error.classList.contains('frontend-validation-error')) {
    error = document.createElement('p');
    error.className = 'frontend-validation-error mt-1 text-xs text-red-500';
    field.insertAdjacentElement('afterend', error);
  }

  error.textContent = message;
  error.classList.toggle('hidden', !message);
  field.classList.toggle('border-red-500', Boolean(message));
  field.setAttribute('aria-invalid', message ? 'true' : 'false');
  return !message;
}

function initConfirmModal() {
  const modal = document.getElementById('confirm-modal');
  if (!modal) return;

  const message = modal.querySelector('#confirm-modal-message');
  const cancel = modal.querySelector('[data-confirm-cancel]');
  const submit = modal.querySelector('[data-confirm-submit]');
  let pendingForm = null;

  function close() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    pendingForm = null;
  }

  document.addEventListener('submit', function (event) {
    const form = event.target.closest('form[data-confirm-message]');
    if (!form || form.dataset.confirmed === '1') return;
    event.preventDefault();
    pendingForm = form;
    message.textContent = form.dataset.confirmMessage;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    submit.focus();
  });

  cancel.addEventListener('click', close);
  submit.addEventListener('click', function () {
    if (!pendingForm) return;
    const form = pendingForm;
    form.dataset.confirmed = '1';
    close();
    HTMLFormElement.prototype.submit.call(form);
  });

  modal.addEventListener('click', function (event) {
    if (event.target === modal) close();
  });
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
  });
}

function refreshAdminList(form) {
  const target = document.getElementById(form.dataset.adminTarget);
  if (!target) return form.submit();
  const url = new URL(form.action, window.location.origin);
  new FormData(form).forEach((value, key) => { if (value) url.searchParams.set(key, value); else url.searchParams.delete(key); });
  target.classList.add('opacity-50', 'pointer-events-none');
  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
    .then(response => { if (!response.ok) throw new Error('Request failed'); return response.text(); })
    .then(html => {
      const parsed = new DOMParser().parseFromString(html, 'text/html');
      const replacement = parsed.getElementById(form.dataset.adminTarget);
      if (!replacement) throw new Error('Results not found');
      target.replaceWith(replacement);
      window.history.replaceState({}, '', url);
      bindAdminPagination(form);
    })
    .catch(() => form.submit())
    .finally(() => document.getElementById(form.dataset.adminTarget)?.classList.remove('opacity-50', 'pointer-events-none'));
}

function bindAdminPagination(form) {
  const target = document.getElementById(form.dataset.adminTarget);
  // Scoped to the paginator's own <nav> (Laravel's default pagination view
  // renders <nav role="navigation">...</nav>) so this only ever rebinds
  // "page 2", "next", etc. Selecting target.querySelectorAll('a[href]')
  // directly would also catch every other link inside the results
  // container — e.g. each row's Edit button — and hijack it into a filter
  // refresh instead of letting it navigate to the edit page.
  target?.querySelectorAll('nav[role="navigation"] a[href]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      const url = new URL(link.href);
      form.querySelectorAll('[name]').forEach(input => { if (url.searchParams.has(input.name)) input.value = url.searchParams.get(input.name); });
      refreshAdminList(form);
    });
  });
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-admin-filter]').forEach(bindAdminPagination);
});