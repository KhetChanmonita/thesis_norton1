@extends('admin.layouts.admin')
@section('title','ការកក់')
@section('page-title')<span>គ្រប់គ្រង</span>ការកក់@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_bookings.css') }}">
@endpush

@section('content')
@php
$statTotal     = \App\Models\Booking::count();
$statPending   = \App\Models\Booking::where('status','pending')->count();
$statProgress  = \App\Models\Booking::where('status','in_progress')->count();
$statDone      = \App\Models\Booking::where('status','completed')->count();
$statCancelled = \App\Models\Booking::where('status','cancelled')->count();
@endphp

{{-- Stats Row --}}
<div class="bks-stats">
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:linear-gradient(135deg,#FF6B00,#ff9040);">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num">{{ $statTotal }}</div>
            <div class="bks-stat-lbl">ការកក់សរុប</div>
        </div>
    </div>
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num bks-num-amber">{{ $statPending }}</div>
            <div class="bks-stat-lbl">រង់ចាំ</div>
        </div>
    </div>
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);">
            <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num bks-num-blue">{{ $statProgress }}</div>
            <div class="bks-stat-lbl">កំពុងដឹក</div>
        </div>
    </div>
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num bks-num-green">{{ $statDone }}</div>
            <div class="bks-stat-lbl">បានបញ្ចប់</div>
        </div>
    </div>
    <div class="bks-stat">
        <div class="bks-stat-icon" style="background:linear-gradient(135deg,#ef4444,#f87171);">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="bks-stat-body">
            <div class="bks-stat-num bks-num-red">{{ $statCancelled }}</div>
            <div class="bks-stat-lbl">បានលុបចោល</div>
        </div>
    </div>
</div>

