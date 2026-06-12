@extends('admin.layouts.admin')
@section('title', 'ការបង់ប្រាក់')
@section('page-title')<span>គ្រប់គ្រង</span>ការបង់ប្រាក់@endsection

@section('content')

{{-- ── Stats Cards ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <div class="val" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;">
                ${{ number_format($totalRevenue, 2) }}
            </div>
            <div class="lbl">ចំណូលសរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <div class="val" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;">
                ${{ number_format($todayRevenue, 2) }}
            </div>
            <div class="lbl">ចំណូលថ្ងៃនេះ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalCount }}</div>
            <div class="lbl">ការទូទាត់សរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-credit-card"></i></div>
        <div class="stat-info">
            <div class="val">{{ $methods->count() }}</div>
            <div class="lbl">ប្រភេទវិធីទូទាត់</div>
        </div>
    </div>
</div>

{{-- ── Search / Filter Toolbar ── --}}
<div style="margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">

        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ឈ្មោះអតិថិជន / លេខយោង"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                          font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;width:240px;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">វិធីទូទាត់</label>
            <select name="method"
                    style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                           font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;background:#fff;outline:none;">
                <option value="">ទាំងអស់</option>
                @foreach($methods as $m)
                <option value="{{ $m }}" {{ request('method')===$m ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $m)) }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">កាលបរិច្ឆេទ</label>
            <input type="date" name="date" value="{{ request('date') }}"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                          font-family:'Montserrat',sans-serif;font-size:0.85rem;outline:none;">
        </div>

        <button type="submit" class="btn btn-ghost" style="padding:9px 18px;">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('method') || request('date'))
        <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost" style="padding:9px 14px;">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

{{-- ── Payments Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-receipt"></i>
            បញ្ជីការបង់ប្រាក់
            <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;font-weight:700;
                         background:#f1f5f9;color:#64748b;padding:2px 10px;border-radius:50px;margin-left:4px;">
                {{ $payments->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">ល.រ</th>
                    <th>អតិថិជន</th>
                    <th>លេខការកក់</th>
                    <th>ចំនួនទឹកប្រាក់</th>
                    <th>វិធីទូទាត់</th>
                    <th>លេខយោង</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th style="text-align:center;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-weight:700;
                                     color:#94a3b8;font-size:0.82rem;">
                            {{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
                        </span>
                    </td>

                    {{-- Customer --}}
                    <td>
                        @if($p->booking && $p->booking->customer)
                            <div style="display:flex;align-items:center;gap:9px;">
                                <div style="width:36px;height:36px;border-radius:50%;
                                            background:linear-gradient(135deg,#667eea,#764ba2);
                                            display:flex;align-items:center;justify-content:center;
                                            font-family:'Montserrat',sans-serif;font-weight:800;
                                            font-size:0.8rem;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(mb_substr($p->booking->customer->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;color:#1e293b;font-size:0.88rem;">
                                        {{ $p->booking->customer->full_name }}
                                    </div>
                                    @if($p->booking->customer->phone)
                                    <div style="font-size:0.72rem;color:#94a3b8;">
                                        {{ $p->booking->customer->phone }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span style="color:#cbd5e1;font-size:0.83rem;">—</span>
                        @endif
                    </td>

                    {{-- Booking ID --}}
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-weight:700;
                                     font-size:0.83rem;color:#FF6B00;
                                     background:#fff3e8;padding:3px 10px;border-radius:6px;">
                            #{{ $p->booking_id }}
                        </span>
                    </td>

                    {{-- Amount --}}
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-weight:800;
                                     font-size:1rem;color:#10b981;">
                            ${{ number_format($p->amount, 2) }}
                        </span>
                    </td>

                    {{-- Payment method badge --}}
                    <td>
                        @php
                            $methodMap = [
                                'cash'          => ['icon'=>'fas fa-money-bill-wave','color'=>'#059669','bg'=>'#d1fae5','label'=>'សាច់ប្រាក់'],
                                'bank_transfer' => ['icon'=>'fas fa-exchange-alt',   'color'=>'#2563eb','bg'=>'#dbeafe','label'=>'ផ្ទេរប្រាក់'],
                                'credit_card'   => ['icon'=>'fas fa-credit-card',    'color'=>'#7c3aed','bg'=>'#ede9fe','label'=>'កាតឥណទាន'],
                                'acleda'        => ['icon'=>'fas fa-university',     'color'=>'#c0392b','bg'=>'#fee2e2','label'=>'ACLEDA'],
                                'aba'           => ['icon'=>'fas fa-mobile-alt',     'color'=>'#d97706','bg'=>'#fef3c7','label'=>'ABA'],
                                'wing'          => ['icon'=>'fas fa-mobile-alt',     'color'=>'#4f46e5','bg'=>'#e0e7ff','label'=>'Wing'],
                                'khqr'          => ['icon'=>'fas fa-qrcode',         'color'=>'#475569','bg'=>'#f1f5f9','label'=>'KHQR'],
                                'pi_pay'        => ['icon'=>'fas fa-wallet',         'color'=>'#dc2626','bg'=>'#fee2e2','label'=>'Pi Pay'],
                            ];
                            $mk  = strtolower($p->payment_method ?? '');
                            $mi  = $methodMap[$mk] ?? ['icon'=>'fas fa-coins','color'=>'#64748b','bg'=>'#f1f5f9',
                                    'label'=> $p->payment_method ? ucfirst(str_replace('_',' ',$p->payment_method)) : '—'];
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:6px;
                                     background:{{ $mi['bg'] }};color:{{ $mi['color'] }};
                                     padding:4px 12px;border-radius:20px;font-size:0.78rem;font-weight:700;">
                            <i class="{{ $mi['icon'] }}" style="font-size:0.7rem;"></i>
                            {{ $mi['label'] }}
                        </span>
                    </td>

                    {{-- Transaction reference --}}
                    <td>
                        @if($p->transaction_reference)
                            <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;
                                         color:#475569;background:#f8fafc;padding:3px 9px;
                                         border-radius:6px;border:1px solid #e2e8f0;
                                         letter-spacing:0.5px;">
                                {{ $p->transaction_reference }}
                            </span>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td>
                        @php $d = $p->payment_date ?? $p->date; @endphp
                        @if($d)
                            <div style="font-size:0.83rem;color:#475569;">
                                <i class="fas fa-calendar-alt" style="color:#FF6B00;font-size:0.7rem;margin-right:4px;"></i>
                                {{ $d->format('d/m/Y') }}
                            </div>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Delete --}}
                    <td>
                        <div style="display:flex;justify-content:center;">
                            <form method="POST"
                                  action="{{ route('admin.payments.destroy', $p->payment_id) }}"
                                  onsubmit="return confirm('លុបការទូទាត់នេះ?')">
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
                    <td colspan="8" style="text-align:center;padding:52px;color:#94a3b8;">
                        <i class="fas fa-receipt" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.25;"></i>
                        <div style="font-size:0.9rem;">មិនមានការទូទាត់</div>
                        @if(request('search') || request('method') || request('date'))
                        <a href="{{ route('admin.payments.index') }}"
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
    @if($payments->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;
                display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:0.8rem;color:#94a3b8;">
            បង្ហាញ {{ $payments->firstItem() }}–{{ $payments->lastItem() }} នៃ {{ $payments->total() }}
        </span>
        <div style="display:flex;gap:6px;">
            @if($payments->onFirstPage())
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $payments->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $payments->currentPage() === $page ? 'active' : '' }}">
                    {{ $page }}
                </a>
            @endforeach
            @if($payments->hasMorePages())
                <a href="{{ $payments->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
