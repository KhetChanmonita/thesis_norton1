@extends('admin.layouts.admin')
@section('title', 'កាលវិភាគ')
@section('page-title')<span>គ្រប់គ្រង</span>កាលវិភាគរថយន្ត@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_schedules.css') }}">
@endpush

@section('content')

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
        <div class="stat-icon orange"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <div class="val">{{ $todayCount }}</div>
            <div class="lbl">ថ្ងៃនេះ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="val">{{ $upcomingCount }}</div>
            <div class="lbl">នាពេលខាងមុខ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-history"></i></div>
        <div class="stat-info">
            <div class="val">{{ $pastCount }}</div>
            <div class="lbl">ផុតកំណត់</div>
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
        <div>
            <label class="sch-filter-label">ថ្ងៃអាចប្រើ</label>
            <input type="date" name="date" value="{{ request('date') }}"
                   class="sch-date-input">
        </div>
        <button type="submit" class="btn btn-ghost sch-btn-search-pad">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('date'))
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
                    <th>ថ្ងៃអាចប្រើប្រាស់</th>
                    <th>ស្ថានភាពថ្ងៃ</th>
                    <th>បង្កើតថ្ងៃ</th>
                    <th class="sch-col-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                @php
                    $avail = $s->date_of_truck_available
                        ? \Carbon\Carbon::parse($s->date_of_truck_available)
                        : null;

                    if (!$avail) {
                        $dayStatus = ['label'=>'មិនកំណត់', 'color'=>'#94a3b8', 'bg'=>'#f8fafc'];
                    } elseif ($avail->isToday()) {
                        $dayStatus = ['label'=>'ថ្ងៃនេះ', 'color'=>'#d97706', 'bg'=>'#fffbeb'];
                    } elseif ($avail->isFuture()) {
                        $dayStatus = ['label'=>'នាពេលខាងមុខ', 'color'=>'#059669', 'bg'=>'#ecfdf5'];
                    } else {
                        $dayStatus = ['label'=>'ផុតកំណត់', 'color'=>'#dc2626', 'bg'=>'#fef2f2'];
                    }
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
                                        · {{ $s->truck->capacity_ton }} t
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
                        @if($s->location_truck)
                        <div class="sch-location-cell">
                            <i class="fas fa-map-marker-alt sch-location-icon"></i>
                            <span class="sch-location-text">{{ $s->location_truck }}</span>
                        </div>
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Available date --}}
                    <td>
                        @if($avail)
                        <div class="sch-avail-cell">
                            <i class="fas fa-calendar sch-avail-icon"></i>
                            <span class="sch-avail-date">
                                {{ $avail->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="sch-avail-rel">
                            {{ $avail->locale('km')->diffForHumans() }}
                        </div>
                        @else
                            <span class="sch-dash">—</span>
                        @endif
                    </td>

                    {{-- Day status badge --}}
                    <td>
                        <span class="sch-day-badge"
                              style="background:{{ $dayStatus['bg'] }};color:{{ $dayStatus['color'] }};border:1px solid {{ $dayStatus['color'] }}33;">
                            <i class="fas fa-circle sch-badge-dot-sm"></i>
                            {{ $dayStatus['label'] }}
                        </span>
                    </td>

                    {{-- Created date --}}
                    <td class="sch-created-cell">
                        {{ $s->created_at->format('d/m/Y') }}
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="sch-actions-cell">
                            <button class="btn btn-ghost btn-sm"
                                    onclick="openEditSchedule(
                                        {{ $s->schedule_id }},
                                        {{ $s->truck_id }},
                                        {{ $s->driver_id }},
                                        {{ json_encode($s->location_truck) }},
                                        {{ json_encode($s->date_of_truck_available ? \Carbon\Carbon::parse($s->date_of_truck_available)->format('Y-m-d') : null) }}
                                    )"
                                    title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST"
                                  action="{{ route('admin.schedules.destroy', $s->schedule_id) }}"
                                  onsubmit="return confirm('លុបកាលវិភាគនេះ?')">
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
                    <td colspan="8" class="sch-empty-cell">
                        <i class="fas fa-calendar-alt sch-empty-icon"></i>
                        <div class="sch-empty-text">មិនមានកាលវិភាគ</div>
                        @if(request('search') || request('date'))
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
                        <label class="form-label">រថយន្ត <span class="sch-required">*</span></label>
                        <select name="truck_id" class="form-control" required>
                            <option value="">-- ជ្រើសរើសរថយន្ត --</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                                @if($tr->capacity_ton)({{ $tr->capacity_ton }} t)@endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ <span class="sch-required">*</span></label>
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
                    <div class="form-group">
                        <label class="form-label">ថ្ងៃអាចប្រើប្រាស់</label>
                        <input type="date" name="date_of_truck_available" class="form-control"
                               min="{{ date('Y-m-d') }}">
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
                        <label class="form-label">រថយន្ត <span class="sch-required">*</span></label>
                        <select name="truck_id" id="es_truck" class="form-control" required>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ <span class="sch-required">*</span></label>
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
                    <div class="form-group">
                        <label class="form-label">ថ្ងៃអាចប្រើប្រាស់</label>
                        <input type="date" name="date_of_truck_available" id="es_date" class="form-control">
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

@push('scripts')
<script>
function openEditSchedule(id, truckId, driverId, location, date) {
    document.getElementById('editScheduleForm').action = '{{ url("/admin/schedules") }}/' + id;
    document.getElementById('es_truck').value  = truckId  || '';
    document.getElementById('es_driver').value = driverId || '';
    document.getElementById('es_loc').value    = location || '';
    document.getElementById('es_date').value   = date     || '';
    document.getElementById('editScheduleModal').classList.add('open');
}
</script>
@endpush

@endsection
