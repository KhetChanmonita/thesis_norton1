@extends('admin.layouts.admin')
@section('title','ការកក់')
@section('page-title')<span>គ្រប់គ្រង</span>ការកក់@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_bookings.css') }}">
@endpush

@section('content')

{{-- Filter bar --}}
<div class="card bkg-filter-card">
    <div class="card-body bkg-filter-body">
        <form method="GET" class="bkg-filter-form">
            <div class="form-group bkg-filter-field-wide">
                <label class="form-label">ស្វែងរកអតិថិជន</label>
                <input type="text" name="search" class="form-control" placeholder="ឈ្មោះ / ទូរស័ព្ទ" value="{{ request('search') }}">
            </div>
            <div class="form-group bkg-filter-field-sm">
                <label class="form-label">លេខការកក់</label>
                <input type="text" name="booking_code" class="form-control" placeholder="ឧ. LS2608-1" value="{{ request('booking_code') }}">
            </div>
            <div class="form-group bkg-filter-field-sm">
                <label class="form-label">ស្ថានភាព</label>
                <select name="status" class="form-control">
                    <option value="">ទាំងអស់</option>
                    @foreach(['pending','confirmed','in_progress','completed','cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bkg-filter-btns">
                <button type="submit" class="btn btn-orange btn-sm"><i class="fas fa-search"></i> ស្វែងរក</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-redo"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-clipboard-list"></i>
            បញ្ជីការកក់
            <span class="bkg-count-badge">
                {{ $bookings->total() }}
            </span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th><th>លេខការកក់</th><th>អតិថិជន</th><th>ប្រភេទ</th>
                    <th>ទីតាំង</th><th>កាលបរិច្ឆេទ</th>
                    <th>ទំហំ (kg)</th><th>តម្លៃ</th>
                    <th>ស្ថានភាព</th><th>សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td>
                        <span class="bkg-row-num">
                            {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                        </span>
                    </td>
                    <td>
                        <span class="bkg-id-badge">
                            {{ $b->formatted_id }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $b->customer->full_name ?? '—' }}</strong><br>
                        <small class="bkg-phone">{{ $b->customer->phone ?? '' }}</small>
                    </td>
                    <td>{{ $b->booking_type === 'import' ? '🟦 នាំចូល' : '🟩 នាំចេញ' }}</td>
                    <td>
                        <small><i class="fas fa-map-pin bkg-map-pin"></i> {{ Str::limit($b->pickup_location,20) }}</small><br>
                        <small>
                            <i class="fas fa-map-marker-alt bkg-map-marker"></i> {{ Str::limit($b->dropoff_location,20) }}
                            @if($b->dropoff_location_link)
                                <a href="{{ $b->dropoff_location_link }}" target="_blank" rel="noopener" class="bkg-map-link" title="មើលលើ Google Maps">
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
                    <td>
                        @php
                            $secondSum = $b->extraCharges->where('stage','second')->sum('amount');
                            $trueTotal = ($b->total_price ?? 0) + $secondSum;
                        @endphp
                        {{ $trueTotal > 0 ? '$'.number_format($trueTotal,2) : '—' }}
                    </td>
                    <td><span class="badge badge-{{ $b->status }}">{{ ['pending'=>'រង់ចាំ','confirmed'=>'បានអនុម័ត','in_progress'=>'កំពុងដឹក','completed'=>'បានបញ្ចប់','cancelled'=>'បានលុប'][$b->status] ?? $b->status }}</span></td>
                    <td>
                        <div class="bkg-action-btns">
                            <button class="btn btn-ghost btn-sm" onclick='showBookingDetail(@json($b))' title="មើលលម្អិត">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if(in_array($b->status, ['completed', 'cancelled']))
                            <button class="btn btn-ghost btn-sm bkg-btn-disabled" disabled title="ស្ថានភាពនេះជាស្ថានភាពចុងក្រោយ — មិនអាចផ្លាស់ប្ដូរបានទេ">
                                <i class="fas fa-lock"></i>
                            </button>
                            @else
                            <button class="btn btn-ghost btn-sm" onclick="changeStatus({{ $b->booking_id }}, '{{ $b->status }}')" title="ប្ដូរស្ថានភាព">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            @endif
                            @if($b->status === 'completed')
                            <button class="btn btn-ghost btn-sm bkg-btn-disabled" disabled title="ការកក់បានបញ្ចប់ — មិនអាចបន្ថែមការគិតប្រាក់បន្ថែមទៀតបានទេ">
                                <i class="fas fa-lock"></i>
                            </button>
                            @else
                            <button class="btn btn-ghost btn-sm" onclick="openExtraChargeModal({{ $b->booking_id }})" title="ការគិតប្រាក់បន្ថែម">
                                <i class="fas fa-money-bill-wave"></i>
                            </button>
                            @endif
                            <button class="btn btn-danger btn-sm" title="លុប"
                                    onclick="confirmDeleteBooking({{ $b->booking_id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="bkg-empty-cell">មិនមានការកក់</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="bkg-pagination">{{ $bookings->links('vendor.pagination.custom') }}</div>
    @endif
</div>

{{-- Booking Detail Modal --}}
<div class="modal-overlay" id="bookingDetailModal">
    <div class="modal-box bkg-modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-clipboard-list"></i> ព័ត៌មានលម្អិតការកក់ <span id="bd_id"></span></h3>
            <button class="modal-close" onclick="document.getElementById('bookingDetailModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="bkg-detail-section">
                <div class="bkg-detail-title"><i class="fas fa-user"></i> ព័ត៌មានអតិថិជន</div>
                <div class="bkg-detail-grid">
                    <div><span class="bkg-detail-lbl">ឈ្មោះពេញ</span><span class="bkg-detail-val" id="bd_name"></span></div>
                    <div><span class="bkg-detail-lbl">ទូរស័ព្ទ</span><span class="bkg-detail-val" id="bd_phone"></span></div>
                    <div><span class="bkg-detail-lbl">អ៊ីមែល</span><span class="bkg-detail-val" id="bd_email"></span></div>
                </div>
            </div>

            <div class="bkg-detail-section">
                <div class="bkg-detail-title"><i class="fas fa-truck"></i> ព័ត៌មានដឹកជញ្ជូន</div>
                <div class="bkg-detail-grid">
                    <div><span class="bkg-detail-lbl">ប្រភេទ</span><span class="bkg-detail-val" id="bd_type"></span></div>
                    <div><span class="bkg-detail-lbl">លេខកុងតឺន័រ</span><span class="bkg-detail-val" id="bd_container"></span></div>
                    <div><span class="bkg-detail-lbl">រថយន្តកំណត់</span><span class="bkg-detail-val" id="bd_truck"></span></div>
                    <div><span class="bkg-detail-lbl">ទម្ងន់ទំនិញ</span><span class="bkg-detail-val" id="bd_weight"></span></div>
                </div>
            </div>

            <div class="bkg-detail-section">
                <div class="bkg-detail-title"><i class="fas fa-map-marked-alt"></i> ទីតាំង &amp; កាលបរិច្ឆេទ</div>
                <div class="bkg-detail-grid">
                    <div class="bkg-detail-full"><span class="bkg-detail-lbl">ទីតាំងទទួល</span><span class="bkg-detail-val" id="bd_pickup"></span></div>
                    <div class="bkg-detail-full">
                        <span class="bkg-detail-lbl">ទីតាំងដឹកទៅ</span>
                        <span class="bkg-detail-val" id="bd_dropoff"></span>
                        <a href="#" id="bd_map_link" target="_blank" rel="noopener" class="bkg-modal-map-link">
                            <i class="fas fa-external-link-alt"></i> Maps
                        </a>
                    </div>
                    <div><span class="bkg-detail-lbl">កាលបរិច្ឆេទទទួល</span><span class="bkg-detail-val" id="bd_pickdate"></span></div>
                    <div><span class="bkg-detail-lbl">កាលបរិច្ឆេទដឹកទៅ</span><span class="bkg-detail-val" id="bd_dropdate"></span></div>
                </div>
            </div>

            <div class="bkg-detail-section">
                <div class="bkg-detail-title"><i class="fas fa-file-alt"></i> ឯកសារ &amp; ការទូទាត់</div>
                <div class="bkg-detail-grid">
                    <div><span class="bkg-detail-lbl">ឯកសារទំនិញ</span><span class="bkg-detail-val" id="bd_file"></span></div>
                    <div><span class="bkg-detail-lbl">តម្លៃសរុប</span><span class="bkg-detail-val" id="bd_price"></span></div>
                    <div><span class="bkg-detail-lbl">ស្ថានភាពទូទាត់</span><span class="bkg-detail-val" id="bd_paystatus"></span></div>
                    <div><span class="bkg-detail-lbl">ស្ថានភាពកក់</span><span class="bkg-detail-val" id="bd_status"></span></div>
                    <div><span class="bkg-detail-lbl">ថ្ងៃកក់</span><span class="bkg-detail-val" id="bd_bookdate"></span></div>
                </div>
            </div>

            <div class="bkg-detail-section">
                <div class="bkg-detail-title"><i class="fas fa-money-bill-wave"></i> ការគិតប្រាក់បន្ថែម</div>
                <div id="bd_charges_list"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('bookingDetailModal').classList.remove('open')">
                បិទ
            </button>
        </div>
    </div>
</div>

{{-- Status Modal --}}
<div class="modal-overlay" id="statusModal">
    <div class="modal-box bkg-modal-md">
        <div class="modal-header">
            <h3><i class="fas fa-exchange-alt"></i> ផ្លាស់ប្ដូរស្ថានភាព</h3>
            <button class="modal-close" onclick="document.getElementById('statusModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="statusForm">
            @csrf @method('PATCH')
            <div class="modal-body">

                <div class="form-group bkg-modal-form-group">
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
                <div id="priceField" class="bkg-hidden">
                    <div class="bkg-price-box">
                        <div class="bkg-price-box-header">
                            <i class="fas fa-dollar-sign"></i>
                            កំណត់តម្លៃដឹកជញ្ជូន (ចាំបាច់សម្រាប់ការទូទាត់)
                        </div>
                        <div class="form-group bkg-no-margin">
                            <label class="form-label">តម្លៃសរុប (USD)</label>
                            <div class="bkg-price-input-wrap">
                                <span class="bkg-price-prefix">$</span>
                                <input type="number" name="total_price" id="totalPriceInput"
                                       class="form-control bkg-price-input-pl" placeholder="ឧ. 500.00"
                                       min="0" step="0.01">
                            </div>
                            <p class="bkg-price-hint">
                                <i class="fas fa-info-circle"></i>
                                អតិថិជននឹងបង់ 50% (&asymp; $<span id="halfPrice">0.00</span>) នៅពេលអនុម័ត
                                និង 50% ទៀតនៅពេលបញ្ចប់
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Completion notice + optional extra charge --}}
                <div id="completedNotice" class="bkg-hidden">
                    <div class="bkg-completed-box">
                        <div class="bkg-completed-box-inner">
                            <i class="fas fa-bell"></i>
                            អតិថិជននឹងទទួលបានការជូនដំណឹងដើម្បីបង់ 50% ចុងក្រោយ
                        </div>
                    </div>

                    <div style="margin-top:14px;border:1px dashed #e2a96a;border-radius:10px;padding:14px;background:#fffbf5;">
                        <div style="font-size:0.82rem;font-weight:700;color:#b45309;margin-bottom:10px;">
                            <i class="fas fa-plus-circle"></i> បន្ថែមការគិតប្រាក់បន្ថែម                        </div>

                        <div class="form-group bkg-modal-form-group" style="margin-bottom:10px;">
                            <label class="form-label" style="font-size:0.8rem;">ប្រភេទការគិតប្រាក់</label>
                            <select name="completion_charge_type" id="completionChargeType" class="form-control"
                                    style="font-family:'Kantumruy Pro',sans-serif;"
                                    onchange="toggleCompletionCharge(this.value)">
                                <option value="">— មិនបន្ថែម —</option>
                                <option value="standby">ឈប់រង់ចាំ (Standby — $50/ថ្ងៃ)</option>
                                <option value="extra_charge">ការគិតប្រាក់បន្ថែម (Extra Charge)</option>
                                <option value="empty_return">ត្រឡប់ទទេ (Empty Return)</option>
                                <option value="overweight">ទម្ងន់លើស (Over Weight)</option>
                            </select>
                        </div>

                        <div id="completionDaysField" class="form-group bkg-modal-form-group bkg-hidden" style="margin-bottom:10px;">
                            <label class="form-label" style="font-size:0.8rem;">ចំនួនថ្ងៃឈប់រង់ចាំ</label>
                            <input type="number" name="completion_days" id="completionDaysInput"
                                   class="form-control" placeholder="ឧ. 2" min="1" step="1"
                                   oninput="updateCompletionTotal(this.value)">
                            <p class="bkg-standby-hint">
                                សរុប: <strong id="completionTotalDisplay" class="bkg-standby-total">$0.00</strong>
                            </p>
                        </div>

                        <div id="completionAmountField" class="form-group bkg-modal-form-group bkg-hidden" style="margin-bottom:10px;">
                            <label class="form-label" style="font-size:0.8rem;">ចំនួនទឹកប្រាក់ (USD)</label>
                            <input type="number" name="completion_amount" id="completionAmountInput"
                                   class="form-control" placeholder="ឧ. 25.00" min="0" step="0.01">
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:0.8rem;">កំណត់ចំណាំ (ស្រេចចិត្ត)</label>
                            <textarea name="completion_note" class="form-control" rows="2"
                                      placeholder="ព័ត៌មានបន្ថែម..."></textarea>
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

