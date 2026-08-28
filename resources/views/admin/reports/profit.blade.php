@extends('admin.layouts.admin')
@section('title', 'របាយការណ៍ប្រាក់ចំណេញ')
@section('page-title')<span>គ្រប់គ្រង</span>របាយការណ៍ប្រាក់ចំណេញ@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_reports.css') }}">
@endpush

@section('content')

@php
    $typeLabel = [
        'salary' => 'ប្រាក់ខែអ្នកបើកបរ',
        'fuel'   => 'ប្រេងឥន្ធនៈ',
        'repair' => 'ជួសជុលរថយន្ត',
        'other'  => 'ផ្សេងៗ',
    ];
@endphp

<div class="rpt-rev-header">
    <a href="{{ route('admin.reports.index', ['month' => $month]) }}" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> 
    </a>
    <a href="{{ route('admin.reports.profit.print', ['month' => $month]) }}" target="_blank" class="btn btn-orange">
        <i class="fas fa-print"></i> បញ្ចេញរបាយការណ៍
    </a>
</div>

{{-- ── Stats Cards ── --}}
<div class="stats-grid rpt-stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-hand-holding-usd"></i></div>
        <div class="stat-info">
            <div class="val rpt-stat-val">${{ number_format($totalRevenue, 2) }}</div>
            <div class="lbl">ចំណូលសរុបក្នុងខែ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <div class="val rpt-stat-val">${{ number_format($totalExpense, 2) }}</div>
            <div class="lbl">ចំណាយសរុបក្នុងខែ</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $profit >= 0 ? 'green' : 'red' }}">
            <i class="fas {{ $profit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
        </div>
        <div class="stat-info">
            <div class="val rpt-stat-val" style="color:{{ $profit >= 0 ? '#059669' : '#dc2626' }};">
                {{ $profit >= 0 ? '' : '-' }}${{ number_format(abs($profit), 2) }}
            </div>
            <div class="lbl">ប្រាក់ចំណេញក្នុងខែ</div>
        </div>
    </div>
</div>

{{-- ── Month Filter ── --}}
<div class="rpt-toolbar">
    <form method="GET" class="rpt-filter-form">
        <div>

            <input type="month" name="month" value="{{ $month }}" class="rpt-month-input">
        </div>
        <button type="submit" class="btn btn-ghost rpt-btn-search-pad">
            <i class="fas fa-search"></i> តម្រង
        </button>
    </form>
</div>

{{-- ── Revenue Breakdown ── --}}
<div class="card rpt-card-mb">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-hand-holding-usd"></i>
            ចំណូល — ការទូទាត់ទាំងអស់ក្នុងខែ
            <span class="rpt-count-badge">{{ $payments->count() }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>លេខការកក់</th>
                    <th>អតិថិជន</th>
                    <th>ដំណាក់កាល</th>
                    <th>ចំនួនទឹកប្រាក់</th>
                    <th>កាលបរិច្ឆេទ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $i => $pay)
                <tr>
                    <td><span class="rpt-row-num">{{ $i + 1 }}</span></td>
                    <td>
                        <span class="rpt-id-badge">
                            {{ $pay->booking?->formatted_id ?? '#'.$pay->booking_id }}
                        </span>
                    </td>
                    <td class="rpt-cell-text">{{ $pay->booking?->customer?->full_name ?? $pay->booking?->bookedByUser?->user_name ?? '—' }}</td>
                    <td>
                        {{ $pay->payment_stage === 'first' ? 'ការទូទាត់លើកទី១' : ($pay->payment_stage === 'second' ? 'ការទូទាត់លើកទី២' : '—') }}
                    </td>
                    <td><strong class="rpt-amount rpt-amount-green">${{ number_format($pay->amount, 2) }}</strong></td>
                    <td class="rpt-date-cell">{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="rpt-empty-cell">មិនមានការទូទាត់ក្នុងខែនេះ</td></tr>
                @endforelse
            </tbody>
            @if($payments->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="4" class="rpt-tfoot-label">សរុបចំណូល</td>
                    <td class="rpt-tfoot-val"><strong class="rpt-total-green">${{ number_format($totalRevenue, 2) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ── Expense Breakdown ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-file-invoice-dollar"></i>
            ចំណាយ — ចំណាយទាំងអស់ក្នុងខែ
            <span class="rpt-count-badge">{{ $expenses->count() }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>ប្រភេទ</th>
                    <th>ចំនួនទឹកប្រាក់</th>
                    <th>អ្នកបើកបរ / រថយន្ត</th>
                    <th>បរិយាយ</th>
                    <th>កាលបរិច្ឆេទ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $i => $e)
                <tr>
                    <td><span class="rpt-row-num">{{ $i + 1 }}</span></td>
                    <td>{{ $typeLabel[$e->expense_type] ?? $e->expense_type }}</td>
                    <td><strong class="rpt-amount">${{ number_format($e->amount, 2) }}</strong></td>
                    <td class="rpt-cell-text">
                        @if($e->driver) <div>{{ $e->driver->full_name }}</div> @endif
                        @if($e->truck) <div>{{ $e->truck->truck_name }} ({{ $e->truck->plate_number }})</div> @endif
                        @if(!$e->driver && !$e->truck) <span class="rpt-dash">—</span> @endif
                    </td>
                    <td class="rpt-desc-cell">{{ $e->description ?: '—' }}</td>
                    <td class="rpt-date-cell">{{ $e->expense_date ? $e->expense_date->format('d/m/Y') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="rpt-empty-cell">មិនមានចំណាយក្នុងខែនេះ</td></tr>
                @endforelse
            </tbody>
            @if($expenses->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="2" class="rpt-tfoot-label">សរុបចំណាយ</td>
                    <td class="rpt-tfoot-val"><strong class="rpt-total-red">${{ number_format($totalExpense, 2) }}</strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
