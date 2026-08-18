@extends('admin.layouts.admin')
@section('title', 'កាលវិភាគ')
@section('page-title')<span>គ្រប់គ្រង</span>កាលវិភាគរថយន្ត@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_schedules.css') }}">
@endpush

@section('content')

{{-- Validation errors — reopen the correct modal so user can see the problem --}}
@if($errors->any())
<div class="sch-error-box" id="schErrorBox">
    <i class="fas fa-exclamation-circle sch-error-icon"></i>
    <div>
        @foreach($errors->all() as $e)
        <div class="sch-error-text">{{ $e }}</div>
        @endforeach
    </div>
</div>
@endif

{{-- ── Stats Cards ── --}}
<div class="stats-grid sch-stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <div class="val">{{ $total }}</div>
            <div class="lbl">កាលវិភាគសរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-truck"></i></div>
        <div class="stat-info">
            <div class="val">{{ $todayCount }}</div>
            <div class="lbl">កំពុងដឹកទំនិញ</div>
        </div>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="sch-toolbar">
    <form method="GET" class="sch-filter-form">
        <div>
            <label class="sch-filter-label">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="រថយន្ត / អ្នកបើកបរ / ទីតាំង"
                   class="sch-search-input">
        </div>
        <button type="submit" class="btn btn-ghost sch-btn-search-pad">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search'))
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-ghost sch-btn-clear-pad">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>

    <button class="btn btn-orange"
            onclick="document.getElementById('addScheduleModal').classList.add('open')">
        <i class="fas fa-plus"></i> បន្ថែមកាលវិភាគ
    </button>
</div>

