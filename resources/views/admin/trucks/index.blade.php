@extends('admin.layouts.admin')
@section('title','រថយន្ត')
@section('page-title')<span>គ្រប់គ្រង</span>រថយន្ត@endsection

@section('content')

{{-- Validation errors — reopen the correct modal so user can see the problem --}}
@if($errors->any())
<div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:14px 18px;
             margin-bottom:16px;display:flex;align-items:flex-start;gap:10px;">
    <i class="fas fa-exclamation-circle" style="color:#dc2626;margin-top:2px;flex-shrink:0;"></i>
    <div>
        @foreach($errors->all() as $e)
        <div style="font-size:0.85rem;color:#991b1b;font-weight:600;">{{ $e }}</div>
        @endforeach
    </div>
</div>
@endif

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div style="font-size:0.85rem;color:#64748b;">
        រថយន្តសរុប: <strong>{{ $trucks->total() }}</strong>
    </div>
    <button class="btn btn-orange" onclick="document.getElementById('addTruckModal').classList.add('open')">
        <i class="fas fa-plus"></i> បន្ថែមរថយន្ត
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>លំដាប់</th>
                    <th>រូបភាពរថយន្ត</th>
                    <th>ឈ្មោះរថយន្ត</th>
                    <th>ស្លាកលេខរថយន្ត</th>
                    <th>ចំនួនភ្លៅរថយន្ត</th>
                    <th>ពណ៌រថយន្ត</th>
                    <th>ទម្ងន់រថយន្ត (តោន)</th>
                    <th>ធ្វើបច្ចុប្បន្នភាព</th>
                    <th>ស្ថានភាពរថយន្ត</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trucks as $index => $t)
                <tr>
                    {{-- Sequential number, not real ID --}}
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-weight:700;color:#64748b;">
                            {{ ($trucks->currentPage() - 1) * $trucks->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Truck image --}}
                    <td>
                        @if($t->truck_picture && file_exists(public_path($t->truck_picture)))
                            <img src="{{ asset($t->truck_picture) }}"
                                 alt="{{ $t->truck_name }}"
                                 class="truck-thumb-preview"
                                 data-src="{{ asset($t->truck_picture) }}"
                                 data-name="{{ $t->truck_name }}"
                                 style="width:80px;height:54px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;cursor:zoom-in;">
                        @else
                            <div style="width:80px;height:54px;background:#f1f5f9;border-radius:8px;border:1.5px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-truck" style="color:#cbd5e1;font-size:1.2rem;"></i>
                            </div>
                        @endif
                    </td>

                    <td><strong>{{ $t->truck_name }}</strong></td>
                    <td><span class="badge badge-new">{{ $t->plate_number }}</span></td>
                    <td>{{ $t->truck_size ?? '—' }}</td>
                    <td>{{ $t->truck_color ?? '—' }}</td>
                    <td>{{ $t->capacity_ton ? number_format($t->capacity_ton, 2).' t' : '—' }}</td>

                    {{-- Edit / Delete actions --}}
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-ghost btn-sm" onclick="editTruck({{ $t }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.trucks.destroy', $t->truck_id) }}"
                                  onsubmit="return confirm('លុបរថយន្ត {{ $t->truck_name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
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
                              style="margin:0;">
                            @csrf @method('PATCH')
                            <select name="status"
                                    onchange="this.form.submit()"
                                    style="padding:5px 10px;border-radius:20px;
                                           font-family:'Kantumruy Pro',sans-serif;font-size:0.78rem;
                                           font-weight:700;cursor:pointer;border:1.5px solid #e2e8f0;
                                           background:{{ $s['bg'] }};color:{{ $s['color'] }};
                                           outline:none;appearance:auto;">
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
                    <td colspan="9" style="text-align:center;color:#94a3b8;padding:40px;">
                        <i class="fas fa-truck" style="font-size:2rem;margin-bottom:10px;display:block;opacity:0.3;"></i>
                        មិនមានរថយន្តទេ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($trucks->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;">
        {{ $trucks->links() }}
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
                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label">រូបរថយន្ត</label>
                    <div id="addImagePreview"
                         onclick="document.getElementById('addTruckImage').click()"
                         style="position:relative;width:100%;height:200px;background:#f1f5f9;
                                border-radius:12px;overflow:hidden;cursor:pointer;
                                border:2px dashed #e2e8f0;transition:border-color 0.2s;">
                        {{-- Placeholder --}}
                        <div id="addImgPlaceholder"
                             style="position:absolute;top:0;left:0;width:100%;height:100%;
                                    display:flex;flex-direction:column;align-items:center;
                                    justify-content:center;gap:8px;">
                            <i class="fas fa-cloud-upload-alt" style="font-size:2.2rem;color:#cbd5e1;"></i>
                            <span style="font-size:0.82rem;color:#94a3b8;font-weight:600;">ចុចដើម្បីជ្រើសរើសរូបភាព</span>
                            <span style="font-size:0.72rem;color:#cbd5e1;">JPG, PNG, WEBP — អតិបរមា 2MB</span>
                        </div>
                        {{-- Preview image (shown after selection) --}}
                        <img id="addPreviewImg" src="" alt=""
                             style="position:absolute;top:0;left:0;width:100%;height:100%;
                                    object-fit:cover;display:none;">
                    </div>
                    <input type="file" name="truck_picture" id="addTruckImage"
                           accept="image/*" style="display:none;"
                           onchange="previewAdd(this)">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ឈ្មោះរថយន្ត <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="truck_name" class="form-control" placeholder="Mitsubishi FUSO" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្លាកលេខរថយន្ត <span style="color:#ef4444;">*</span></label>
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
                        <input type="number" name="capacity_ton" class="form-control" placeholder="12.5" step="0.01">
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
                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label">រូបរថយន្ត</label>

                    {{-- Preview box (display only — NOT clickable) --}}
                    <div id="editImagePreview"
                         style="position:relative;width:100%;height:220px;background:#f1f5f9;
                                border-radius:12px;overflow:hidden;
                                border:2px solid #e2e8f0;margin-bottom:10px;transition:all 0.25s;">

                        {{-- Truck image --}}
                        <img id="editCurrentImg" src="" alt=""
                             style="position:absolute;top:0;left:0;width:100%;height:100%;
                                    object-fit:contain;padding:8px;display:none;">

                        {{-- Placeholder when no image --}}
                        <div id="editImgPlaceholder"
                             style="position:absolute;top:0;left:0;width:100%;height:100%;
                                    display:flex;flex-direction:column;align-items:center;
                                    justify-content:center;gap:8px;">
                            <i class="fas fa-image" style="font-size:2.5rem;color:#cbd5e1;"></i>
                            <span style="font-size:0.82rem;color:#94a3b8;">មិនទាន់មានរូបភាព</span>
                        </div>
                    </div>

                    {{-- <label for="..."> is the most reliable cross-browser way to open file browser --}}
                    <label for="editTruckImage"
                           style="display:flex;align-items:center;justify-content:center;gap:8px;
                                  width:100%;padding:10px;background:#f1f5f9;border:1.5px dashed #cbd5e1;
                                  border-radius:9px;cursor:pointer;font-family:'Kantumruy Pro',sans-serif;
                                  font-size:0.85rem;font-weight:600;color:#64748b;transition:all 0.2s;">
                        <i class="fas fa-cloud-upload-alt" style="color:#FF6B00;font-size:1rem;"></i>
                        ជ្រើសរើស / ប្ដូររូបភាព
                    </label>
                    <input type="file" name="truck_picture" id="editTruckImage"
                           accept="image/*" style="display:none;"
                           onchange="previewEdit(this)">
                    <span id="editFileNameLabel"
                          style="display:none;font-size:0.75rem;color:#10b981;margin-top:6px;
                                 font-weight:600;display:none;">
                    </span>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ឈ្មោះរថយន្ត <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="truck_name" id="et_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ស្លាកលេខរថយន្ត <span style="color:#ef4444;">*</span></label>
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
                        <input type="number" name="capacity_ton" id="et_cap" class="form-control" step="0.01">
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
<div id="imgViewModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:99999;flex-direction:column;align-items:center;justify-content:center;gap:16px;">
    {{-- Close button --}}
    <button onclick="document.getElementById('imgViewModal').style.display='none'"
            style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,0.15);border:none;color:#fff;width:42px;height:42px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
        <i class="fas fa-times"></i>
    </button>
    <p id="imgViewLabel" style="color:rgba(255,255,255,0.7);font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;"></p>
    <img id="imgViewSrc" src=""
         style="max-width:88vw;max-height:82vh;border-radius:14px;box-shadow:0 24px 60px rgba(0,0,0,0.6);object-fit:contain;">
    <p style="color:rgba(255,255,255,0.4);font-size:0.75rem;">ចុចគ្រប់ទីកន្លែងដើម្បីបិទ</p>
</div>

@push('scripts')
<script>
/* =========================================================
   Global functions — called from HTML attributes (onclick/onchange)
   ========================================================= */

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
