@extends('admin.layouts.admin')
@section('title', 'អ្នកបើកបរ')
@section('page-title')<span>គ្រប់គ្រង</span>អ្នកបើកបរ@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_drivers.css') }}">
@endpush

@section('content')

{{-- ── Stats Cards ── --}}
<div class="stats-grid drv-stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-id-badge"></i></div>
        <div class="stat-info">
            <div class="val">{{ $total }}</div>
            <div class="lbl">អ្នកបើកបរសរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalActive }}</div>
            <div class="lbl">កំពុងធ្វើការ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-umbrella-beach"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalLeave }}</div>
            <div class="lbl">ឈប់សម្រាក</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalInactive }}</div>
            <div class="lbl">មិនសកម្ម</div>
        </div>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="drv-toolbar">

    <form method="GET" class="drv-filter-form">
        <div>
            <label class="drv-filter-label">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ឈ្មោះ / ទូរស័ព្ទ"
                   class="drv-search-input">
        </div>
        <div>
            <label class="drv-filter-label">ស្ថានភាព</label>
            <select name="status" class="drv-status-select">
                <option value="">ទាំងអស់</option>
                <option value="active"   {{ request('status')==='active'   ?'selected':'' }}>កំពុងធ្វើការ</option>
                <option value="inactive" {{ request('status')==='inactive' ?'selected':'' }}>មិនសកម្ម</option>
                <option value="on_leave" {{ request('status')==='on_leave' ?'selected':'' }}>ឈប់សម្រាក</option>
            </select>
        </div>
        <button type="submit" class="btn btn-ghost drv-btn-search-pad">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-ghost drv-btn-clear-pad">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>

    <button class="btn btn-orange"
            onclick="document.getElementById('addDriverModal').classList.add('open')">
        <i class="fas fa-plus"></i> បន្ថែមអ្នកបើកបរ
    </button>
</div>

