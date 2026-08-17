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

{{-- Tab Bar --}}
<div class="sr-tab-wrap">
    <button class="sr-tab-btn active" data-tab="import">
        <i class="fas fa-ship"></i> Import — ទំនិញចូល
    </button>
    <button class="sr-tab-btn" data-tab="export">
        <i class="fas fa-truck-loading"></i> Export — ទំនិញចេញ
    </button>
</div>

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