{{-- Delete Confirm Modal --}}
<div class="modal-overlay confirm-overlay" id="deleteBookingModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteBookingForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបការកក់នេះ?</div>
                <p class="confirm-subtitle">សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ</p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteBookingModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> លុប
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Add Extra Charge Modal --}}
<div class="modal-overlay" id="extraChargeModal">
    <div class="modal-box bkg-modal-md">
        <div class="modal-header">
            <h3><i class="fas fa-money-bill-wave"></i> បន្ថែមការគិតប្រាក់បន្ថែម</h3>
            <button class="modal-close" onclick="document.getElementById('extraChargeModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="extraChargeForm" method="POST">
            @csrf
            <div class="modal-body">

                <div class="form-group bkg-modal-form-group">
                    <label class="form-label">ប្រភេទការគិតប្រាក់</label>
                    <select name="charge_type" id="chargeTypeSelect" class="form-control" required onchange="toggleChargeAmountField(this.value)">
                        <option value="extra_charge">ការគិតប្រាក់បន្ថែម (Extra Charge)</option>
                        <option value="empty_return">ត្រឡប់ទទេ (Empty Return)</option>
                        <option value="overweight">ទម្ងន់លើស (Over Weight)</option>
                        <option value="standby">ឈប់រង់ចាំ (Standby — $50/ថ្ងៃ)</option>
                    </select>
                </div>

                <div class="form-group bkg-modal-form-group" id="chargeAmountField">
                    <label class="form-label">ចំនួនទឹកប្រាក់ (USD)</label>
                    <input type="number" name="amount" id="chargeAmountInput" class="form-control" placeholder="ឧ. 25.00" min="0" step="0.01">
                </div>

                <div class="form-group bkg-modal-form-group bkg-hidden" id="chargeDaysField">
                    <label class="form-label">ចំនួនថ្ងៃឈប់រង់ចាំ</label>
                    <input type="number" name="days" id="chargeDaysInput" class="form-control" placeholder="ឧ. 2" min="1" step="1" oninput="updateStandbyTotal(this.value)">
                    <p class="bkg-standby-hint">
                        សរុប: <strong id="standbyTotalDisplay" class="bkg-standby-total">$0.00</strong>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">កំណត់ចំណាំ (ស្រេចចិត្ត)</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="ព័ត៌មានបន្ថែម..."></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('extraChargeModal').classList.remove('open')">
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
function confirmDeleteBooking(id) {
    document.getElementById('deleteBookingForm').action = '{{ url("/admin/bookings") }}/' + id;
    document.getElementById('deleteBookingModal').classList.add('open');
}

