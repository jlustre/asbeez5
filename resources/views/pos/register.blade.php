@extends('pos.layout')

@section('title', 'AsBeez POS • Cash Register')

@section('top-left')
<a class="icon-btn" title="Home" href="{{ route('pos.home') }}">🏠</a>
<div class="icon-btn" title="Customer" id="btnCustomer">👤</div>
<div id="customerBadge" class="pill" style="display:none;">Customer: <span id="customerName">-</span><span class="x"
        id="clearCustomer">✕</span></div>
@endsection

@section('top-right')
<div class="flex-row" style="align-items:center; gap:10px;">
    <div class="icon-btn" title="Switch Cashier" id="btnSwitchCashier">🔄</div>
    <div class="icon-btn" title="Menu">▤</div>
    <div class="icon-btn" title="Apps">◼</div>
    <div class="icon-btn" title="Lock">🔒</div>
</div>
@endsection

@section('content')
<!-- Left side -->
<div>
    <div class="searchbar">
        <input class="input" placeholder="UPC">
        <input class="input" placeholder="Item Name/SKU/Distributor">
        <div class="round-btn" title="Add">＋</div>
    </div>
    <div class="table">
        <div class="thead" style="font-size: 1em">
            <div>SKU</div>
            <div>NAME</div>
            <div>QTY</div>
            <div>PRICE</div>
            <div>SUB-TOTAL</div>
        </div>
        <div class="tbody" id="cart-body"></div>
    </div>
</div>

<!-- Middle categories -->
<div class="categories">
    <div class="cat active" data-cat="counter">Counter items</div>
    <div class="cat" data-cat="dock">Dock lines & ropes</div>
    <div class="cat" data-cat="moorings">Moorings & accs</div>
</div>

<!-- Right quick items -->
<div class="items-grid" id="items-grid">
    <div class="item" data-sku="LIMES" data-price="1.50">LIMES</div>
    <div class="item" data-sku="ICEBAG5" data-price="3.00">ICE BAG\n5lbs</div>
</div>
@endsection

@section('modals')
<!-- Customer Modal -->
<div class="modal-backdrop" id="customerBackdrop"></div>
<div class="modal" id="customerModal" role="dialog" aria-modal="true" aria-labelledby="customerModalTitle">
    <div class="modal-card">
        <div class="modal-head">
            <strong id="customerModalTitle">Customer</strong>
            <span class="close-x" id="closeCustomer">✕</span>
        </div>
        <div class="modal-body">
            <div class="grid-2">
                <div>
                    <input id="customerSearch" class="input" placeholder="Search by name, phone or email">
                    <div class="list" id="customerList" style="margin-top:10px; max-height:300px; overflow:auto;"></div>
                </div>
                <div>
                    <div style="font-weight:800; margin-bottom:8px;">Add New Customer</div>
                    <div style="display:grid; gap:8px;">
                        <input id="newCustName" class="input" placeholder="Full Name">
                        <input id="newCustPhone" class="input" placeholder="Phone">
                        <input id="newCustEmail" class="input" placeholder="Email">
                        <input id="newCustLoyalty" class="input" placeholder="Loyalty ID (optional)">
                        <button class="btn" id="saveCustomer" style="width:160px;">Save & Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Switch Cashier Modal -->
