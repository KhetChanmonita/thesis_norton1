@extends('admin.layouts.admin')
@section('title', 'របាយការណ៍ចំណាយ')
@section('page-title')<span>គ្រប់គ្រង</span>របាយការណ៍ចំណាយ@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_reports.css') }}">
@endpush

@section('content')

@php
    $typeLabel = [
        'salary' => 'ប្រាក់ខែអ្នកបើកបរ',
        'fuel'   => 'ប្រេងឥន្ធនៈ',
        'repair' => 'ជួសជុលរថយន្ត',
        'other'  => 'ផ្សេងៗ',
    ];
    $typeIcon = [
        'salary' => ['icon'=>'fa-money-check-alt','class'=>'blue'],
        'fuel'   => ['icon'=>'fa-gas-pump','class'=>'orange'],
        'repair' => ['icon'=>'fa-tools','class'=>'red'],
        'other'  => ['icon'=>'fa-receipt','class'=>'purple'],
    ];
@endphp

{{-- ── Stats Cards ── --}}
<div class="stats-grid rpt-stats-grid">
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <div class="val rpt-stat-val">
                ${{ number_format($grandTotal, 2) }}
            </div>
            <div class="lbl">ចំណាយសរុបក្នុងខែ</div>
        </div>
    </div>
    @foreach($typeLabel as $key => $label)
    <div class="stat-card">
        <div class="stat-icon {{ $typeIcon[$key]['class'] }}"><i class="fas {{ $typeIcon[$key]['icon'] }}"></i></div>
        <div class="stat-info">
            <div class="val rpt-stat-val">
                ${{ number_format($totals[$key] ?? 0, 2) }}
            </div>
            <div class="lbl">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Analysis Charts ── --}}