function openExtraChargeModal(bookingId) {
    document.getElementById('extraChargeForm').action = '{{ url("/admin/bookings") }}/' + bookingId + '/extra-charges';
    document.getElementById('chargeTypeSelect').value = 'extra_charge';
    document.getElementById('chargeAmountInput').value = '';
    document.getElementById('chargeDaysInput').value = '';
    document.getElementById('standbyTotalDisplay').textContent = '$0.00';
    toggleChargeAmountField('extra_charge');
    document.getElementById('extraChargeModal').classList.add('open');
}

function toggleChargeAmountField(type) {
    var amountField = document.getElementById('chargeAmountField');
    var daysField    = document.getElementById('chargeDaysField');
    var amountInput  = document.getElementById('chargeAmountInput');
    var daysInput    = document.getElementById('chargeDaysInput');
    if (type === 'standby') {
        amountField.style.display = 'none';
        amountInput.required      = false;
        daysField.style.display   = 'block';
        daysInput.required        = true;
    } else {
        amountField.style.display = 'block';
        amountInput.required      = true;
        daysField.style.display   = 'none';
        daysInput.required        = false;
    }
}

function updateStandbyTotal(days) {
    var total = (parseInt(days) || 0) * 50;
    document.getElementById('standbyTotalDisplay').textContent = '$' + total.toFixed(2);
}

