@extends('admin.layouts.admin')
@section('title','រថយន្ត')
@section('page-title')<span>គ្រប់គ្រង</span>រថយន្ត@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_trucks.css') }}">
@endpush

@section('content')

{{-- Validation errors — reopen the correct modal so user can see the problem --}}
@if($errors->any())
<div class="trk-error-box">
    <i class="fas fa-exclamation-circle trk-error-icon"></i>
    <div>
        @foreach($errors->all() as $e)
        <div class="trk-error-text">{{ $e }}</div>
        @endforeach
    </div>
</div>
@endif

{{-- ── Stats Cards ── --}}
<div class="stats-grid trk-stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-truck"></i></div>
        <div class="stat-info">
            <div class="val">{{ $total }}</div>
            <div class="lbl">រថយន្តសរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalAvailable }}</div>
            <div class="lbl">ទំនេរ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-route"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalInProgress }}</div>
            <div class="lbl">កំពុងដំណើរការ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-shipping-fast"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalDelivering }}</div>
            <div class="lbl">កំពុងដឹកទំនិញ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-tools"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalMaintenance }}</div>
            <div class="lbl">កំពុងជួសជុល</div>
        </div>
    </div>
</div>

<div class="trk-toolbar">
    <div class="trk-toolbar-start">
        <form method="GET" class="trk-filter-form">
            <div>
                <label class="trk-filter-label">ស្វែងរក</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ស្លាកលេខរថយន្ត"
                       class="trk-search-input">
            </div>
            <button type="submit" class="btn btn-ghost trk-btn-search-pad">
                <i class="fas fa-search"></i> ស្វែងរក
            </button>
            @if(request('search'))
            <a href="{{ route('admin.trucks.index') }}" class="btn btn-ghost trk-btn-clear-pad">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </form>
    </div>

    <button class="btn btn-orange" onclick="document.getElementById('addTruckModal').classList.add('open')">
        <i class="fas fa-plus"></i> បន្ថែមរថយន្ត
    </button>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-truck"></i>
            បញ្ជីរថយន្ត
            <span class="trk-count-badge">
                {{ $trucks->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>រូបភាពរថយន្ត</th>
                    <th>ឈ្មោះរថយន្ត</th>
                    <th>ស្លាកលេខរថយន្ត</th>
                    <th>ចំនួនភ្លៅរថយន្ត</th>
                    <th>ពណ៌រថយន្ត</th>
                    <th>ទម្ងន់រថយន្ត (តោន)</th>
                    <th>អ្នកបើកបរ</th>
                    <th>ធ្វើបច្ចុប្បន្នភាព</th>
                    <th>ស្ថានភាពរថយន្ត</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trucks as $index => $t)
                <tr>
                    {{-- Sequential number, not real ID --}}
                    <td>
                        <span class="trk-row-num">
                            {{ ($trucks->currentPage() - 1) * $trucks->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Truck image --}}
                    <td>
                        @if($t->truck_picture && file_exists(public_path($t->truck_picture)))
                            <img src="{{ asset($t->truck_picture) }}"
                                 alt="{{ $t->truck_name }}"
                                 class="truck-thumb-preview trk-thumb"
                                 data-src="{{ asset($t->truck_picture) }}"
                                 data-name="{{ $t->truck_name }}">
                        @else
                            <div class="trk-thumb-placeholder">
                                <i class="fas fa-truck trk-thumb-icon"></i>
                            </div>
                        @endif
                    </td>

                    <td><strong>{{ $t->truck_name }}</strong></td>
                    <td><span class="badge badge-new">{{ $t->plate_number }}</span></td>
                    <td>{{ $t->truck_size ?? '—' }}</td>
                    <td>{{ $t->truck_color ?? '—' }}</td>
                    <td>{{ $t->capacity_ton ? number_format($t->capacity_ton, 2).' T' : '—' }}</td>

                    {{-- Driver --}}
                    <td>
                        @if($t->drivers->isEmpty())
                            មិនទាន់មានអ្នកបើកបរ
                        @else
                            @foreach($t->drivers as $d)
                                {{ $d->full_name }}@if(!$loop->last), @endif
                            @endforeach
                        @endif
                    </td>

                    {{-- Edit / Delete actions --}}
                    <td>
                        <div class="trk-actions-cell">
                            <button class="btn btn-ghost btn-sm" onclick="editTruck({{ $t }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm"
                                    onclick="confirmDeleteTruck({{ $t->truck_id }}, {{ json_encode($t->truck_name) }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>

                    {{-- Status column — dropdown --}}
                    <td>
                        @php
                            $current = $t->status ?? 'available';
                            $styles  = [
                                'available'   => ['label'=>'ទំនេរ',           'color'=>'#065f46','bg'=>'#d1fae5'],
                                'in_progress' => ['label'=>'កំពុងដំណើរការ',   'color'=>'#1e40af','bg'=>'#dbeafe'],
                                'delivering'  => ['label'=>'កំពុងដឹកទំនិញ', 'color'=>'#92400e','bg'=>'#fef3c7'],
                                'maintenance' => ['label'=>'កំពុងជួសជុល',   'color'=>'#991b1b','bg'=>'#fee2e2'],
                            ];
                            $s = $styles[$current] ?? $styles['available'];
                        @endphp
                        <form method="POST"
                              action="{{ route('admin.trucks.status', $t->truck_id) }}"
                              class="trk-form-inline">
                            @csrf @method('PATCH')
                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="trk-status-select"
                                    style="background:{{ $s['bg'] }};color:{{ $s['color'] }};">
                                <option value="available"   {{ $current==='available'   ?'selected':'' }}>ទំនេរ</option>
                                <option value="in_progress" {{ $current==='in_progress' ?'selected':'' }}>កំពុងដំណើរការ</option>
                                <option value="delivering"  {{ $current==='delivering'  ?'selected':'' }}>កំពុងដឹកទំនិញ</option>
                                <option value="maintenance" {{ $current==='maintenance' ?'selected':'' }}>កំពុងជួសជុល</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="trk-empty-cell">
                        <i class="fas fa-truck trk-empty-icon"></i>
                        មិនមានរថយន្តទេ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($trucks->hasPages())
    <div class="trk-pagination-bar">
        <span class="trk-pagination-info">
            បង្ហាញ {{ $trucks->firstItem() }}–{{ $trucks->lastItem() }} នៃ {{ $trucks->total() }}
        </span>
        <div class="trk-pagination-pages">
            @if($trucks->onFirstPage())
                <span class="page-btn trk-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $trucks->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($trucks->getUrlRange(1, $trucks->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $trucks->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($trucks->hasMorePages())
                <a href="{{ $trucks->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn trk-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ===== ADD MODAL ===== --}}
<div class="modal-overlay" id="addTruckModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-truck"></i> បន្ថែមរថយន្ត</h3>
            <button class="modal-close" onclick="document.getElementById('addTruckModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.trucks.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                {{-- Image upload --}}
                <div class="form-group trk-mb-20">
                    <label class="form-label">រូបរថយន្ត</label>
                    <div id="addImagePreview"
                         onclick="document.getElementById('addTruckImage').click()"
                         class="trk-img-preview">
                        {{-- Placeholder --}}
                        <div id="addImgPlaceholder" class="trk-img-placeholder">
                            <i class="fas fa-cloud-upload-alt trk-upload-icon"></i>
                            <span class="trk-upload-text">ចុចដើម្បីជ្រើសរើសរូបភាព</span>
                            <span class="trk-upload-hint">JPG, PNG, WEBP — អតិបរមា 2MB</span>
                        </div>
                        {{-- Preview image (shown after selection) --}}
                        <img id="addPreviewImg" src="" alt="" class="trk-img-preview-img">
                    </div>
                    <input type="file" name="truck_picture" id="addTruckImage"
                           accept="image/*" class="d-none"
                           onchange="previewAdd(this)">
                    <small class="trk-upload-hint-text">
                        <i class="fas fa-folder-open trk-upload-folder-icon"></i>
                        រូបភាពនឹងត្រូវបានរក្សាទុកនៅក្នុង: <code class="trk-upload-code">public/images/trucks/</code>
                    </small>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ឈ្មោះរថយន្ត</label>
                        <input type="text" name="truck_name" class="form-control" placeholder="Mitsubishi FUSO" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្លាកលេខរថយន្ត</label>
                        <input type="text" name="plate_number" class="form-control" placeholder="ភន 1234" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំនួនភ្លៅរថយន្ត</label>
                        <input type="text" name="truck_size" class="form-control" placeholder="12000Kg">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ពណ៌រថយន្ត</label>
                        <input type="text" name="truck_color" class="form-control" placeholder="ពណ៌បៃតង">
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">ទម្ងន់រថយន្ត (តោន)</label>
                        <input type="text" name="capacity_ton" class="form-control" placeholder="12.5" inputmode="decimal">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('addTruckModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== EDIT MODAL ===== --}}
<div class="modal-overlay" id="editTruckModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> កែប្រែរថយន្ត</h3>
            <button class="modal-close" onclick="document.getElementById('editTruckModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editTruckForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body">

                {{-- Image upload --}}
                <div class="form-group trk-mb-20">
                    <label class="form-label">រូបរថយន្ត</label>

                    {{-- Preview box (display only — NOT clickable) --}}
                    <div id="editImagePreview" class="trk-edit-img-preview">

                        {{-- Truck image --}}
                        <img id="editCurrentImg" src="" alt="" class="trk-edit-img-current">

                        {{-- Placeholder when no image --}}
                        <div id="editImgPlaceholder" class="trk-edit-img-placeholder">
                            <i class="fas fa-image trk-edit-placeholder-icon"></i>
                            <span class="trk-edit-placeholder-text">មិនទាន់មានរូបភាព</span>
                        </div>
                    </div>

                    {{-- <label for="..."> is the most reliable cross-browser way to open file browser --}}
                    <label for="editTruckImage" class="trk-upload-label">
                        <i class="fas fa-cloud-upload-alt trk-upload-label-icon"></i>
                        ជ្រើសរើស / ប្ដូររូបភាព
                    </label>
                    <input type="file" name="truck_picture" id="editTruckImage"
                           accept="image/*" class="d-none"
                           onchange="previewEdit(this)">
                    <span id="editFileNameLabel" class="trk-filename-label">
                    </span>
                    <small class="trk-upload-hint-text">
                        <i class="fas fa-folder-open trk-upload-folder-icon"></i>
                        រូបភាពនឹងត្រូវបានរក្សាទុកនៅក្នុង: <code class="trk-upload-code">public/images/trucks/</code>
                    </small>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ឈ្មោះរថយន្ត</label>
                        <input type="text" name="truck_name" id="et_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្លាកលេខរថយន្ត</label>
                        <input type="text" name="plate_number" id="et_plate" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ចំនួនភ្លៅរថយន្ត</label>
                        <input type="text" name="truck_size" id="et_size" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ពណ៌រថយន្ត</label>
                        <input type="text" name="truck_color" id="et_color" class="form-control">
                    </div>
                    <div class="form-group form-full">
                        <label class="form-label">ទម្ងន់រថយន្ត (តោន)</label>
                        <input type="text" name="capacity_ton" id="et_cap" class="form-control" inputmode="decimal">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('editTruckModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== IMAGE PREVIEW MODAL ===== --}}
<div id="imgViewModal" class="trk-img-view-modal">
    {{-- Close button --}}
    <button onclick="document.getElementById('imgViewModal').style.display='none'"
            class="trk-img-view-close">
        <i class="fas fa-times"></i>
    </button>
    <p id="imgViewLabel" class="trk-img-view-label"></p>
    <img id="imgViewSrc" src="" class="trk-img-view-img">
    <p class="trk-img-view-hint">ចុចគ្រប់ទីកន្លែងដើម្បីបិទ</p>
</div>

{{-- ===== DELETE CONFIRM MODAL ===== --}}
<div class="modal-overlay confirm-overlay" id="deleteTruckModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteTruckForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបរថយន្តនេះ?</div>
                <p class="confirm-subtitle" id="deleteTruckName"></p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteTruckModal').classList.remove('open')">
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
/* =========================================================
   Global functions — called from HTML attributes (onclick/onchange)
   ========================================================= */

function confirmDeleteTruck(id, name) {
    document.getElementById('deleteTruckForm').action = '{{ url("/admin/trucks") }}/' + id;
    document.getElementById('deleteTruckName').textContent = name + ' — សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ';
    document.getElementById('deleteTruckModal').classList.add('open');
}

function previewAdd(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var img     = document.getElementById('addPreviewImg');
        var holder  = document.getElementById('addImgPlaceholder');
        img.src              = e.target.result;
        img.style.display    = 'block';
        holder.style.display = 'none';
        document.getElementById('addImagePreview').style.borderColor = '#FF6B00';
    };
    reader.readAsDataURL(input.files[0]);
}

function editTruck(t) {
    document.getElementById('editTruckForm').action = '{{ url("/admin/trucks") }}/' + t.truck_id;
    document.getElementById('et_name').value  = t.truck_name  || '';
    document.getElementById('et_plate').value = t.plate_number || '';
    document.getElementById('et_size').value  = t.truck_size   || '';
    document.getElementById('et_color').value = t.truck_color  || '';
    document.getElementById('et_cap').value   = t.capacity_ton || '';

    // Reset file input and filename label so old selection doesn't carry over
    document.getElementById('editTruckImage').value = '';
    var lbl = document.getElementById('editFileNameLabel');
    if (lbl) { lbl.style.display = 'none'; lbl.textContent = ''; }

    var img  = document.getElementById('editCurrentImg');
    var hold = document.getElementById('editImgPlaceholder');
    var wrap = document.getElementById('editImagePreview');

    if (t.truck_picture) {
        img.src              = '{{ asset("") }}' + t.truck_picture;
        img.style.display    = 'block';
        hold.style.display   = 'none';
        wrap.style.background = '#1e293b';
        wrap.style.border     = '2px solid #334155';
    } else {
        img.style.display    = 'none';
        hold.style.display   = 'flex';
        wrap.style.background = '#f1f5f9';
        wrap.style.border     = '2px dashed #e2e8f0';
    }
    document.getElementById('editTruckModal').classList.add('open');
}

function previewEdit(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        var img    = document.getElementById('editCurrentImg');
        var holder = document.getElementById('editImgPlaceholder');
        var wrap   = document.getElementById('editImagePreview');
        img.src              = e.target.result;
        img.style.display    = 'block';
        holder.style.display = 'none';
        wrap.style.background = '#1e293b';
        wrap.style.border     = '2px solid #334155';
    };
    reader.readAsDataURL(file);
    // Show selected filename so admin knows the file was picked
    var lbl = document.getElementById('editFileNameLabel');
    if (lbl) {
        lbl.textContent   = '✓ ' + file.name;
        lbl.style.display = 'block';
    }
}

/* =========================================================
   DOM-ready listeners
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {

    /* Image thumbnail → full-screen preview */
    document.querySelectorAll('.truck-thumb-preview').forEach(function (img) {
        img.addEventListener('click', function () {
            document.getElementById('imgViewSrc').src           = this.getAttribute('data-src');
            document.getElementById('imgViewLabel').textContent  = this.getAttribute('data-name');
            document.getElementById('imgViewModal').style.display = 'flex';
        });
    });

    /* Close preview when clicking the dark backdrop */
    document.getElementById('imgViewModal').addEventListener('click', function (e) {
        if (e.target === this || e.target.nodeName === 'P') {
            this.style.display = 'none';
        }
    });

    /* Close preview with Escape key */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('imgViewModal').style.display = 'none';
        }
    });

}); /* end DOMContentLoaded */
</script>
@endpush

@endsection
