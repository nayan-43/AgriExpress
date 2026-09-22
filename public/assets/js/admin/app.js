/* AgriExpress Admin — UI helpers and page renderers */

const $ = s => document.querySelector(s);

const BADGE = {
  Processing: 'bg-amber-100 text-amber-700', Shipped: 'bg-sky-100 text-sky-700',
  Delivered: 'bg-emerald-100 text-emerald-700', Cancelled: 'bg-red-100 text-red-700',
  Paid: 'bg-emerald-100 text-emerald-700', Pending: 'bg-red-100 text-red-600',
  Published: 'bg-emerald-100 text-emerald-700', Draft: 'bg-amber-100 text-amber-700',
  Active: 'bg-emerald-100 text-emerald-700', Inactive: 'bg-red-100 text-red-600',
  Blocked: 'bg-red-100 text-red-600'
};
const AVATAR_COLORS = ['bg-pink-100 text-pink-700', 'bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-violet-100 text-violet-700', 'bg-cyan-100 text-cyan-700'];

const pill = t => `<span class="text-[11.5px] font-semibold rounded-full px-2.5 py-1 ${BADGE[t] || 'bg-slate-100 text-slate-600'}">${t}</span>`;
const thumb = (i, bg, size = 'w-9 h-9') => `<span class="${size} rounded-lg ${bg} grid place-items-center"><i class="fa-solid ${i} text-[13px]"></i></span>`;
const initials = n => n.split(' ').map(w => w[0]).join('').slice(0, 2);
const avatar = (n, idx = 0) => `<span class="w-8 h-8 rounded-full ${AVATAR_COLORS[idx % 6]} grid place-items-center text-[11px] font-semibold">${initials(n)}</span>`;

const actionBtns = `<div class="flex items-center gap-2 text-[13px]">
  <button class="w-8 h-8 rounded-lg border bd grid place-items-center text-blue-600" onclick="toast('Edit opened')" aria-label="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
  <button class="w-8 h-8 rounded-lg border bd grid place-items-center text-red-500" onclick="toast('Delete requested')" aria-label="Delete"><i class="fa-regular fa-trash-can"></i></button>
  <button class="w-8 h-8 rounded-lg grid place-items-center muted" aria-label="More"><i class="fa-solid fa-ellipsis-vertical"></i></button>
</div>`;

function toast(msg) {
  const t = $('#toast');
  if (!t) return;
  t.textContent = msg; t.style.opacity = 1;
  clearTimeout(t._h); t._h = setTimeout(() => t.style.opacity = 0, 1800);
}

function toggleAll(box, cls) {
  document.querySelectorAll('.' + cls).forEach(c => c.checked = box.checked);
}

function pager(total) {
  let h = `<button class="w-8 h-8 rounded-lg border bd grid place-items-center muted"><i class="fa-solid fa-chevron-left text-[11px]"></i></button>`;
  [1, 2, 3, 4, 5].forEach(n => {
    h += `<button class="w-8 h-8 rounded-lg border text-[13px] ${n === 1 ? 'bg-blue-600 text-white border-blue-600' : 'bd'}">${n}</button>`;
  });
  h += `<span class="px-1 muted">…</span><button class="w-8 h-8 rounded-lg border bd text-[13px]">${total}</button>`;
  h += `<button class="w-8 h-8 rounded-lg border bd grid place-items-center muted"><i class="fa-solid fa-chevron-right text-[11px]"></i></button>`;
  return h;
}

function emptyRow(cols, msg) {
  return `<tr><td colspan="${cols}" class="px-5 py-10 text-center muted">${msg}</td></tr>`;
}

/* ---------------- dashboard ---------------- */
let salesChart, catChart;

