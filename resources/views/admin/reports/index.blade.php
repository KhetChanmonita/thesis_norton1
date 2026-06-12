@extends('admin.layouts.admin')
@section('title', 'របាយការណ៍ចំណាយ')
@section('page-title')<span>គ្រប់គ្រង</span>របាយការណ៍ចំណាយ@endsection

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
<div class="stats-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <div class="val" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;">
                ${{ number_format($grandTotal, 2) }}
            </div>
            <div class="lbl">ចំណាយសរុបក្នុងខែ</div>
        </div>
    </div>
    @foreach($typeLabel as $key => $label)
    <div class="stat-card">
        <div class="stat-icon {{ $typeIcon[$key]['class'] }}"><i class="fas {{ $typeIcon[$key]['icon'] }}"></i></div>
        <div class="stat-info">
            <div class="val" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;">
                ${{ number_format($totals[$key] ?? 0, 2) }}
            </div>
            <div class="lbl">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Analysis Charts ── --}}
<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:18px;margin-bottom:24px;align-items:start;">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-pie"></i> សមាមាត្រចំណាយតាមប្រភេទ</div>
        </div>
        <div class="card-body" style="display:flex;justify-content:center;">
            <div style="max-width:260px;width:100%;">
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
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:14px;margin-bottom:20px;flex-wrap:wrap;">

    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ខែ</label>
            <input type="month" name="month" value="{{ $month }}"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                          font-family:'Montserrat',sans-serif;font-size:0.85rem;outline:none;">
        </div>
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ប្រភេទ</label>
            <select name="expense_type"
                    style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                           font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;background:#fff;outline:none;">
                <option value="">ទាំងអស់</option>
                @foreach($typeLabel as $key => $label)
                <option value="{{ $key }}" {{ request('expense_type')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-ghost" style="padding:9px 18px;">
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
            <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;font-weight:700;
                         background:#f1f5f9;color:#64748b;padding:2px 10px;border-radius:50px;margin-left:4px;">
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
                    <th style="text-align:center;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                <tr>
                    <td>
                        @php $ti = $typeIcon[$e->expense_type] ?? ['icon'=>'fa-receipt','class'=>'purple']; @endphp
                        <span style="display:inline-flex;align-items:center;gap:6px;font-size:0.85rem;font-weight:600;color:#1e293b;">
                            <i class="fas {{ $ti['icon'] }}" style="color:#FF6B00;"></i>
                            {{ $typeLabel[$e->expense_type] ?? $e->expense_type }}
                        </span>
                    </td>
                    <td>
                        <strong style="font-family:'Montserrat',sans-serif;color:#ef4444;font-size:0.95rem;">
                            ${{ number_format($e->amount, 2) }}
                        </strong>
                    </td>
                    <td style="font-size:0.83rem;color:#475569;">
                        @if($e->driver)
                            <div><i class="fas fa-id-badge" style="color:#3b82f6;font-size:0.7rem;"></i> {{ $e->driver->full_name }}</div>
                        @endif
                        @if($e->truck)
                            <div><i class="fas fa-truck" style="color:#FF6B00;font-size:0.7rem;"></i> {{ $e->truck->truck_name }} ({{ $e->truck->plate_number }})</div>
                        @endif
                        @if(!$e->driver && !$e->truck)
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td style="font-size:0.83rem;color:#475569;max-width:260px;">
                        {{ $e->description ?: '—' }}
                    </td>
                    <td style="font-size:0.83rem;color:#475569;white-space:nowrap;">
                        {{ $e->expense_date ? $e->expense_date->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        <div style="display:flex;justify-content:center;gap:6px;">
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
                    <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;">
                        <i class="fas fa-file-invoice-dollar" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.25;"></i>
                        <div style="font-size:0.9rem;">មិនមានចំណាយសម្រាប់ខែនេះ</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($expenses->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:0.8rem;color:#94a3b8;">
            បង្ហាញ {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} នៃ {{ $expenses->total() }}
        </span>
        <div style="display:flex;gap:6px;">
            @if($expenses->onFirstPage())
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $expenses->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($expenses->getUrlRange(1, $expenses->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $expenses->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($expenses->hasMorePages())
                <a href="{{ $expenses->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
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
                        <label class="form-label">ប្រភេទចំណាយ <span style="color:#ef4444;">*</span></label>
                        <select name="expense_type" id="exp_type" class="form-control" onchange="toggleExpenseFields(this.value)" required>
                            @foreach($typeLabel as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំនួនទឹកប្រាក់ (USD) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="amount" class="form-control" min="0" step="0.01" placeholder="ឧ. 250.00" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">កាលបរិច្ឆេទ <span style="color:#ef4444;">*</span></label>
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
                    <div class="form-group" id="exp_truck_field" style="display:none;">
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
                        <label class="form-label">ប្រភេទចំណាយ <span style="color:#ef4444;">*</span></label>
                        <select name="expense_type" id="edit_exp_type" class="form-control" onchange="toggleExpenseFields(this.value, 'edit_')" required>
                            @foreach($typeLabel as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំនួនទឹកប្រាក់ (USD) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="amount" id="edit_exp_amount" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">កាលបរិច្ឆេទ <span style="color:#ef4444;">*</span></label>
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
                    <div class="form-group" id="edit_exp_truck_field" style="display:none;">
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
