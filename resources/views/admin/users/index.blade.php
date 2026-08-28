@extends('admin.layouts.admin')
@section('title','គ្រប់គ្រងគណនី')
@section('page-title')<span>គ្រប់គ្រង</span>គណនីប្រព័ន្ធ@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_bookings.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin_reports.css') }}">
<style>
/* ══════════════════════════════════════════════
   Users page — .usr-* component layer
══════════════════════════════════════════════ */

/* ── 5-column stat grid ───────────────────── */
.usr-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) { .usr-stats { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 700px)  { .usr-stats { grid-template-columns: repeat(2, 1fr); } }

/* ── Role-colored avatars ─────────────────── */
.usr-ava {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem; flex-shrink: 0;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: .02em;
}
.usr-ava-admin       { background: #ede9fe; color: #6d28d9; }
.usr-ava-operation       { background: #dbeafe; color: #1d4ed8; }
.usr-ava-accountant  { background: #d1fae5; color: #047857; }
.usr-ava-driver      { background: #fef3c7; color: #b45309; }

/* ── Role pill badges ─────────────────────── */
.usr-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .7rem; font-weight: 700; letter-spacing: .04em;
    white-space: nowrap;
}
.usr-pill i { font-size: .62rem; }
.usr-pill-admin       { background: #ede9fe; color: #6d28d9; }
.usr-pill-operation       { background: #dbeafe; color: #1d4ed8; }
.usr-pill-accountant  { background: #d1fae5; color: #047857; }
.usr-pill-driver      { background: #fef3c7; color: #b45309; }

/* ── Driver chip ──────────────────────────── */
.usr-driver-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: #fff3e8; color: #c2410c;
    border-radius: 12px; padding: 2px 9px;
    font-size: .72rem; font-weight: 600;
}

/* ── Table name cell ──────────────────────── */
.usr-name-cell { display: flex; align-items: center; gap: 10px; }
.usr-name-body { min-width: 0; }
.usr-username  { font-weight: 600; color: #1e293b; font-size: .875rem; line-height: 1.2; }
.usr-email-sub { font-size: .76rem; color: #94a3b8; margin-top: 1px; }

/* ── Action buttons ───────────────────────── */
.usr-actions { display: flex; align-items: center; gap: 6px; justify-content: center; }

/* ── Modal section headers ────────────────── */
.usr-section-lbl {
    font-size: .68rem; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .08em;
    margin: 16px 0 10px;
    display: flex; align-items: center; gap: 6px;
}
.usr-section-lbl::after {
    content: ''; flex: 1; height: 1px; background: #f1f5f9;
}

/* ── Driver toggle field ──────────────────── */
.usr-driver-field { display: none; }
.usr-driver-field.show { display: block; }

/* ── Invalid field highlight ─────────────── */
.is-invalid { border-color: #ef4444 !important; }
.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.15) !important; }

/* ── Empty state ──────────────────────────── */
.usr-empty {
    text-align: center; padding: 64px 20px; color: #94a3b8;
}
.usr-empty-icon {
    font-size: 2.8rem; margin-bottom: 14px; display: block;
    opacity: .35;
}
</style>
@endpush

@section('content')
@php
$roleConfig = [
    'admin'      => ['label'=>'Admin',      'icon'=>'fa-shield-alt', 'pill'=>'usr-pill-admin',      'ava'=>'usr-ava-admin',      'grad'=>'linear-gradient(135deg,#6d28d9,#a78bfa)'],
    'operation'      => ['label'=>'Operation',  'icon'=>'fa-user-tie',   'pill'=>'usr-pill-operation',      'ava'=>'usr-ava-operation',      'grad'=>'linear-gradient(135deg,#1d4ed8,#60a5fa)'],
    'accountant' => ['label'=>'Accountant', 'icon'=>'fa-calculator', 'pill'=>'usr-pill-accountant', 'ava'=>'usr-ava-accountant', 'grad'=>'linear-gradient(135deg,#047857,#34d399)'],
    'driver'     => ['label'=>'Driver',     'icon'=>'fa-id-badge',   'pill'=>'usr-pill-driver',     'ava'=>'usr-ava-driver',     'grad'=>'linear-gradient(135deg,#b45309,#fbbf24)'],
];
$totalUsers = $roleCounts->sum();
@endphp

{{-- ── VALIDATION ERRORS ────────────────────────────── --}}
@if($errors->any())
<div class="alert alert-error" style="margin-bottom:20px;">
    <i class="fas fa-exclamation-circle"></i>
    <span>
        @foreach($errors->all() as $err)
            {{ $err }}@if(!$loop->last) &nbsp;·&nbsp; @endif
        @endforeach
    </span>
</div>
@endif

{{-- ── STAT CARDS ──────────────────────────────────── --}}
<div class="usr-stats">
    {{-- Total --}}
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:linear-gradient(135deg,#FF6B00,#ff9040);">
            <i class="fas fa-users-cog"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num">{{ $totalUsers }}</div>
            <div class="bks-stat-lbl">គណនីសរុប</div>
        </div>
    </div>

    {{-- Per-role --}}
    @foreach($roleConfig as $roleKey => $cfg)
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:{{ $cfg['grad'] }};">
            <i class="fas {{ $cfg['icon'] }}"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num">{{ $roleCounts[$roleKey] ?? 0 }}</div>
            <div class="bks-stat-lbl">{{ $cfg['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── TOOLBAR ──────────────────────────────────────── --}}
<div class="bks-toolbar">
    <form method="GET" class="bks-filter" style="flex:1;min-width:0;">
        <div class="bks-filter-group" style="flex:2;min-width:200px;">
            <i class="fas fa-search bks-filter-icon"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ស្វែងរកឈ្មោះ ឬ អ៊ីមែល..."
                   class="bks-filter-input">
        </div>
        <div class="bks-filter-group bks-filter-group-select">
            <select name="role" class="bks-filter-select">
                <option value="">-- សិទ្ធិទាំងអស់ --</option>
                @foreach($roleConfig as $k => $cfg)
                <option value="{{ $k }}" {{ request('role') == $k ? 'selected' : '' }}>{{ $cfg['label'] }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bks-btn-search">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('role'))
        <a href="{{ route('admin.users.index') }}" class="bks-btn-clear" title="លុបតម្រង">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
    <button class="bks-btn-new"
            onclick="document.getElementById('addUserModal').classList.add('open')">
        <i class="fas fa-plus"></i> បន្ថែមគណនី
    </button>
</div>

{{-- ── USER TABLE ───────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-user-cog"></i> បញ្ជីគណនីប្រព័ន្ធ
            <span class="bks-count-badge">{{ $users->total() }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:46px;">ល.រ</th>
                    <th>ឈ្មោះ / អ៊ីមែល</th>
                    <th>ទូរស័ព្ទ</th>
                    <th>សិទ្ធិ</th>
                    <th>បង្កើតនៅ</th>
                    <th class="rpt-col-center" style="width:90px;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                @php
                    $cfg = $roleConfig[$u->role] ?? ['label'=>$u->role,'icon'=>'fa-user','pill'=>'','ava'=>'','grad'=>''];
                @endphp
                <tr>
                    <td><span class="rpt-row-num">{{ $users->firstItem() + $i }}</span></td>

                    <td>
                        <div class="usr-name-cell">
                            <div class="usr-ava {{ $cfg['ava'] }}">
                                {{ strtoupper(substr($u->user_name, 0, 2)) }}
                            </div>
                            <div class="usr-name-body">
                                <div class="usr-username">{{ $u->user_name }}</div>
                                <div class="usr-email-sub">{{ $u->email ?? '—' }}</div>
                            </div>
                        </div>
                    </td>

                    <td style="font-size:.82rem;color:#64748b;">{{ $u->phone ?? '—' }}</td>

                    <td>
                        <span class="usr-pill {{ $cfg['pill'] }}">
                            <i class="fas {{ $cfg['icon'] }}"></i>
                            {{ $cfg['label'] }}
                        </span>
                    </td>

                    <td style="font-size:.78rem;color:#94a3b8;font-variant-numeric:tabular-nums;">
                        {{ $u->created_at ? $u->created_at->format('d/m/Y') : '—' }}
                    </td>

                    <td>
                        <div class="usr-actions">
                            <button class="btn btn-ghost btn-sm" title="កែប្រែ"
                                    onclick="openEditUser(
                                        {{ $u->user_id }},
                                        '{{ addslashes($u->user_name) }}',
                                        '{{ addslashes($u->email ?? '') }}',
                                        '{{ addslashes($u->phone ?? '') }}',
                                        '{{ $u->role }}',
                                        {{ $u->driver_id ?? 'null' }}
                                    )">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($u->user_id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u->user_id) }}"
                                  onsubmit="return confirm('លុបគណនី {{ addslashes($u->user_name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="លុប">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-ghost btn-sm bkg-btn-disabled" disabled title="មិនអាចលុបខ្លួនឯង">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="usr-empty">
                            <i class="fas fa-user-slash usr-empty-icon"></i>
                            <div style="font-size:.9rem;font-weight:600;margin-bottom:4px;">មិនមានគណនី</div>
                            <div style="font-size:.8rem;">
                                @if(request('search') || request('role'))
                                    រកមិនឃើញតាមលក្ខខណ្ឌស្វែងរក
                                @else
                                    មិនទាន់មានគណនីប្រព័ន្ធ
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════
     ADD MODAL
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="addUserModal">
    <div class="modal-box bkg-modal-md">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus" style="color:#FF6B00;"></i> បន្ថែមគណនី</h3>
            <button class="modal-close"
                    onclick="document.getElementById('addUserModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-body">

                <div class="usr-section-lbl"><i class="fas fa-user"></i> ព័ត៌មានផ្ទាល់ខ្លួន</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ឈ្មោះ <span class="req">*</span></label>
                        <input type="text" name="user_name" class="form-control @error('user_name') is-invalid @enderror"
                               value="{{ old('user_name') }}" required placeholder="ឧ. Chan Dara">
                        @error('user_name')<div style="color:#ef4444;font-size:.75rem;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">ទូរស័ព្ទ</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone') }}" placeholder="ឧ. 012 345 678">
                    </div>
                </div>

                <div class="usr-section-lbl"><i class="fas fa-lock"></i> ព័ត៌មានចូល</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">អ៊ីមែល <span class="req">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required placeholder="example@email.com">
                        @error('email')<div style="color:#ef4444;font-size:.75rem;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">ពាក្យសម្ងាត់ <span class="req">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required placeholder="យ៉ាងតិច ៨ តួអក្សរ">
                        @error('password')<div style="color:#ef4444;font-size:.75rem;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="usr-section-lbl"><i class="fas fa-shield-alt"></i> សិទ្ធិប្រើប្រាស់</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">សិទ្ធិ <span class="req">*</span></label>
                        <select name="role" class="form-control" id="add_role"
                                onchange="toggleDriverField('add_driver_field', this.value)" required>
                            <option value="operation">Operation</option>
                            <option value="accountant">Accountant</option>
                            <option value="driver">Driver</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group usr-driver-field" id="add_driver_field">
                        <label class="form-label">ភ្ជាប់អ្នកបើកបរ</label>
                        <select name="driver_id" class="form-control">
                            <option value="">-- ជ្រើសរើស --</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->driver_id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('addUserModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="editUserModal">
    <div class="modal-box bkg-modal-md">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit" style="color:#FF6B00;"></i> កែប្រែគណនី</h3>
            <button class="modal-close"
                    onclick="document.getElementById('editUserModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editUserForm">
            @csrf @method('PUT')
            <div class="modal-body">

                <div class="usr-section-lbl"><i class="fas fa-user"></i> ព័ត៌មានផ្ទាល់ខ្លួន</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ឈ្មោះ <span class="req">*</span></label>
                        <input type="text" name="user_name" id="edit_user_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ទូរស័ព្ទ</label>
                        <input type="text" name="phone" id="edit_user_phone" class="form-control">
                    </div>
                </div>

                <div class="usr-section-lbl"><i class="fas fa-lock"></i> ព័ត៌មានចូល</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">អ៊ីមែល <span class="req">*</span></label>
                        <input type="email" name="email" id="edit_user_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            ពាក្យសម្ងាត់ថ្មី
                            <span style="color:#94a3b8;font-size:.72rem;font-weight:400;">(ទុកទទេ = មិនផ្លាស់ប្ដូរ)</span>
                        </label>
                        <input type="password" name="password" class="form-control"
                               placeholder="ទុកទទេ ប្រសិនបើមិនផ្លាស់ប្ដូរ">
                    </div>
                </div>

                <div class="usr-section-lbl"><i class="fas fa-shield-alt"></i> សិទ្ធិប្រើប្រាស់</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">សិទ្ធិ <span class="req">*</span></label>
                        <select name="role" id="edit_user_role" class="form-control"
                                onchange="toggleDriverField('edit_driver_field', this.value)" required>
                            <option value="operation">Operation</option>
                            <option value="accountant">Accountant</option>
                            <option value="driver">Driver</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group usr-driver-field" id="edit_driver_field">
                        <label class="form-label">ភ្ជាប់អ្នកបើកបរ</label>
                        <select name="driver_id" id="edit_user_driver" class="form-control">
                            <option value="">-- ជ្រើសរើស --</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->driver_id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('editUserModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
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
var editUserUrlBase = '{{ url("/admin/users") }}';

function openEditUser(id, name, email, phone, role, driverId) {
    document.getElementById('editUserForm').action = editUserUrlBase + '/' + id;
    document.getElementById('edit_user_name').value   = name;
    document.getElementById('edit_user_email').value  = email;
    document.getElementById('edit_user_phone').value  = phone || '';
    document.getElementById('edit_user_role').value   = role;
    document.getElementById('edit_user_driver').value = driverId || '';
    toggleDriverField('edit_driver_field', role);
    document.getElementById('editUserModal').classList.add('open');
}

function toggleDriverField(fieldId, role) {
    var field = document.getElementById(fieldId);
    if (!field) return;
    if (role === 'driver') {
        field.classList.add('show');
    } else {
        field.classList.remove('show');
    }
}

// Auto-reopen the correct modal if there are validation errors
@if($errors->any())
    @if(old('_method') === 'PUT')
        document.addEventListener('DOMContentLoaded', function () {
            var editId  = '{{ old("_edit_user_id") }}';
            // Restore edit modal fields from old() input
            document.getElementById('edit_user_name').value  = '{{ addslashes(old("user_name","")) }}';
            document.getElementById('edit_user_email').value = '{{ addslashes(old("email","")) }}';
            document.getElementById('edit_user_phone').value = '{{ addslashes(old("phone","")) }}';
            var role = '{{ old("role","operation") }}';
            document.getElementById('edit_user_role').value  = role;
            toggleDriverField('edit_driver_field', role);
            document.getElementById('editUserModal').classList.add('open');
        });
    @else
        document.addEventListener('DOMContentLoaded', function () {
            // Restore add modal fields from old() input
            document.getElementById('add_role').value = '{{ old("role","operation") }}';
            toggleDriverField('add_driver_field', '{{ old("role","operation") }}');
            document.getElementById('addUserModal').classList.add('open');
        });
    @endif
@endif
</script>
@endpush
@endsection
