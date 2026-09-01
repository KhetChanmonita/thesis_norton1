@extends('admin.layouts.admin')
@section('title','តម្លៃដឹកជញ្ជូន')
@section('page-title')<span>គ្រប់គ្រង</span>តម្លៃដឹកជញ្ជូន@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_shipping_rates.css') }}">
@endpush

@section('content')

{{-- Stats Row --}}
<div class="sr-stats-row">
    <div class="sr-stat-card">
        <div class="sr-stat-icon orange"><i class="fas fa-ship"></i></div>
        <div>
            <div class="sr-stat-num">2</div>
            <div class="sr-stat-lbl">ច្រកកំពង់ផែ</div>
        </div>
    </div>
    <div class="sr-stat-card">
        <div class="sr-stat-icon blue"><i class="fas fa-map-marker-alt"></i></div>
        <div>
            <div class="sr-stat-num">25</div>
            <div class="sr-stat-lbl">ខេត្ត / រាជធានី</div>
        </div>
    </div>
    <div class="sr-stat-card">
        <div class="sr-stat-icon green"><i class="fas fa-download"></i></div>
        <div>
            <div class="sr-stat-num">Import</div>
            <div class="sr-stat-lbl">ទំនិញចូល</div>
        </div>
    </div>
    <div class="sr-stat-card">
        <div class="sr-stat-icon purple"><i class="fas fa-upload"></i></div>
        <div>
            <div class="sr-stat-num">Export</div>
            <div class="sr-stat-lbl">ទំនិញចេញ</div>
        </div>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div style="margin-bottom:14px;padding:10px 16px;background:#d1fae5;border:1.5px solid #6ee7b7;border-radius:8px;color:#065f46;font-size:.84rem;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Tab Bar + Action Buttons --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:4px;">
    <div class="sr-tab-wrap" style="margin-bottom:0;">
        <button class="sr-tab-btn active" data-tab="import">
            <i class="fas fa-ship"></i> Import — ទំនិញចូល
        </button>
        <button class="sr-tab-btn" data-tab="export">
            <i class="fas fa-truck-loading"></i> Export — ទំនិញចេញ
        </button>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        {{-- Bulk Adjust button --}}
        <button type="button"
                onclick="document.getElementById('bulkAdjustModal').classList.add('open')"
                style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;
                       background:linear-gradient(135deg,#FF6B00,#ff8c38);border:none;border-radius:8px;
                       color:#fff;font-size:.78rem;font-weight:700;cursor:pointer;
                       font-family:var(--font,'Kantumruy Pro',sans-serif);
                       box-shadow:0 2px 10px rgba(255,107,0,.3);
                       transition:opacity .15s,transform .15s;"
                onmouseover="this.style.opacity='.88';"
                onmouseout="this.style.opacity='1';">
            <i class="fas fa-sliders-h"></i> កែតម្លៃជាច្រើន
        </button>
    </div>
</div>

{{-- ══════════ BULK ADJUST MODAL ══════════ --}}
<div class="modal-overlay" id="bulkAdjustModal">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-header" style="background:linear-gradient(135deg,#FF6B00,#e55a00);border-radius:14px 14px 0 0;">
            <h3 style="color:#fff;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-sliders-h"></i> កែតម្លៃដឹកជញ្ជូនជាច្រើន
            </h3>
            <button class="modal-close" style="color:#fff;opacity:.85;"
                    onclick="document.getElementById('bulkAdjustModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.shipping.bulk-adjust') }}" id="bulkAdjustForm">
            @csrf
            <div class="modal-body" style="padding:22px 24px;">

                {{-- Scope row --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
                    <div>
                        <label style="display:block;font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">ប្រភេទការដឹក</label>
                        <select name="scope_type" id="ba_scope_type" class="form-control" onchange="baPreview()" style="font-family:inherit;">
                            <option value="">ទាំងអស់ (Import + Export)</option>
                            <option value="import">Import — ទំនិញចូល</option>
                            <option value="export">Export — ទំនិញចេញ</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">ច្រកកំពង់ផែ</label>
                        <select name="scope_origin" id="ba_scope_origin" class="form-control" onchange="baPreview()" style="font-family:inherit;">
                            <option value="">ទាំងអស់ (SHV + PP)</option>
                            <option value="sihanoukville">ព្រះសីហនុ (SHV)</option>
                            <option value="phnom_penh">ភ្នំពេញ (PP)</option>
                        </select>
                    </div>
                </div>

                {{-- Adjust type --}}
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px;">របៀបកែតម្លៃ</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;" id="ba_type_grid">
                        <label class="ba-type-opt" data-val="percent_increase">
                            <input type="radio" name="adjust_type" value="percent_increase" onchange="baPreview()" style="display:none;">
                            <div class="ba-type-card">
                                <i class="fas fa-percentage" style="color:#059669;font-size:1rem;margin-bottom:4px;"></i>
                                <div style="font-size:.8rem;font-weight:700;color:#059669;">បន្ថែម %</div>
                                <div style="font-size:.7rem;color:#64748b;">ឧ. +10% ✕ ថ្លៃចាស់</div>
                            </div>
                        </label>
                        <label class="ba-type-opt" data-val="percent_decrease">
                            <input type="radio" name="adjust_type" value="percent_decrease" onchange="baPreview()" style="display:none;">
                            <div class="ba-type-card">
                                <i class="fas fa-percentage" style="color:#dc2626;font-size:1rem;margin-bottom:4px;"></i>
                                <div style="font-size:.8rem;font-weight:700;color:#dc2626;">កាត់ %</div>
                                <div style="font-size:.7rem;color:#64748b;">ឧ. -5% ✕ ថ្លៃចាស់</div>
                            </div>
                        </label>
                        <label class="ba-type-opt" data-val="fixed_add">
                            <input type="radio" name="adjust_type" value="fixed_add" onchange="baPreview()" style="display:none;">
                            <div class="ba-type-card">
                                <i class="fas fa-dollar-sign" style="color:#2563eb;font-size:1rem;margin-bottom:4px;"></i>
                                <div style="font-size:.8rem;font-weight:700;color:#2563eb;">បន្ថែម $</div>
                                <div style="font-size:.7rem;color:#64748b;">ឧ. +$5 រាល់ខេត្ត</div>
                            </div>
                        </label>
                        <label class="ba-type-opt" data-val="fixed_subtract">
                            <input type="radio" name="adjust_type" value="fixed_subtract" onchange="baPreview()" style="display:none;">
                            <div class="ba-type-card">
                                <i class="fas fa-dollar-sign" style="color:#9333ea;font-size:1rem;margin-bottom:4px;"></i>
                                <div style="font-size:.8rem;font-weight:700;color:#9333ea;">កាត់ $</div>
                                <div style="font-size:.7rem;color:#64748b;">ឧ. -$3 រាល់ខេត្ត</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Value + Round --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
                    <div>
                        <label style="display:block;font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">តម្លៃ <span id="ba_value_unit" style="color:#FF6B00;"></span></label>
                        <input type="number" name="adjust_value" id="ba_value"
                               class="form-control" min="0.01" step="0.01"
                               placeholder="ឧ. 10" oninput="baPreview()">
                    </div>
                    <div>
                        <label style="display:block;font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">បង្គត់ទៅ</label>
                        <select name="round_to" id="ba_round" class="form-control" onchange="baPreview()" style="font-family:inherit;">
                            <option value="0">មិនបង្គត់</option>
                            <option value="0.5">$0.50</option>
                            <option value="1" selected>$1.00</option>
                            <option value="5">$5.00</option>
                        </select>
                    </div>
                </div>

                {{-- Live Preview --}}
                <div id="ba_preview" style="display:none;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:14px 16px;">
                    <div style="font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:10px;">
                        <i class="fas fa-eye"></i> មើលជាមុន
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-size:.8rem;color:#475569;">ចំនួនខេត្ត/ច្រកដែលប្រែប្រួល</span>
                        <strong id="ba_count" style="font-family:'Kantumruy Pro',sans-serif;color:#FF6B00;"></strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-size:.8rem;color:#475569;">ថ្លៃទាបបំផុត</span>
                        <span style="font-size:.82rem;">
                            <span id="ba_old_min" style="color:#94a3b8;text-decoration:line-through;font-family:'Kantumruy Pro',sans-serif;"></span>
                            <i class="fas fa-arrow-right" style="font-size:.6rem;color:#94a3b8;margin:0 4px;"></i>
                            <strong id="ba_new_min" style="color:#059669;font-family:'Kantumruy Pro',sans-serif;"></strong>
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-size:.8rem;color:#475569;">ថ្លៃខ្ពស់បំផុត</span>
                        <span style="font-size:.82rem;">
                            <span id="ba_old_max" style="color:#94a3b8;text-decoration:line-through;font-family:'Kantumruy Pro',sans-serif;"></span>
                            <i class="fas fa-arrow-right" style="font-size:.6rem;color:#94a3b8;margin:0 4px;"></i>
                            <strong id="ba_new_max" style="color:#059669;font-family:'Kantumruy Pro',sans-serif;"></strong>
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:.8rem;color:#475569;">ភាគរយផ្លាស់ប្ដូរជាមធ្យម</span>
                        <strong id="ba_avg_change" style="font-family:'Kantumruy Pro',sans-serif;font-size:.85rem;"></strong>
                    </div>
                </div>

                <div id="ba_no_type" style="margin-top:10px;padding:8px 12px;background:#fef3c7;border:1.5px solid #fde68a;border-radius:8px;color:#92400e;font-size:.78rem;display:none;">
                    <i class="fas fa-exclamation-triangle"></i> សូមជ្រើសរើស «របៀបកែតម្លៃ» ជាមុន
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('bulkAdjustModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" id="ba_submit" class="btn btn-orange" disabled
                        style="opacity:.5;cursor:not-allowed;">
                    <i class="fas fa-check"></i> អនុវត្ត
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.ba-type-opt { cursor:pointer; }
.ba-type-card {
    border:2px solid #e2e8f0;
    border-radius:10px;
    padding:12px 10px;
    text-align:center;
    transition:border-color .15s,background .15s;
    background:#fff;
}
.ba-type-opt input:checked + .ba-type-card {
    border-color:#FF6B00;
    background:#fff8f3;
}
.ba-type-card:hover {
    border-color:#fed7aa;
    background:#fffbf5;
}
</style>

{{-- IMPORT PANEL --}}
<div id="panel-import">
    <div class="sr-banner import">
        <div class="sr-banner-icon"><i class="fas fa-ship"></i></div>
        <div>
            <strong>Import — ទំនិញចូល</strong>
            <span style="font-weight:400;margin-left:8px;">ពីកំពង់ផែ &rarr; ខេត្ត/រាជធានីគោលដៅ</span>
        </div>
    </div>
    <div class="sr-panel-grid">
        <div class="sr-port-card">
            <div class="sr-port-header import">
                <div class="sr-port-icon import"><i class="fas fa-anchor"></i></div>
                <div>
                    <div class="sr-port-title">មាត់ច្រកកំពង់ផែព្រះសីហនុ</div>
                    <div class="sr-port-sub">Sihanoukville Port (SHV)</div>
                </div>
            </div>
            <div class="sr-port-body">
                @include('admin.shipping-rates._edit_panel', [
                    'panelId'   => 'imp_shv',
                    'mapKey'    => 'import_sihanoukville',
                    'portLabel' => 'មាត់ច្រកកំពង់ផែព្រះសីហនុ',
                    'direction' => 'import',
                ])
            </div>
        </div>
        <div class="sr-port-card">
            <div class="sr-port-header import">
                <div class="sr-port-icon import"><i class="fas fa-anchor"></i></div>
                <div>
                    <div class="sr-port-title">មាត់ច្រកកំពង់ផែភ្នំពេញ</div>
                    <div class="sr-port-sub">Phnom Penh Port (PP)</div>
                </div>
            </div>
            <div class="sr-port-body">
                @include('admin.shipping-rates._edit_panel', [
                    'panelId'   => 'imp_pp',
                    'mapKey'    => 'import_phnom_penh',
                    'portLabel' => 'មាត់ច្រកកំពង់ផែភ្នំពេញ',
                    'direction' => 'import',
                ])
            </div>
        </div>
    </div>
</div>

{{-- EXPORT PANEL --}}
<div id="panel-export" style="display:none;">
    <div class="sr-banner export">
        <div class="sr-banner-icon"><i class="fas fa-truck-loading"></i></div>
        <div>
            <strong>Export — ទំនិញចេញ</strong>
            <span style="font-weight:400;margin-left:8px;">ពីខេត្ត/រាជធានី &rarr; មាត់ច្រកកំពង់ផែ</span>
        </div>
    </div>
    <div class="sr-panel-grid">
        <div class="sr-port-card">
            <div class="sr-port-header export">
                <div class="sr-port-icon export"><i class="fas fa-anchor"></i></div>
                <div>
                    <div class="sr-port-title">មាត់ច្រកកំពង់ផែព្រះសីហនុ</div>
                    <div class="sr-port-sub">Sihanoukville Port (SHV)</div>
                </div>
            </div>
            <div class="sr-port-body">
                @include('admin.shipping-rates._edit_panel', [
                    'panelId'   => 'exp_shv',
                    'mapKey'    => 'export_sihanoukville',
                    'portLabel' => 'មាត់ច្រកកំពង់ផែព្រះសីហនុ',
                    'direction' => 'export',
                ])
            </div>
        </div>
        <div class="sr-port-card">
            <div class="sr-port-header export">
                <div class="sr-port-icon export"><i class="fas fa-anchor"></i></div>
                <div>
                    <div class="sr-port-title">មាត់ច្រកកំពង់ផែភ្នំពេញ</div>
                    <div class="sr-port-sub">Phnom Penh Port (PP)</div>
                </div>
            </div>
            <div class="sr-port-body">
                @include('admin.shipping-rates._edit_panel', [
                    'panelId'   => 'exp_pp',
                    'mapKey'    => 'export_phnom_penh',
                    'portLabel' => 'មាត់ច្រកកំពង់ផែភ្នំពេញ',
                    'direction' => 'export',
                ])
            </div>
        </div>
    </div>
</div>

<script>
const RATES    = @json($ratesMap);
const BASE_URL = "{{ $updateRoute }}";

// ── Bulk Adjust preview logic ──────────────────────────────────
function baGetPrices() {
    var scopeType   = document.getElementById('ba_scope_type').value;
    var scopeOrigin = document.getElementById('ba_scope_origin').value;
    var prices = [];
    for (var mapKey in RATES) {
        // mapKey = "import_sihanoukville" etc.
        var parts = mapKey.split('_');
        // type is first part, origin is the rest joined
        var kType   = parts[0];
        var kOrigin = parts.slice(1).join('_');
        if (scopeType   && kType   !== scopeType)   continue;
        if (scopeOrigin && kOrigin !== scopeOrigin) continue;
        for (var prov in RATES[mapKey]) {
            prices.push(parseFloat(RATES[mapKey][prov].price));
        }
    }
    return prices;
}

function baApplyFormula(price, type, value, roundTo) {
    var newPrice;
    if (type === 'percent_increase') newPrice = price * (1 + value / 100);
    else if (type === 'percent_decrease') newPrice = Math.max(0, price * (1 - value / 100));
    else if (type === 'fixed_add')        newPrice = price + value;
    else if (type === 'fixed_subtract')   newPrice = Math.max(0, price - value);
    else return price;
    if (roundTo > 0) newPrice = Math.round(newPrice / roundTo) * roundTo;
    return Math.round(newPrice * 100) / 100;
}

function baPreview() {
    var type    = document.querySelector('input[name="adjust_type"]:checked');
    var value   = parseFloat(document.getElementById('ba_value').value);
    var roundTo = parseFloat(document.getElementById('ba_round').value) || 0;
    var preview = document.getElementById('ba_preview');
    var noType  = document.getElementById('ba_no_type');
    var submit  = document.getElementById('ba_submit');

    // Update value unit label
    var unitEl = document.getElementById('ba_value_unit');
    if (type) {
        unitEl.textContent = (type.value.includes('percent')) ? '(%)' : '($)';
    } else {
        unitEl.textContent = '';
    }

    if (!type) { preview.style.display='none'; noType.style.display='block'; submit.disabled=true; submit.style.opacity='.5'; submit.style.cursor='not-allowed'; return; }
    noType.style.display = 'none';

    if (!value || value <= 0) { preview.style.display='none'; submit.disabled=true; submit.style.opacity='.5'; submit.style.cursor='not-allowed'; return; }

    var prices    = baGetPrices();
    if (prices.length === 0) { preview.style.display='none'; return; }

    var newPrices = prices.map(function(p) { return baApplyFormula(p, type.value, value, roundTo); });
    var oldMin = Math.min.apply(null, prices);
    var oldMax = Math.max.apply(null, prices);
    var newMin = Math.min.apply(null, newPrices);
    var newMax = Math.max.apply(null, newPrices);
    var avgOld = prices.reduce(function(a,b){return a+b;},0)/prices.length;
    var avgNew = newPrices.reduce(function(a,b){return a+b;},0)/newPrices.length;
    var pctChange = avgOld > 0 ? ((avgNew - avgOld) / avgOld * 100).toFixed(1) : 0;

    document.getElementById('ba_count').textContent   = prices.length + ' ខេត្ត/ច្រក';
    document.getElementById('ba_old_min').textContent = '$' + oldMin.toFixed(2);
    document.getElementById('ba_new_min').textContent = '$' + newMin.toFixed(2);
    document.getElementById('ba_old_max').textContent = '$' + oldMax.toFixed(2);
    document.getElementById('ba_new_max').textContent = '$' + newMax.toFixed(2);
    var changeEl = document.getElementById('ba_avg_change');
    var isIncrease = parseFloat(pctChange) >= 0;
    changeEl.textContent = (isIncrease ? '+' : '') + pctChange + '%';
    changeEl.style.color = isIncrease ? '#dc2626' : '#059669';

    preview.style.display = 'block';
    submit.disabled = false;
    submit.style.opacity = '1';
    submit.style.cursor = 'pointer';
}

// Reset preview when modal closes
document.getElementById('bulkAdjustModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
});
// ────────────────────────────────────────────────────────────────

function buildUrl(rateId) {
    return BASE_URL.replace('__ID__', rateId);
}

function toggleProvinceDropdown(panelId) {
    var list = document.getElementById('list_' + panelId);
    var isOpen = list.classList.contains('open');
    document.querySelectorAll('.sr-select-list.open').forEach(function(el) {
        el.classList.remove('open');
    });
    if (!isOpen) list.classList.add('open');
}

function selectProvince(panelId, mapKey, value, label) {
    document.getElementById('sel_' + panelId).value = value;
    document.getElementById('btn_label_' + panelId).textContent = label;
    document.getElementById('list_' + panelId).classList.remove('open');
    onProvinceChange(panelId, mapKey);
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.sr-select-wrap')) {
        document.querySelectorAll('.sr-select-list.open').forEach(function(el) {
            el.classList.remove('open');
        });
    }
});

function onProvinceChange(panelId, mapKey) {
    var sel        = document.getElementById('sel_' + panelId);
    var priceInput = document.getElementById('price_' + panelId);
    var form       = document.getElementById('form_' + panelId);
    var currentLbl = document.getElementById('current_' + panelId);
    var wrapper    = document.getElementById('price_wrap_' + panelId);
    var en = sel.value;
    if (!en || !RATES[mapKey] || !RATES[mapKey][en]) {
        wrapper.style.display = 'none';
        return;
    }
    var rate = RATES[mapKey][en];
    priceInput.value = rate.price;
    form.action = buildUrl(rate.rate_id);
    currentLbl.textContent = '$' + parseFloat(rate.price).toFixed(2);
    wrapper.style.display = 'block';
}

document.querySelectorAll('.sr-tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.sr-tab-btn').forEach(function(t) {
            t.classList.remove('active');
        });
        this.classList.add('active');
        var tab = this.dataset.tab;
        document.getElementById('panel-import').style.display = tab === 'import' ? 'block' : 'none';
        document.getElementById('panel-export').style.display = tab === 'export' ? 'block' : 'none';
    });
});
</script>

@endsection
