/* AgriExpress Admin — demo data.
   Replace these arrays with fetch() calls to your API, then run the
   matching render function (renderProducts, renderOrders, ...). */

const PRODUCTS = [
  {n:'Wireless Headphones',c:'Electronics',p:89.99,s:25,st:'Published',i:'fa-headphones',bg:'bg-blue-100 text-blue-700'},
  {n:'Running Shoes',c:'Sports',p:59.99,s:40,st:'Published',i:'fa-shoe-prints',bg:'bg-emerald-100 text-emerald-700'},
  {n:'Smart Watch',c:'Electronics',p:129.99,s:18,st:'Published',i:'fa-stopwatch',bg:'bg-violet-100 text-violet-700'},
  {n:'Backpack',c:'Bags',p:79.99,s:32,st:'Published',i:'fa-bag-shopping',bg:'bg-amber-100 text-amber-700'},
  {n:'Sunglasses',c:'Accessories',p:29.99,s:50,st:'Published',i:'fa-glasses',bg:'bg-cyan-100 text-cyan-700'},
  {n:'T-Shirt',c:'Men',p:24.99,s:60,st:'Draft',i:'fa-shirt',bg:'bg-pink-100 text-pink-700'},
  {n:'Sneakers',c:'Women',p:69.99,s:15,st:'Published',i:'fa-shoe-prints',bg:'bg-lime-100 text-lime-700'},
  {n:'Yoga Mat',c:'Fitness',p:39.99,s:22,st:'Published',i:'fa-person-praying',bg:'bg-orange-100 text-orange-700'}
];

const ORDERS = [
  {id:'#1024',no:'ORD-1024',cu:'Emily Carter',t:89.99,s:'Processing',pay:'Paid',d:'Sep 16, 2025',items:'3 items',i:'fa-headphones'},
  {id:'#1023',no:'ORD-1023',cu:'James Miller',t:49.99,s:'Shipped',pay:'Paid',d:'Sep 15, 2025',items:'1 item',i:'fa-shoe-prints'},
  {id:'#1022',no:'ORD-1022',cu:'Sophia Davis',t:129.98,s:'Delivered',pay:'Paid',d:'Sep 14, 2025',items:'2 items',i:'fa-stopwatch'},
  {id:'#1021',no:'ORD-1021',cu:'Daniel Wilson',t:79.99,s:'Cancelled',pay:'Pending',d:'Sep 14, 2025',items:'1 item',i:'fa-bag-shopping'},
  {id:'#1020',no:'ORD-1020',cu:'Olivia Brown',t:199.96,s:'Delivered',pay:'Paid',d:'Sep 13, 2025',items:'4 items',i:'fa-shirt'},
  {id:'#1019',no:'ORD-1019',cu:'Michael Lee',t:59.99,s:'Processing',pay:'Paid',d:'Sep 12, 2025',items:'1 item',i:'fa-shoe-prints'},
  {id:'#1018',no:'ORD-1018',cu:'Emma Johnson',t:89.50,s:'Shipped',pay:'Paid',d:'Sep 11, 2025',items:'2 items',i:'fa-glasses'},
  {id:'#1017',no:'ORD-1017',cu:'William Taylor',t:120.00,s:'Delivered',pay:'Paid',d:'Sep 10, 2025',items:'3 items',i:'fa-person-praying'}
];

const CUSTOMERS = [
  {n:'Emily Carter',e:'emily@example.com',p:'+91 98765 43210',o:5,s:'Active'},
  {n:'James Miller',e:'james@example.com',p:'+91 87654 32109',o:3,s:'Active'},
  {n:'Sophia Davis',e:'sophia@example.com',p:'+91 76543 21098',o:7,s:'Active'},
  {n:'Daniel Wilson',e:'daniel@example.com',p:'+91 65432 10987',o:2,s:'Blocked'},
  {n:'Olivia Brown',e:'olivia@example.com',p:'+91 54321 09876',o:4,s:'Active'},
  {n:'Michael Lee',e:'michael@example.com',p:'+91 43210 98765',o:1,s:'Active'},
  {n:'Emma Johnson',e:'emma@example.com',p:'+91 32109 87654',o:6,s:'Active'},
  {n:'William Taylor',e:'william@example.com',p:'+91 21098 76543',o:3,s:'Active'}
];

const CATEGORIES = [
  {n:'Fashion',sl:'fashion',c:28,s:'Active',i:'fa-shirt',bg:'bg-pink-100 text-pink-700'},
  {n:'Electronics',sl:'electronics',c:24,s:'Active',i:'fa-plug',bg:'bg-blue-100 text-blue-700'},
  {n:'Home & Living',sl:'home-living',c:18,s:'Active',i:'fa-couch',bg:'bg-emerald-100 text-emerald-700'},
  {n:'Beauty',sl:'beauty',c:15,s:'Inactive',i:'fa-spray-can-sparkles',bg:'bg-rose-100 text-rose-700'},
  {n:'Sports',sl:'sports',c:12,s:'Active',i:'fa-dumbbell',bg:'bg-violet-100 text-violet-700'},
  {n:'Accessories',sl:'accessories',c:10,s:'Active',i:'fa-glasses',bg:'bg-amber-100 text-amber-700'}
];

const COUPONS = [
  {c:'WELCOME10',d:'10% OFF',t:'Percentage',m:'$50.00',e:'Sep 30, 2026',s:'Active'},
  {c:'SUMMER20',d:'20% OFF',t:'Percentage',m:'$100.00',e:'Oct 15, 2026',s:'Active'},
  {c:'FLAT50',d:'$50.00 OFF',t:'Fixed',m:'$200.00',e:'Oct 31, 2026',s:'Active'},
  {c:'FESTIVE15',d:'15% OFF',t:'Percentage',m:'$75.00',e:'Nov 10, 2026',s:'Active'},
  {c:'NEWUSER10',d:'10% OFF',t:'Percentage',m:'$40.00',e:'Dec 31, 2026',s:'Inactive'},
  {c:'DIWALI25',d:'25% OFF',t:'Percentage',m:'$150.00',e:'Nov 5, 2026',s:'Active'}
];

const STATS = [
  {l:'Total sales',v:'$12,493.00',d:'12.5%',i:'fa-cart-shopping',tone:'bg-blue-50',ic:'bg-blue-500',line:'#3b82f6'},
  {l:'Total orders',v:'48',d:'8.3%',i:'fa-box',tone:'bg-emerald-50',ic:'bg-emerald-500',line:'#10b981'},
  {l:'Total customers',v:'36',d:'20.0%',i:'fa-user',tone:'bg-violet-50',ic:'bg-violet-500',line:'#8b5cf6'},
  {l:'Total products',v:'124',d:'4.2%',i:'fa-tag',tone:'bg-amber-50',ic:'bg-amber-500',line:'#f59e0b'}
];

const CAT_DATA = [
  ['Fashion',32,'#3b82f6'],['Electronics',21,'#10b981'],['Home & Living',15,'#f59e0b'],
  ['Beauty',12,'#ec4899'],['Sports',10,'#8b5cf6'],['Others',10,'#cbd5e1']
];

const ORDER_ITEMS = [
  ['Wireless Headphones','Color: Black',1,89.99,'fa-headphones'],
  ['Running Shoes','Color: White · Size: 9',1,59.99,'fa-shoe-prints'],
  ['Smart Watch','Color: Black',1,129.99,'fa-stopwatch']
];