function toggleCompletionCharge(type) {
    var daysField   = document.getElementById('completionDaysField');
    var amountField = document.getElementById('completionAmountField');
    var daysInput   = document.getElementById('completionDaysInput');
    var amtInput    = document.getElementById('completionAmountInput');
    daysField.classList.add('bkg-hidden');
    amountField.classList.add('bkg-hidden');
    daysInput.required = false;
    amtInput.required  = false;
    if (type === 'standby') {
        daysField.classList.remove('bkg-hidden');
        daysInput.required = true;
    } else if (type !== '') {
        amountField.classList.remove('bkg-hidden');
        amtInput.required = true;
    }
}

function updateCompletionTotal(days) {
    var total = (parseInt(days) || 0) * 50;
    document.getElementById('completionTotalDisplay').textContent = '$' + total.toFixed(2);
}

var paymentStatusLabel = {
    'unpaid':       'មិនទាន់បង់ប្រាក់',
    'deposit_paid': 'បានបង់ប្រាក់កក់ 50%',
    'fully_paid':   'បានបង់ប្រាក់',
};

function showBookingDetail(b) {
    document.getElementById('bd_id').textContent       = b.formatted_id;
    document.getElementById('bd_name').textContent      = (b.customer && b.customer.full_name) || '—';
    document.getElementById('bd_phone').textContent     = (b.customer && b.customer.phone) || '—';
    document.getElementById('bd_email').textContent     = (b.customer && b.customer.email) || '—';
    document.getElementById('bd_type').textContent      = b.booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ';
    document.getElementById('bd_container').textContent = b.container_number || '—';
    document.getElementById('bd_truck').textContent     = b.truck ? (b.truck.truck_name + ' — ' + b.truck.plate_number) : '—';
    document.getElementById('bd_weight').textContent    = b.cargo_weight ? Number(b.cargo_weight).toLocaleString() + ' kg' : '—';
    document.getElementById('bd_pickup').textContent    = b.pickup_location || '—';
    document.getElementById('bd_dropoff').textContent   = b.dropoff_location || '—';

    var mapLink = document.getElementById('bd_map_link');
    if (b.dropoff_location_link) {
        mapLink.href = b.dropoff_location_link;
        mapLink.style.display = 'inline';
    } else {
        mapLink.style.display = 'none';
    }

    document.getElementById('bd_pickdate').textContent = b.pick_up_date ? new Date(b.pick_up_date).toLocaleDateString('en-GB') : '—';
    document.getElementById('bd_dropdate').textContent = b.drop_off_date ? new Date(b.drop_off_date).toLocaleDateString('en-GB') : '—';

    var fileEl = document.getElementById('bd_file');
    if (b.cargo_list_file) {
        fileEl.innerHTML = '<a href="/' + b.cargo_list_file + '" target="_blank" rel="noopener" class="bkg-file-link">មើលឯកសារ <i class="fas fa-external-link-alt"></i></a>';
    } else {
        fileEl.textContent = '—';
    }

    var secondExtraSum = (b.extra_charges || []).filter(function(c){ return c.stage === 'second'; }).reduce(function(s,c){ return s + parseFloat(c.amount||0); }, 0);
    var trueTotal = (parseFloat(b.total_price||0)) + secondExtraSum;
    document.getElementById('bd_price').textContent = trueTotal > 0 ? '$' + trueTotal.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}) : '—';
    var statusLabel = {
        'pending':     'កំពុងរងចាំ',
        'confirmed':   'បានអនុម័ត',
        'in_progress': 'កំពុងដឹក',
        'completed':   'បានបញ្ចប់',
        'cancelled':   'បានលុប',
    };
    document.getElementById('bd_paystatus').textContent = paymentStatusLabel[b.payment_status] || (b.payment_status || '—');
    document.getElementById('bd_status').textContent    = statusLabel[b.status] || b.status || '—';
    document.getElementById('bd_bookdate').textContent  = b.booking_date ? new Date(b.booking_date).toLocaleDateString('en-GB') : '—';

    var chargeRespLabel = { 'Pending': 'រង់ចាំការឆ្លើយតប', 'Accepted': 'យល់ព្រម', 'Rejected': 'បដិសេធ' };
    var chargeRespColor = { 'Pending': '#92400e', 'Accepted': '#065f46', 'Rejected': '#991b1b' };
    var chargesEl = document.getElementById('bd_charges_list');
    var charges = b.extra_charges || [];
    if (charges.length === 0) {
        chargesEl.innerHTML = '<span class="bkg-detail-val bkg-phone">មិនមានការគិតប្រាក់បន្ថែម</span>';
    } else {
        chargesEl.innerHTML = charges.map(function (c) {
            var color = chargeRespColor[c.client_response] || '#64748b';
            var label = chargeRespLabel[c.client_response] || c.client_response;
            return '<div class="bkg-charge-row">' +
                   '<span class="bkg-charge-reason">' + c.reason + '</span>' +
                   '<span class="bkg-charge-meta">' +
                   '<strong class="bkg-charge-amount">$' + Number(c.amount).toLocaleString(undefined,{minimumFractionDigits:2}) + '</strong>' +
                   '<span class="bkg-charge-resp" style="color:' + color + ';">' + label + '</span>' +
                   '</span></div>';
        }).join('');
    }

    document.getElementById('bookingDetailModal').classList.add('open');
}

function changeStatus(id, currentStatus) {
    document.getElementById('statusForm').action = '/admin/bookings/' + id + '/status';
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('totalPriceInput').value = '';
    document.getElementById('halfPrice').textContent = '0.00';
    document.getElementById('completionChargeType').value = '';
    document.getElementById('completionDaysInput').value = '';
    document.getElementById('completionAmountInput').value = '';
    document.getElementById('completionTotalDisplay').textContent = '$0.00';
    toggleCompletionCharge('');
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