<div class="modal-backdrop" id="cashierBackdrop"></div>
<div class="modal" id="cashierModal" role="dialog" aria-modal="true" aria-labelledby="cashierModalTitle">
    <div class="modal-card">
        <div class="modal-head">
            <strong id="cashierModalTitle">Switch Cashier</strong>
            <span class="close-x" id="closeCashier">✕</span>
        </div>
        <div class="modal-body">
            <div class="grid-2">
                <div>
                    <div style="font-weight:800; margin-bottom:8px;">Select Cashier</div>
                    <div class="list" id="cashierList"></div>
                </div>
                <div>
                    <div style="font-weight:800; margin-bottom:8px;">Confirm</div>
                    <input id="cashierPin" class="input" placeholder="Enter PIN" maxlength="6">
                    <button class="btn" id="confirmSwitch" style="margin-top:10px; width:160px;">Switch</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<div class="footer-inner">
    <div style="display:flex; align-items:center; gap:10px;">
        <div id="itemCountBadge"
            style="width:40px; height:40px; background:#DAA520; border-radius:8px; display:grid; place-items:center; font-weight:900;">
            0</div>
        <div>Items</div>
    </div>
    <div class="totals">
        <div class="tot-card">
            <div class="tot-title">TOTAL</div>
            <div class="tot-value" id="cashSubtotal">$0.00</div>
        </div>
        <div class="tot-card">
            <div class="tot-title">TAX</div>
            <div class="tot-value" id="cashTax">$0.00</div>
        </div>
        <div class="register-total">
            <div>Grand Total</div>
            <div id="grandTotal" style="font-size: 1.2em">$0.00</div>
        </div>
    </div>
    <div class="tender">
        <button class="btn" data-amt="1">$ 1</button>
        <button class="btn" data-amt="5">$ 5</button>
        <button class="btn" data-amt="10">$ 10</button>
        <button class="btn" data-amt="20">$ 20</button>
        <button class="btn" data-amt="50">$ 50</button>
        <button class="btn" data-amt="100">$ 100</button>
        <button class="btn">EXACT AMT</button>
        <button class="btn alt">CARD</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const grid = document.getElementById('items-grid');
    const cartBody = document.getElementById('cart-body');
    const grandTotalEl = document.getElementById('grandTotal');
    const cashSubtotalEl = document.getElementById('cashSubtotal');
    const cardSubtotalEl = document.getElementById('cardSubtotal');
    const itemCountEl = document.getElementById('itemCountBadge');
    let items = []; // {sku,name,qty,reg,card}

    function fmt(n){ return '$' + (Number(n).toFixed(2)); }
    function renderCart(){
      cartBody.innerHTML = '';
      items.forEach(row => {
        const line = document.createElement('div');
        line.style.display = 'grid';
        line.style.gridTemplateColumns = '1.1fr 1.4fr .7fr .8fr .9fr';
        line.style.padding = '8px 10px';
        line.style.borderBottom = '1px solid #eef2f7';
        const sub = Number(row.reg) * Number(row.qty);
        line.innerHTML = `<div>${row.sku}</div><div>${row.name}</div><div>${row.qty}</div><div>${fmt(row.reg)}</div><div>${fmt(sub)}</div>`;
        cartBody.appendChild(line);
      });
            const total = items.reduce((t, r) => t + (r.reg * r.qty), 0);
            const count = items.reduce((t, r) => t + r.qty, 0);
      grandTotalEl.textContent = fmt(total);
    cashSubtotalEl.textContent = fmt(total);
    if(cardSubtotalEl){ cardSubtotalEl.textContent = fmt(total); }
            if(itemCountEl){ itemCountEl.textContent = count; }
    }
    grid.addEventListener('click', (e) => {
      const btn = e.target.closest('.item');
      if(!btn) return;
      const sku = btn.getAttribute('data-sku');
      const name = btn.textContent.replace(/\n/g,' ');
      const price = parseFloat(btn.getAttribute('data-price')) || 0;
      const existing = items.find(i => i.sku === sku);
      if(existing){ existing.qty += 1; }
      else{ items.push({ sku, name, qty:1, reg: price, card: price }); }
      renderCart();
    });

        document.querySelectorAll('.btn[data-amt]').forEach(b => {
            b.addEventListener('click', () => alert('Tender: $' + b.getAttribute('data-amt')));
        });

        // Customer modal logic
        const btnCustomer = document.getElementById('btnCustomer');
        const customerModal = document.getElementById('customerModal');
        const customerBackdrop = document.getElementById('customerBackdrop');
        const closeCustomer = document.getElementById('closeCustomer');
        const customerList = document.getElementById('customerList');
        const customerSearch = document.getElementById('customerSearch');
        const customerBadge = document.getElementById('customerBadge');
        const customerNameEl = document.getElementById('customerName');
        const clearCustomer = document.getElementById('clearCustomer');
        const saveCustomerBtn = document.getElementById('saveCustomer');
        const newCustName = document.getElementById('newCustName');
        const newCustPhone = document.getElementById('newCustPhone');
        const newCustEmail = document.getElementById('newCustEmail');
        const newCustLoyalty = document.getElementById('newCustLoyalty');

        let customers = [];
        let selectedCustomer = null;

        function openCustomer(){ customerBackdrop.style.display='block'; customerModal.style.display='flex'; loadCustomers(customerSearch.value||''); }
        function closeCustomerModal(){ customerBackdrop.style.display='none'; customerModal.style.display='none'; }
        async function loadCustomers(filter=''){
            const url = `/api/pos/customers${filter?`?q=${encodeURIComponent(filter)}`:''}`;
            const res = await fetch(url);
            const json = await res.json();
            customers = (json && json.data) || [];
            renderCustomerList();
        }
        function renderCustomerList(){
            customerList.innerHTML = '';
            customers.forEach(c => {
                const row = document.createElement('div');
                row.className = 'list-row';
                row.innerHTML = `<div><strong>${c.name}</strong><div style='color:#6b7280; font-size:12px;'>${c.phone||''} • ${c.email||''} ${c.loyalty_id? '• '+c.loyalty_id:''}</div></div><button class='btn' data-id='${c.id}'>Select</button>`;
                row.querySelector('button').addEventListener('click', () => {
                    selectedCustomer = c; updateCustomerBadge(); closeCustomerModal();
                });
                customerList.appendChild(row);
            });
        }
        function updateCustomerBadge(){
            if(selectedCustomer){ customerNameEl.textContent = selectedCustomer.name; customerBadge.style.display='inline-flex'; }
            else{ customerBadge.style.display='none'; }
        }
        btnCustomer.addEventListener('click', openCustomer);
        closeCustomer.addEventListener('click', closeCustomerModal);
        customerBackdrop.addEventListener('click', closeCustomerModal);
        customerSearch.addEventListener('input', (e)=> loadCustomers(e.target.value));
        clearCustomer.addEventListener('click', ()=>{ selectedCustomer=null; updateCustomerBadge(); });
        saveCustomerBtn.addEventListener('click', async ()=>{
            const name = newCustName.value.trim();
            if(!name){ alert('Name is required'); return; }
            const payload = { name, phone:newCustPhone.value.trim(), email:newCustEmail.value.trim(), loyalty_id:newCustLoyalty.value.trim() };
            const res = await fetch('/api/pos/customers', { method:'POST', headers:{ 'Content-Type':'application/json' }, body: JSON.stringify(payload) });
            if(!res.ok){ alert('Failed to save customer'); return; }
            const json = await res.json();
            selectedCustomer = json.data; updateCustomerBadge(); closeCustomerModal();
            newCustName.value = newCustPhone.value = newCustEmail.value = newCustLoyalty.value = '';
        });

        // Cashier switch modal logic
        const btnSwitch = document.getElementById('btnSwitchCashier');
        const cashierModal = document.getElementById('cashierModal');
        const cashierBackdrop = document.getElementById('cashierBackdrop');
        const closeCashier = document.getElementById('closeCashier');
        const cashierList = document.getElementById('cashierList');
        const cashierPin = document.getElementById('cashierPin');
        const confirmSwitch = document.getElementById('confirmSwitch');
        const currentCashierLabel = document.getElementById('currentCashier');

        let cashiers = [];
        let pendingCashier = null;

        function openCashier(){ cashierBackdrop.style.display='block'; cashierModal.style.display='flex'; loadCashiers(); }
        function closeCashierModal(){ cashierBackdrop.style.display='none'; cashierModal.style.display='none'; cashierPin.value=''; }
        async function loadCashiers(){
            const res = await fetch('/api/pos/cashiers');
            const json = await res.json();
            cashiers = (json && json.data) || [];
            renderCashierList();
        }
        function renderCashierList(){
            cashierList.innerHTML = '';
            cashiers.forEach(c => {
                const row = document.createElement('div');
                row.className = 'list-row';
                row.innerHTML = `<div><strong>${c.name}</strong></div><button class='btn' data-id='${c.id}'>Choose</button>`;
                row.querySelector('button').addEventListener('click', ()=>{ pendingCashier = c; Array.from(cashierList.children).forEach(r=> r.style.background=''); row.style.background='#fff8e1'; });
                cashierList.appendChild(row);
            });
        }
        btnSwitch.addEventListener('click', openCashier);
        closeCashier.addEventListener('click', closeCashierModal);
        cashierBackdrop.addEventListener('click', closeCashierModal);
        confirmSwitch.addEventListener('click', async ()=>{
            if(!pendingCashier){ alert('Select a cashier'); return; }
            const pin = cashierPin.value.trim();
            if(pin.length === 0){ alert('Enter PIN'); return; }
            const res = await fetch('/api/pos/cashiers/switch', { method:'POST', headers:{ 'Content-Type':'application/json' }, body: JSON.stringify({ cashier_id: pendingCashier.id, pin }) });
            if(!res.ok){ const msg = await res.json().catch(()=>({message:'Invalid PIN'})); alert(msg.message||'Invalid PIN'); return; }
            const json = await res.json();
            currentCashierLabel.textContent = 'Cashier: ' + (json.data?.name || pendingCashier.name);
            closeCashierModal();
        });

        // Initialize current cashier from session
        (async function(){
            try{
                const res = await fetch('/api/pos/session');
                const json = await res.json();
                if(json && json.data){ currentCashierLabel.textContent = 'Cashier: ' + json.data.name; }
            }catch(e){ /* ignore */ }
        })();
</script>
@endsection