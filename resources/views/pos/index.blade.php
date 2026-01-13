@extends('pos.layout')

@section('title', 'AsBeez POS')

@section('content')
<div>
    <div class="panel" id="pos-panel">
        <div class="employee-id" style="margin-bottom:12px;">
            <label for="empCode" class="label">EMPLOYEE CODE</label>
            <input id="empCode" type="text" placeholder="e.g. C001" autocomplete="off"
                style="padding:8px;border:1px solid #ccc;border-radius:6px;width:140px;">
        </div>
        <div class="pin-boxes" aria-label="PIN input">
            <div class="pin" id="p0">&nbsp;</div>
            <div class="pin" id="p1">&nbsp;</div>
            <div class="pin" id="p2">&nbsp;</div>
            <div class="pin" id="p3">&nbsp;</div>
        </div>
        <div class="keypad" role="group" aria-label="Keypad">
            <button class="btn" data-key="1">1</button>
            <button class="btn" data-key="2">2</button>
            <button class="btn" data-key="3">3</button>
            <button class="btn" data-key="4">4</button>
            <button class="btn" data-key="5">5</button>
            <button class="btn" data-key="6">6</button>
            <button class="btn" data-key="7">7</button>
            <button class="btn" data-key="8">8</button>
            <button class="btn" data-key="9">9</button>
            <button class="btn btn-danger" data-key="back">×</button>
            <button class="btn" data-key="0">0</button>
            <button class="btn btn-enter" data-key="enter">ENTER</button>
        </div>
    </div>

    <div class="action-row" aria-label="POS actions">
        <div class="action-card" data-action="sign-in">
            <div>🔐</div>
            <div class="title">SIGN IN</div>
        </div>
        <div class="action-card" data-action="clock-in">
            <div>⏲️</div>
            <div class="title">CLOCK IN</div>
        </div>
        <div class="action-card" data-action="break">
            <div>☕</div>
            <div class="title">BREAK</div>
        </div>
        <div class="action-card" data-action="resume">
            <div>🔄</div>
            <div class="title">RESUME</div>
        </div>
        <div class="action-card" data-action="clock-out">
            <div>⏲️</div>
            <div class="title">CLOCK OUT</div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@php
$displayBranchCode = config('app.pos_branch_code');
if (empty($displayBranchCode)) {
$bid = session('pos_branch_id');
if ($bid) {
$displayBranchCode = optional(\App\Models\Branch::find($bid))->code;
}
}
@endphp
<div class="kv"><span class="label">STORE NAME:</span><strong id="storeName">{{ config('app.pos_branch_name', 'ASBEEZ')
        }}</strong>
</div>
<div class="kv"><span class="label">REGISTER NO:</span><strong id="registerNo">{{ config('app.pos_register_number')
        }}</strong></div>
<div class="kv"><span class="label">BRANCH:</span><strong id="branchCode">{{ $displayBranchCode ?? 'N/A' }}</strong>
</div>
<div class="kv"><span class="label">VERSION:</span><strong id="versionNo">{{ config('app.pos_version') }}</strong></div>
@endsection

@section('scripts')
<script>
    (function(){
    const pins = ['', '', '', ''];
    const pinEls = [document.getElementById('p0'), document.getElementById('p1'), document.getElementById('p2'), document.getElementById('p3')];
    const panel = document.getElementById('pos-panel');

    function renderPins() {
        for (let i = 0; i < 4; i++) {
            const v = pins[i];
            pinEls[i].textContent = v ? '•' : '\u00A0';
        }
    }
    function pushDigit(d) {
        const idx = pins.findIndex(x => x === '');
        if (idx >= 0) { pins[idx] = String(d); renderPins(); }
    }
    function backspace() {
        const idx = pins.slice().reverse().findIndex(x => x !== '');
        if (idx === -1) return;
        const realIdx = 3 - idx;
        pins[realIdx] = '';
        renderPins();
    }
    function getPin() { return pins.join(''); }
    function clearPin() { for (let i=0;i<4;i++) pins[i] = ''; renderPins(); }

    panel.addEventListener('click', (e) => {
        const t = e.target.closest('button[data-key]');
        if (!t) return;
        const key = t.getAttribute('data-key');
        if (key === 'back') { backspace(); return; }
        if (key === 'enter') {
            const pin = getPin();
            if (pin.length < 4) { shake(panel); return; }
            // Authenticate via backend
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const code = document.getElementById('empCode').value.trim();
            if (!code) { shake(panel); alert('Please enter your employee code'); return; }
            fetch("{{ url('/pos/login') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ code, pin }),
            }).then(async (res) => {
                if (!res.ok) { throw new Error('Invalid PIN or CODE'); }
                const data = await res.json();
                if (data && data.success) {
                    window.location.href = "{{ route('pos.home') }}";
                } else {
                    throw new Error('Invalid PIN or CODE');
                }
            }).catch(err => {
                shake(panel);
                clearPin();
                alert('Sign-in failed: ' + err.message);
            });
            return;
        }
        pushDigit(key);
    });

    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('click', () => {
            const action = card.getAttribute('data-action');
            // Stub actions – integrate with backend later
            alert('Action: ' + action.toUpperCase());
        });
    });

    function shake(el){
        el.style.transition = 'transform 0.1s';
        el.style.transform = 'translateX(6px)';
        setTimeout(() => { el.style.transform = 'translateX(-6px)'; }, 100);
        setTimeout(() => { el.style.transform = 'translateX(0)'; }, 200);
    }

    renderPins();
})();
</script>
@endsection