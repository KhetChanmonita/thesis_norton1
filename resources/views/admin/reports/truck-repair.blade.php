@extends('admin.layouts.admin')

@section('title', 'របាយការណ៍ជួសជុលរថយន្ត')
@section('page-title')<span>របាយការណ៍</span>ជួសជុលរថយន្ត@endsection

@push('styles')
<style>
/* ── Page wrapper ── */
.tr-page { display: flex; flex-direction: column; gap: 22px; }

/* ── Top bar ── */
.tr-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.tr-topbar-left { display: flex; align-items: center; gap: 14px; }
.tr-icon-wrap {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, #FF6B00, #ff9040);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(255,107,0,.3);
    flex-shrink: 0;
}
.tr-icon-wrap i { color: #fff; font-size: 1.2rem; }
.tr-title { font-family: var(--font-head); font-size: 1.18rem; font-weight: 800; color: #1e293b; margin: 0; }
.tr-subtitle { font-size: .8rem; color: var(--gray); margin-top: 2px; }
.tr-print-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    color: #374151;
    font-family: var(--font);
    font-size: .88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.tr-print-btn:hover { background: #f8fafc; border-color: #94a3b8; color: #1e293b; }

/* ── Filter card ── */
.tr-filter-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
    padding: 18px 22px;
}
.tr-filter-label {
    font-family: var(--font); font-size: .78rem;
    font-weight: 600; color: var(--gray);
    text-transform: uppercase; letter-spacing: .04em;
    margin-bottom: 6px; display: block;
}
.tr-filter-row { display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap; }
.tr-filter-group { display: flex; flex-direction: column; }
.tr-input, .tr-select {
    border: 1.5px solid var(--border);
    border-radius: 9px;
    padding: 8px 12px;
    font-family: var(--font);
    font-size: .9rem;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.tr-input:focus, .tr-select:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(255,107,0,.12);
    background: #fff;
}
.tr-select { min-width: 200px; }
.tr-btn-search {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    background: linear-gradient(135deg, #FF6B00, #ff8c38);
    color: #fff; border: none; border-radius: 9px;
    font-family: var(--font); font-size: .9rem; font-weight: 600;
    cursor: pointer; transition: opacity .2s, box-shadow .2s;
    box-shadow: 0 3px 10px rgba(255,107,0,.3);
}
.tr-btn-search:hover { opacity: .88; }
.tr-btn-reset {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 14px;
    background: #f1f5f9; color: #374151;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-family: var(--font); font-size: .9rem; font-weight: 500;
    text-decoration: none; transition: background .2s;
}
.tr-btn-reset:hover { background: #e2e8f0; }

/* ── Stat cards ── */
.tr-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
@media(max-width:768px){ .tr-stats { grid-template-columns: 1fr; } }
.tr-stat {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid var(--border);
    padding: 22px 20px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
    transition: transform .2s, box-shadow .2s;
    position: relative; overflow: hidden;
}
.tr-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.tr-stat::before {
    content: '';
    position: absolute; top: 0; left: 0;
    width: 4px; height: 100%;
    border-radius: 16px 0 0 16px;
}
.tr-stat-orange::before { background: linear-gradient(180deg,#FF6B00,#ff9040); }
.tr-stat-red::before    { background: linear-gradient(180deg,#ef4444,#fca5a5); }
.tr-stat-green::before  { background: linear-gradient(180deg,#10b981,#6ee7b7); }
.tr-stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.tr-stat-orange .tr-stat-icon { background: #fff7ed; color: #FF6B00; }
.tr-stat-red    .tr-stat-icon { background: #fef2f2; color: #ef4444; }
.tr-stat-green  .tr-stat-icon { background: #f0fdf4; color: #10b981; }
.tr-stat-val {
    font-family: var(--font-head); font-size: 1.7rem;
    font-weight: 800; line-height: 1; color: #1e293b;
}
.tr-stat-lbl { font-size: .78rem; color: var(--gray); margin-top: 4px; }

/* ── Section cards ── */
.tr-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
    overflow: hidden;
}
.tr-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1.5px solid var(--border);
    background: #fafbfc;
}
.tr-card-head-title {
    display: flex; align-items: center; gap: 9px;
    font-family: var(--font); font-weight: 700;
    font-size: .95rem; color: #1e293b;
}
.tr-card-head-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem;
}
.icon-orange { background: #fff7ed; color: #FF6B00; }
.icon-green  { background: #f0fdf4; color: #10b981; }
.icon-blue   { background: #eff6ff; color: #3b82f6; }
.tr-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .75rem; font-weight: 600;
}
.tr-badge-gray { background: #f1f5f9; color: #64748b; }
.tr-badge-orange { background: #fff7ed; color: #FF6B00; }

/* ── Summary table ── */
.tr-table { width: 100%; border-collapse: collapse; }
.tr-table th {
    padding: 11px 16px;
    background: #f8fafc;
    font-family: var(--font); font-size: .8rem; font-weight: 700;
    color: var(--gray); text-transform: uppercase; letter-spacing: .04em;
    border-bottom: 1.5px solid var(--border);
}
.tr-table td {
    padding: 13px 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: .9rem; color: #334155;
    vertical-align: middle;
}
.tr-table tbody tr:last-child td { border-bottom: none; }
.tr-table tbody tr:hover td { background: #fafbff; }
.tr-table tfoot td {
    padding: 12px 16px;
    background: #f8fafc;
    border-top: 2px solid var(--border);
    font-weight: 700;
}
.tr-truck-name { font-weight: 700; color: #1e293b; }
.tr-plate { font-size: .78rem; color: var(--gray); margin-top: 2px; }
.tr-count-badge {
    display: inline-block;
    padding: 3px 10px;
    background: #fff7ed;
    color: #FF6B00;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 700;
}
.tr-amount { font-weight: 700; color: #ef4444; font-family: var(--font-head); }
.tr-total-amount { font-weight: 800; color: #ef4444; font-family: var(--font-head); font-size: 1rem; }

/* ── Add form ── */
.tr-form-body { padding: 20px 22px; }
.tr-form-row { display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end; }
.tr-form-group { display: flex; flex-direction: column; flex: 1; min-width: 160px; }
.tr-form-group.narrow { flex: 0 0 140px; }
.tr-form-group.wide { flex: 2; }
.tr-form-label {
    font-family: var(--font); font-size: .78rem; font-weight: 600;
    color: var(--gray); text-transform: uppercase;
    letter-spacing: .04em; margin-bottom: 6px;
}
.tr-req { color: #ef4444; margin-left: 2px; }
.tr-form-input, .tr-form-select {
    border: 1.5px solid var(--border);
    border-radius: 9px;
    padding: 9px 13px;
    font-family: var(--font); font-size: .9rem; color: #1e293b;
    background: #f8fafc; outline: none;
    transition: border-color .2s, box-shadow .2s;
    width: 100%;
}
.tr-form-input:focus, .tr-form-select:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16,185,129,.12);
    background: #fff;
}
.tr-form-input.is-invalid, .tr-form-select.is-invalid { border-color: #ef4444; }
.tr-invalid-msg { font-size: .75rem; color: #ef4444; margin-top: 4px; }
.tr-btn-save {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 22px;
    background: linear-gradient(135deg,#10b981,#059669);
    color: #fff; border: none; border-radius: 9px;
    font-family: var(--font); font-size: .9rem; font-weight: 600;
    cursor: pointer; white-space: nowrap;
    box-shadow: 0 3px 10px rgba(16,185,129,.3);
    transition: opacity .2s;
}
.tr-btn-save:hover { opacity: .88; }

/* ── Repair list ── */
.tr-date-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f1f5f9; color: #475569;
    padding: 3px 9px; border-radius: 6px;
    font-size: .78rem; font-weight: 600;
}
.tr-desc { color: #334155; }
.tr-desc-empty { color: #cbd5e1; font-style: italic; }
.tr-btn-del {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border: 1.5px solid #fca5a5;
    background: #fef2f2; color: #ef4444;
    border-radius: 8px; cursor: pointer;
    transition: all .2s; font-size: .82rem;
}
.tr-btn-del:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

/* ── Empty state ── */
.tr-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 56px 20px; gap: 12px;
}
.tr-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: #f8fafc; display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: #cbd5e1;
}
.tr-empty-title { font-weight: 700; color: #94a3b8; font-size: .95rem; }
.tr-empty-sub { font-size: .82rem; color: #cbd5e1; }

/* ── Delete modal ── */
.tr-modal-overlay {
    position: fixed; inset: 0; background: rgba(15,23,42,.45);
    backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999; opacity: 0; pointer-events: none;
    transition: opacity .25s;
}
.tr-modal-overlay.open { opacity: 1; pointer-events: all; }
.tr-modal {
    background: #fff; border-radius: 20px;
    padding: 32px 28px; text-align: center;
    width: 360px; max-width: 94vw;
    box-shadow: 0 20px 60px rgba(0,0,0,.18);
    transform: scale(.92) translateY(12px);
    transition: transform .28s cubic-bezier(.34,1.56,.64,1);
}
.tr-modal-overlay.open .tr-modal { transform: scale(1) translateY(0); }
.tr-modal-del-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; margin: 0 auto 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: #ef4444;
}
.tr-modal-title { font-family: var(--font-head); font-weight: 800; font-size: 1.05rem; color: #1e293b; }
.tr-modal-sub { font-size: .85rem; color: var(--gray); margin-top: 6px; }
.tr-modal-actions { display: flex; gap: 10px; justify-content: center; margin-top: 22px; }
.tr-modal-cancel {
    flex: 1; padding: 10px; border: 1.5px solid var(--border);
    background: #f8fafc; border-radius: 10px;
    font-family: var(--font); font-size: .9rem; font-weight: 600;
    color: #374151; cursor: pointer; transition: background .2s;
}
.tr-modal-cancel:hover { background: #e2e8f0; }
.tr-modal-confirm {
    flex: 1; padding: 10px;
    background: linear-gradient(135deg,#ef4444,#dc2626);
    border: none; border-radius: 10px;
    font-family: var(--font); font-size: .9rem; font-weight: 600;
    color: #fff; cursor: pointer;
    box-shadow: 0 3px 10px rgba(239,68,68,.3);
    transition: opacity .2s;
}
.tr-modal-confirm:hover { opacity: .88; }

/* ── Flash ── */
.tr-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; border-radius: 10px;
    font-size: .9rem; font-weight: 500;
}
.tr-flash-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
</style>
@endpush

@section('content')
<div class="tr-page">

{{-- ── TOP BAR ── --}}
<div class="tr-topbar">
    <div class="tr-topbar-left">
        <div class="tr-icon-wrap"><i class="fas fa-tools"></i></div>
        <div>
            <div class="tr-title">របាយការណ៍ជួសជុលរថយន្ត</div>
            <div class="tr-subtitle">ការតាមដានការជួសជុល និងចំណាយប្រចាំខែ</div>
        </div>
    </div>
    <a href="{{ route('admin.reports.truck-repair.print', ['month' => $month, 'truck_id' => $truckId]) }}"
       target="_blank" class="tr-print-btn">
        <i class="fas fa-print"></i> បោះពុម្ពរបាយការណ៍
    </a>
</div>

{{-- ── FILTER ── --}}
<div class="tr-filter-card">
    <form method="GET" action="{{ route('admin.reports.truck-repair') }}">
        <div class="tr-filter-row">
            <div class="tr-filter-group">
                <span class="tr-filter-label"><i class="fas fa-calendar-alt" style="margin-right:4px;"></i>ខែ</span>
                <input type="month" name="month" value="{{ $month }}" class="tr-input">
            </div>
            <div class="tr-filter-group">
                <span class="tr-filter-label"><i class="fas fa-truck" style="margin-right:4px;"></i>រថយន្ត</span>
                <select name="truck_id" class="tr-select">
                    <option value="">រថយន្តទាំងអស់</option>
                    @foreach($trucks as $t)
                        <option value="{{ $t->truck_id }}" {{ $truckId == $t->truck_id ? 'selected' : '' }}>
                            {{ $t->truck_name }} — {{ $t->plate_number }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;">
                <button type="submit" class="tr-btn-search">
                    <i class="fas fa-search"></i> ស្វែងរក
                </button>
                <a href="{{ route('admin.reports.truck-repair') }}" class="tr-btn-reset">
                    <i class="fas fa-redo"></i> កំណត់ឡើងវិញ
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ── STAT CARDS ── --}}
<div class="tr-stats">
    <div class="tr-stat tr-stat-orange">
        <div class="tr-stat-icon"><i class="fas fa-wrench"></i></div>
        <div>
            <div class="tr-stat-val">{{ $repairCount }}</div>
            <div class="tr-stat-lbl">ចំនួនដងជួសជុល</div>
        </div>
    </div>
    <div class="tr-stat tr-stat-red">
        <div class="tr-stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="tr-stat-val">${{ number_format($grandTotal, 2) }}</div>
            <div class="tr-stat-lbl">ចំណាយសរុបជួសជុល</div>
        </div>
    </div>
    <div class="tr-stat tr-stat-green">
        <div class="tr-stat-icon"><i class="fas fa-truck"></i></div>
        <div>
            <div class="tr-stat-val">{{ $truckSummary->count() }}</div>
            <div class="tr-stat-lbl">ចំនួនរថយន្តជួសជុល</div>
        </div>
    </div>
</div>

{{-- ── PER-TRUCK SUMMARY ── --}}
@if($truckSummary->isNotEmpty())
<div class="tr-card">
    <div class="tr-card-head">
        <div class="tr-card-head-title">
            <div class="tr-card-head-icon icon-orange"><i class="fas fa-chart-bar"></i></div>
            សង្ខេបតាមរថយន្ត — {{ $month }}
        </div>
        <span class="tr-badge tr-badge-orange">{{ $truckSummary->count() }} គ្រឿង</span>
    </div>
    <div>
        <table class="tr-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>រថយន្ត</th>
                    <th class="text-center" style="text-align:center;">ចំនួនដង</th>
                    <th style="text-align:right;">ចំណាយសរុប</th>
                </tr>
            </thead>
            <tbody>
                @foreach($truckSummary as $item)
                <tr>
                    <td style="color:#94a3b8;font-size:.82rem;">{{ $loop->iteration }}</td>
                    <td>
                        <div class="tr-truck-name">
                            <i class="fas fa-truck" style="color:#FF6B00;margin-right:6px;font-size:.85rem;"></i>
                            {{ $item['truck']->truck_name ?? 'N/A' }}
                        </div>
                        <div class="tr-plate">{{ $item['truck']->plate_number ?? '' }}</div>
                    </td>
                    <td style="text-align:center;">
                        <span class="tr-count-badge">{{ $item['count'] }} ដង</span>
                    </td>
                    <td style="text-align:right;">
                        <span class="tr-amount">${{ number_format($item['total'], 2) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;color:#64748b;font-size:.85rem;">ចំណាយសរុប</td>
                    <td style="text-align:right;"><span class="tr-total-amount">${{ number_format($grandTotal, 2) }}</span></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

{{-- ── ADD REPAIR FORM ── --}}
<div class="tr-card">
    <div class="tr-card-head">
        <div class="tr-card-head-title">
            <div class="tr-card-head-icon icon-green"><i class="fas fa-plus"></i></div>
            កត់ត្រាការជួសជុលថ្មី
        </div>
    </div>
    <div class="tr-form-body">
        @if(session('success'))
        <div class="tr-flash tr-flash-success" style="margin-bottom:16px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        <form method="POST" action="{{ route('admin.reports.store') }}">
            @csrf
            <input type="hidden" name="expense_type" value="repair">
            <div class="tr-form-row">
                <div class="tr-form-group">
                    <label class="tr-form-label">រថយន្ត<span class="tr-req">*</span></label>
                    <select name="truck_id" class="tr-form-select {{ $errors->has('truck_id') ? 'is-invalid' : '' }}" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($trucks as $t)
                            <option value="{{ $t->truck_id }}" {{ old('truck_id') == $t->truck_id ? 'selected' : '' }}>
                                {{ $t->truck_name }} ({{ $t->plate_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('truck_id')<div class="tr-invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="tr-form-group narrow">
                    <label class="tr-form-label">ចំណាយ ($)<span class="tr-req">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01"
                           value="{{ old('amount') }}"
                           class="tr-form-input {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                           placeholder="0.01" required
                           oninput="if(this.value<0.01||this.value==='')this.value=''">
                    @error('amount')<div class="tr-invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="tr-form-group narrow">
                    <label class="tr-form-label">កាលបរិច្ឆេទ<span class="tr-req">*</span></label>
                    <input type="date" name="expense_date"
                           value="{{ old('expense_date', now()->toDateString()) }}"
                           class="tr-form-input {{ $errors->has('expense_date') ? 'is-invalid' : '' }}" required>
                    @error('expense_date')<div class="tr-invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="tr-form-group wide">
                    <label class="tr-form-label">ការពិពណ៌នា / បញ្ហា</label>
                    <input type="text" name="description"
                           value="{{ old('description') }}"
                           class="tr-form-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                           placeholder="ពិពណ៌នាបញ្ហា ឬការជួសជុល...">
                    @error('description')<div class="tr-invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div style="align-self:flex-end;">
                    <button type="submit" class="tr-btn-save">
                        <i class="fas fa-save"></i> រក្សាទុក
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── REPAIR LIST ── --}}
<div class="tr-card">
    <div class="tr-card-head">
        <div class="tr-card-head-title">
            <div class="tr-card-head-icon icon-blue"><i class="fas fa-list-ul"></i></div>
            បញ្ជីការជួសជុល
            @if($selectedTruck)
                <span style="color:var(--gray);font-weight:500;">— {{ $selectedTruck->truck_name }} ({{ $selectedTruck->plate_number }})</span>
            @endif
        </div>
        <span class="tr-badge tr-badge-gray"><i class="fas fa-file-alt" style="margin-right:4px;"></i>{{ $repairCount }} កំណត់ត្រា</span>
    </div>

    @if($repairs->isEmpty())
    <div class="tr-empty">
        <div class="tr-empty-icon"><i class="fas fa-tools"></i></div>
        <div class="tr-empty-title">គ្មានការជួសជុលក្នុងខែ {{ $month }}</div>
        <div class="tr-empty-sub">បន្ថែមការជួសជុលដោយប្រើទម្រង់ខាងលើ</div>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="tr-table">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>រថយន្ត</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>ការពិពណ៌នា / បញ្ហា</th>
                    <th style="text-align:right;">ចំណាយ</th>
                    <th style="text-align:center;width:60px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($repairs as $i => $r)
                <tr>
                    <td style="color:#94a3b8;font-size:.82rem;font-weight:600;">{{ $i + 1 }}</td>
                    <td>
                        <div class="tr-truck-name">
                            <i class="fas fa-truck" style="color:#FF6B00;margin-right:6px;font-size:.82rem;"></i>
                            {{ $r->truck->truck_name ?? 'N/A' }}
                        </div>
                        <div class="tr-plate">{{ $r->truck->plate_number ?? '' }}</div>
                    </td>
                    <td>
                        <span class="tr-date-chip">
                            <i class="fas fa-calendar" style="font-size:.7rem;"></i>
                            {{ \Carbon\Carbon::parse($r->expense_date)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td style="max-width:260px;">
                        @if($r->description)
                            <span class="tr-desc">{{ $r->description }}</span>
                        @else
                            <span class="tr-desc-empty">គ្មានការពិពណ៌នា</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <span class="tr-amount">${{ number_format($r->amount, 2) }}</span>
                    </td>
                    <td style="text-align:center;">
                        <button class="tr-btn-del" onclick="openDeleteModal({{ $r->expense_id }})" title="លុប">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;color:#64748b;font-size:.85rem;">ចំណាយសរុបខែ {{ $month }}</td>
                    <td style="text-align:right;"><span class="tr-total-amount">${{ number_format($grandTotal, 2) }}</span></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>

</div>{{-- /.tr-page --}}

{{-- ── DELETE MODAL ── --}}
<div class="tr-modal-overlay" id="trDeleteOverlay" onclick="if(event.target===this)closeDeleteModal()">
    <div class="tr-modal">
        <div class="tr-modal-del-icon"><i class="fas fa-trash-alt"></i></div>
        <div class="tr-modal-title">លុបកំណត់ត្រា?</div>
        <div class="tr-modal-sub">ការជួសជុលនេះនឹងត្រូវបានលុបចោលជាអចិន្ត្រៃយ៍</div>
        <div class="tr-modal-actions">
            <button class="tr-modal-cancel" onclick="closeDeleteModal()">មិនលុប</button>
            <form id="trDeleteForm" method="POST" style="flex:1;display:flex;">
                @csrf @method('DELETE')
                <button type="submit" class="tr-modal-confirm" style="flex:1;">លុបចោល</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openDeleteModal(id) {
    document.getElementById('trDeleteForm').action = '/admin/reports/' + id;
    document.getElementById('trDeleteOverlay').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('trDeleteOverlay').classList.remove('open');
}
</script>
@endpush
