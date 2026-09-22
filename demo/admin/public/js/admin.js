/* AgriExpress Admin — shared JS behavior only (sidebar, theme, toast, bulk
   checkboxes). Anything used by a single page (image previews, the
   dashboard charts, etc.) lives in that page's own @push('scripts')
   block instead — see resources/views/admin/*.blade.php. */

function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('-translate-x-full');
  document.getElementById('overlay').classList.toggle('hidden');
}

function toggleTheme(){
  const cur = document.documentElement.getAttribute('data-theme');
  const next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  try { localStorage.setItem('se-theme', next); } catch (e) {}

  // Pages that draw theme-aware canvases (e.g. the dashboard's charts)
  // listen for this instead of admin.js knowing about any specific page.
  document.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: next } }));
}

try {
  const saved = localStorage.getItem('se-theme');
  if (saved) document.documentElement.setAttribute('data-theme', saved);
} catch (e) {}

function toast(msg){
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg; t.style.opacity = 1;
  clearTimeout(t._h); t._h = setTimeout(()=> t.style.opacity = 0, 1800);
}

// Used by the "select all" checkbox in the header of any table that has
// per-row checkboxes with a shared class (products, categories, ...).
function toggleAll(box, cls){
  document.querySelectorAll('.'+cls).forEach(c => c.checked = box.checked);
}
