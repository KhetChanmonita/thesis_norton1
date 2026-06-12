@extends('admin.layouts.admin')
@section('title', 'អ្នកបើកបរ')
@section('page-title')<span>គ្រប់គ្រង</span>អ្នកបើកបរ@endsection

@section('content')

{{-- ── Stats Cards ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
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
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:14px;margin-bottom:20px;flex-wrap:wrap;">

    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ឈ្មោះ / ទូរស័ព្ទ"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;width:220px;outline:none;">
        </div>
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ស្ថានភាព</label>
            <select name="status"
                    style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;background:#fff;outline:none;">
                <option value="">ទាំងអស់</option>
                <option value="active"   {{ request('status')==='active'   ?'selected':'' }}>កំពុងធ្វើការ</option>
                <option value="inactive" {{ request('status')==='inactive' ?'selected':'' }}>មិនសកម្ម</option>
                <option value="on_leave" {{ request('status')==='on_leave' ?'selected':'' }}>ឈប់សម្រាក</option>
            </select>
        </div>
        <button type="submit" class="btn btn-ghost" style="padding:9px 18px;">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-ghost" style="padding:9px 14px;">
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
            <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;font-weight:700;
                         background:#f1f5f9;color:#64748b;padding:2px 10px;border-radius:50px;margin-left:4px;">
                {{ $drivers->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">ល.រ</th>
                    <th>អ្នកបើកបរ</th>
                    <th>លេខទូរស័ព្ទ</th>
                    <th>ថ្ងៃចូលធ្វើការ</th>
                    <th>ស្ថានភាព</th>
                    <th>រថយន្តដែលបានកំណត់</th>
                    <th style="text-align:center;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $d)
                <tr>
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-weight:700;color:#94a3b8;font-size:0.82rem;">
                            {{ ($drivers->currentPage() - 1) * $drivers->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Driver avatar: photo fills the gradient circle, else show initial --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:42px;height:42px;border-radius:50%;
                                        background:linear-gradient(135deg,#667eea,#764ba2);
                                        display:flex;align-items:center;justify-content:center;
                                        overflow:hidden;flex-shrink:0;
                                        {{ $d->driver_picture ? 'cursor:zoom-in;' : '' }}">
                                @if($d->driver_picture)
                                    <img src="{{ asset($d->driver_picture) }}"
                                         alt="{{ $d->full_name }}"
                                         class="driver-thumb-preview"
                                         data-src="{{ asset($d->driver_picture) }}"
                                         data-name="{{ $d->full_name }}"
                                         style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <span style="font-family:'Montserrat',sans-serif;font-weight:800;
                                                 font-size:0.85rem;color:#fff;">
                                        {{ strtoupper(mb_substr($d->full_name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:700;color:#1e293b;">{{ $d->full_name }}</div>
                                <div style="font-size:0.75rem;color:#94a3b8;">ID #{{ $d->driver_id }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        @if($d->phone)
                            <a href="tel:{{ $d->phone }}" style="color:#3b82f6;text-decoration:none;font-size:0.87rem;">
                                <i class="fas fa-phone" style="font-size:0.7rem;margin-right:4px;"></i>
                                {{ $d->phone }}
                            </a>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    <td style="font-size:0.85rem;color:#475569;">
                        @if($d->hire_date)
                            <i class="fas fa-calendar" style="color:#FF6B00;font-size:0.7rem;margin-right:4px;"></i>
                            {{ \Carbon\Carbon::parse($d->hire_date)->format('d/m/Y') }}
                        @else
                            <span style="color:#cbd5e1;">—</span>
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
                            <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                            {{ $st['label'] }}
                        </span>
                    </td>

                    <td>
                        @if($d->truck)
                            <div style="display:flex;align-items:center;gap:7px;">
                                <div style="width:28px;height:28px;background:#fff3e8;border-radius:7px;
                                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-truck" style="color:#FF6B00;font-size:0.7rem;"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.83rem;font-weight:600;color:#1e293b;">{{ $d->truck->truck_name }}</div>
                                    <div style="font-size:0.72rem;color:#94a3b8;">{{ $d->truck->plate_number }}</div>
                                </div>
                            </div>
                        @else
                            <span style="color:#cbd5e1;font-size:0.83rem;">មិនទាន់កំណត់</span>
                        @endif
                    </td>

                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
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
                            <form method="POST"
                                  action="{{ route('admin.drivers.destroy', $d->driver_id) }}"
                                  onsubmit="return confirm('លុបអ្នកបើកបរ?')">
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
                    <td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                        <i class="fas fa-id-badge" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.25;"></i>
                        <div style="font-size:0.9rem;">មិនមានអ្នកបើកបរ</div>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.drivers.index') }}"
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

    @if($drivers->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:0.8rem;color:#94a3b8;">
            បង្ហាញ {{ $drivers->firstItem() }}–{{ $drivers->lastItem() }} នៃ {{ $drivers->total() }}
        </span>
        <div style="display:flex;gap:6px;">
            @if($drivers->onFirstPage())
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $drivers->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($drivers->getUrlRange(1, $drivers->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $drivers->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($drivers->hasMorePages())
                <a href="{{ $drivers->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
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
                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label">រូបអ្នកបើកបរ <span style="font-size:0.75rem;color:#94a3b8;">(ជម្រើស)</span></label>

                    {{-- Using <label for> is the most reliable way to trigger file browser --}}
                    <label for="addDriverImage" id="addDriverPreview"
                           style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                                  width:100%;height:170px;background:#f8fafc;border:2px dashed #e2e8f0;
                                  border-radius:12px;cursor:pointer;overflow:hidden;transition:border-color 0.2s;
                                  gap:10px;">
                        <div id="addDriverImgWrap"
                             style="width:90px;height:90px;border-radius:50%;
                                    background:linear-gradient(135deg,#667eea,#764ba2);
                                    display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            <i id="addDriverIcon" class="fas fa-user" style="font-size:2.2rem;color:#fff;"></i>
                            <img id="addDriverImg" src="" alt=""
                                 style="display:none;width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size:0.82rem;color:#64748b;font-weight:600;">ចុចដើម្បីបន្ថែមរូបភាព</div>
                            <div style="font-size:0.72rem;color:#94a3b8;margin-top:2px;">JPG, PNG, WEBP — អតិបរមា 2MB</div>
                        </div>
                    </label>
                    <input type="file" name="driver_picture" id="addDriverImage"
                           accept="image/*" style="display:none;"
                           onchange="previewAddDriver(this)">
                </div>

                <div class="form-grid">
                    <div class="form-group form-full">
                        <label class="form-label">ឈ្មោះពេញ <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="full_name" class="form-control"
                               placeholder="បញ្ចូលឈ្មោះពេញ" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="tel" name="phone" class="form-control" placeholder="012 345 678">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ថ្ងៃចូលធ្វើការ</label>
                        <input type="date" name="hire_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្ថានភាព <span style="color:#ef4444;">*</span></label>
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
                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label">រូបអ្នកបើកបរ</label>

                    {{-- Circular photo preview (not clickable — just display) --}}
                    <div style="display:flex;flex-direction:column;align-items:center;gap:12px;margin-bottom:4px;">
                        <div style="width:90px;height:90px;border-radius:50%;
                                    background:linear-gradient(135deg,#667eea,#764ba2);
                                    overflow:hidden;display:flex;align-items:center;justify-content:center;">
                            <i id="editDriverIcon" class="fas fa-user" style="font-size:2.2rem;color:#fff;"></i>
                            <img id="editDriverImg" src="" alt=""
                                 style="display:none;width:100%;height:100%;object-fit:cover;">
                        </div>

                        {{-- Separate upload button — <label for> triggers file browser reliably --}}
                        <label for="editDriverImage"
                               style="display:inline-flex;align-items:center;gap:8px;padding:9px 20px;
                                      background:#f1f5f9;border:1.5px dashed #cbd5e1;border-radius:9px;
                                      cursor:pointer;font-family:'Kantumruy Pro',sans-serif;
                                      font-size:0.82rem;font-weight:600;color:#64748b;transition:all 0.2s;">
                            <i class="fas fa-cloud-upload-alt" style="color:#FF6B00;"></i>
                            ជ្រើសរើស / ប្ដូររូបភាព
                        </label>
                        <span id="editDriverFileName"
                              style="font-size:0.75rem;color:#94a3b8;display:none;"></span>
                    </div>
                    <input type="file" name="driver_picture" id="editDriverImage"
                           accept="image/*" style="display:none;"
                           onchange="previewEditDriver(this)">
                </div>

                <div class="form-grid">
                    <div class="form-group form-full">
                        <label class="form-label">ឈ្មោះពេញ <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="full_name" id="ed_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="tel" name="phone" id="ed_phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ថ្ងៃចូលធ្វើការ</label>
                        <input type="date" name="hire_date" id="ed_hire" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្ថានភាព <span style="color:#ef4444;">*</span></label>
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
<div id="driverImgViewModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:99999;
            flex-direction:column;align-items:center;justify-content:center;gap:16px;">
    <button onclick="document.getElementById('driverImgViewModal').style.display='none'"
            style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,0.15);border:none;
                   color:#fff;width:42px;height:42px;border-radius:50%;font-size:18px;cursor:pointer;
                   display:flex;align-items:center;justify-content:center;">
        <i class="fas fa-times"></i>
    </button>
    <p id="driverImgViewLabel" style="color:rgba(255,255,255,0.7);font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;"></p>
    <img id="driverImgViewSrc" src=""
         style="max-width:360px;max-height:360px;border-radius:50%;box-shadow:0 24px 60px rgba(0,0,0,0.6);object-fit:cover;">
    <p style="color:rgba(255,255,255,0.4);font-size:0.75rem;">ចុចគ្រប់ទីកន្លែងដើម្បីបិទ</p>
</div>

@push('scripts')
<script>
/* =================================================
   Global functions
   ================================================= */

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
