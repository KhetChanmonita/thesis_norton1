@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page-title')<span>ផ្ទាំង</span>គ្រប់គ្រង@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
@endpush

@section('content')

{{-- Stats Grid --}}
<div class="stats-grid">
    <a href="{{ route('admin.trucks.index') }}" class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-truck"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['trucks'] }}</div>
            <div class="lbl">រថយន្តសរុប</div>
        </div>
    </a>
    <a href="{{ route('admin.drivers.index') }}" class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-id-badge"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['drivers'] }}</div>
            <div class="lbl">អ្នកបើកបរ</div>
        </div>
    </a>
    <a href="{{ route('admin.customers.index') }}" class="stat-card">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['customers'] }}</div>
            <div class="lbl">អតិថិជន</div>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index') }}" class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['bookings'] }}</div>
            <div class="lbl">ការកក់សរុប</div>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}" class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['pending'] }}</div>
            <div class="lbl">កក់រង់ចាំ</div>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index', ['status'=>'completed']) }}" class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['completed'] }}</div>
            <div class="lbl">ការដឹកបានបញ្ចប់</div>
        </div>
    </a>
    <a href="{{ route('admin.payments.index') }}" class="stat-card">
        <div class="stat-icon red"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <div class="val">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="lbl">ចំណូលសរុប</div>
        </div>
    </a>
    <a href="{{ route('admin.schedules.index') }}" class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['schedules'] }}</div>
            <div class="lbl">កាលវិភាគ</div>
        </div>
    </a>
    <a href="#recent-messages" class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-envelope"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['new_messages'] }}</div>
            <div class="lbl">សារទាក់ទងថ្មី</div>
        </div>
    </a>
</div>

{{-- Booking status bar --}}
<div class="card dsh-status-card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-chart-bar"></i> ស្ថានភាពការកក់</div>
    </div>
    <div class="card-body dsh-status-body">
        @foreach(['pending'=>['label'=>'រង់ចាំ','color'=>'#f59e0b'],'confirmed'=>['label'=>'បានបញ្ជាក់','color'=>'#3b82f6'],'in_progress'=>['label'=>'កំពុងដឹក','color'=>'#10b981'],'completed'=>['label'=>'បញ្ចប់','color'=>'#059669'],'cancelled'=>['label'=>'បានបោះបង់','color'=>'#ef4444']] as $key=>$info)
        <div class="dsh-status-item">
            <div class="dsh-status-val" style="color:{{ $info['color'] }};">{{ $stats[$key] ?? 0 }}</div>
            <div class="dsh-status-lbl">{{ $info['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Two columns --}}
<div class="dsh-two-col">

    {{-- Recent Bookings --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-clipboard-list"></i> ការកក់ថ្មីៗ</div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-ghost btn-sm">មើលទាំងអស់</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>អតិថិជន</th>
                        <th>ប្រភេទ</th>
                        <th>ទំហំទំនិញ</th>
                        <th>ស្ថានភាព</th>
                        <th>ថ្ងៃកក់</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $b)
                    <tr>
                        <td><strong>{{ $b->formatted_id }}</strong></td>
                        <td>{{ $b->customer->full_name ?? '—' }}</td>
                        <td>{{ $b->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ' }}</td>
                        <td>{{ $b->cargo_weight ? number_format($b->cargo_weight).' kg' : '—' }}</td>
                        <td><span class="badge badge-{{ $b->status }}">{{ $b->status }}</span></td>
                        <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="dsh-empty-cell">មិនមានទិន្នន័យ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-money-bill-wave"></i> ការទូទាត់ថ្មីៗ</div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost btn-sm">មើលទាំងអស់</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>ចំនួន</th>
                        <th>វិធី</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $p)
                    <tr>
                        <td>{{ $p->booking?->formatted_id ?? '#'.$p->booking_id }}<br><small class="dsh-sub-text">{{ $p->booking->customer->full_name ?? '' }}</small></td>
                        <td><strong class="dsh-amount">${{ number_format($p->amount, 2) }}</strong></td>
                        <td>{{ $p->payment_method ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="dsh-empty-cell">មិនមានទិន្នន័យ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Recent Contact Messages --}}
<div class="card dsh-msg-card" id="recent-messages">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-envelope"></i> សារទាក់ទងថ្មីៗ</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ឈ្មោះ</th>
                    <th>ទំនាក់ទំនង</th>
                    <th>ប្រភេទ</th>
                    <th>សារ</th>
                    <th>ស្ថានភាព</th>
                    <th>ពេលវេលា</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $inquiryLabel = [
                        'import'      => 'នាំចូល',
                        'export'      => 'នាំចេញ',
                        'price'       => 'សុំសម្រង់តម្លៃ',
                        'partnership' => 'ភាពជាដៃគូ',
                        'other'       => 'ផ្សេងៗ',
                    ];
                    $statusLabel = [
                        'new'     => 'ថ្មី',
                        'read'    => 'បានអាន',
                        'replied' => 'បានឆ្លើយតប',
                    ];
                @endphp
                @forelse($recentMessages as $m)
                <tr>
                    <td><strong>{{ $m->full_name }}</strong>@if($m->company_name)<br><small class="dsh-sub-text">{{ $m->company_name }}</small>@endif</td>
                    <td>
                        {{ $m->phone }}
                        @if($m->email)<br><small class="dsh-sub-text">{{ $m->email }}</small>@endif
                    </td>
                    <td>{{ $inquiryLabel[$m->inquiry_type] ?? $m->inquiry_type }}</td>
                    <td class="dsh-msg-cell">{{ Str::limit($m->message, 80) }}</td>
                    <td><span class="badge badge-{{ $m->status }}">{{ $statusLabel[$m->status] ?? $m->status }}</span></td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="dsh-empty-cell">មិនមានសារ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