function spark(color) {
  const pts = [18, 14, 16, 10, 12, 6, 3].map((y, x) => `${x * 14},${y}`).join(' ');
  return `<svg viewBox="0 0 88 22" class="w-21.5 h-6.5" aria-hidden="true"><polyline points="${pts}" fill="none" stroke="${color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
}

function buildCharts() {
  const grid = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
  const tick = getComputedStyle(document.documentElement).getPropertyValue('--muted').trim();

  const ctx = document.getElementById('salesChart');
  const g = ctx.getContext('2d').createLinearGradient(0, 0, 0, 220);
  g.addColorStop(0, 'rgba(59,130,246,.28)');
  g.addColorStop(1, 'rgba(59,130,246,0)');

  salesChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Sep 10', 'Sep 11', 'Sep 12', 'Sep 13', 'Sep 14', 'Sep 15', 'Sep 16'],
      datasets: [
        { label: 'This week', data: [1100, 1600, 1500, 2150, 1700, 1900, 3050], borderColor: '#2563eb', backgroundColor: g, fill: true, tension: .4, pointRadius: 4, pointBackgroundColor: '#2563eb', borderWidth: 2.5 },
        { label: 'Last week', data: [800, 1250, 1200, 1400, 1150, 1350, 1500], borderColor: '#94a3b8', borderDash: [5, 5], fill: false, tension: .4, pointRadius: 3, pointBackgroundColor: '#94a3b8', borderWidth: 2 }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { color: tick, callback: v => '$' + v.toLocaleString() }, grid: { color: grid } },
        x: { ticks: { color: tick }, grid: { display: false } }
      }
    }
  });

  catChart = new Chart(document.getElementById('catChart'), {
    type: 'doughnut',
    data: { labels: CAT_DATA.map(c => c[0]), datasets: [{ data: CAT_DATA.map(c => c[1]), backgroundColor: CAT_DATA.map(c => c[2]), borderWidth: 0, spacing: 2 }] },
    options: { cutout: '72%', plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
  });
}

function refreshCharts() {
  if (!salesChart) return;
  salesChart.destroy(); catChart.destroy(); buildCharts();
}

function renderDashboard() {
  $('#statCards').innerHTML = STATS.map(s => `
    <div class="rounded-2xl p-5 border ${s.tone}" style="border-color:rgba(0,0,0,.05)">
      <div class="flex items-start gap-3">
        <span class="w-11 h-11 rounded-xl ${s.ic} text-white grid place-items-center shrink-0"><i class="fa-solid ${s.i}"></i></span>
        <div class="min-w-0"><p class="text-[13px] text-slate-600">${s.l}</p><p class="text-[22px] font-bold text-slate-900 mt-0.5">${s.v}</p></div>
      </div>
      <div class="flex items-end justify-between mt-3">
        <p class="text-[12.5px]"><span class="text-emerald-600 font-semibold"><i class="fa-solid fa-arrow-up text-[10px]"></i> ${s.d}</span> <span class="text-slate-500">vs last week</span></p>
        ${spark(s.line)}
      </div>
    </div>`).join('');

  $('#catLegend').innerHTML = CAT_DATA.map(([n, v, c]) => `
    <li class="flex items-center gap-2.5"><i class="fa-solid fa-circle text-[8px]" style="color:${c}"></i><span class="flex-1">${n}</span><span class="font-semibold">${v}%</span></li>`).join('');

  $('#recentOrders').innerHTML = ORDERS.slice(0, 5).map((o, idx) => `
    <tr>
      <td class="px-5 py-3.5 font-medium">#${o.no}</td>
      <td class="px-5 py-3.5"><span class="flex items-center gap-2.5">${avatar(o.cu, idx)}${o.cu}</span></td>
      <td class="px-5 py-3.5"><span class="flex items-center gap-2.5">${thumb(o.i, 'bg-slate-100 text-slate-600', 'w-8 h-8')}<span class="muted">${o.items}</span></span></td>
      <td class="px-5 py-3.5 font-medium">$${o.t.toFixed(2)}</td>
      <td class="px-5 py-3.5">${pill(o.s)}</td>
      <td class="px-5 py-3.5">${pill(o.pay)}</td>
      <td class="px-5 py-3.5 muted">${o.d}</td>
      <td class="px-5 py-3.5"><a href="order-details.html" class="border bd rounded-lg px-3 py-1.5 text-[12.5px] text-blue-600 font-medium">View</a></td>
    </tr>`).join('');

  $('#topProducts').innerHTML = [['Wireless Headphones', 42, 'fa-headphones'], ['Running Shoes', 38, 'fa-shoe-prints'], ['Smart Watch', 32, 'fa-stopwatch']]
    .map(([n, s, i], k) => `<li class="flex items-center gap-3"><span class="muted w-4">${k + 1}.</span>${thumb(i, 'bg-slate-100 text-slate-600')}<span class="flex-1 font-medium">${n}</span><span class="muted text-[12.5px]">${s} sold</span></li>`).join('');

  $('#recentCustomers').innerHTML = CUSTOMERS.slice(0, 3).map((c, i) => `
    <li class="flex items-center gap-3">${avatar(c.n, i)}<span class="min-w-0 flex-1"><span class="block font-medium truncate">${c.n}</span><span class="block muted text-[12px] truncate">${c.e}</span></span><span class="muted text-[11.5px] whitespace-nowrap">Sep 1${6 - i}</span></li>`).join('');

  $('#popularCats').innerHTML = CATEGORIES.slice(0, 4).map(c => `
    <li class="flex items-center gap-3">${thumb(c.i, c.bg)}<span class="flex-1 font-medium">${c.n}</span><span class="muted text-[12.5px]">${c.c} products</span></li>`).join('');

  $('#activity').innerHTML = [
    ['fa-cart-shopping', 'bg-emerald-100 text-emerald-700', 'New order #ORD-1024', '$89.99 · 2 minutes ago'],
    ['fa-box', 'bg-blue-100 text-blue-700', 'Product added', 'Wireless Headphones · 12 minutes ago'],
    ['fa-user-plus', 'bg-violet-100 text-violet-700', 'New customer registered', 'Sarah Johnson · 25 minutes ago'],
    ['fa-arrows-rotate', 'bg-amber-100 text-amber-700', 'Stock updated', 'iPhone Case (Black) · 1 hour ago'],
    ['fa-truck', 'bg-teal-100 text-teal-700', 'Order delivered', '#ORD-1021 · 2 hours ago']
  ].map(([i, bg, t, s]) => `<li class="flex gap-3">${thumb(i, bg, 'w-9 h-9 shrink-0')}<span><span class="block font-medium">${t}</span><span class="block muted text-[12px] mt-0.5">${s}</span></span></li>`).join('');

  $('#lowStock').innerHTML = [
    ['Wireless Headphones', 5, 'fa-headphones', 'bg-red-500', '20%'],
    ['Running Shoes', 12, 'fa-shoe-prints', 'bg-amber-500', '48%'],
    ['Smart Watch', 8, 'fa-stopwatch', 'bg-amber-500', '32%'],
    ['Backpack', 3, 'fa-bag-shopping', 'bg-red-500', '12%'],
    ['Sunglasses', 15, 'fa-glasses', 'bg-emerald-500', '60%']
  ].map(([n, s, i, c, w]) => `<li class="flex items-center gap-3">${thumb(i, 'bg-slate-100 text-slate-600', 'w-10 h-10 shrink-0')}
    <span class="flex-1 min-w-0"><span class="block font-medium text-[13.5px] truncate">${n}</span><span class="block muted text-[12px]">Stock: ${s}</span>
    <span class="block h-1.5 rounded-full mt-1.5" style="background:var(--border)"><span class="block h-1.5 rounded-full ${c}" style="width:${w}"></span></span></span></li>`).join('');

  buildCharts();
}

/* ---------------- products ---------------- */
function renderProducts() {
  const q = $('#prodSearch').value.toLowerCase(), c = $('#prodCat').value, s = $('#prodStatus').value;
  const rows = PRODUCTS.filter(p => p.n.toLowerCase().includes(q) && (c.startsWith('All') || p.c === c) && (s.startsWith('All') || p.st === s));
  $('#productRows').innerHTML = rows.length ? rows.map(p => `
    <tr>
      <td class="px-4 py-3"><input type="checkbox" class="p-check"></td>
      <td class="px-4 py-3">${thumb(p.i, p.bg, 'w-10 h-10')}</td>
      <td class="px-4 py-3 font-medium">${p.n}</td>
      <td class="px-4 py-3 text-blue-600">${p.c}</td>
      <td class="px-4 py-3 font-medium">$${p.p.toFixed(2)}</td>
      <td class="px-4 py-3 muted">${p.s}</td>
      <td class="px-4 py-3">${pill(p.st)}</td>
      <td class="px-4 py-3">${actionBtns}</td>
    </tr>`).join('') : emptyRow(8, 'No products match this search. Try a different name or clear the filters.');
  $('#prodCount').textContent = `Showing 1 to ${rows.length} of 124 products`;
}

function initProducts() {
  $('#prodCat').innerHTML = '<option>All categories</option>' + [...new Set(PRODUCTS.map(p => p.c))].map(c => `<option>${c}</option>`).join('');
  $('#prodPager').innerHTML = pager(16);
  renderProducts();
}

/* ---------------- orders ---------------- */
let orderTab = 'All';
const ORDER_TABS = [['All', 48], ['Processing', 12], ['Shipped', 18], ['Delivered', 15], ['Cancelled', 3]];

function renderTabs() {
  $('#orderTabs').innerHTML = ORDER_TABS.map(([t, c]) => `
    <button onclick="orderTab='${t}';renderTabs();renderOrders()" class="py-3.5 font-medium border-b-2 whitespace-nowrap ${orderTab === t ? 'border-blue-600 text-blue-600' : 'border-transparent muted'}">${t} (${c})</button>`).join('');
}

function renderOrders() {
  const q = $('#orderSearch').value.toLowerCase();
  const rows = ORDERS.filter(o => (orderTab === 'All' || o.s === orderTab) && (o.cu.toLowerCase().includes(q) || o.no.toLowerCase().includes(q)));
  $('#orderRows').innerHTML = rows.length ? rows.map((o, i) => `
    <tr>
      <td class="px-5 py-3.5 font-medium">${o.id}</td>
      <td class="px-5 py-3.5">${o.no}</td>
      <td class="px-5 py-3.5"><span class="flex items-center gap-2.5">${avatar(o.cu, i)}${o.cu}</span></td>
      <td class="px-5 py-3.5 font-medium">$${o.t.toFixed(2)}</td>
      <td class="px-5 py-3.5">${pill(o.s)}</td>
      <td class="px-5 py-3.5 muted">${o.d}</td>
      <td class="px-5 py-3.5"><a href="order-details.html?order=${o.no}" class="border bd rounded-lg px-3 py-1.5 text-[12.5px] text-blue-600 font-medium">View</a></td>
    </tr>`).join('') : emptyRow(7, 'No orders here yet. Change the filter to see more.');
  $('#orderCount').textContent = `Showing 1 to ${rows.length} of 48 orders`;
}

function initOrders() {
  $('#orderPager').innerHTML = pager(16);
  renderTabs(); renderOrders();
}

/* ---------------- order details ---------------- */
function initOrderDetails() {
  const no = new URLSearchParams(location.search).get('order') || 'ORD-1024';
  $('#odNum').textContent = '#' + no;
  $('#odCrumb').textContent = '#' + no;
  $('#odItems').innerHTML = ORDER_ITEMS.map(([n, v, q, p, i]) => `
    <tr>
      <td class="py-3.5"><span class="flex items-center gap-3">${thumb(i, 'bg-slate-100 text-slate-600', 'w-10 h-10')}<span><span class="block font-medium">${n}</span><span class="block muted text-[12px]">${v}</span></span></span></td>
      <td class="py-3.5">${q}</td>
      <td class="py-3.5">$${p.toFixed(2)}</td>
      <td class="py-3.5 text-right font-medium">$${(p * q).toFixed(2)}</td>
    </tr>`).join('');
}

/* ---------------- customers ---------------- */
function renderCustomers() {
  const q = $('#custSearch').value.toLowerCase(), s = $('#custStatus').value;
  const rows = CUSTOMERS.filter(c => (c.n + c.e + c.p).toLowerCase().includes(q) && (s.startsWith('All') || c.s === s));
  $('#customerRows').innerHTML = rows.length ? rows.map((c, i) => `
    <tr>
      <td class="px-5 py-3.5"><span class="flex items-center gap-2.5">${avatar(c.n, i)}<span class="font-medium">${c.n}</span></span></td>
      <td class="px-5 py-3.5 muted">${c.e}</td>
      <td class="px-5 py-3.5 muted">${c.p}</td>
      <td class="px-5 py-3.5">${c.o}</td>
      <td class="px-5 py-3.5">${pill(c.s)}</td>
      <td class="px-5 py-3.5">${actionBtns}</td>
    </tr>`).join('') : emptyRow(6, 'No customers match this search.');
  $('#custCount').textContent = `Showing 1 to ${rows.length} of 36 customers`;
}

/* ---------------- categories ---------------- */
function renderCategories() {
  $('#categoryRows').innerHTML = CATEGORIES.map(c => `
    <tr>
      <td class="px-4 py-3"><input type="checkbox" class="c-check"></td>
      <td class="px-4 py-3">${thumb(c.i, c.bg, 'w-10 h-10')}</td>
      <td class="px-4 py-3 font-medium">${c.n}</td>
      <td class="px-4 py-3 muted">${c.sl}</td>
      <td class="px-4 py-3">${c.c}</td>
      <td class="px-4 py-3">${pill(c.s)}</td>
      <td class="px-4 py-3">${actionBtns}</td>
    </tr>`).join('');
}

/* ---------------- coupons ---------------- */
function renderCoupons() {
  const q = $('#couponSearch').value.toLowerCase(), s = $('#couponStatus').value;
  const rows = COUPONS.filter(c => c.c.toLowerCase().includes(q) && (s.startsWith('All') || c.s === s));
  $('#couponRows').innerHTML = rows.length ? rows.map(c => `
    <tr>
      <td class="px-5 py-3.5 font-semibold">${c.c}</td>
      <td class="px-5 py-3.5">${c.d}</td>
      <td class="px-5 py-3.5 muted">${c.t}</td>
      <td class="px-5 py-3.5">${c.m}</td>
      <td class="px-5 py-3.5 muted">${c.e}</td>
      <td class="px-5 py-3.5">${pill(c.s)}</td>
      <td class="px-5 py-3.5">${actionBtns}</td>
    </tr>`).join('') : emptyRow(7, 'No coupons found. Create one to get started.');
}

/* ---------------- settings ---------------- */
const SET_TABS = [['General', 'fa-gear'], ['Store information', 'fa-store'], ['Shipping', 'fa-truck'], ['Payment', 'fa-credit-card'], ['Email', 'fa-envelope'], ['SEO', 'fa-magnifying-glass-chart'], ['Maintenance', 'fa-screwdriver-wrench']];
let setTab = 'General';

function renderSetTabs() {
  $('#setTabs').innerHTML = SET_TABS.map(([t, i]) => `
    <button onclick="setTab='${t}';renderSetTabs()" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-left ${setTab === t ? 'bg-blue-600 text-white' : 'hover:bg-black/5'}"><i class="fa-solid ${i} w-4 text-[13px]"></i>${t}</button>`).join('');
  $('#setTitle').textContent = setTab === 'General' ? 'General settings' : setTab;
}