{{-- Toolbar: filter + new booking --}}
<div class="bks-toolbar">
    <form method="GET" class="bks-filter">
        <div class="bks-filter-group">
            <span class="bks-filter-icon"><i class="fas fa-search"></i></span>
            <input type="text" name="search" class="bks-filter-input"
                   placeholder="ស្វែងរកអតិថិជន..."
                   value="{{ request('search') }}">
        </div>
        <div class="bks-filter-group bks-filter-group-sm">
            <span class="bks-filter-icon"><i class="fas fa-hashtag"></i></span>
            <input type="text" name="booking_code" class="bks-filter-input"
                   placeholder="LS2608-1"
                   value="{{ request('booking_code') }}">
        </div>
        <div class="bks-filter-group bks-filter-group-select">
            <select name="status" class="bks-filter-select">
                <option value="">ស្ថានភាពទាំងអស់</option>
                @foreach(['pending'=>'រង់ចាំ','confirmed'=>'បានអនុម័ត','in_progress'=>'កំពុងដឹក','completed'=>'បានបញ្ចប់','cancelled'=>'បានលុប'] as $val=>$lbl)
                <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bks-btn-search">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request()->hasAny(['search','booking_code','status']))
        <a href="{{ route('admin.bookings.index') }}" class="bks-btn-clear" title="សម្អាតការស្វែងរក">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
    <button type="button" class="bks-btn-new"
            onclick="document.getElementById('adminBookingModal').classList.add('open')">
        <i class="fas fa-plus"></i> បង្កើតការកក់ថ្មី
    </button>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-clipboard-list"></i>
            បញ្ជីការកក់
            <span class="bks-total-badge">{{ $bookings->total() }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="bks-th-num">#</th>
                    <th>លេខការកក់</th>
                    <th>អតិថិជន</th>
                    <th>ប្រភេទ</th>
                    <th>ទីតាំង</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th class="bks-th-right">ទម្ងន់ (kg)</th>
                    <th class="bks-th-right">តម្លៃ ($)</th>
                    <th>ស្ថានភាព</th>
                    <th class="bks-th-center">សកម្មភាព</th>
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
                        @if($b->customer)
                            <strong>{{ $b->customer->full_name }}</strong><br>
                            <small class="bkg-phone">{{ $b->customer->phone ?? '' }}</small>
                        @elseif($b->bookedByUser)
                            <strong>{{ $b->bookedByUser->user_name }}</strong><br>
                            <small class="bkg-phone" style="color:#FF6B00;">{{ ucfirst($b->bookedByUser->role) }}</small>
                        @else
                            <span>—</span>
                        @endif
                    </td>
                    <td>
                        <span class="bks-type-chip bks-type-{{ $b->booking_type }}">
                            {{ $b->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ' }}
                        </span>
                    </td>
                    <td>
                        <div class="bks-loc">
                            <div class="bks-loc-row bks-loc-from">
                                <i class="fas fa-circle"></i>
                                {{ Str::limit($b->pickup_location, 22) }}
                            </div>
                            <div class="bks-loc-arrow"><i class="fas fa-long-arrow-alt-down"></i></div>
                            <div class="bks-loc-row bks-loc-to">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ Str::limit($b->dropoff_location, 22) }}
                                @if($b->dropoff_location_link)
                                    <a href="{{ $b->dropoff_location_link }}" target="_blank" rel="noopener" class="bkg-map-link" title="Google Maps">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="bks-dates">
                            <div class="bks-date-row">
                                <span class="bks-date-lbl">ទទួល</span>
                                {{ $b->pick_up_date ? \Carbon\Carbon::parse($b->pick_up_date)->format('d/m/Y') : '—' }}
                            </div>
                            <div class="bks-date-row">
                                <span class="bks-date-lbl">ដឹង</span>
                                {{ $b->drop_off_date ? \Carbon\Carbon::parse($b->drop_off_date)->format('d/m/Y') : '—' }}
                            </div>
                        </div>
                    </td>
                    <td class="bks-td-right">
                        <span class="bks-weight">{{ $b->cargo_weight ? number_format($b->cargo_weight) : '—' }}</span>
                    </td>
                    <td class="bks-td-right">
                        @php
                            $secondSum = $b->extraCharges->where('stage','second')->sum('amount');
                            $trueTotal = ($b->total_price ?? 0) + $secondSum;
                        @endphp
                        @if($trueTotal > 0)
                            <span class="bks-price">${{ number_format($trueTotal, 2) }}</span>
                        @else
                            <span class="bks-dash">—</span>
                        @endif
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
                            <button class="btn btn-ghost btn-sm" onclick="changeStatus({{ $b->booking_id }}, '{{ $b->status }}', {{ $b->total_price ?? 0 }}, '{{ $b->payment_status }}')" title="ប្ដូរស្ថានភាព">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            @endif
                            @if(in_array($b->status, ['completed', 'cancelled']))
                            <button class="btn btn-ghost btn-sm bkg-btn-disabled" disabled title="{{ $b->status === 'cancelled' ? 'ការកក់ត្រូវបានលុបចោល' : 'ការកក់បានបញ្ចប់ — មិនអាចបន្ថែមការគិតប្រាក់បន្ថែមទៀតបានទេ' }}">
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
                <div class="bkg-detail-title"><i class="fas fa-user"></i> <span id="bd_section_label">ព័ត៌មានអតិថិជន</span></div>
                <div class="bkg-detail-grid">
                    <div><span class="bkg-detail-lbl">ឈ្មោះ</span><span class="bkg-detail-val" id="bd_name"></span></div>
                    <div><span class="bkg-detail-lbl" id="bd_phone_lbl">ទូរស័ព្ទ</span><span class="bkg-detail-val" id="bd_phone"></span></div>
                    <div><span class="bkg-detail-lbl" id="bd_email_lbl">អ៊ីមែល</span><span class="bkg-detail-val" id="bd_email"></span></div>
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
                        <option value="pending">រង់ចាំ — Pending</option>
                        <option value="confirmed">បានអនុម័ត — Confirmed</option>
                        <option value="in_progress">កំពុងដឹក — In Progress</option>
                        <option value="completed">បានបញ្ចប់ — Completed</option>
                        <option value="cancelled">បានលុប — Cancelled</option>
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
                                       min="0" step="0.01" readonly
                                       style="background:#f8fafc;cursor:not-allowed;color:#64748b;">
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
                            <span style="display:block;font-size:0.74rem;color:#b45309;margin-top:4px;font-weight:500;">
                                <i class="fas fa-info-circle"></i>
                                ថ្លៃ Standby ប្រសិនបើមាន → ទូទាត់ 100% ក្នុងការទូទាត់ចុងក្រោយ
                            </span>
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
                                <span class="bkg-standby-full-note">— ទូទាត់ 100% ក្នុងការទូទាត់ចុងក្រោយ (មិនបែង 50%)</span>
                            </p>
                        </div>

                        <div id="completionAmountField" class="form-group bkg-modal-form-group bkg-hidden" style="margin-bottom:10px;">
                            <label class="form-label" style="font-size:0.8rem;">ចំនួនទឹកប្រាក់ (USD)</label>
                            <input type="number" name="completion_amount" id="completionAmountInput"
                                   class="form-control" placeholder="ឧ. 25.00" min="0" step="0.01">
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:0.8rem;">កំណត់ចំណាំ</label>
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
                        <span class="bkg-standby-full-note">— ទូទាត់ 100% ក្នុងការទូទាត់ចុងក្រោយ (មិនបែង 50%)</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">កំណត់ចំណាំ</label>
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
    // Customer info: external customer OR internal staff user
    var isInternal = !b.customer && b.booked_by_user;
    document.getElementById('bd_section_label').textContent = isInternal ? 'ព័ត៌មានអ្នកស្នើ (បុគ្គលិក)' : 'ព័ត៌មានអតិថិជន';
    document.getElementById('bd_name').textContent  = b.customer ? (b.customer.full_name || '—')
                                                    : (b.booked_by_user ? b.booked_by_user.user_name : '—');
    document.getElementById('bd_phone_lbl').textContent = isInternal ? 'សិទ្ធិ' : 'ទូរស័ព្ទ';
    document.getElementById('bd_phone').textContent = b.customer ? (b.customer.phone || '—')
                                                    : (b.booked_by_user ? b.booked_by_user.role : '—');
    document.getElementById('bd_email_lbl').textContent = isInternal ? 'អ៊ីម៉ែល' : 'អ៊ីមែល';
    document.getElementById('bd_email').textContent = b.customer ? (b.customer.email || '—')
                                                    : (b.booked_by_user ? (b.booked_by_user.email || '—') : '—');
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

function changeStatus(id, currentStatus, bookingPrice, paymentStatus) {
    document.getElementById('statusForm').action = '/admin/bookings/' + id + '/status';
    document.getElementById('statusSelect').value = currentStatus;

    // "in_progress" and "completed" only available after customer pays first 50%
    var firstPaid = (paymentStatus === 'deposit_paid' || paymentStatus === 'fully_paid');
    [
        { val: 'in_progress', label: 'កំពុងដឹក — In Progress' },
        { val: 'completed',   label: 'បានបញ្ចប់ — Completed'  },
    ].forEach(function(o) {
        var opt = document.querySelector('#statusSelect option[value="' + o.val + '"]');
        if (!opt) return;
        opt.disabled = !firstPaid;
        opt.style.display = firstPaid ? '' : 'none';
        opt.textContent = firstPaid ? o.label : o.label + ' (រង់ចាំ 50% ដំបូង)';
    });

    // Auto-fill price from customer booking
    var price = parseFloat(bookingPrice) || 0;
    document.getElementById('totalPriceInput').value = price > 0 ? price : '';
    document.getElementById('halfPrice').textContent = price > 0 ? (price / 2).toFixed(2) : '0.00';
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

{{-- ══════════════════════════════════════════════════════════
     ADMIN CREATE BOOKING MODAL
══════════════════════════════════════════════════════════ --}}
<style>
/* ── Overlay ── */
.ab-overlay {
    position:fixed;inset:0;
    background:rgba(15,23,42,.55);
    backdrop-filter:blur(6px);
    display:flex;align-items:center;justify-content:center;
    z-index:9999;opacity:0;pointer-events:none;
    transition:opacity .28s;
}
.ab-overlay.open { opacity:1;pointer-events:all; }

/* ── Modal shell ── */
.ab-modal {
    background:#fff;border-radius:22px;
    width:820px;max-width:96vw;
    max-height:92vh;overflow-y:auto;overflow-x:hidden;
    box-shadow:0 32px 80px rgba(0,0,0,.22);
    transform:scale(.94) translateY(22px);
    transition:transform .32s cubic-bezier(.34,1.56,.64,1);
    display:flex;flex-direction:column;
}
.ab-overlay.open .ab-modal { transform:scale(1) translateY(0); }

/* Orange accent stripe */
.ab-modal-stripe {
    height:4px;
    background:linear-gradient(90deg,#FF6B00 0%,#ff9040 60%,#ffcba0 100%);
    border-radius:22px 22px 0 0;
    flex-shrink:0;
}

/* ── Header ── */
.ab-modal-head {
    display:flex;align-items:center;justify-content:space-between;
    padding:20px 28px 18px;
    border-bottom:1px solid #f1f5f9;
    position:sticky;top:0;background:#fff;z-index:2;
    flex-shrink:0;
}
.ab-modal-title { display:flex;align-items:center;gap:14px; }
.ab-modal-icon {
    width:48px;height:48px;border-radius:14px;
    background:linear-gradient(135deg,#FF6B00,#ff9040);
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:1.15rem;flex-shrink:0;
    box-shadow:0 4px 14px rgba(255,107,0,.36);
}
.ab-title-text { display:flex;flex-direction:column;gap:3px; }
.ab-title-main {
    font-family:var(--font-head);font-size:1.08rem;font-weight:800;color:#1e293b;
}
.ab-title-sub {
    font-family:var(--font);font-size:.73rem;color:#94a3b8;font-weight:500;
}
.ab-close {
    width:36px;height:36px;border-radius:10px;
    background:#f8fafc;border:1px solid #e5eaf0;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    color:#64748b;font-size:.88rem;transition:all .2s;flex-shrink:0;
}
.ab-close:hover { background:#fee2e2;border-color:#fca5a5;color:#dc2626; }

/* ── Body ── */
.ab-modal-body { padding:6px 28px 28px;flex:1; }

/* ── Section step headers ── */
.ab-step {
    display:flex;align-items:center;gap:12px;
    margin:28px 0 16px;
}
.ab-step-num {
    width:30px;height:30px;border-radius:9px;
    background:linear-gradient(135deg,#FF6B00,#ff9040);
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-family:var(--font-head);font-size:.8rem;font-weight:800;
    flex-shrink:0;box-shadow:0 3px 10px rgba(255,107,0,.32);
}
.ab-step-title {
    font-family:var(--font);font-size:.84rem;font-weight:700;color:#374151;
    white-space:nowrap;
}
.ab-step-rule { flex:1;height:1px;background:linear-gradient(90deg,#f1f5f9,transparent); }

/* ── Form layout ── */
.ab-row  { display:flex;gap:16px;flex-wrap:wrap; }
.ab-group { display:flex;flex-direction:column;flex:1;min-width:200px; }
.ab-group.half { flex:0 0 calc(50% - 8px); }
.ab-group.full { flex:0 0 100%; }

/* ── Labels ── */
.ab-label {
    font-family:var(--font);font-size:.76rem;font-weight:700;
    color:#4b5563;margin-bottom:3px;
    display:flex;align-items:baseline;gap:4px;flex-wrap:wrap;
}
.ab-req { color:#ef4444;margin-left:1px; }

/* ── Inputs ── */
.ab-input,.ab-select {
    border:1.5px solid #e5eaf0;border-radius:10px;
    padding:10px 14px;font-family:var(--font);font-size:.9rem;
    color:#1e293b;background:#fff;outline:none;width:100%;
    transition:border-color .2s,box-shadow .2s;
}
.ab-select {
    appearance:none;-webkit-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='%2394a3b8' d='M0 0l5 6 5-6z'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 14px center;
    background-color:#fff;padding-right:38px;
}
.ab-input:focus,.ab-select:focus {
    border-color:#FF6B00;
    box-shadow:0 0 0 3px rgba(255,107,0,.13);
}
.ab-input.is-err,.ab-select.is-err {
    border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.1);
}
.ab-err-msg { font-size:.73rem;color:#ef4444;margin-top:4px; }

/* ── Hints ── */
.ab-truck-hint {
    font-size:.73rem;color:#f59e0b;margin-top:5px;
    display:flex;align-items:center;gap:5px;
}
.ab-price-hint { font-size:.73rem;color:#94a3b8;margin-top:5px; }

/* ── File upload zone ── */
.ab-upload-zone {
    display:flex;align-items:center;gap:16px;
    border:2px dashed #e2e8f0;border-radius:12px;
    padding:16px 20px;cursor:pointer;
    background:#fafbfc;
    transition:border-color .22s,background .22s;
}
.ab-upload-zone:hover { border-color:#FF6B00;background:#fff9f5; }
.ab-upload-icon { font-size:1.7rem;color:#FF6B00;flex-shrink:0; }
.ab-upload-name {
    font-weight:600;color:#374151;font-size:.88rem;
    transition:color .2s;
}
.ab-upload-hint { font-size:.73rem;color:#94a3b8;margin-top:2px; }

/* ── Footer ── */
.ab-modal-foot {
    display:flex;align-items:center;justify-content:space-between;
    gap:10px;padding:16px 28px;
    border-top:1px solid #f1f5f9;
    background:#fafbfc;
    border-radius:0 0 22px 22px;
    position:sticky;bottom:0;flex-shrink:0;
}
.ab-foot-note {
    font-size:.73rem;color:#94a3b8;
    display:flex;align-items:center;gap:5px;
}
.ab-foot-note i { color:#FF6B00; }
.ab-foot-actions { display:flex;gap:10px; }

.ab-btn-cancel {
    padding:10px 22px;
    border:1.5px solid #e2e8f0;background:#fff;border-radius:10px;
    font-family:var(--font);font-size:.88rem;font-weight:600;
    color:#374151;cursor:pointer;
    display:flex;align-items:center;gap:7px;
    transition:background .2s,border-color .2s;
}
.ab-btn-cancel:hover { background:#f8fafc;border-color:#cbd5e1; }

.ab-btn-save {
    padding:10px 26px;
    background:linear-gradient(135deg,#FF6B00,#ff8c38);
    border:none;border-radius:10px;
    font-family:var(--font);font-size:.88rem;font-weight:700;
    color:#fff;cursor:pointer;
    box-shadow:0 4px 14px rgba(255,107,0,.38);
    transition:opacity .2s,transform .15s;
    display:flex;align-items:center;gap:8px;
}
.ab-btn-save:hover  { opacity:.9;transform:translateY(-1px); }
.ab-btn-save:active { transform:translateY(0); }

/* ── Container weight badge & overweight alert (reused from trucks_section) ── */
.ts-container-weight-badge {
    display:inline-flex;align-items:center;gap:6px;
    margin-top:6px;padding:4px 10px;
    font-family:var(--font);font-size:.75rem;font-weight:600;
    color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;
}
.ts-ow-alert {
    display:flex;align-items:flex-start;gap:7px;
    margin-top:8px;padding:8px 12px;
    font-family:var(--font);font-size:.78rem;border-radius:8px;line-height:1.5;
}
.ts-ow-alert i { flex-shrink:0;margin-top:2px;font-size:.85rem; }
.ts-ow-ok  { color:#065f46;background:#d1fae5;border:1px solid #6ee7b7; }
.ts-ow-ok i  { color:#059669; }
.ts-ow-warn { color:#7c2d12;background:#fff7ed;border:1px solid #fed7aa; }
.ts-ow-warn i { color:#ea580c; }
</style>

<div class="ab-overlay" id="adminBookingModal" onclick="if(event.target===this)closeAdminBooking()">
    <div class="ab-modal">
        <div class="ab-modal-stripe"></div>
        <div class="ab-modal-head">
            <div class="ab-modal-title">
                <div class="ab-modal-icon"><i class="fas fa-truck"></i></div>
                <div class="ab-title-text">
                    <div class="ab-title-main">បង្កើតការកក់ថ្មី</div>
                    <div class="ab-title-sub">Admin · ការកក់ដោយផ្ទាល់ពីក្រុមហ៊ុន</div>
                </div>
            </div>
            <button class="ab-close" onclick="closeAdminBooking()"><i class="fas fa-times"></i></button>
        </div>

        <form method="POST" action="{{ route('admin.bookings.store') }}" id="adminBookingForm" enctype="multipart/form-data">
            @csrf
            {{-- Sentinel: tells server this error came from this modal --}}
            <input type="hidden" name="_from_admin_booking" value="1">
            <div class="ab-modal-body">

                {{-- Validation error banner --}}
                @if($errors->any() && old('_from_admin_booking'))
                <div style="margin:0 0 14px;padding:10px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:.8rem;color:#dc2626;font-family:var(--font);">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>មានបញ្ហា:</strong>
                    <ul style="margin:6px 0 0 18px;padding:0;">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- Section 1: Staff Requester & Truck --}}
                <div class="ab-step">
                    <div class="ab-step-num">1</div>
                    <div class="ab-step-title"><i class="fas fa-user-tie" style="color:#FF6B00;margin-right:6px;"></i>ព័ត៌មានអ្នកស្នើ និងរថយន្ត</div>
                    <div class="ab-step-rule"></div>
                </div>
                <div class="ab-row">
                    <div class="ab-group half">
                        <label class="ab-label">អ្នកស្នើការដឹក <span style="color:#94a3b8;font-weight:400;">(បុគ្គលិកក្រុមហ៊ុន)</span></label>
                        <select name="booked_by_user_id"
                                class="ab-select {{ $errors->has('booked_by_user_id') ? 'is-err' : '' }}"
                                required>
                            <option value="">-- ជ្រើសរើសសិទ្ធិ / បុគ្គលិក --</option>
                            @foreach($staffUsers as $u)
                                @php
                                    $roleLabel = ['admin'=>'Admin','operation'=>'Operation','accountant'=>'Accountant'][$u->role] ?? $u->role;
                                @endphp
                                <option value="{{ $u->user_id }}" {{ old('booked_by_user_id') == $u->user_id ? 'selected' : '' }}>
                                    {{ $u->user_name }} — {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('booked_by_user_id')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group half">
                        <label class="ab-label">រថយន្ត</label>
                        <select name="truck_id" class="ab-select {{ $errors->has('truck_id') ? 'is-err' : '' }}" required>
                            <option value="">-- ជ្រើសរើសរថយន្ត --</option>
                            @foreach($trucks as $t)
                                <option value="{{ $t->truck_id }}" {{ old('truck_id') == $t->truck_id ? 'selected' : '' }}>
                                    {{ $t->truck_name }} — {{ $t->plate_number }}
                                    @if($t->capacity_ton) ({{ $t->capacity_ton }}T) @endif
                                </option>
                            @endforeach
                        </select>
                        @if($trucks->isEmpty())
                            <div class="ab-truck-hint"><i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i> រថយន្តទំនេរគ្មាននៅពេលនេះ</div>
                        @endif
                        @error('truck_id')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Section 2: Booking Details --}}
                <div class="ab-step">
                    <div class="ab-step-num">2</div>
                    <div class="ab-step-title"><i class="fas fa-ship" style="color:#FF6B00;margin-right:6px;"></i>ព័ត៌មានការដឹក និងទីតាំង</div>
                    <div class="ab-step-rule"></div>
                </div>
                <div class="ab-row" style="margin-bottom:3px;">
                    <div class="ab-group half">
                        <label class="ab-label">ប្រភេទការដឹកជញ្ជូន</label>
                        <select name="booking_type" id="ab_bookingType"
                                class="ab-select {{ $errors->has('booking_type') ? 'is-err' : '' }}"
                                required onchange="abToggleType(this.value); abAdminLookupPrice();">
                            <option value="">-- ជ្រើសរើស --</option>
                            <option value="import" {{ old('booking_type')=='import'?'selected':'' }}>នាំចូល (Import)</option>
                            <option value="export" {{ old('booking_type')=='export'?'selected':'' }}>នាំចេញ (Export)</option>
                        </select>
                        @error('booking_type')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group half">
                        <label class="ab-label">
                            លេខកុងតឺន័រ
                            <span style="color:#94a3b8;font-weight:400;font-size:.75rem;">(អក្សរ ៤ ចុងជា U + លេខ ៧ ខ្ទង់ — ឧ. TIIU1234567)</span>
                        </label>
                        <input type="text" name="container_number"
                               value="{{ old('container_number') }}"
                               class="ab-input {{ $errors->has('container_number') ? 'is-err' : '' }}"
                               placeholder="ឧ. TIIU1234567"
                               pattern="[A-Za-z]{3}[Uu][0-9]{7}"
                               maxlength="11"
                               style="text-transform:uppercase;">
                        @error('container_number')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group half">
                        <label class="ab-label">ទំហំកុងតឺន័រ</label>
                        <select name="container_size" id="ab_containerSize" class="ab-select" onchange="abAdminOnContainerChange()">
                            <option value="">-- ជ្រើសរើស --</option>
                            <option value="20F" {{ old('container_size')=='20F'?'selected':'' }}>20F</option>
                            <option value="40F" {{ old('container_size')=='40F'?'selected':'' }}>40F</option>
                            <option value="45F" {{ old('container_size')=='45F'?'selected':'' }}>45F</option>
                        </select>
                        <div id="ab_containerWeightBadge" class="ts-container-weight-badge" style="display:none;"></div>
                    </div>
                </div>

                {{-- Locations --}}
                <div class="ab-row" style="margin-top:3px;">
                    <div class="ab-group half" id="ab_pickupField">
                        <label class="ab-label"><span id="ab_pickupLabel">ទីតាំងទទួល</span></label>
                        <input type="text" name="pickup_location" id="ab_pickupInput"
                               value="{{ old('pickup_location') }}"
                               class="ab-input {{ $errors->has('pickup_location') ? 'is-err' : '' }}"
                               placeholder="ឧ. កំពង់ផែស្វ័យយ័តភ្នំពេញ" required>
                        <select name="pickup_location" id="ab_pickupSelect"
                                class="ab-select {{ $errors->has('pickup_location') ? 'is-err' : '' }}"
                                style="display:none;" disabled onchange="abAdminLookupPrice()">
                            <option value="">-- ជ្រើសរើសកំពង់ផែ --</option>
                            <option value="កំពង់ផែស្វ័យយ័តព្រះសីហនុ" data-key="sihanoukville" {{ old('pickup_location')=='កំពង់ផែស្វ័យយ័តព្រះសីហនុ'?'selected':'' }}>
                                កំពង់ផែស្វ័យយ័តព្រះសីហនុ (SHV Port)
                            </option>
                            <option value="កំពង់ផែស្វ័យយ័តភ្នំពេញ" data-key="phnom_penh" {{ old('pickup_location')=='កំពង់ផែស្វ័យយ័តភ្នំពេញ'?'selected':'' }}>
                                កំពង់ផែស្វ័យយ័តភ្នំពេញ (Phnom Penh Port)
                            </option>
                        </select>
                        @error('pickup_location')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group half">
                        <label class="ab-label"><span id="ab_dropoffLabel">ទីតាំងដឹកទៅ</span></label>
                        <select name="dropoff_location" id="ab_dropoffProvince"
                                class="ab-select {{ $errors->has('dropoff_location') ? 'is-err' : '' }}" required
                                onchange="abAdminLookupPrice()">
                            <option value="">-- ជ្រើសរើសខេត្ត/ក្រុង --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p['km'] }}" {{ old('dropoff_location')==$p['km']?'selected':'' }}>
                                    {{ $p['km'] }} ({{ $p['en'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('dropoff_location')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group full">
                        <label class="ab-label">
                            Google Maps Link
                            <span style="color:#94a3b8;font-weight:400;">(ជម្រើស — ចម្លងតំណពី Google Maps)</span>
                        </label>
                        <input type="url" name="dropoff_location_link"
                               value="{{ old('dropoff_location_link') }}"
                               class="ab-input"
                               placeholder="https://maps.google.com/?q=...">
                        @error('dropoff_location_link')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Dates --}}
                <div class="ab-row" style="margin-top:3px;">
                    <div class="ab-group half">
                        <label class="ab-label"><span id="ab_pickupDateLabel">កាលបរិច្ឆេទថ្ងៃដឹក</span></label>
                        <input type="date" name="pick_up_date" id="ab_pickUpDate"
                               value="{{ old('pick_up_date', now()->toDateString()) }}"
                               class="ab-input {{ $errors->has('pick_up_date') ? 'is-err' : '' }}"
                               required
                               onchange="document.getElementById('ab_dropOffDate').min = this.value;">
                        @error('pick_up_date')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group half">
                        <label class="ab-label"><span id="ab_dropoffDateLabel">កាលបរិច្ឆេទថ្ងៃទម្លាក់</span></label>
                        <input type="date" name="drop_off_date" id="ab_dropOffDate"
                               value="{{ old('drop_off_date') }}"
                               class="ab-input {{ $errors->has('drop_off_date') ? 'is-err' : '' }}"
                               required>
                        @error('drop_off_date')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Cargo & Price --}}
                <div class="ab-row" style="margin-top:3px;">
                    <div class="ab-group half">
                        <label class="ab-label">ទម្ងន់ទំនិញ (គីឡូក្រាម)</label>
                        <input type="number" name="cargo_weight" id="ab_cargoWeight" step="0.01" min="1"
                               value="{{ old('cargo_weight') }}"
                               class="ab-input {{ $errors->has('cargo_weight') ? 'is-err' : '' }}"
                               placeholder="ឧ. 12000" required oninput="abAdminUpdateOverweight()">
                        <div id="ab_overweightAlert" class="ts-ow-alert" style="display:none;"></div>
                        @error('cargo_weight')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="ab-group half">
                        <label class="ab-label">
                            តម្លៃដឹកជញ្ជូន ($)
                        </label>
                        <input type="number" name="total_price" step="0.01" min="0"
                               value="{{ old('total_price') }}"
                               class="ab-input"
                               id="ab_total_price"
                               placeholder="ឧ. 500"
                               oninput="updateHalf()">
                        <div class="ab-price-hint">50% បង់ជាមុន = $<span id="ab_half">0.00</span></div>
                        @error('total_price')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Section 3: Cargo File Upload --}}
                <div class="ab-step">
                    <div class="ab-step-num">3</div>
                    <div class="ab-step-title"><i class="fas fa-file-upload" style="color:#FF6B00;margin-right:6px;"></i>ឯកសារ / បញ្ជីទំនិញ</div>
                    <div class="ab-step-rule"></div>
                </div>
                <div class="ab-row">
                    <div class="ab-group full">
                        <label class="ab-label">
                            ឯកសារបញ្ជីទំនិញ
                            <span style="color:#94a3b8;font-weight:400;">(PDF, JPG, PNG — អតិបរមា 5MB)</span>
                        </label>
                        <label for="ab_cargoFile" id="ab_cargoFileLabel" class="ab-upload-zone">
                            <i class="fas fa-cloud-upload-alt ab-upload-icon"></i>
                            <div>
                                <div id="ab_cargoFileName" class="ab-upload-name">ចុចដើម្បីជ្រើសរើសឯកសារ</div>
                                <div class="ab-upload-hint">ឧ. Contract Release, Packing List</div>
                            </div>
                        </label>
                        <input type="file" name="cargo_list_file" id="ab_cargoFile"
                               accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                               onchange="abUpdateCargoName(this)">
                        @error('cargo_list_file')<div class="ab-err-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>{{-- /.ab-modal-body --}}

            <div class="ab-modal-foot">
                <div class="ab-foot-note">
                    <i class="fas fa-info-circle"></i> វាលដែលមានពណ៌ក្រហម ចាំបាច់ត្រូវបំពេញ
                </div>
                <div class="ab-foot-actions">
                    <button type="button" class="ab-btn-cancel" onclick="closeAdminBooking()">
                        <i class="fas fa-times"></i> បោះបង់
                    </button>
                    <button type="submit" class="ab-btn-save">
                        <i class="fas fa-save"></i> រក្សាទុកការកក់
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function closeAdminBooking() {
    document.getElementById('adminBookingModal').classList.remove('open');
}


function updateHalf() {
    var v = parseFloat(document.getElementById('ab_total_price').value || 0);
    document.getElementById('ab_half').textContent = (v / 2).toFixed(2);
}

function abUpdateCargoName(input) {
    var el = document.getElementById('ab_cargoFileName');
    el.textContent = input.files.length ? input.files[0].name : 'ចុចដើម្បីជ្រើសរើសឯកសារ';
}

function abToggleType(type) {
    var pickupLabel    = document.getElementById('ab_pickupLabel');
    var dropoffLabel   = document.getElementById('ab_dropoffLabel');
    var pickUpLbl      = document.getElementById('ab_pickupDateLabel');
    var dropOffLbl     = document.getElementById('ab_dropoffDateLabel');
    var pickupInput    = document.getElementById('ab_pickupInput');
    var pickupSelect   = document.getElementById('ab_pickupSelect');

    if (type === 'import') {
        pickupLabel.textContent  = 'ទីតាំងលើកទំនិញ';
        dropoffLabel.textContent = 'ទីតាំងទម្លាក់ទំនិញ';
        pickUpLbl.textContent    = 'ថ្ងៃដឹក (ថ្ងៃលើក)';
        dropOffLbl.textContent   = 'ថ្ងៃទម្លាក់ (ដល់ដៃ)';
        // Switch pickup to port select
        pickupInput.style.display  = 'none';
        pickupInput.disabled       = true;
        pickupInput.required       = false;
        pickupSelect.style.display = 'block';
        pickupSelect.disabled      = false;
        pickupSelect.required      = true;
    } else if (type === 'export') {
        pickupLabel.textContent  = 'ទីតាំងទម្លាក់ទំនិញ';
        dropoffLabel.textContent = 'ទីតាំងច្រកទំនិញ';
        pickUpLbl.textContent    = 'ថ្ងៃលើកទូរ';
        dropOffLbl.textContent   = 'ថ្ងៃឡើងទំនិញ';
        // Switch pickup to port select
        pickupInput.style.display  = 'none';
        pickupInput.disabled       = true;
        pickupInput.required       = false;
        pickupSelect.style.display = 'block';
        pickupSelect.disabled      = false;
        pickupSelect.required      = true;
    } else {
        pickupLabel.textContent  = 'ទីតាំងទទួល';
        dropoffLabel.textContent = 'ទីតាំងដឹកទៅ';
        pickUpLbl.textContent    = 'កាលបរិច្ឆេទថ្ងៃដឹក';
        dropOffLbl.textContent   = 'កាលបរិច្ឆេទថ្ងៃទម្លាក់';
        // Switch pickup back to text input
        pickupInput.style.display  = 'block';
        pickupInput.disabled       = false;
        pickupInput.required       = true;
        pickupSelect.style.display = 'none';
        pickupSelect.disabled      = true;
        pickupSelect.required      = false;
    }
}

// ===== Admin booking: auto-price, container weight, overweight =====

const abRatesAdmin = @json($ratesJson);
const AB_ADMIN_CONTAINER_WEIGHT = { '20F': 2000, '40F': 4000, '45F': 4500 };
const AB_ADMIN_TRUCK_TARE_KG    = 12000;
const AB_ADMIN_OW_THRESHOLD     = 40000;
const AB_ADMIN_OW_TIERS = [
    { max: 41000, charge: 30  },
    { max: 42000, charge: 60  },
    { max: 43000, charge: 90  },
    { max: 44000, charge: 120 },
    { max: Infinity, charge: 150 },
];

var abAdminBasePrice = 0; // base shipping rate, updated by abAdminLookupPrice

function abAdminGetOwCharge() {
    var cargoWeight     = parseFloat((document.getElementById('ab_cargoWeight') || {}).value) || 0;
    var size            = (document.getElementById('ab_containerSize') || {}).value || '';
    var containerWeight = AB_ADMIN_CONTAINER_WEIGHT[size] || 0;
    var totalWeight     = cargoWeight + containerWeight + AB_ADMIN_TRUCK_TARE_KG;
    if (totalWeight <= AB_ADMIN_OW_THRESHOLD || cargoWeight <= 0) return { charge: 0, totalWeight: totalWeight, overKg: 0 };
    var charge = 150;
    for (var i = 0; i < AB_ADMIN_OW_TIERS.length; i++) {
        if (totalWeight <= AB_ADMIN_OW_TIERS[i].max) { charge = AB_ADMIN_OW_TIERS[i].charge; break; }
    }
    return { charge: charge, totalWeight: totalWeight, overKg: totalWeight - AB_ADMIN_OW_THRESHOLD };
}

function abAdminRefreshTotal() {
    var cargoWeight = parseFloat((document.getElementById('ab_cargoWeight') || {}).value) || 0;
    var size        = (document.getElementById('ab_containerSize') || {}).value || '';
    var ow          = abAdminGetOwCharge();
    var total       = abAdminBasePrice + ow.charge;

    var priceInput = document.getElementById('ab_total_price');
    if (priceInput) {
        priceInput.value = (abAdminBasePrice > 0 || ow.charge > 0) ? total.toFixed(2) : '';
        updateHalf();
    }

    var alertEl = document.getElementById('ab_overweightAlert');
    if (!alertEl) return;
    if (cargoWeight <= 0 && !size) { alertEl.style.display = 'none'; return; }

    if (ow.charge > 0) {
        alertEl.className = 'ts-ow-alert ts-ow-warn';
        alertEl.style.display = '';
        alertEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i>' +
            ' ទម្ងន់សរុប <strong>' + (ow.totalWeight/1000).toFixed(2) + ' T</strong>' +
            ' (លើស <strong>' + (ow.overKg/1000).toFixed(2) + ' T</strong>) —' +
            ' ថ្លៃដឹក <strong>$' + abAdminBasePrice.toFixed(2) + '</strong>' +
            ' + ថ្លៃលើសសំណន់ <strong>$' + ow.charge + '</strong>' +
            ' = <strong style="color:#c2410c;">$' + total.toFixed(2) + '</strong>';
    } else if (ow.totalWeight > 0) {
        alertEl.className = 'ts-ow-alert ts-ow-ok';
        alertEl.style.display = '';
        alertEl.innerHTML = '<i class="fas fa-check-circle"></i> ទម្ងន់សរុប <strong>' + (ow.totalWeight/1000).toFixed(2) + ' T</strong> — ស្ថិតក្នុងដែន 40T';
    } else {
        alertEl.style.display = 'none';
    }
}

function abAdminLookupPrice() {
    var type     = document.getElementById('ab_bookingType').value;
    var portSel  = document.getElementById('ab_pickupSelect');
    var portOpt  = portSel ? portSel.options[portSel.selectedIndex] : null;
    var portKey  = portOpt ? (portOpt.dataset.key || '') : '';
    var province = document.getElementById('ab_dropoffProvince') ? document.getElementById('ab_dropoffProvince').value : '';
    var rate     = (type && portKey && province) ? abRatesAdmin.find(function(r) {
        return r.type === type && r.origin === portKey && r.province_name_km === province;
    }) : null;
    abAdminBasePrice = rate ? parseFloat(rate.base_price) : 0;
    abAdminRefreshTotal();
}

function abAdminOnContainerChange() {
    var size   = document.getElementById('ab_containerSize').value;
    var badge  = document.getElementById('ab_containerWeightBadge');
    var weight = AB_ADMIN_CONTAINER_WEIGHT[size];
    if (badge) {
        if (weight) {
            badge.textContent = 'ទម្ងន់កុងតឺន័រ ' + size + ': ' + weight.toLocaleString() + ' kg';
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    }
    abAdminRefreshTotal();
}

function abAdminUpdateOverweight() {
    abAdminRefreshTotal();
}

// Re-open modal if validation failed (sentinel field confirms it came from this form)
@if($errors->any() && old('_from_admin_booking'))
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('adminBookingModal').classList.add('open');
    // Restore booking type toggle
    var t = '{{ old('booking_type') }}';
    if (t) abToggleType(t);
    // Restore container weight badge
    var sz = '{{ old('container_size') }}';
    if (sz) abAdminOnContainerChange();
});
@endif
</script>
@endpush

@endsection