@extends('admin.layouts.admin')
@section('title', 'គ្រប់គ្រងវិក្កយបត្រ')
@section('page-title')<span>គ្រប់គ្រង</span>វិក្កយបត្រ@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_cost_sheet.css') }}">
@endpush

@section('content')

@php
    $typeLabel = ['import' => 'នាំចូល', 'export' => 'នាំចេញ'];
@endphp

<a href="{{ route('admin.reports.index', ['month' => $month]) }}" class="btn btn-ghost cs-back-mb">
    <i class="fas fa-arrow-left"></i> 
</a>

{{-- ── Toolbar ── --}}
<div class="rpt-toolbar">
    <form method="GET" class="rpt-filter-form">
        <div>
            <label class="rpt-filter-label">ស្វែងរកអតិថិជន</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ឈ្មោះអតិថិជន" class="rpt-search-input">
        </div>
        <div>
            <label class="rpt-filter-label">ខែ</label>
            <input type="month" name="month" value="{{ $month }}" class="rpt-month-input">
        </div>
        <button type="submit" class="btn btn-ghost rpt-btn-search-pad">
            <i class="fas fa-search"></i> តម្រង
        </button>
        @if(request('search'))
        <a href="{{ route('admin.reports.cost-sheet', ['month' => $month]) }}" class="btn btn-ghost rpt-btn-clear-pad">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
    <span id="csSaveStatus" class="cs-save-status"></span>
</div>

