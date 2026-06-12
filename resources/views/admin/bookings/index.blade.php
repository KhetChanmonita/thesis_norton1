@extends('admin.layouts.admin')
@section('title','ការកក់')
@section('page-title')<span>គ្រប់គ្រង</span>ការកក់@endsection

@section('content')

{{-- Filter bar --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:14px 22px;">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div class="form-group" style="flex:1;min-width:200px;">
                <label class="form-label">ស្វែងរកអតិថិជន</label>
                <input type="text" name="search" class="form-control" placeholder="ឈ្មោះ / ទូរស័ព្ទ" value="{{ request('search') }}">
            </div>
            <div class="form-group" style="min-width:160px;">
                <label class="form-label">ស្ថានភាព</label>
                <select name="status" class="form-control">
                    <option value="">ទាំងអស់</option>
                    @foreach(['pending','confirmed','in_progress','completed','cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;padding-bottom:1px;">
                <button type="submit" class="btn btn-orange btn-sm"><i class="fas fa-search"></i> ស្វែងរក</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-redo"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-clipboard-list"></i> ការកក់ ({{ $bookings->total() }})</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>អតិថិជន</th><th>ប្រភេទ</th>
                    <th>ទីតាំង</th><th>កាលបរិច្ឆេទ</th>
                    <th>ទំហំ (kg)</th><th>តម្លៃ</th>
                    <th>ស្ថានភាព</th><th>សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td><strong>#{{ $b->booking_id }}</strong></td>
                    <td>
                        <strong>{{ $b->customer->full_name ?? '—' }}</strong><br>
                        <small style="color:#94a3b8;">{{ $b->customer->phone ?? '' }}</small>
                    </td>
                    <td>{{ $b->booking_type === 'import' ? '🟦 នាំចូល' : '🟩 នាំចេញ' }}</td>
                    <td>
                        <small><i class="fas fa-map-pin" style="color:#FF6B00;"></i> {{ Str::limit($b->pickup_location,20) }}</small><br>
                        <small>
                            <i class="fas fa-map-marker-alt" style="color:#10b981;"></i> {{ Str::limit($b->dropoff_location,20) }}
                            @if($b->dropoff_location_link)
                                <a href="{{ $b->dropoff_location_link }}" target="_blank" rel="noopener" style="color:#FF6B00;" title="មើលលើ Google Maps">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                        </small>
                    </td>
                    <td>
                        <small>ទទួល: {{ $b->pick_up_date ? \Carbon\Carbon::parse($b->pick_up_date)->format('d/m/Y') : '—' }}</small><br>
                        <small>ដឹង: {{ $b->drop_off_date ? \Carbon\Carbon::parse($b->drop_off_date)->format('d/m/Y') : '—' }}</small>
                    </td>
                    <td>{{ $b->cargo_weight ? number_format($b->cargo_weight) : '—' }}</td>
                    <td>{{ $b->total_price ? '$'.number_format($b->total_price,2) : '—' }}</td>
                    <td><span class="badge badge-{{ $b->status }}">{{ $b->status }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-ghost btn-sm" onclick="changeStatus({{ $b->booking_id }}, '{{ $b->status }}')">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.bookings.destroy', $b->booking_id) }}" onsubmit="return confirm('លុប?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;color:#94a3b8;padding:32px;">មិនមានការកក់</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;">{{ $bookings->links() }}</div>
    @endif
</div>

{{-- Status Modal --}}
<div class="modal-overlay" id="statusModal">
    <div class="modal-box" style="max-width:440px;">
        <div class="modal-header">
            <h3><i class="fas fa-exchange-alt"></i> ផ្លាស់ប្ដូរស្ថានភាព</h3>
            <button class="modal-close" onclick="document.getElementById('statusModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="statusForm">
            @csrf @method('PATCH')
            <div class="modal-body">

                <div class="form-group" style="margin-bottom:18px;">
                    <label class="form-label">ស្ថានភាពថ្មី</label>
                    <select name="status" id="statusSelect" class="form-control"
                            onchange="togglePriceField(this.value)">
                        <option value="pending">pending — រង់ចាំ</option>
                        <option value="confirmed">confirmed — បានអនុម័ត</option>
                        <option value="in_progress">in_progress — កំពុងដឹក</option>
                        <option value="completed">completed — បានបញ្ចប់</option>
                        <option value="cancelled">cancelled — បានលុប</option>
                    </select>
                </div>

                {{-- Total price field — shown when confirming --}}
                <div id="priceField" style="display:none;">
                    <div style="background:#fff7ed;border:1.5px solid #fed7aa;border-radius:10px;padding:14px 16px;margin-bottom:4px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;color:#c2410c;font-weight:700;font-size:0.85rem;">
                            <i class="fas fa-dollar-sign"></i>
                            កំណត់តម្លៃដឹកជញ្ជូន (ចាំបាច់សម្រាប់ការទូទាត់)
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">តម្លៃសរុប (USD)</label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                                              font-weight:700;color:#64748b;">$</span>
                                <input type="number" name="total_price" id="totalPriceInput"
                                       class="form-control" placeholder="ឧ. 500.00"
                                       min="0" step="0.01"
                                       style="padding-left:28px;">
                            </div>
                            <p style="font-size:0.75rem;color:#94a3b8;margin-top:6px;">
                                <i class="fas fa-info-circle"></i>
                                អតិថិជននឹងបង់ 50% (&asymp; $<span id="halfPrice">0.00</span>) នៅពេលអនុម័ត
                                និង 50% ទៀតនៅពេលបញ្ចប់
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Completion notice --}}
                <div id="completedNotice" style="display:none;">
                    <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:14px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;color:#065f46;font-weight:700;font-size:0.85rem;">
                            <i class="fas fa-bell"></i>
                            អតិថិជននឹងទទួលបានការជូនដំណឹងដើម្បីបង់ 50% ចុងក្រោយ
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('statusModal').classList.remove('open')">
                    បោះបង់
                </button>
                <button type="submit" class="btn btn-orange">
                    <i class="fas fa-check"></i> ធ្វើបច្ចុប្បន្នភាព
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function changeStatus(id, currentStatus) {
    document.getElementById('statusForm').action = '/admin/bookings/' + id + '/status';
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('totalPriceInput').value = '';
    document.getElementById('halfPrice').textContent = '0.00';
    togglePriceField(currentStatus);
    document.getElementById('statusModal').classList.add('open');
}

function togglePriceField(status) {
    document.getElementById('priceField').style.display     = (status === 'confirmed') ? 'block' : 'none';
    document.getElementById('completedNotice').style.display = (status === 'completed') ? 'block' : 'none';
}

document.getElementById('totalPriceInput').addEventListener('input', function () {
    var half = parseFloat(this.value || 0) / 2;
    document.getElementById('halfPrice').textContent = half.toFixed(2);
});
</script>
@endpush
@endsection
