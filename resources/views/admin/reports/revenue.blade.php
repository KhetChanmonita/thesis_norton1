@extends('admin.layouts.admin')
@section('title', 'ចំណូលសរុបក្នុងខែ')
@section('page-title')<span>គ្រប់គ្រង</span>ចំណូលសរុបក្នុងខែ@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_reports.css') }}">
@endpush

@section('content')

@php
    $typeLabel   = ['import' => 'នាំចូល', 'export' => 'នាំចេញ'];
    $payLabel    = ['unpaid' => 'មិនទាន់បង់', 'deposit_paid' => 'បង់ 50%', 'fully_paid' => 'បានបង់ប្រាក់'];
    $statusLabel = ['pending' => 'រង់ចាំ', 'confirmed' => 'បានអនុម័ត', 'in_progress' => 'កំពុងដឹក', 'completed' => 'បានបញ្ចប់', 'cancelled' => 'បានលុបចោល'];
@endphp

<div class="rpt-rev-header">
    <a href="{{ route('admin.reports.index', ['month' => $month]) }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> 
    </a>
    <a href="{{ route('admin.reports.revenue.print', ['month' => $month]) }}" target="_blank" class="btn btn-orange">
        <i class="fas fa-print"></i> ទាញយករបាយការណ៍
    </a>
</div>

{{-- ── Stats Cards ── --}}
<div class="stats-grid rpt-stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-hand-holding-usd"></i></div>
        <div class="stat-info">
            <div class="val rpt-stat-val">${{ number_format($totalValue, 2) }}</div>
            <div class="lbl">តម្លៃសរុបនៃការកក់ក្នុងខែ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-info">
            <div class="val rpt-stat-val">{{ $totalCount }}</div>
            <div class="lbl">ការកក់សរុបក្នុងខែ</div>
        </div>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="rpt-toolbar">
    <form method="GET" class="rpt-filter-form">
        <div>
            <label class="rpt-filter-label">ខែ</label>
            <input type="month" name="month" value="{{ $month }}" class="rpt-month-input">
        </div>
        <button type="submit" class="btn btn-ghost rpt-btn-search-pad">
            <i class="fas fa-search"></i> តម្រង
        </button>
    </form>
</div>

{{-- ── Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-clipboard-list"></i>
            បញ្ជីការកក់ក្នុងខែ
            <span class="rpt-count-badge">{{ $bookings->total() }}</span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>លេខការកក់</th>
                    <th>អតិថិជន</th>
                    <th>ប្រភេទ</th>
                    <th>តម្លៃសរុប</th>
                    <th>ស្ថានភាពទូទាត់</th>
                    <th>ស្ថានភាព</th>
                    <th>ថ្ងៃកក់</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td>
                        <span class="rpt-row-num">
                            {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                        </span>
                    </td>
                    <td>
                        <span class="rpt-id-badge">
                            {{ $b->formatted_id }}
                        </span>
                    </td>
                    <td class="rpt-cell-text">
                        {{ $b->customer->full_name ?? '—' }}
                    </td>
                    <td>{{ $typeLabel[$b->booking_type] ?? $b->booking_type }}</td>
                    <td>
                        <strong class="rpt-amount rpt-amount-green">
                            {{ $b->total_price ? '$'.number_format($b->total_price, 2) : '—' }}
                        </strong>
                    </td>
                    <td>
                        <span class="badge badge-{{ $b->payment_status }}">
                            {{ $payLabel[$b->payment_status] ?? $b->payment_status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $b->status }}">
                            {{ $statusLabel[$b->status] ?? $b->status }}
                        </span>
                    </td>
                    <td class="rpt-date-cell">
                        {{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="rpt-empty-cell">
                        <i class="fas fa-clipboard-list rpt-empty-icon"></i>
                        <div class="rpt-empty-text">មិនមានការកក់សម្រាប់ខែនេះ</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
    <div class="rpt-pagination-bar">
        <span class="rpt-pagination-info">
            បង្ហាញ {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} នៃ {{ $bookings->total() }}
        </span>
        <div class="rpt-pagination-pages">
            @if($bookings->onFirstPage())
                <span class="page-btn rpt-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $bookings->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $bookings->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($bookings->hasMorePages())
                <a href="{{ $bookings->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn rpt-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
