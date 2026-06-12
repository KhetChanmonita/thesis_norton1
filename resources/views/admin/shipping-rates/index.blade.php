@extends('admin.layouts.admin')
@section('title','តម្លៃដឹកជញ្ជូន')
@section('page-title')<span>គ្រប់គ្រង</span>តម្លៃដឹកជញ្ជូន@endsection

@section('content')

{{-- Type Tabs --}}
<div style="display:flex;gap:0;margin-bottom:28px;border-bottom:2.5px solid #e2e8f0;">
    <button class="sr-tab active" data-tab="import"
            style="padding:11px 32px;border:none;background:none;cursor:pointer;
                   font-family:'Kantumruy Pro',sans-serif;font-size:0.95rem;font-weight:700;
                   color:#ff6b00;border-bottom:3px solid #ff6b00;margin-bottom:-2.5px;transition:all 0.2s;">
        <i class="fas fa-ship" style="margin-right:8px;"></i>Import
    </button>
    <button class="sr-tab" data-tab="export"
            style="padding:11px 32px;border:none;background:none;cursor:pointer;
                   font-family:'Kantumruy Pro',sans-serif;font-size:0.95rem;font-weight:700;
                   color:#64748b;border-bottom:3px solid transparent;margin-bottom:-2.5px;transition:all 0.2s;">
        <i class="fas fa-truck-loading" style="margin-right:8px;"></i>Export
    </button>
</div>

{{-- IMPORT PANEL --}}
<div id="panel-import">
    <div style="background:#fff3e8;border:1.5px solid #fed7aa;border-radius:12px;padding:12px 18px;
                margin-bottom:24px;font-size:0.83rem;color:#92400e;">
        <i class="fas fa-info-circle" style="margin-right:6px;"></i>
        <strong>Import</strong> &mdash; ទំនិញចូល: ពីកំពង់ផែ &rarr; ខេត្ត/រាជធានីគោលដៅ
    </div>
    <div style="display:flex;gap:28px;align-items:flex-start;flex-wrap:wrap;">
        <div style="width:420px;flex-shrink:0;">
        @include('admin.shipping-rates._edit_panel', [
            'panelId'    => 'imp_shv',
            'mapKey'     => 'import_sihanoukville',
            'portLabel'  => 'មាត់ច្រកកំពង់ផែព្រះសីហនុ',
            'direction'  => 'import',
        ])
        </div>
        <div style="width:420px;flex-shrink:0;">
        @include('admin.shipping-rates._edit_panel', [
            'panelId'    => 'imp_pp',
            'mapKey'     => 'import_phnom_penh',
            'portLabel'  => 'មាត់ច្រកកំពង់ផែភ្នំពេញ',
            'direction'  => 'import',
        ])
        </div>
    </div>
</div>

{{-- EXPORT PANEL --}}
<div id="panel-export" style="display:none;">
    <div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:12px 18px;
                margin-bottom:24px;font-size:0.83rem;color:#1e40af;">
        <i class="fas fa-info-circle" style="margin-right:6px;"></i>
        <strong>Export</strong> &mdash; ទំនិញចេញ: ពីខេត្ត/រាជធានី &rarr; មាត់ច្រកកំពង់ផែ
    </div>
    <div style="display:flex;gap:28px;align-items:flex-start;flex-wrap:wrap;">
        <div style="width:420px;flex-shrink:0;">
        @include('admin.shipping-rates._edit_panel', [
            'panelId'    => 'exp_shv',
            'mapKey'     => 'export_sihanoukville',
            'portLabel'  => 'មាត់ច្រកកំពង់ផែព្រះសីហនុ',
            'direction'  => 'export',
        ])
        </div>
        <div style="width:420px;flex-shrink:0;">
        @include('admin.shipping-rates._edit_panel', [
            'panelId'    => 'exp_pp',
            'mapKey'     => 'export_phnom_penh',
            'portLabel'  => 'មាត់ច្រកកំពង់ផែភ្នំពេញ',
            'direction'  => 'export',
        ])
        </div>
    </div>
</div>

<script>
const RATES    = @json($ratesMap);
const BASE_URL = "{{ $updateRoute }}";

// Build form action URL from rate_id
function buildUrl(rateId) {
    return BASE_URL.replace('__ID__', rateId);
}

// When province is selected — fill price + set form action
function onProvinceChange(panelId, mapKey) {
    var sel    = document.getElementById('sel_' + panelId);
    var priceInput = document.getElementById('price_' + panelId);
    var saveBtn    = document.getElementById('save_' + panelId);
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

// Tab switching
document.querySelectorAll('.sr-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.sr-tab').forEach(function(t) {
            t.style.color = '#64748b';
            t.style.borderBottomColor = 'transparent';
        });
        this.style.color = '#ff6b00';
        this.style.borderBottomColor = '#ff6b00';
        var tab = this.dataset.tab;
        document.getElementById('panel-import').style.display = tab === 'import' ? 'block' : 'none';
        document.getElementById('panel-export').style.display = tab === 'export' ? 'block' : 'none';
    });
});
</script>

@endsection