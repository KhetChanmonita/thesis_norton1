@extends('admin.layouts.admin')
@section('title', 'របាយការណ៍ប្រេងឥន្ធនៈ')
@section('page-title')<span>របាយការណ៍</span>ប្រេងឥន្ធនៈ@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_reports.css') }}">
    <style>
        .fuel-stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:20px; }
        .fuel-stat-card { background:#fff; border-radius:14px; padding:18px 20px; display:flex; align-items:center; gap:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); }
        .fuel-stat-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
        .fuel-stat-icon.orange { background:#fff3e8; color:#FF6B00; }
        .fuel-stat-icon.blue   { background:#eff6ff; color:#3b82f6; }
        .fuel-stat-icon.green  { background:#f0fdf4; color:#059669; }
        .fuel-stat-icon.teal   { background:#f0fdfa; color:#0d9488; }
        .fuel-stat-val { font-family:'Montserrat',sans-serif; font-size:1.25rem; font-weight:800; color:#1e293b; }
        .fuel-stat-lbl { font-size:.75rem; color:#64748b; margin-top:2px; }

        .fuel-toolbar { display:flex; align-items:flex-end; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
        .fuel-filter-form { display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap; }
        .fuel-filter-label { font-size:.72rem; font-weight:700; color:#64748b; display:block; margin-bottom:4px; }
        .fuel-month-input, .fuel-truck-select { border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 11px; font-family:inherit; font-size:.82rem; background:#fff; color:#334155; }
        .fuel-month-input:focus, .fuel-truck-select:focus { outline:none; border-color:#FF6B00; }

        .truck-summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px; margin-bottom:20px; }
        .truck-sum-card { background:#fff; border-radius:12px; padding:14px 16px; border:1.5px solid #f1f5f9; box-shadow:0 1px 6px rgba(0,0,0,.05); }
        .truck-sum-name { font-weight:700; font-size:.88rem; color:#1e293b; margin-bottom:8px; display:flex; align-items:center; gap:6px; }
        .truck-sum-row { display:flex; justify-content:space-between; font-size:.78rem; color:#64748b; padding:3px 0; border-bottom:1px solid #f8fafc; }
        .truck-sum-row:last-child { border:none; font-weight:700; color:#1e293b; font-size:.82rem; }
        .fuel-card-hidden { display:none; }
        .truck-sum-val { font-family:'Montserrat',sans-serif; font-weight:700; }
        .truck-sum-val.orange { color:#FF6B00; }
        .truck-sum-val.blue   { color:#3b82f6; }
        .truck-sum-val.green  { color:#059669; }

        .fuel-table th, .fuel-table td { vertical-align:middle; }
        .fuel-booking-chip { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; background:#fff3e8; color:#c2410c; border-radius:20px; font-size:.72rem; font-weight:700; font-family:'Montserrat',sans-serif; }
        .fuel-no-booking { font-size:.75rem; color:#94a3b8; }
        .fuel-amount-col { font-family:'Montserrat',sans-serif; font-weight:700; }
        .fuel-amount-col.orange { color:#FF6B00; }
        .fuel-amount-col.blue   { color:#3b82f6; }
        .fuel-amount-col.green  { color:#059669; }
        .fuel-total-row td { background:#fffbf5; font-weight:700; border-top:2px solid #fed7aa; }
        .fuel-empty-cell { text-align:center; padding:48px 20px; color:#94a3b8; }
        .fuel-empty-icon { font-size:2.5rem; color:#e2e8f0; display:block; margin-bottom:10px; }
        .fuel-count-badge { display:inline-flex; align-items:center; justify-content:center; min-width:22px; height:22px; padding:0 6px; background:#fff3e8; color:#FF6B00; border-radius:20px; font-size:.72rem; font-weight:700; margin-left:8px; }
        .fuel-tag { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; background:#fff3e8; color:#FF6B00; border-radius:6px; font-size:.72rem; font-weight:700; }
    </style>
@endpush

@section('content')

{{-- ── Stats ── --}}
<div class="fuel-stat-grid">
    <div class="fuel-stat-card">
        <div class="fuel-stat-icon orange"><i class="fas fa-gas-pump"></i></div>
        <div>
            <div class="fuel-stat-val">${{ number_format($grandFuel, 2) }}</div>
            <div class="fuel-stat-lbl">ចំណាយប្រេងសរុប</div>
        </div>
    </div>
    <div class="fuel-stat-card">
        <div class="fuel-stat-icon blue"><i class="fas fa-hand-holding-usd"></i></div>
        <div>
            <div class="fuel-stat-val">${{ number_format($grandAllowance, 2) }}</div>
            <div class="fuel-stat-lbl">លុយជើងតៃកុង</div>
        </div>
    </div>
    <div class="fuel-stat-card">
        <div class="fuel-stat-icon green"><i class="fas fa-calculator"></i></div>
        <div>
            <div class="fuel-stat-val">${{ number_format($grandTotal, 2) }}</div>
            <div class="fuel-stat-lbl">ចំណាយសរុប (ប្រេង + អ្នកបើក)</div>
        </div>
    </div>
    <div class="fuel-stat-card">
        <div class="fuel-stat-icon teal"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="fuel-stat-val">{{ $fuels->count() }}</div>
            <div class="fuel-stat-lbl">ចំនួនការដឹកក្នុងខែ</div>
        </div>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="fuel-toolbar">
    <form method="GET" action="{{ route('admin.reports.fuel') }}" class="fuel-filter-form">
        <div>
            <label class="fuel-filter-label">ខែ</label>
            <input type="month" name="month" value="{{ $month }}" class="fuel-month-input">
        </div>
        <div>
            <label class="fuel-filter-label">រថយន្ត</label>
            <select name="truck_id" class="fuel-truck-select">
                <option value="">ទាំងអស់</option>
                @foreach($trucks as $tr)
                <option value="{{ $tr->truck_id }}" {{ request('truck_id') == $tr->truck_id ? 'selected' : '' }}>
                    {{ $tr->truck_name }} — {{ $tr->plate_number }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-ghost">
            <i class="fas fa-search"></i> តម្រង
        </button>
    </form>

    <div class="rpt-toolbar-end">
        <a href="{{ route('admin.reports.index', ['month' => $month, 'expense_type' => 'fuel']) }}"
           class="btn btn-ghost">
            <i class="fas fa-list"></i> បញ្ជីចំណាយ
        </a>
        <a href="{{ route('admin.reports.fuel.print', array_filter(['month' => $month, 'truck_id' => $truckId])) }}"
           target="_blank" class="btn btn-ghost">
            <i class="fas fa-print"></i> ទាញយករបាយការណ៍
        </a>
        <button class="btn btn-orange" onclick="document.getElementById('addFuelModal').classList.add('open')">
            <i class="fas fa-plus"></i> បន្ថែមប្រេង
        </button>
    </div>
</div>

{{-- ── Per-truck Summary Cards ── --}}
@if($truckSummary->isNotEmpty())
<div class="truck-summary-grid" id="truckSummaryGrid">
    @foreach($truckSummary as $ts)
    <div class="truck-sum-card{{ $loop->index >= 5 ? ' fuel-card-hidden' : '' }}">
        <div class="truck-sum-name">
            <i class="fas fa-truck" style="color:#FF6B00;"></i>
            {{ $ts['truck']?->truck_name ?? '—' }}
            <span style="font-weight:400;color:#94a3b8;font-size:.75rem;">({{ $ts['truck']?->plate_number }})</span>
        </div>
        <div class="truck-sum-row">
            <span>ចំនួនដំណើរ</span>
            <span class="truck-sum-val">{{ $ts['count'] }} ដំណើរ</span>
        </div>
        <div class="truck-sum-row">
            <span>ចំណាយប្រេង</span>
            <span class="truck-sum-val orange">${{ number_format($ts['total_fuel'], 2) }}</span>
        </div>
        <div class="truck-sum-row">
            <span>លុយជើងតៃកុង</span>
            <span class="truck-sum-val blue">${{ number_format($ts['total_allowance'], 2) }}</span>
        </div>
        <div class="truck-sum-row">
            <span>សរុប</span>
            <span class="truck-sum-val green">${{ number_format($ts['total'], 2) }}</span>
        </div>
    </div>
    @endforeach
</div>
@if($truckSummary->count() > 5)
<div style="text-align:center;margin-bottom:16px;">
    <button id="truckSeeMoreBtn" class="btn btn-ghost" onclick="toggleTruckCards()">
        <i class="fas fa-chevron-down" id="truckSeeMoreIcon"></i>
        មើលបន្ថែម ({{ $truckSummary->count() - 5 }} បន្ថែម)
    </button>
</div>
@endif
@endif

{{-- ── Main Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-gas-pump"></i>
            លម្អិតប្រេងឥន្ធនៈ
            <span class="fuel-count-badge">{{ $fuels->count() }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table class="fuel-table">
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>ការកក់</th>
                    <th>រថយន្ត</th>
                    <th>អ្នកបើកបរ</th>
                    <th>ចំណាយប្រេង</th>
                    <th>លុយជើងតៃកុង</th>
                    <th>សរុប</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>បរិយាយ</th>
                    <th class="rpt-col-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fuels as $i => $f)
                @php
                    $rowTotal = $f->amount + ($f->driver_allowance ?? 0);
                    $bk = $f->booking;
                    $bookingLabel = $bk
                        ? 'LS' . $bk->booking_date->format('ym') . '-' . $bk->booking_id
                        : null;
                    $bkData = $bk ? [
                        'label'         => $bookingLabel,
                        'customer'      => $bk->customer?->full_name ?? $bk->bookedByUser?->user_name ?? '—',
                        'phone'         => $bk->customer?->phone ?? ($bk->bookedByUser ? ucfirst($bk->bookedByUser->role) : '—'),
                        'type'          => $bk->booking_type === 'import' ? 'នាំចូល (Import)' : 'នាំចេញ (Export)',
                        'container_num' => $bk->container_number ?? '—',
                        'container_size'=> $bk->container_size ?? '—',
                        'pickup'        => $bk->pickup_location ?? '—',
                        'dropoff'       => $bk->dropoff_location ?? '—',
                        'maps_link'     => $bk->dropoff_location_link ?? '',
                        'cargo_weight'  => $bk->cargo_weight ? number_format($bk->cargo_weight).' kg' : '—',
                        'pick_up_date'  => $bk->pick_up_date  ? $bk->pick_up_date->format('d/m/Y')  : '—',
                        'drop_off_date' => $bk->drop_off_date ? $bk->drop_off_date->format('d/m/Y') : '—',
                        'booking_date'  => $bk->booking_date  ? $bk->booking_date->format('d/m/Y')  : '—',
                        'total_price'   => '$'.number_format($bk->total_price, 2),
                        'status'        => $bk->status,
                        'truck'         => $bk->truck ? $bk->truck->truck_name.' ('.$bk->truck->plate_number.')' : '—',
                        'fuel'          => '$'.number_format($f->amount, 2),
                        'allowance'     => $f->driver_allowance > 0 ? '$'.number_format($f->driver_allowance, 2) : '—',
                        'total_fuel'    => '$'.number_format($rowTotal, 2),
                        'driver'        => $f->driver?->full_name ?? '—',
                    ] : null;
                @endphp
                <tr>
                    <td><span class="rpt-row-num">{{ $i + 1 }}</span></td>
                    <td>
                        @if($bookingLabel)
                            <span class="fuel-booking-chip fuel-booking-chip-click"
                                  onclick='showBookingDetail(@json($bkData))'
                                  title="ចុចដើម្បីមើលព័ត៌មានការកក់" style="cursor:pointer;">
                                <i class="fas fa-file-invoice"></i> {{ $bookingLabel }}
                                <i class="fas fa-eye" style="font-size:.65rem;opacity:.7;margin-left:2px;"></i>
                            </span>
                            @if($bk->customer || $bk->bookedByUser)
                            <div style="font-size:.73rem;color:#64748b;margin-top:3px;">
                                {{ $bk->customer?->full_name ?? $bk->bookedByUser?->user_name }}
                            </div>
                            @endif
                        @else
                            <span class="fuel-no-booking">—</span>
                        @endif
                    </td>
                    <td class="rpt-cell-text">
                        @if($f->truck)
                            <div><i class="fas fa-truck rpt-truck-icon"></i> {{ $f->truck->truck_name }}</div>
                            <div style="font-size:.72rem;color:#94a3b8;">{{ $f->truck->plate_number }}</div>
                        @else
                            <span class="rpt-dash">—</span>
                        @endif
                    </td>
                    <td class="rpt-cell-text">
                        @if($f->driver)
                            <div><i class="fas fa-id-badge rpt-driver-icon"></i> {{ $f->driver->full_name }}</div>
                        @else
                            <span class="rpt-dash">—</span>
                        @endif
                    </td>
                    <td>
                        <strong class="fuel-amount-col orange">${{ number_format($f->amount, 2) }}</strong>
                    </td>
                    <td>
                        @if($f->driver_allowance > 0)
                        <strong class="fuel-amount-col blue">${{ number_format($f->driver_allowance, 2) }}</strong>
                        @else
                        <span class="rpt-dash">—</span>
                        @endif
                    </td>
                    <td>
                        <strong class="fuel-amount-col green">${{ number_format($rowTotal, 2) }}</strong>
                    </td>
                    <td class="rpt-date-cell">
                        {{ $f->expense_date ? $f->expense_date->format('d/m/Y') : '—' }}
                    </td>
                    <td class="rpt-desc-cell">{{ $f->description ?: '—' }}</td>
                    <td>
                        <div class="rpt-actions-cell">
                            <button type="button" class="btn btn-ghost btn-sm" title="កែប្រែ"
                                    onclick="openEditFuel({{ $f->expense_id }}, {{ $f->amount }}, {{ $f->driver_allowance ?? 0 }}, '{{ $f->expense_date ? $f->expense_date->format('Y-m-d') : '' }}', {{ $f->driver_id ?? 'null' }}, {{ $f->truck_id ?? 'null' }}, {{ $f->booking_id ?? 'null' }}, {{ json_encode($f->description) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" title="លុប"
                                    onclick="confirmDeleteFuel({{ $f->expense_id }}, '{{ $bookingLabel ?? 'ប្រេង' }} — ${{ number_format($rowTotal,2) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="fuel-empty-cell">
                        <i class="fas fa-gas-pump fuel-empty-icon"></i>
                        <div>មិនមានទិន្នន័យប្រេងសម្រាប់ខែនេះ</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($fuels->isNotEmpty())
            <tfoot>
                <tr class="fuel-total-row">
                    <td colspan="4" style="text-align:right;padding-right:12px;">សរុប</td>
                    <td><strong class="fuel-amount-col orange">${{ number_format($grandFuel, 2) }}</strong></td>
                    <td><strong class="fuel-amount-col blue">${{ number_format($grandAllowance, 2) }}</strong></td>
                    <td><strong class="fuel-amount-col green">${{ number_format($grandTotal, 2) }}</strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ══════════ ADD FUEL MODAL ══════════ --}}
<div class="modal-overlay" id="addFuelModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-gas-pump"></i> បន្ថែមចំណាយប្រេង</h3>
            <button class="modal-close" onclick="document.getElementById('addFuelModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.reports.store') }}">
            @csrf
            <input type="hidden" name="expense_type" value="fuel">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ការកក់ (Booking)
                            @if($availableBookings->isEmpty())
                                <span style="font-size:.7rem;color:#94a3b8;font-weight:400;"> — ការកក់ទាំងអស់មានប្រេងហើយ</span>
                            @else
                                <span style="font-size:.7rem;color:#059669;font-weight:400;">({{ $availableBookings->count() }} ការកក់នៅសល់)</span>
                            @endif
                        </label>
                        <select name="booking_id" class="form-control" id="add_fuel_booking">
                            <option value="">— មិនភ្ជាប់ការកក់ —</option>
                            @foreach($availableBookings as $bk)
                            @php $bl = 'LS'.$bk->booking_date->format('ym').'-'.$bk->booking_id; @endphp
                            <option value="{{ $bk->booking_id }}"
                                    data-truck="{{ $bk->truck_id }}"
                                    data-driver="{{ $bk->driver_id ?? '' }}">
                                {{ $bl }}
                                @if($bk->customer || $bk->bookedByUser) — {{ $bk->customer?->full_name ?? $bk->bookedByUser?->user_name }}@endif
                                @if($bk->truck) | {{ $bk->truck->truck_name }}@endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">រថយន្ត</label>
                        <select name="truck_id" id="add_fuel_truck" class="form-control">
                            <option value="">— ជ្រើសរើស —</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">{{ $tr->truck_name }} — {{ $tr->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ</label>
                        <select name="driver_id" id="add_fuel_driver" class="form-control">
                            <option value="">— ជ្រើសរើស —</option>
                            @foreach(\App\Models\Driver::orderBy('full_name')->get() as $d)
                            <option value="{{ $d->driver_id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំណាយប្រេង (USD)</label>
                        <input type="number" name="amount" class="form-control" min="0" step="0.01" placeholder="ឧ. 80.00" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">លុយជើងតៃកុង (USD)</label>
                        <input type="number" name="driver_allowance" class="form-control" min="0" step="0.01" placeholder="ឧ. 30.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">កាលបរិច្ឆេទ</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">បរិយាយ</label>
                        <textarea name="description" class="form-control" placeholder="ព័ត៌មានបន្ថែម (ជម្រើស)" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('addFuelModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ EDIT FUEL MODAL ══════════ --}}
<div class="modal-overlay" id="editFuelModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> កែប្រែចំណាយប្រេង</h3>
            <button class="modal-close" onclick="document.getElementById('editFuelModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editFuelForm" action="">
            @csrf @method('PUT')
            <input type="hidden" name="expense_type" value="fuel">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ការកក់ (Booking)</label>
                        {{-- options are injected by openEditFuel() based on available + current booking --}}
                        <select name="booking_id" id="edit_fuel_booking" class="form-control">
                            <option value="">— មិនភ្ជាប់ការកក់ —</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">រថយន្ត</label>
                        <select name="truck_id" id="edit_fuel_truck" class="form-control">
                            <option value="">— ជ្រើសរើស —</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">{{ $tr->truck_name }} — {{ $tr->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ</label>
                        <select name="driver_id" id="edit_fuel_driver" class="form-control">
                            <option value="">— ជ្រើសរើស —</option>
                            @foreach(\App\Models\Driver::orderBy('full_name')->get() as $d)
                            <option value="{{ $d->driver_id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំណាយប្រេង (USD)</label>
                        <input type="number" name="amount" id="edit_fuel_amount" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">លុយជើងតៃកុង (USD)</label>
                        <input type="number" name="driver_allowance" id="edit_fuel_allowance" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">កាលបរិច្ឆេទ</label>
                        <input type="date" name="expense_date" id="edit_fuel_date" class="form-control" required>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">បរិយាយ</label>
                        <textarea name="description" id="edit_fuel_desc" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('editFuelModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ DELETE CONFIRM ══════════ --}}
<div class="modal-overlay confirm-overlay" id="deleteFuelModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteFuelForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបចំណាយប្រេងនេះ?</div>
                <p class="confirm-subtitle" id="deleteFuelName"></p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteFuelModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> លុប</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ BOOKING DETAIL MODAL ══════════ --}}
<div class="modal-overlay" id="bookingDetailModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal-box" style="max-width:560px;">
        <div class="modal-header" style="background:linear-gradient(135deg,#FF6B00,#e55a00);color:#fff;border-radius:14px 14px 0 0;">
            <h3 style="color:#fff;"><i class="fas fa-file-invoice"></i> <span id="bkd_label"></span></h3>
            <button class="modal-close" style="color:#fff;opacity:.85;"
                    onclick="document.getElementById('bookingDetailModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="padding:0;">

            {{-- Fuel summary banner --}}
            <div id="bkd_fuel_banner" style="display:grid;grid-template-columns:1fr 1fr 1fr;text-align:center;background:#fffbf5;border-bottom:1px solid #fed7aa;padding:14px 0;">
                <div>
                    <div style="font-size:.7rem;color:#94a3b8;margin-bottom:3px;">ចំណាយប្រេង</div>
                    <div id="bkd_fuel" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;font-weight:800;color:#FF6B00;"></div>
                </div>
                <div style="border-left:1px solid #fed7aa;border-right:1px solid #fed7aa;">
                    <div style="font-size:.7rem;color:#94a3b8;margin-bottom:3px;">លុយជើងតៃកុង</div>
                    <div id="bkd_allowance" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;font-weight:800;color:#3b82f6;"></div>
                </div>
                <div>
                    <div style="font-size:.7rem;color:#94a3b8;margin-bottom:3px;">សរុបប្រេង+អ្នកបើក</div>
                    <div id="bkd_total_fuel" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;font-weight:800;color:#059669;"></div>
                </div>
            </div>

            {{-- Booking details rows --}}
            <div style="padding:16px 22px;display:grid;gap:0;">
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-user"></i> អតិថិជន</span><span id="bkd_customer" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-phone"></i> ទូរស័ព្ទ</span><span id="bkd_phone" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-exchange-alt"></i> ប្រភេទ</span><span id="bkd_type" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-barcode"></i> លេខកុងតឺន័រ</span><span id="bkd_container_num" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-box"></i> ទំហំកុងតឺន័រ</span><span id="bkd_container_size" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-weight-hanging"></i> ទម្ងន់ទំនិញ</span><span id="bkd_cargo_weight" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-map-marker-alt" style="color:#FF6B00;"></i> ទីតាំងទទួល</span><span id="bkd_pickup" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-map-marker-alt" style="color:#10b981;"></i> ទីតាំងដឹកទៅ</span>
                    <span class="bkd-val"><span id="bkd_dropoff"></span>
                    <a id="bkd_maps" href="#" target="_blank" style="margin-left:6px;color:#FF6B00;font-size:.75rem;display:none;"><i class="fas fa-map"></i> Maps</a></span>
                </div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-truck"></i> រថយន្ត</span><span id="bkd_truck" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-id-badge"></i> អ្នកបើកបរ</span><span id="bkd_driver" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-calendar-check"></i> ថ្ងៃកក់</span><span id="bkd_booking_date" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-calendar-alt"></i> ថ្ងៃទទួល</span><span id="bkd_pickup_date" class="bkd-val"></span></div>
                <div class="bkd-row"><span class="bkd-lbl"><i class="fas fa-calendar-times"></i> ថ្ងៃដឹកទៅ</span><span id="bkd_dropoff_date" class="bkd-val"></span></div>
                <div class="bkd-row" style="border-bottom:none;">
                    <span class="bkd-lbl"><i class="fas fa-dollar-sign"></i> តម្លៃសរុបការដឹក</span>
                    <span id="bkd_total_price" class="bkd-val" style="font-family:'Montserrat',sans-serif;font-weight:800;color:#FF6B00;font-size:1rem;"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="justify-content:flex-end;">
            <button type="button" class="btn btn-ghost"
                    onclick="document.getElementById('bookingDetailModal').classList.remove('open')">
                <i class="fas fa-times"></i> បិទ
            </button>
        </div>
    </div>
</div>

<style>
.fuel-booking-chip-click:hover { background:#fed7aa; color:#9a3412; }
.bkd-row { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; padding:8px 0; border-bottom:1px solid #f8fafc; font-size:.82rem; }
.bkd-lbl { color:#64748b; min-width:140px; flex-shrink:0; }
.bkd-val { color:#1e293b; font-weight:600; text-align:right; }
</style>

@push('scripts')
<script>
var editFuelUrl   = @json(route('admin.reports.update', ['expense' => '__ID__']));
var deleteFuelUrl = @json(url('/admin/reports/__ID__'));

var truckCardsExpanded = false;
function toggleTruckCards() {
    truckCardsExpanded = !truckCardsExpanded;
    document.querySelectorAll('#truckSummaryGrid .truck-sum-card').forEach(function(card, idx) {
        if (idx >= 5) card.style.display = truckCardsExpanded ? 'block' : 'none';
    });
    var btn = document.getElementById('truckSeeMoreBtn');
    btn.innerHTML = truckCardsExpanded
        ? '<i class="fas fa-chevron-up"></i> បង្រួម'
        : '<i class="fas fa-chevron-down"></i> មើលបន្ថែម ({{ $truckSummary->count() - 5 }} បន្ថែម)';
}

function showBookingDetail(d) {
    document.getElementById('bkd_label').textContent         = d.label;
    document.getElementById('bkd_fuel').textContent          = d.fuel;
    document.getElementById('bkd_allowance').textContent     = d.allowance;
    document.getElementById('bkd_total_fuel').textContent    = d.total_fuel;
    document.getElementById('bkd_customer').textContent      = d.customer;
    document.getElementById('bkd_phone').textContent         = d.phone;
    document.getElementById('bkd_type').textContent          = d.type;
    document.getElementById('bkd_container_num').textContent = d.container_num;
    document.getElementById('bkd_container_size').textContent= d.container_size;
    document.getElementById('bkd_cargo_weight').textContent  = d.cargo_weight;
    document.getElementById('bkd_pickup').textContent        = d.pickup;
    document.getElementById('bkd_dropoff').textContent       = d.dropoff;
    document.getElementById('bkd_truck').textContent         = d.truck;
    document.getElementById('bkd_driver').textContent        = d.driver;
    document.getElementById('bkd_booking_date').textContent  = d.booking_date;
    document.getElementById('bkd_pickup_date').textContent   = d.pick_up_date;
    document.getElementById('bkd_dropoff_date').textContent  = d.drop_off_date;
    document.getElementById('bkd_total_price').textContent   = d.total_price;
    var mapsLink = document.getElementById('bkd_maps');
    if (d.maps_link) { mapsLink.href = d.maps_link; mapsLink.style.display = ''; }
    else { mapsLink.style.display = 'none'; }
    document.getElementById('bookingDetailModal').classList.add('open');
}

// Bookings without fuel yet (for Add modal — already rendered in server-side select)
// For Edit modal: available bookings + the current booking of this record
var availableBookingOptions = @json($availableBookings->map(fn($b) => [
    'id'       => $b->booking_id,
    'label'    => 'LS'.$b->booking_date->format('ym').'-'.$b->booking_id
               . ($b->customer ? ' — '.$b->customer->full_name : ($b->bookedByUser ? ' — '.$b->bookedByUser->user_name : ''))
               . ($b->truck    ? ' | '.$b->truck->truck_name   : ''),
]));

// Map of booking_id → label for all booked fuel records (so edit modal can show the current one)
var fuelBookingMap = @json($fuels->whereNotNull('booking_id')->mapWithKeys(fn($f) => [
    $f->booking_id => 'LS'.($f->booking?->booking_date ?? now())->format('ym').'-'.$f->booking_id
                   . ($f->booking?->customer ? ' — '.$f->booking->customer->full_name : ($f->booking?->bookedByUser ? ' — '.$f->booking->bookedByUser->user_name : ''))
]));

// When booking is selected in Add modal, auto-fill truck
document.getElementById('add_fuel_booking').addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    if (opt.dataset.truck) document.getElementById('add_fuel_truck').value = opt.dataset.truck;
    if (opt.dataset.driver) document.getElementById('add_fuel_driver').value = opt.dataset.driver;
});

function openEditFuel(id, amount, allowance, date, driverId, truckId, bookingId, desc) {
    document.getElementById('editFuelForm').action    = editFuelUrl.replace('__ID__', id);
    document.getElementById('edit_fuel_amount').value    = amount;
    document.getElementById('edit_fuel_allowance').value = allowance || '';
    document.getElementById('edit_fuel_date').value      = date;
    document.getElementById('edit_fuel_driver').value    = driverId || '';
    document.getElementById('edit_fuel_truck').value     = truckId || '';
    document.getElementById('edit_fuel_desc').value      = desc || '';

    // Build the booking dropdown: current booking (if any) + all available ones
    var sel = document.getElementById('edit_fuel_booking');
    sel.innerHTML = '<option value="">— មិនភ្ជាប់ការកក់ —</option>';
    // Add current booking first (if it's already taken by this record)
    if (bookingId && fuelBookingMap[bookingId]) {
        var opt = document.createElement('option');
        opt.value = bookingId;
        opt.textContent = fuelBookingMap[bookingId];
        sel.appendChild(opt);
    }
    // Then add all available (not yet linked) bookings
    availableBookingOptions.forEach(function(b) {
        if (String(b.id) === String(bookingId)) return; // skip if already added above
        var opt = document.createElement('option');
        opt.value = b.id;
        opt.textContent = b.label;
        sel.appendChild(opt);
    });
    sel.value = bookingId || '';
    document.getElementById('editFuelModal').classList.add('open');
}

function confirmDeleteFuel(id, label) {
    document.getElementById('deleteFuelForm').action = deleteFuelUrl.replace('__ID__', id);
    document.getElementById('deleteFuelName').textContent = label + ' — សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ';
    document.getElementById('deleteFuelModal').classList.add('open');
}
</script>
@endpush

@endsection