{{-- ── Cost Sheet Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-table"></i>
            តារាងបញ្ជីប្រចាំខែ
            <span class="rpt-count-badge">{{ $bookings->total() }}</span>
        </div>
    </div>

    <div class="table-wrap cs-table-wrap">
        {{-- Determine which cost-sheet columns have at least one non-empty value --}}
        @php
            $col        = $bookings->getCollection();
            $showSize   = $col->contains(fn($b) => (bool)($b->costSheet?->size) || (bool)$b->container_size);
            $showPrice  = $col->contains(fn($b) => ($b->costSheet && $b->costSheet->price > 0) || $b->total_price > 0);
            $showLolo   = $col->contains(fn($b) => $b->costSheet && $b->costSheet->lolo > 0);
            $showOW     = $col->contains(fn($b) => $b->costSheet && $b->costSheet->over_weight > 0);
            $showEW     = $col->contains(fn($b) => $b->costSheet && $b->costSheet->express_way > 0);
            $showExtra  = $col->contains(fn($b) => $b->costSheet && $b->costSheet->extra > 0);
            $showER     = $col->contains(fn($b) => $b->costSheet && $b->costSheet->empty_return > 0);
            $showST     = $col->contains(fn($b) => $b->costSheet && $b->costSheet->standby_truck > 0);
            $showRepair = $col->contains(fn($b) => $b->costSheet && $b->costSheet->repair > 0);
            $showTotal  = $showPrice || $showLolo || $showOW || $showEW || $showExtra || $showER || $showST || $showRepair;
            $showDesc   = $col->contains(fn($b) => $b->extraCharges->isNotEmpty());
            $emptyColspan = 14
                + (int)$showSize  + (int)$showPrice  + (int)$showLolo
                + (int)$showOW    + (int)$showEW    + (int)$showExtra  + (int)$showER
                + (int)$showST    + (int)$showRepair + (int)$showTotal + (int)$showDesc + 1;
        @endphp

        <table class="cs-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Booking ID</th>
                    <th>អតិថិជន</th>
                    <th>ទូរស័ព្ទ</th>
                    <th>ប្រភេទ</th>
                    <th>ស្លាករថយន្ត</th>
                    <th>លេខកុងតឺន័រ</th>
                    <th>ទីតាំងទទួល</th>
                    <th>ទីតាំងដឹក</th>
                    <th>ថ្ងៃទទួល</th>
                    <th>ថ្ងៃដឹក</th>
                    <th>ទំងន់ (kg)</th>
                    <th>ស្ថានភាព</th>
                    <th>ការបង់ប្រាក់</th>
                    @if($showDesc)<th>ការអធិប្បាយ / Description</th>@endif
                    @if($showSize)<th>Size</th>@endif
                    @if($showPrice)<th>Price</th>@endif
                    @if($showLolo)<th>LoLo</th>@endif
                    @if($showOW)<th>Over Weight</th>@endif
                    @if($showEW)<th>ផ្លូវល្បឿនលឿន</th>@endif
                    @if($showExtra)<th>Extra</th>@endif
                    @if($showER)<th>Empty Return</th>@endif
                    @if($showST)<th>Standby Truck</th>@endif
                    @if($showRepair)<th>Repair</th>@endif
                    @if($showTotal)<th>Total</th>@endif
                    <th class="cs-th-center">Invoice</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $i => $b)
                @php
                    $cs = $b->costSheet;
                    $priceDefault  = ($cs && $cs->price > 0) ? $cs->price : ($b->total_price ?? '');
                    $sizeDefault   = ($cs && $cs->size)     ? $cs->size  : ($b->container_size ?? '');
                @endphp
                <tr data-booking-id="{{ $b->booking_id }}">
                    <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $i + 1 }}</td>
                    <td class="cs-readonly">{{ $b->formatted_id }}</td>
                    <td class="cs-readonly">{{ $b->customer->full_name ?? '—' }}</td>
                    <td class="cs-readonly cs-nowrap">{{ $b->customer->phone ?? '—' }}</td>
                    <td class="cs-readonly">
                        @if($b->booking_type === 'import')
                            <span class="cs-badge cs-badge-import">នាំចូល</span>
                        @else
                            <span class="cs-badge cs-badge-export">នាំចេញ</span>
                        @endif
                    </td>
                    <td class="cs-readonly cs-nowrap">{{ $b->truck->plate_number ?? '—' }}</td>
                    <td class="cs-readonly">{{ $b->container_number ?? '—' }}</td>
                    <td class="cs-readonly">{{ $b->pickup_location ?? '—' }}</td>
                    <td class="cs-readonly">{{ $b->dropoff_location ?? '—' }}</td>
                    <td class="cs-readonly cs-nowrap">{{ $b->pick_up_date ? $b->pick_up_date->format('d/m/Y') : '—' }}</td>
                    <td class="cs-readonly cs-nowrap">{{ $b->drop_off_date ? $b->drop_off_date->format('d/m/Y') : '—' }}</td>
                    <td class="cs-readonly cs-right">{{ $b->cargo_weight ?? '—' }}</td>
                    <td class="cs-readonly">
                        @php
                            $stMap = ['pending'=>['រង់ចាំ','cs-badge-pending'],'confirmed'=>['បានបញ្ជាក់','cs-badge-confirmed'],'in_progress'=>['កំពុងដំណើរ','cs-badge-progress'],'completed'=>['បានបញ្ចប់','cs-badge-done'],'cancelled'=>['បានបោះបង់','cs-badge-cancel']];
                            $st = $stMap[$b->status] ?? [$b->status,'cs-badge-pending'];
                        @endphp
                        <span class="cs-badge {{ $st[1] }}">{{ $st[0] }}</span>
                    </td>
                    <td class="cs-readonly">
                        @php
                            $pmMap = ['unpaid'=>['មិនទាន់បង់','cs-badge-unpaid'],'paid'=>['បានបង់ប្រាក់','cs-badge-paid'],'fully_paid'=>['បានបង់ប្រាក់','cs-badge-paid'],'partial'=>['បង់មួយភាគ','cs-badge-partial'],'deposit_paid'=>['បង់ 50%','cs-badge-partial']];
                            $pm = $pmMap[$b->payment_status] ?? [$b->payment_status ?? '—','cs-badge-pending'];
                        @endphp
                        <span class="cs-badge {{ $pm[1] }}">{{ $pm[0] }}</span>
                    </td>
                    @php
                        $secondExtraSum = $b->extraCharges->where('stage', 'second')->sum('amount');
                        $trueTotal      = ($b->total_price ?? 0) + $secondExtraSum;
                    @endphp
                    @if($showDesc)
                    <td class="cs-desc-cell">
                        @foreach($b->extraCharges as $ec)
                        <div class="cs-desc-item {{ $ec->stage === 'second' ? 'cs-desc-second' : 'cs-desc-first' }}">
                            <span class="cs-desc-label">{{ $ec->reason }}</span>
                            <span class="cs-desc-amt">+${{ number_format($ec->amount, 2) }}</span>
                            @if($ec->stage === 'second')<span class="cs-desc-tag">ចុងក្រោយ</span>@endif
                        </div>
                        @endforeach
                    </td>
                    @endif
                    @if($showSize)<td><input type="text" class="cs-input cs-text cs-size" data-field="size" value="{{ $sizeDefault }}"></td>@endif
                    @if($showPrice)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="price" value="{{ $priceDefault }}"></td>@endif
                    @if($showLolo)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="lolo" value="{{ $cs->lolo ?? '' }}"></td>@endif
                    @if($showOW)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="over_weight" value="{{ $cs->over_weight ?? '' }}"></td>@endif
                    @if($showEW)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="express_way" value="{{ $cs->express_way ?? '' }}"></td>@endif
                    @if($showExtra)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="extra" value="{{ $cs->extra ?? '' }}"></td>@endif
                    @if($showER)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="empty_return" value="{{ $cs->empty_return ?? '' }}"></td>@endif
                    @if($showST)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="standby_truck" value="{{ $cs->standby_truck ?? '' }}"></td>@endif
                    @if($showRepair)<td><input type="number" step="0.01" min="0" class="cs-input cs-num" data-field="repair" value="{{ $cs->repair ?? '' }}"></td>@endif
                    @if($showTotal)<td class="cs-total">${{ number_format($trueTotal, 2) }}</td>@endif
                    <td class="cs-td-invoice">
                        <a href="{{ route('admin.reports.invoice', $b->booking_id) }}"
                           class="cs-invoice-btn"
                           title="រក្សាទុក និងបោះពុម្ព Invoice">
                            <i class="fas fa-file-invoice"></i> Print
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $emptyColspan }}" class="rpt-empty-cell">មិនមានការកក់សម្រាប់ខែនេះ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
    <div class="rpt-pagination-bar">
        <span class="rpt-pagination-info">
            បង្ហាញ {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} នៃ {{ $bookings->total() }}
        </span>
        <div class="rpt-pagination-pages">
            @if($bookings->onFirstPage())
                <span class="page-btn rpt-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $bookings->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $bookings->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($bookings->hasMorePages())
                <a href="{{ $bookings->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn rpt-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
