@extends('admin.layouts.admin')
@section('title','អតិថិជន')
@section('page-title')<span>គ្រប់គ្រង</span>អតិថិជន@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-users"></i> បញ្ជីអតិថិជន ({{ $customers->total() }})</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>ឈ្មោះពេញ</th><th>ទូរស័ព្ទ</th>
                    <th>អ៊ីមែល</th><th>ក្រុមហ៊ុន</th>
                    <th>ការកក់</th><th>ចូលថ្ងៃ</th><th>សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td>{{ $c->customer_id }}</td>
                    <td><strong>{{ $c->full_name }}</strong></td>
                    <td>{{ $c->phone ?? '—' }}</td>
                    <td>{{ $c->email ?? '—' }}</td>
                    <td>{{ $c->company_name ?? '—' }}</td>
                    <td><span class="badge badge-new">{{ $c->bookings_count }} ការកក់</span></td>
                    <td>{{ $c->created_at->format('d/m/Y') }}</td>
                    <td style="display:flex;gap:6px;align-items:center;">
                        <a href="{{ route('admin.customers.edit', $c->customer_id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.customers.destroy', $c->customer_id) }}" onsubmit="return confirm('លុបអតិថិជននេះ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:32px;">មិនមានអតិថិជន</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <span style="font-size:0.8rem;color:#94a3b8;">
            បង្ហាញ {{ $customers->firstItem() }}–{{ $customers->lastItem() }} នៃ {{ $customers->total() }}
        </span>
        <div style="display:flex;gap:6px;align-items:center;">
            @if($customers->onFirstPage())
                <span class="page-nav-btn disabled"><i class="fas fa-chevron-left"></i> មុន</span>
            @else
                <a href="{{ $customers->previousPageUrl() }}" class="page-nav-btn"><i class="fas fa-chevron-left"></i> មុន</a>
            @endif

            @foreach($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $customers->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($customers->hasMorePages())
                <a href="{{ $customers->nextPageUrl() }}" class="page-nav-btn">បន្ទាប់ <i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-nav-btn disabled">បន្ទាប់ <i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