{{-- ── Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-id-badge"></i>
            បញ្ជីអ្នកបើកបរ
            <span class="drv-count-badge">
                {{ $drivers->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="drv-col-narrow">ល.រ</th>
                    <th>អ្នកបើកបរ</th>
                    <th>លេខទូរស័ព្ទ</th>
                    <th>ថ្ងៃចូលធ្វើការ</th>
                    <th>ស្ថានភាព</th>
                    <th>រថយន្តដែលបានកំណត់</th>
                    <th class="drv-col-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $d)
                <tr>
                    <td>
                        <span class="drv-row-num">
                            {{ ($drivers->currentPage() - 1) * $drivers->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Driver avatar: photo fills the gradient circle, else show initial --}}
                    <td>
                        <div class="drv-driver-cell">
                            <div class="drv-avatar {{ $d->driver_picture ? 'drv-avatar-zoom' : '' }}">
                                @if($d->driver_picture)
                                    <img src="{{ asset($d->driver_picture) }}"
                                         alt="{{ $d->full_name }}"
                                         class="driver-thumb-preview drv-avatar-img"
                                         data-src="{{ asset($d->driver_picture) }}"
                                         data-name="{{ $d->full_name }}">
                                @else
                                    <span class="drv-avatar-initial">
                                        {{ strtoupper(mb_substr($d->full_name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <div class="drv-driver-name">{{ $d->full_name }}</div>
                                <div class="drv-text-muted-sm">ID D{{ $d->driver_id }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        @if($d->phone)
                            <a href="tel:{{ $d->phone }}" class="drv-phone-link">
                                <i class="fas fa-phone drv-icon-mr"></i>
                                {{ $d->phone }}
                            </a>
                        @else
                            <span class="drv-dash">—</span>
                        @endif
                    </td>

                    <td class="drv-hire-cell">
                        @if($d->hire_date)
                            <i class="fas fa-calendar drv-icon-orange-mr"></i>
                            {{ \Carbon\Carbon::parse($d->hire_date)->format('d/m/Y') }}
                        @else
                            <span class="drv-dash">—</span>
                        @endif
                    </td>

                    <td>
                        @php
                            $statusMap = [
                                'active'   => ['label'=>'កំពុងធ្វើការ', 'class'=>'badge-active'],
                                'inactive' => ['label'=>'មិនសកម្ម',    'class'=>'badge-inactive'],
                                'on_leave' => ['label'=>'ឈប់សម្រាក',   'class'=>'badge-on_leave'],
                            ];
                            $st = $statusMap[$d->status] ?? ['label'=>$d->status,'class'=>'badge-inactive'];
                        @endphp
                        <span class="badge {{ $st['class'] }}">
                            <i class="fas fa-circle drv-badge-dot"></i>
                            {{ $st['label'] }}
                        </span>
                    </td>

                    <td>
                        @if($d->truck)
                            <div class="drv-truck-cell">
                                <div class="drv-truck-icon-box">
                                    <i class="fas fa-truck drv-truck-icon"></i>
                                </div>
                                <div>
                                    <div class="drv-truck-name">{{ $d->truck->truck_name }}</div>
                                    <div class="drv-truck-plate">{{ $d->truck->plate_number }}</div>
                                </div>
                            </div>
                        @else
                            <span class="drv-truck-none">មិនទាន់កំណត់</span>
                        @endif
                    </td>

                    <td>
                        <div class="drv-actions-cell">
                            <button class="btn btn-ghost btn-sm"
                                    onclick="openEditDriver(
                                        {{ $d->driver_id }},
                                        {{ json_encode($d->full_name) }},
                                        {{ json_encode($d->phone) }},
                                        {{ json_encode($d->hire_date ? $d->hire_date->format('Y-m-d') : null) }},
                                        {{ json_encode($d->status) }},
                                        {{ json_encode($d->assigned_truck) }},
                                        {{ json_encode($d->driver_picture) }})"
                                    title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" title="លុប"
                                    onclick="confirmDeleteDriver({{ $d->driver_id }}, {{ json_encode($d->full_name) }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="drv-empty-cell">
                        <i class="fas fa-id-badge drv-empty-icon"></i>
                        <div class="drv-empty-text">មិនមានអ្នកបើកបរ</div>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.drivers.index') }}" class="drv-empty-clear-link">
                            លុបការស្វែងរក
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($drivers->hasPages())
    <div class="drv-pagination-bar">
        <span class="drv-pagination-info">
            បង្ហាញ {{ $drivers->firstItem() }}–{{ $drivers->lastItem() }} នៃ {{ $drivers->total() }}
        </span>
        <div class="drv-pagination-pages">
            @if($drivers->onFirstPage())
                <span class="page-btn drv-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $drivers->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($drivers->getUrlRange(1, $drivers->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $drivers->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($drivers->hasMorePages())
                <a href="{{ $drivers->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn drv-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ══════════════ ADD MODAL ══════════════ --}}
<div class="modal-overlay" id="addDriverModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> បន្ថែមអ្នកបើកបរ</h3>
            <button class="modal-close"
                    onclick="document.getElementById('addDriverModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.drivers.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                {{-- Profile picture upload --}}
                <div class="form-group drv-mb-20">
                    <label class="form-label">រូបអ្នកបើកបរ <span class="drv-text-muted-sm">(ជម្រើស)</span></label>

                    {{-- Using <label for> is the most reliable way to trigger file browser --}}
                    <label for="addDriverImage" id="addDriverPreview" class="drv-upload-label">
                        <div id="addDriverImgWrap" class="drv-avatar-circle-90">
                            <i id="addDriverIcon" class="fas fa-user drv-avatar-icon-lg"></i>
                            <img id="addDriverImg" src="" alt="" class="drv-avatar-img-hidden">
                        </div>
                        <div class="drv-text-center">
                            <div class="drv-upload-title">ចុចដើម្បីបន្ថែមរូបភាព</div>
                            <div class="drv-upload-hint">JPG, PNG, WEBP — អតិបរមា 2MB</div>
                        </div>
                    </label>
                    <input type="file" name="driver_picture" id="addDriverImage"
                           accept="image/*" class="d-none"
                           onchange="previewAddDriver(this)">
                </div>

                <div class="form-grid">
                    <div class="form-group form-full">
                        <label class="form-label">ឈ្មោះពេញ</label>
                        <input type="text" name="full_name" class="form-control"
                               placeholder="បញ្ចូលឈ្មោះពេញ" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" class="form-control" placeholder="012 345 678" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ថ្ងៃចូលធ្វើការ</label>
                        <input type="date" name="hire_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្ថានភាព</label>
                        <select name="status" class="form-control">
                            <option value="active">កំពុងធ្វើការ (active)</option>
                            <option value="inactive">មិនសកម្ម (inactive)</option>
                            <option value="on_leave">ឈប់សម្រាក (on_leave)</option>
                        </select>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">រថយន្តដែលបានកំណត់</label>
                        <select name="assigned_truck" class="form-control">
                            <option value="">-- មិនទាន់កំណត់ --</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                                @if($tr->capacity_ton) ({{ $tr->capacity_ton }} t) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('addDriverModal').classList.remove('open')">
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
<div class="modal-overlay" id="editDriverModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> កែប្រែអ្នកបើកបរ</h3>
            <button class="modal-close"
                    onclick="document.getElementById('editDriverModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editDriverForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body">

                {{-- Profile picture upload --}}
                <div class="form-group drv-mb-20">
                    <label class="form-label">រូបអ្នកបើកបរ</label>

                    {{-- Circular photo preview (not clickable — just display) --}}
                    <div class="drv-edit-photo-wrap">
                        <div class="drv-avatar-circle-90">
                            <i id="editDriverIcon" class="fas fa-user drv-avatar-icon-lg"></i>
                            <img id="editDriverImg" src="" alt="" class="drv-avatar-img-hidden">
                        </div>

                        {{-- Separate upload button — <label for> triggers file browser reliably --}}
                        <label for="editDriverImage" class="drv-upload-btn">
                            <i class="fas fa-cloud-upload-alt text-orange"></i>
                            ជ្រើសរើស / ប្ដូររូបភាព
                        </label>
                        <span id="editDriverFileName" class="drv-filename-label"></span>
                    </div>
                    <input type="file" name="driver_picture" id="editDriverImage"
                           accept="image/*" class="d-none"
                           onchange="previewEditDriver(this)">
                </div>

                <div class="form-grid">
                    <div class="form-group form-full">
                        <label class="form-label">ឈ្មោះពេញ</label>
                        <input type="text" name="full_name" id="ed_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" id="ed_phone" class="form-control" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ថ្ងៃចូលធ្វើការ</label>
                        <input type="date" name="hire_date" id="ed_hire" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្ថានភាព</label>
                        <select name="status" id="ed_status" class="form-control">
                            <option value="active">កំពុងធ្វើការ (active)</option>
                            <option value="inactive">មិនសកម្ម (inactive)</option>
                            <option value="on_leave">ឈប់សម្រាក (on_leave)</option>
                        </select>
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">រថយន្តដែលបានកំណត់</label>
                        <select name="assigned_truck" id="ed_truck" class="form-control">
                            <option value="">-- មិនទាន់កំណត់ --</option>
                            @foreach($trucks as $tr)
                            <option value="{{ $tr->truck_id }}">
                                {{ $tr->truck_name }} — {{ $tr->plate_number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('editDriverModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════ PHOTO PREVIEW MODAL ══════════════ --}}
<div id="driverImgViewModal" class="drv-img-view-modal">
    <button onclick="document.getElementById('driverImgViewModal').style.display='none'"
            class="drv-img-view-close">
        <i class="fas fa-times"></i>
    </button>
    <p id="driverImgViewLabel" class="drv-img-view-label"></p>
    <img id="driverImgViewSrc" src="" class="drv-img-view-img">
    <p class="drv-img-view-hint">ចុចគ្រប់ទីកន្លែងដើម្បីបិទ</p>
</div>

{{-- ══════════════ DELETE CONFIRM MODAL ══════════════ --}}
<div class="modal-overlay confirm-overlay" id="deleteDriverModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteDriverForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបអ្នកបើកបរនេះ?</div>
                <p class="confirm-subtitle" id="deleteDriverName"></p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteDriverModal').classList.remove('open')">
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
/* =================================================
   Global functions
   ================================================= */

function confirmDeleteDriver(id, name) {
    document.getElementById('deleteDriverForm').action = '{{ url("/admin/drivers") }}/' + id;
    document.getElementById('deleteDriverName').textContent = name + ' — សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ';
    document.getElementById('deleteDriverModal').classList.add('open');
}

function previewAddDriver(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('addDriverIcon').style.display = 'none';
        var img = document.getElementById('addDriverImg');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}

function openEditDriver(id, name, phone, hire_date, status, assigned_truck, driver_picture) {
    document.getElementById('editDriverForm').action = '{{ url("/admin/drivers") }}/' + id;
    document.getElementById('ed_name').value   = name   || '';
    document.getElementById('ed_phone').value  = phone  || '';
    document.getElementById('ed_hire').value   = hire_date || '';
    document.getElementById('ed_status').value = status || 'active';
    document.getElementById('ed_truck').value  = assigned_truck || '';

    // Reset file input and filename label
    document.getElementById('editDriverImage').value = '';
    document.getElementById('editDriverFileName').style.display = 'none';
    document.getElementById('editDriverFileName').textContent   = '';

    var img  = document.getElementById('editDriverImg');
    var icon = document.getElementById('editDriverIcon');

    if (driver_picture) {
        img.src          = '{{ asset("") }}' + driver_picture;
        img.style.display = 'block';
        icon.style.display = 'none';
    } else {
        img.style.display  = 'none';
        icon.style.display = 'block';
    }

    document.getElementById('editDriverModal').classList.add('open');
}

function previewEditDriver(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        var img  = document.getElementById('editDriverImg');
        var icon = document.getElementById('editDriverIcon');
        img.src          = e.target.result;
        img.style.display = 'block';
        icon.style.display = 'none';
    };
    reader.readAsDataURL(file);
    // Show filename so user knows a file was selected
    var lbl = document.getElementById('editDriverFileName');
    lbl.textContent   = file.name;
    lbl.style.display = 'block';
}

/* =================================================
   DOM-ready listeners
   ================================================= */
document.addEventListener('DOMContentLoaded', function () {

    /* Driver photo thumbnail → full-screen preview */
    document.querySelectorAll('.driver-thumb-preview').forEach(function (img) {
        img.addEventListener('click', function () {
            document.getElementById('driverImgViewSrc').src          = this.getAttribute('data-src');
            document.getElementById('driverImgViewLabel').textContent = this.getAttribute('data-name');
            document.getElementById('driverImgViewModal').style.display = 'flex';
        });
    });

    /* Close photo preview on backdrop click */
    document.getElementById('driverImgViewModal').addEventListener('click', function (e) {
        if (e.target === this || e.target.nodeName === 'P') {
            this.style.display = 'none';
        }
    });

    /* Close photo preview on Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('driverImgViewModal').style.display = 'none';
        }
    });

}); /* end DOMContentLoaded */
</script>
@endpush

@endsection