var csUpdateUrlTemplate = @json(url('/admin/reports/cost-sheet/__ID__'));
var csCsrfToken         = @json(csrf_token());
var csSaveTimers        = {};

/* ── Recalculate row total from numeric inputs ── */
function csCalcRowTotal(row) {
    var sum = 0;
    row.querySelectorAll('.cs-num').forEach(function(inp) {
        sum += parseFloat(inp.value) || 0;
    });
    var cell = row.querySelector('.cs-total');
    if (cell) cell.textContent = '$' + sum.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
}

/* ── Save a row to the server, returns a Promise ── */
function csSaveRow(row) {
    var bookingId = row.getAttribute('data-booking-id');
    if (!bookingId) return Promise.resolve();

    var payload = {};
    row.querySelectorAll('.cs-input').forEach(function(inp) {
        payload[inp.getAttribute('data-field')] = inp.value;
    });

    var statusEl = document.getElementById('csSaveStatus');
    if (statusEl) { statusEl.textContent = 'កំពុងរក្សាទុក...'; }

    return fetch(csUpdateUrlTemplate.replace('__ID__', bookingId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csCsrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
    })
    .then(function(res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(function(data) {
        if (statusEl) {
            statusEl.textContent = '✓ បានរក្សាទុករួច';
            setTimeout(function() { statusEl.textContent = ''; }, 2500);
        }
        /* Sync the Total cell with the server-calculated value */
        if (data.total !== undefined) {
            var cell = row.querySelector('.cs-total');
            if (cell) cell.textContent = '$' + parseFloat(data.total).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        }
    })
    .catch(function(err) {
        if (statusEl) statusEl.textContent = '✗ មិនអាចរក្សាទុក (' + err.message + ')';
    });
}

/* ── Wire up each row ── */
document.querySelectorAll('.cs-table tbody tr[data-booking-id]').forEach(function(row) {

    /* Input: update total + debounce-save (700 ms) */
    row.querySelectorAll('.cs-input').forEach(function(inp) {
        inp.addEventListener('input', function() {
            csCalcRowTotal(row);
            var id = row.getAttribute('data-booking-id');
            clearTimeout(csSaveTimers[id]);
            csSaveTimers[id] = setTimeout(function() { csSaveRow(row); }, 700);
        });

        /* Change (blur with value change): cancel debounce, save after 150 ms */
        inp.addEventListener('change', function() {
            var id = row.getAttribute('data-booking-id');
            clearTimeout(csSaveTimers[id]);
            csSaveTimers[id] = setTimeout(function() { csSaveRow(row); }, 150);
        });
    });

    /* Invoice Print button: save first, THEN open the invoice tab */
    var invoiceBtn = row.querySelector('.cs-invoice-btn');
    if (invoiceBtn) {
        invoiceBtn.addEventListener('click', function(e) {
            e.preventDefault();
            var href     = this.href;
            var id       = row.getAttribute('data-booking-id');
            var origHtml = this.innerHTML;
            var self     = this;

            /* Cancel any pending auto-save for this row */
            clearTimeout(csSaveTimers[id]);

            /* Show saving state on button */
            self.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            self.style.opacity = '0.75';

            csSaveRow(row).then(function() {
                /* Small pause so the server write completes before the new tab loads */
                setTimeout(function() {
                    window.open(href, '_blank');
                    self.innerHTML = origHtml;
                    self.style.opacity = '1';
                }, 150);
            });
        });
    }
});
</script>
@endpush

@endsection
