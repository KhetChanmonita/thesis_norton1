@extends('admin.layouts.admin')
@section('title', 'កាលវិភាគ')
@section('page-title')<span>គ្រប់គ្រង</span>កាលវិភាគរថយន្ត@endsection

@section('content')

{{-- ── Stats Cards ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
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
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:14px;margin-bottom:20px;flex-wrap:wrap;">
    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="រថយន្ត / អ្នកបើកបរ / ទីតាំង"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;width:240px;outline:none;">
        </div>
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ថ្ងៃអាចប្រើ</label>
            <input type="date" name="date" value="{{ request('date') }}"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;outline:none;">
        </div>
        <button type="submit" class="btn btn-ghost" style="padding:9px 18px;">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('date'))
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-ghost" style="padding:9px 14px;">
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
            <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;font-weight:700;
                         background:#f1f5f9;color:#64748b;padding:2px 10px;border-radius:50px;margin-left:4px;">
                {{ $schedules->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">ល.រ</th>
                    <th>រថយន្ត</th>
                    <th>អ្នកបើកបរ</th>
                    <th>ទីតាំងរថយន្ត</th>
                    <th>ថ្ងៃអាចប្រើប្រាស់</th>
                    <th>ស្ថានភាពថ្ងៃ</th>
                    <th>បង្កើតថ្ងៃ</th>
                    <th style="text-align:center;">សកម្មភាព</th>
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
                        <span style="font-family:'Montserrat',sans-serif;font-weight:700;color:#94a3b8;font-size:0.82rem;">
                            {{ ($schedules->currentPage() - 1) * $schedules->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Truck --}}
                    <td>
                        @if($s->truck)
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;background:#fff3e8;border-radius:9px;
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-truck" style="color:#FF6B00;font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:#1e293b;font-size:0.88rem;">
                                    {{ $s->truck->truck_name }}
                                </div>
                                <div style="font-size:0.72rem;color:#94a3b8;">
                                    {{ $s->truck->plate_number }}
                                    @if($s->truck->capacity_ton)
                                        · {{ $s->truck->capacity_ton }} t
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Driver --}}
                    <td>
                        @if($s->driver)
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div style="width:32px;height:32px;border-radius:50%;
                                        background:linear-gradient(135deg,#667eea,#764ba2);
                                        display:flex;align-items:center;justify-content:center;
                                        font-family:'Montserrat',sans-serif;font-weight:800;
                                        font-size:0.78rem;color:#fff;flex-shrink:0;">
                                {{ strtoupper(mb_substr($s->driver->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#1e293b;font-size:0.87rem;">
                                    {{ $s->driver->full_name }}
                                </div>
                                <div style="font-size:0.72rem;color:#94a3b8;">
                                    {{ $s->driver->phone ?? '' }}
                                </div>
                            </div>
                        </div>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Location --}}
                    <td>
                        @if($s->location_truck)
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-map-marker-alt" style="color:#FF6B00;font-size:0.75rem;"></i>
                            <span style="font-size:0.87rem;color:#334155;">{{ $s->location_truck }}</span>
                        </div>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Available date --}}
                    <td>
                        @if($avail)
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-calendar" style="color:#FF6B00;font-size:0.7rem;"></i>
                            <span style="font-size:0.87rem;font-weight:600;color:#1e293b;">
                                {{ $avail->format('d/m/Y') }}
                            </span>
                        </div>
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:2px;padding-left:16px;">
                            {{ $avail->locale('km')->diffForHumans() }}
                        </div>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Day status badge --}}
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:5px;
                                     padding:4px 12px;border-radius:50px;
                                     font-size:0.75rem;font-weight:700;
                                     background:{{ $dayStatus['bg'] }};
                                     color:{{ $dayStatus['color'] }};
                                     border:1px solid {{ $dayStatus['color'] }}33;">
                            <i class="fas fa-circle" style="font-size:0.4rem;"></i>
                            {{ $dayStatus['label'] }}
                        </span>
                    </td>

                    {{-- Created date --}}
                    <td style="font-size:0.82rem;color:#94a3b8;">
                        {{ $s->created_at->format('d/m/Y') }}
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
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
                    <td colspan="8" style="text-align:center;padding:48px;color:#94a3b8;">
                        <i class="fas fa-calendar-alt"
                           style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.25;"></i>
                        <div style="font-size:0.9rem;">មិនមានកាលវិភាគ</div>
                        @if(request('search') || request('date'))
                        <a href="{{ route('admin.schedules.index') }}"
                           style="font-size:0.82rem;color:#FF6B00;margin-top:8px;display:inline-block;">
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
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:0.8rem;color:#94a3b8;">
            បង្ហាញ {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} នៃ {{ $schedules->total() }}
        </span>
        <div style="display:flex;gap:6px;">
            @if($schedules->onFirstPage())
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $schedules->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $schedules->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($schedules->hasMorePages())
                <a href="{{ $schedules->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
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
                        <label class="form-label">រថយន្ត <span style="color:#ef4444;">*</span></label>
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
                        <label class="form-label">អ្នកបើកបរ <span style="color:#ef4444;">*</span></label>
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
                        <label class="form-label">រថយន្ត <span style="color:#ef4444;">*</span></label>
                        <select name="truck_id" id="es_truck" class="form-control" required>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">អ្នកបើកបរ <span style="color:#ef4444;">*</span></label>
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
