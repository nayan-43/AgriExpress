/* AgriExpress Admin — shared chrome (sidebar + topbar).
   Injected with JS so every page stays in sync and works over file://
   without needing fetch-based partials. */

const NAV = [
  ['dashboard','index.html','fa-house','Dashboard',''],
  ['products','products.html','fa-box','Products','caret'],
  ['categories','categories.html','fa-layer-group','Categories',''],
  ['orders','orders.html','fa-cart-shopping','Orders','badge:5'],
  ['customers','customers.html','fa-users','Customers',''],
  ['coupons','coupons.html','fa-ticket','Coupons',''],
  ['reviews','#','fa-star','Reviews',''],
  ['inventory','#','fa-clipboard-list','Inventory',''],
  ['reports','#','fa-chart-column','Reports','arrow'],
  ['marketing','#','fa-bullhorn','Marketing','arrow'],
  ['settings','settings.html','fa-gear','Settings','arrow']
];

function navItem([key,href,icon,label,extra],active){
  let right = '';
  if (extra === 'caret')  right = '<i class="fa-solid fa-chevron-down ml-auto text-[10px] opacity-60"></i>';
  if (extra === 'arrow')  right = '<i class="fa-solid fa-chevron-right ml-auto text-[10px] opacity-60"></i>';
  if (extra && extra.startsWith('badge:'))
    right = `<span class="ml-auto text-[11px] bg-red-500 text-white rounded-full px-2 py-0.5">${extra.split(':')[1]}</span>`;
  return `<a href="${href}" class="side-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg ${key===active?'active':''}">
    <i class="fa-solid ${icon} w-4"></i>${label}${right}</a>`;
}

function buildLayout(active){
  document.getElementById('sidebar-slot').innerHTML = `
  <aside id="sidebar" class="fixed lg:static z-40 -translate-x-full lg:translate-x-0 transition-transform duration-200 w-[252px] shrink-0 min-h-screen text-slate-300 flex flex-col" style="background:var(--sidebar)">
    <div class="flex items-center gap-3 px-6 h-[68px] border-b border-white/5">
      <div class="w-9 h-9 rounded-xl bg-blue-600 grid place-items-center text-white"><i class="fa-solid fa-bag-shopping"></i></div>
      <span class="text-white text-lg font-bold tracking-tight">AgriExpress</span>
      <button class="ml-auto lg:hidden text-slate-400" onclick="toggleSidebar()" aria-label="Close menu"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 text-[14px]">
      ${NAV.map(n => navItem(n, active)).join('')}
    </nav>
    <div class="m-4 rounded-2xl p-5 text-white" style="background:var(--sidebar-2)">
      <i class="fa-solid fa-bag-shopping text-2xl text-blue-400"></i>
      <p class="font-semibold mt-3">Grow your sales</p>
      <p class="text-[12.5px] text-slate-400 mt-1 leading-relaxed">Track performance, manage your store and reach more customers.</p>
      <button class="mt-3 w-full bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg py-2">Upgrade plan</button>
    </div>
  </aside>
  <div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>`;

  document.getElementById('topbar-slot').innerHTML = `
  <header class="sticky top-0 z-20 h-[68px] surface border-b flex items-center gap-4 px-4 sm:px-6">
    <button class="text-slate-500" onclick="toggleSidebar()" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <label class="hidden sm:flex items-center gap-2 flex-1 max-w-[430px] rounded-xl px-4 h-10 border bd" style="background:var(--bg)">
      <input class="flex-1 text-sm border-0" placeholder="Search for products, orders, customers...">
      <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
    </label>
    <div class="ml-auto flex items-center gap-4">
      <button class="relative text-slate-500" aria-label="Notifications">
        <i class="fa-regular fa-bell text-lg"></i>
        <span class="absolute -top-2 -right-2 text-[10px] bg-red-500 text-white rounded-full w-4 h-4 grid place-items-center">3</span>
      </button>
      <button class="text-slate-500 hidden sm:block" onclick="toggleTheme()" title="Switch theme"><i class="fa-solid fa-circle-half-stroke text-lg"></i></button>
      <div class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 grid place-items-center font-semibold text-sm">A</div>
        <div class="hidden sm:block leading-tight">
          <p class="text-[13.5px] font-semibold">Admin</p>
          <p class="text-[11.5px] muted">Super Admin</p>
        </div>
        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
      </div>
    </div>
  </header>`;
}

function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('-translate-x-full');
  document.getElementById('overlay').classList.toggle('hidden');
}

function toggleTheme(){
  const cur = document.documentElement.getAttribute('data-theme');
  const next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  try { localStorage.setItem('se-theme', next); } catch (e) {}
  if (typeof refreshCharts === 'function') refreshCharts();
}

try {
  const saved = localStorage.getItem('se-theme');
  if (saved) document.documentElement.setAttribute('data-theme', saved);
} catch (e) {}
