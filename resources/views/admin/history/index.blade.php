@extends('admin.layouts.admin')
@section('title','ប្រវត្តិ')
@section('page-title')<span>ប្រវត្តិ</span>ការដឹកជញ្ជូន@endsection

@section('content')

{{-- ── Search by Container Number ── --}}
<div style="margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">លេខកុងតឺន័រ</label>
            <input type="text" name="container_number" value="{{ request('container_number') }}" placeholder="ស្វែងរកដោយលេខកុងតឺន័រ" style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                          font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;width:240px;outline:none;">
        </div>
        <button type="submit" class="btn btn-ghost" style="padding:9px 18px;">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('container_number'))
        <a href="{{ route('admin.history.index') }}" class="btn btn-ghost" style="padding:9px 14px;">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-history"></i> ប្រវត្តិការដឹក ({{ $histories->total() }})</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
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
                    <td>{{ $h->history_id }}</td>
                    <td><strong>#{{ $h->booking_id }}</strong></td>
                    <td>{{ $h->booking->customer->full_name ?? '—' }}<br>
                        <small style="color:#94a3b8;">{{ $h->booking->customer->phone ?? '' }}</small>
                    </td>
                    <td>{{ $h->booking->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ' }}</td>
                    <td>
                        @if($h->booking->container_number)
                        <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;
                                         color:#475569;background:#f8fafc;padding:3px 9px;
                                         border-radius:6px;border:1px solid #e2e8f0;">
                            {{ $h->booking->container_number }}
                        </span>
                        @else — @endif
                    </td>
                    <td>
                        <small>{{ Str::limit($h->booking->pickup_location ?? '—', 18) }}</small><br>
                        <small style="color:#94a3b8;">
                            → {{ Str::limit($h->booking->dropoff_location ?? '—', 18) }}
                            @if($h->booking->dropoff_location_link)
                            <a href="{{ $h->booking->dropoff_location_link }}" target="_blank" rel="noopener" style="color:#FF6B00;" title="មើលលើ Google Maps">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            @endif
                        </small>
                    </td>
                    <td><strong style="color:#10b981;">${{ number_format($h->total_price ?? 0, 2) }}</strong></td>
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
                    <td colspan="8" style="text-align:center;color:#94a3b8;padding:32px;">
                        មិនមានប្រវត្តិ
                        @if(request('container_number'))
                        <br>
                        <a href="{{ route('admin.history.index') }}" style="font-size:0.82rem;color:#FF6B00;margin-top:8px;display:inline-block;">
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
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;">{{ $histories->links() }}</div>
    @endif
</div>
@endsection