<div class="rpt-charts-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-pie"></i> សមាមាត្រចំណាយតាមប្រភេទ</div>
        </div>
        <div class="card-body rpt-chart-center">
            <div class="rpt-chart-donut-wrap">
                <canvas id="expenseTypeChart"></canvas>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-bar"></i> និន្នាការចំណាយ ៦ខែចុងក្រោយ</div>
        </div>
        <div class="card-body">
            <canvas id="expenseTrendChart" height="160"></canvas>
        </div>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="rpt-toolbar">

    <form method="GET" class="rpt-filter-form">
        <div>
            <label class="rpt-filter-label">ខែ</label>
            <input type="month" name="month" value="{{ $month }}" class="rpt-month-input">
        </div>
        <div>
            <label class="rpt-filter-label">ប្រភេទ</label>
            <select name="expense_type" class="rpt-type-select">
                <option value="">ទាំងអស់</option>
                @foreach($typeLabel as $key => $label)
                <option value="{{ $key }}" {{ request('expense_type')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-ghost rpt-btn-search-pad">
            <i class="fas fa-search"></i> តម្រង
        </button>
    </form>

    <button class="btn btn-orange"
            onclick="document.getElementById('addExpenseModal').classList.add('open')">
        <i class="fas fa-plus"></i> បន្ថែមចំណាយ
    </button>
</div>

{{-- ── Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-file-invoice-dollar"></i>
            បញ្ជីចំណាយ
            <span class="rpt-count-badge">
                {{ $expenses->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ប្រភេទ</th>
                    <th>ចំនួនទឹកប្រាក់</th>
                    <th>អ្នកបើកបរ / រថយន្ត</th>
                    <th>បរិយាយ</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th class="rpt-col-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                <tr>
                    <td>
                        @php $ti = $typeIcon[$e->expense_type] ?? ['icon'=>'fa-receipt','class'=>'purple']; @endphp
                        <span class="rpt-type-cell">
                            <i class="fas {{ $ti['icon'] }} rpt-type-icon"></i>
                            {{ $typeLabel[$e->expense_type] ?? $e->expense_type }}
                        </span>
                    </td>
                    <td>
                        <strong class="rpt-amount">
                            ${{ number_format($e->amount, 2) }}
                        </strong>
                    </td>
                    <td class="rpt-cell-text">
                        @if($e->driver)
                            <div><i class="fas fa-id-badge rpt-driver-icon"></i> {{ $e->driver->full_name }}</div>
                        @endif
                        @if($e->truck)
                            <div><i class="fas fa-truck rpt-truck-icon"></i> {{ $e->truck->truck_name }} ({{ $e->truck->plate_number }})</div>
                        @endif
                        @if(!$e->driver && !$e->truck)
                            <span class="rpt-dash">—</span>
                        @endif
                    </td>
                    <td class="rpt-desc-cell">
                        {{ $e->description ?: '—' }}
                    </td>
                    <td class="rpt-date-cell">
                        {{ $e->expense_date ? $e->expense_date->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        <div class="rpt-actions-cell">
                            <button type="button" class="btn btn-ghost btn-sm" title="កែប្រែ"
                                    onclick="openEditExpense({{ $e->expense_id }}, '{{ $e->expense_type }}', {{ $e->amount }}, '{{ $e->expense_date ? $e->expense_date->format('Y-m-d') : '' }}', {{ $e->driver_id ?? 'null' }}, {{ $e->truck_id ?? 'null' }}, {{ json_encode($e->description) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.reports.destroy', $e->expense_id) }}"
                                  onsubmit="return confirm('លុបចំណាយនេះ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="លុប">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="rpt-empty-cell">
                        <i class="fas fa-file-invoice-dollar rpt-empty-icon"></i>
                        <div class="rpt-empty-text">មិនមានចំណាយសម្រាប់ខែនេះ</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($expenses->hasPages())
    <div class="rpt-pagination-bar">
        <span class="rpt-pagination-info">
            បង្ហាញ {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} នៃ {{ $expenses->total() }}
        </span>
        <div class="rpt-pagination-pages">
            @if($expenses->onFirstPage())
                <span class="page-btn rpt-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $expenses->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($expenses->getUrlRange(1, $expenses->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $expenses->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($expenses->hasMorePages())
                <a href="{{ $expenses->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn rpt-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ══════════════ ADD EXPENSE MODAL ══════════════ --}}
<div class="modal-overlay" id="addExpenseModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar"></i> បន្ថែមចំណាយ</h3>
            <button class="modal-close"
                    onclick="document.getElementById('addExpenseModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.reports.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ប្រភេទចំណាយ <span class="rpt-required">*</span></label>
                        <select name="expense_type" id="exp_type" class="form-control" onchange="toggleExpenseFields(this.value)" required>
                            @foreach($typeLabel as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំនួនទឹកប្រាក់ (USD) <span class="rpt-required">*</span></label>
                        <input type="number" name="amount" class="form-control" min="0" step="0.01" placeholder="ឧ. 250.00" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">កាលបរិច្ឆេទ <span class="rpt-required">*</span></label>
                        <input type="date" name="expense_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group" id="exp_driver_field">
                        <label class="form-label">អ្នកបើកបរ</label>
                        <select name="driver_id" class="form-control">
                            <option value="">-- ជ្រើសរើស --</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->driver_id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="exp_truck_field">
                        <label class="form-label">រថយន្ត</label>
                        <select name="truck_id" class="form-control">
                            <option value="">-- ជ្រើសរើស --</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">{{ $tr->truck_name }} — {{ $tr->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">បរិយាយ</label>
                        <textarea name="description" class="form-control" placeholder="ព័ត៌មានបន្ថែម (ជម្រើស)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('addExpenseModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════ EDIT EXPENSE MODAL ══════════════ --}}
<div class="modal-overlay" id="editExpenseModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> កែប្រែចំណាយ</h3>
            <button class="modal-close"
                    onclick="document.getElementById('editExpenseModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editExpenseForm" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ប្រភេទចំណាយ <span class="rpt-required">*</span></label>
                        <select name="expense_type" id="edit_exp_type" class="form-control" onchange="toggleExpenseFields(this.value, 'edit_')" required>
                            @foreach($typeLabel as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំនួនទឹកប្រាក់ (USD) <span class="rpt-required">*</span></label>
                        <input type="number" name="amount" id="edit_exp_amount" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">កាលបរិច្ឆេទ <span class="rpt-required">*</span></label>
                        <input type="date" name="expense_date" id="edit_exp_date" class="form-control" required>
                    </div>
                    <div class="form-group" id="edit_exp_driver_field">
                        <label class="form-label">អ្នកបើកបរ</label>
                        <select name="driver_id" id="edit_exp_driver_id" class="form-control">
                            <option value="">-- ជ្រើសរើស --</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->driver_id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="edit_exp_truck_field">
                        <label class="form-label">រថយន្ត</label>
                        <select name="truck_id" id="edit_exp_truck_id" class="form-control">
                            <option value="">-- ជ្រើសរើស --</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">{{ $tr->truck_name }} — {{ $tr->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">បរិយាយ</label>
                        <textarea name="description" id="edit_exp_description" class="form-control" placeholder="ព័ត៌មានបន្ថែម (ជម្រើស)"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('editExpenseModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
function toggleExpenseFields(type, prefix) {
    prefix = prefix || '';
    var driverField = document.getElementById(prefix + 'exp_driver_field');
    var truckField  = document.getElementById(prefix + 'exp_truck_field');
    driverField.style.display = (type === 'salary') ? 'block' : 'none';
    truckField.style.display  = (type === 'fuel' || type === 'repair') ? 'block' : 'none';
}

var editExpenseUrlTemplate = @json(route('admin.reports.update', ['expense' => '__ID__']));

function openEditExpense(id, type, amount, date, driverId, truckId, description) {
    document.getElementById('editExpenseForm').action = editExpenseUrlTemplate.replace('__ID__', id);
    document.getElementById('edit_exp_type').value = type;
    document.getElementById('edit_exp_amount').value = amount;
    document.getElementById('edit_exp_date').value = date;
    document.getElementById('edit_exp_driver_id').value = driverId || '';
    document.getElementById('edit_exp_truck_id').value = truckId || '';
    document.getElementById('edit_exp_description').value = description || '';
    toggleExpenseFields(type, 'edit_');
    document.getElementById('editExpenseModal').classList.add('open');
}

document.addEventListener('DOMContentLoaded', function () {
    toggleExpenseFields(document.getElementById('exp_type').value);

    var typeLabels = @json(array_values($typeLabel));
    var typeKeys   = @json(array_keys($typeLabel));
    var typeTotals = @json(collect($typeLabel)->keys()->map(fn($k) => (float)($totals[$k] ?? 0))->values());
    var typeColors = {
        salary: '#3b82f6',
        fuel:   '#FF6B00',
        repair: '#ef4444',
        other:  '#8b5cf6',
    };
    var bgColors = typeKeys.map(function (k) { return typeColors[k]; });

    var typeGrandTotal = typeTotals.reduce(function (a, b) { return a + b; }, 0);

    new Chart(document.getElementById('expenseTypeChart'), {
        type: 'doughnut',
        plugins: [ChartDataLabels],
        data: {
            labels: typeLabels,
            datasets: [{
                data: typeTotals,
                backgroundColor: bgColors,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: "'Kantumruy Pro', sans-serif" } } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var pct = typeGrandTotal > 0 ? (ctx.parsed / typeGrandTotal * 100) : 0;
                            return ctx.label + ': $' + ctx.parsed.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (' + pct.toFixed(1) + '%)';
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 12 },
                    formatter: function (value) {
                        if (typeGrandTotal <= 0) return '';
                        var pct = value / typeGrandTotal * 100;
                        return pct < 5 ? '' : pct.toFixed(1) + '%';
                    }
                }
            }
        }
    });

    var trendData = @json($trendData);
    new Chart(document.getElementById('expenseTrendChart'), {
        type: 'bar',
        data: {
            labels: trendData.map(function (d) { return d.label; }),
            datasets: [
                { label: typeLabels[0], data: trendData.map(d => d.salary), backgroundColor: typeColors.salary },
                { label: typeLabels[1], data: trendData.map(d => d.fuel),   backgroundColor: typeColors.fuel },
                { label: typeLabels[2], data: trendData.map(d => d.repair), backgroundColor: typeColors.repair },
                { label: typeLabels[3], data: trendData.map(d => d.other),  backgroundColor: typeColors.other },
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true, ticks: { font: { family: "'Kantumruy Pro', sans-serif" } } },
                y: { stacked: true, beginAtZero: true, ticks: { callback: v => '$' + v } }
            },
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: "'Kantumruy Pro', sans-serif" } } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ctx.dataset.label + ': $' + ctx.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@endsection
