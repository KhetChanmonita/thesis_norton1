@extends('admin.layouts.admin')
@section('title','ប្រវត្តិ')
@section('page-title')<span>ប្រវត្តិ</span>ការដឹកជញ្ជូន@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_history.css') }}">
@endpush

@section('content')

{{-- ── Search by Container Number ── --}}
<div class="his-search-wrap">
    <form method="GET" class="his-search-form">
        <div>
            <label class="his-search-label">លេខកុងតឺន័រ</label>
            <input type="text" name="container_number"
                   value="{{ request('container_number') }}"
                   placeholder="ស្វែងរកដោយលេខកុងតឺន័រ"
                   class="his-search-input">
        </div>
        <button type="submit" class="btn btn-ghost his-search-btn">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('container_number'))
        <a href="{{ route('admin.history.index') }}" class="btn btn-ghost his-clear-btn">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-history"></i>
            ប្រវត្តិការដឹក
            <span class="his-count-badge">
                {{ $histories->total() }}
            </span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>Booking ID</th>
                    <th>អតិថិជន</th>
                    <th>ប្រភេទ</th>
                    <th>លេខកុងតឺន័រ</th>
                    <th>ផ្លូវដឹក</th>
                    <th>តម្លៃសរុប</th>
                    <th>ថ្ងៃបញ្ចប់</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $h)
                <tr>
                    <td>
                        <span class="his-row-num">
                            {{ ($histories->currentPage() - 1) * $histories->perPage() + $loop->iteration }}
                        </span>
                    </td>
                    <td><strong>{{ $h->booking?->formatted_id ?? '#'.$h->booking_id }}</strong></td>
                    <td>
                        {{ $h->booking->customer->full_name ?? '—' }}<br>
                        <small class="his-phone">{{ $h->booking->customer->phone ?? '' }}</small>
                    </td>
                    <td>{{ $h->booking->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ' }}</td>
                    <td>
                        @if($h->booking->container_number)
                        <span class="his-container-badge">{{ $h->booking->container_number }}</span>
                        @else — @endif
                    </td>
                    <td>
                        <small>{{ Str::limit($h->booking->pickup_location ?? '—', 18) }}</small><br>
                        <small class="his-route-dropoff">
                            → {{ Str::limit($h->booking->dropoff_location ?? '—', 18) }}
                            @if($h->booking->dropoff_location_link)
                            <a href="{{ $h->booking->dropoff_location_link }}" target="_blank" rel="noopener"
                               class="his-map-link" title="មើលលើ Google Maps">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            @endif
                        </small>
                    </td>
                    <td>
                        @php
                            $secondSum = $h->booking?->extraCharges?->where('stage','second')->sum('amount') ?? 0;
                            $trueTotal = ($h->total_price ?? 0) + $secondSum;
                        @endphp
                        <strong class="his-total">${{ number_format($trueTotal, 2) }}</strong>
                    </td>
                    <td>
                        @if($h->completed_date)
                        <span class="badge badge-completed">
                            <i class="fas fa-check"></i>
                            {{ \Carbon\Carbon::parse($h->completed_date)->format('d/m/Y') }}
                        </span>
                        @else — @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="his-empty-cell">
                        មិនមានប្រវត្តិ
                        @if(request('container_number'))
                        <br>
                        <a href="{{ route('admin.history.index') }}" class="his-empty-link">
                            លុបការស្វែងរក
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
    <div class="his-pagination">{{ $histories->links() }}</div>
    @endif
</div>
@endsection