{{-- ── Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-calendar-alt"></i>
            បញ្ជីកាលវិភាគ
            <span class="sch-count-badge">
                {{ $schedules->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="sch-col-narrow">ល.រ</th>
                    <th>រថយន្ត</th>
                    <th>អ្នកបើកបរ</th>
                    <th>ទីតាំងរថយន្ត</th>
                    <th>ឈ្មោះអតិថិជន</th>
                    <th>ស្ថានភាពថ្ងៃ</th>
                    <th class="sch-col-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                @php
                    $truckStatusStyles = [
                        'available'   => ['label'=>'ទំនេរ',           'color'=>'#065f46','bg'=>'#d1fae5'],
                        'in_progress' => ['label'=>'កំពុងដំណើរការ',   'color'=>'#1e40af','bg'=>'#dbeafe'],
                        'delivering'  => ['label'=>'កំពុងដឹកទំនិញ', 'color'=>'#92400e','bg'=>'#fef3c7'],
                        'maintenance' => ['label'=>'កំពុងជួសជុល',   'color'=>'#991b1b','bg'=>'#fee2e2'],
                    ];
                    $truckCurrentStatus = $s->truck->status ?? 'available';
                    $truckStatusStyle   = $truckStatusStyles[$truckCurrentStatus] ?? $truckStatusStyles['available'];
                @endphp
                <tr>
                    <td>
                        <span class="sch-row-num">
                            {{ ($schedules->currentPage() - 1) * $schedules->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Truck --}}
                    <td>
                        @if($s->truck)
                        <div class="sch-truck-cell">
                            <div class="sch-truck-icon-box">
                                <i class="fas fa-truck sch-truck-icon"></i>
                            </div>
                            <div>
                                <div class="sch-truck-name">
                                    {{ $s->truck->truck_name }}
                                </div>
                                <div class="sch-truck-plate">
                                    {{ $s->truck->plate_number }}
                                    @if($s->truck->capacity_ton)
                                        · {{ $s->truck->capacity_ton }} T
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Driver --}}
                    <td>
                        @if($s->driver)
                        <div class="sch-driver-cell">
                            <div class="sch-driver-avatar">
                                {{ strtoupper(mb_substr($s->driver->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="sch-driver-name">
                                    {{ $s->driver->full_name }}
                                </div>
                                <div class="sch-driver-phone">
                                    {{ $s->driver->phone ?? '' }}
                                </div>
                            </div>
                        </div>
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Location --}}
                    <td>
                        @php $activeBooking = $s->truck?->bookings->first(); @endphp
                        @if($activeBooking)
                        <div class="sch-route-cell">
                            <span class="sch-route-badge sch-route-{{ $activeBooking->booking_type }}">
                                <i class="fas {{ $activeBooking->booking_type === 'import' ? 'fa-download' : 'fa-upload' }}"></i>
                                {{ $activeBooking->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ' }}
                            </span>
                            <div class="sch-route-path">
                                <span class="sch-route-from">{{ $activeBooking->pickup_location }}</span>
                                <i class="fas fa-long-arrow-alt-right sch-route-arrow"></i>
                                <span class="sch-route-to">{{ $activeBooking->dropoff_location }}</span>
                            </div>
                        </div>
                        @elseif($s->location_truck)
                        <div class="sch-location-cell">
                            <i class="fas fa-map-marker-alt sch-location-icon"></i>
                            <span class="sch-location-text">{{ $s->location_truck }}</span>
                        </div>
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Customer name --}}
                    <td>
                        @if($activeBooking?->customer)
                        <div class="sch-avail-customer">
                            <i class="fas fa-user sch-avail-customer-icon"></i>
                            {{ $activeBooking->customer->full_name }}
                        </div>
                        @if($activeBooking->booking_date)
                        <div class="sch-avail-rel">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($activeBooking->booking_date)->format('d/m/Y') }}
                        </div>
                        @endif
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Truck status (read-only — follows truck's actual status) --}}
                    <td>
                        @if($s->truck)
                        <span class="sch-day-badge"
                              style="background:{{ $truckStatusStyle['bg'] }};color:{{ $truckStatusStyle['color'] }};border:1px solid {{ $truckStatusStyle['color'] }}33;">
                            <i class="fas fa-circle sch-badge-dot-sm"></i>
                            {{ $truckStatusStyle['label'] }}
                        </span>
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="sch-actions-cell">
                            <button class="btn btn-ghost btn-sm"
                                    onclick="openEditSchedule(
                                        {{ $s->schedule_id }},
                                        {{ $s->truck_id }},
                                        {{ $s->driver_id }},
                                        {{ json_encode($s->location_truck) }}
                                    )"
                                    title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" title="លុប"
                                    onclick="confirmDeleteSchedule({{ $s->schedule_id }}, {{ json_encode(($s->truck->truck_name ?? '—').' — '.($s->driver->full_name ?? '—')) }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="sch-empty-cell">
                        <i class="fas fa-calendar-alt sch-empty-icon"></i>
                        <div class="sch-empty-text">មិនមានកាលវិភាគ</div>
                        @if(request('search'))
                        <a href="{{ route('admin.schedules.index') }}" class="sch-empty-clear-link">
                            លុបការស្វែងរក
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($schedules->hasPages())
    <div class="sch-pagination-bar">
        <span class="sch-pagination-info">
            បង្ហាញ {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} នៃ {{ $schedules->total() }}
        </span>
        <div class="sch-pagination-pages">
            @if($schedules->onFirstPage())
                <span class="page-btn sch-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $schedules->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $schedules->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($schedules->hasMorePages())
                <a href="{{ $schedules->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn sch-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ══════════════ ADD MODAL ══════════════ --}}
<div class="modal-overlay" id="addScheduleModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-plus"></i> បន្ថែមកាលវិភាគ</h3>
            <button class="modal-close"
                    onclick="document.getElementById('addScheduleModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.schedules.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">រថយន្ត</label>
                        <select name="truck_id" class="form-control" required>
                            <option value="">-- ជ្រើសរើសរថយន្ត --</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                                @if($tr->capacity_ton)({{ $tr->capacity_ton }} T)@endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ</label>
                        <select name="driver_id" class="form-control" required>
                            <option value="">-- ជ្រើសរើសអ្នកបើកបរ --</option>
                            @foreach($drivers as $dr)
                            <option value="{{ $dr->driver_id }}">
                                {{ $dr->full_name }}
                                @if($dr->phone) · {{ $dr->phone }}@endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ទីតាំងរថយន្ត</label>
                        <input type="text" name="location_truck" class="form-control"
                               placeholder="ឧ. ភ្នំពេញ, ខេត្តកណ្ដាល">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('addScheduleModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════ EDIT MODAL ══════════════ --}}
<div class="modal-overlay" id="editScheduleModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-edit"></i> កែប្រែកាលវិភាគ</h3>
            <button class="modal-close"
                    onclick="document.getElementById('editScheduleModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editScheduleForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">រថយន្ត</label>
                        <select name="truck_id" id="es_truck" class="form-control" required>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ</label>
                        <select name="driver_id" id="es_driver" class="form-control" required>
                            @foreach($drivers as $dr)
                            <option value="{{ $dr->driver_id }}">{{ $dr->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ទីតាំងរថយន្ត</label>
                        <input type="text" name="location_truck" id="es_loc" class="form-control"
                               placeholder="ឧ. ភ្នំពេញ">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('editScheduleModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirm Modal --}}
<div class="modal-overlay confirm-overlay" id="deleteScheduleModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteScheduleForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបកាលវិភាគនេះ?</div>
                <p class="confirm-subtitle" id="deleteScheduleName"></p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteScheduleModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> លុប
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteSchedule(id, label) {
    document.getElementById('deleteScheduleForm').action = '{{ url("/admin/schedules") }}/' + id;
    document.getElementById('deleteScheduleName').textContent = label + ' — សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ';
    document.getElementById('deleteScheduleModal').classList.add('open');
}

function openEditSchedule(id, truckId, driverId, location) {
    document.getElementById('editScheduleForm').action = '{{ url("/admin/schedules") }}/' + id;
    document.getElementById('es_truck').value  = truckId  || '';
    document.getElementById('es_driver').value = driverId || '';
    document.getElementById('es_loc').value    = location || '';
    document.getElementById('editScheduleModal').classList.add('open');
}


// Auto-dismiss error alert
var schErrorBox = document.getElementById('schErrorBox');
if (schErrorBox) {
    setTimeout(function() {
        schErrorBox.style.transition = 'opacity .4s';
        schErrorBox.style.opacity = '0';
        setTimeout(function() { schErrorBox.style.display = 'none'; }, 400);
    }, 5000);
}
</script>
@endpush

@endsection
