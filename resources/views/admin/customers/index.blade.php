@extends('admin.layouts.admin')
@section('title','អតិថិជន')
@section('page-title')<span>គ្រប់គ្រង</span>អតិថិជន@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_customers.css') }}">
@endpush

@section('content')

{{-- ── Stats Cards ── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="val">{{ $total }}</div>
            <div class="lbl">អតិថិជនសរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalWithBookings }}</div>
            <div class="lbl">មានការកក់</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-building"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalBusiness }}</div>
            <div class="lbl">អតិថិជនក្រុមហ៊ុន</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-user-plus"></i></div>
        <div class="stat-info">
            <div class="val">{{ $totalNewThisMonth }}</div>
            <div class="lbl">ចូលថ្មីខែនេះ</div>
        </div>
    </div>
</div>

<div class="cst-toolbar">
    <form method="GET" class="cst-filter-form">
        <div>
            <label class="cst-filter-label">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ស្វែងរកតាមឈ្មោះ"
                   class="cst-search-input">
        </div>
        <button type="submit" class="btn btn-ghost cst-btn-search-pad">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search'))
        <a href="{{ route('admin.customers.index') }}" class="btn btn-ghost cst-btn-clear-pad">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-users"></i>
            បញ្ជីអតិថិជន
            <span class="cst-count-badge">
                {{ $customers->total() }}
            </span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th><th>ឈ្មោះពេញ</th><th>ទូរស័ព្ទ</th>
                    <th>ក្រុមហ៊ុន</th>
                    <th>លេខកក់សេវា</th><th>លេខរថយន្ត</th>
                    <th>ការកក់</th><th>ចូលថ្ងៃ</th><th>សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td>
                        <span class="cst-row-num">
                            {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}
                        </span>
                    </td>
                    <td><strong>{{ $c->full_name }}</strong></td>
                    <td>{{ $c->phone ?? '—' }}</td>
                    <td>{{ $c->company_name ?? '—' }}</td>
                    <td>{{ $c->latestBooking?->formatted_id ?? '—' }}</td>
                    <td>{{ $c->latestBooking?->truck?->plate_number ?? '—' }}</td>
                    <td><span class="badge badge-new">{{ $c->bookings_count }} ការកក់</span></td>
                    <td>{{ $c->created_at->format('d/m/Y') }}</td>
                    <td class="cst-actions-cell">
                        <a href="{{ route('admin.customers.edit', $c->customer_id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm"
                                onclick="confirmDeleteCustomer({{ $c->customer_id }}, {{ json_encode($c->full_name) }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="cst-empty-cell">
                        មិនមានអតិថិជន
                        @if(request('search'))
                        <br><a href="{{ route('admin.customers.index') }}" class="cst-empty-clear-link">លុបការស្វែងរក</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="cst-pagination-bar">
        <span class="cst-pagination-info">
            បង្ហាញ {{ $customers->firstItem() }}–{{ $customers->lastItem() }} នៃ {{ $customers->total() }}
        </span>
        <div class="cst-pagination-pages">
            @if($customers->onFirstPage())
                <span class="page-btn cst-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $customers->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif

            @foreach($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $customers->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($customers->hasMorePages())
                <a href="{{ $customers->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn cst-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Delete Confirm Modal --}}
<div class="modal-overlay confirm-overlay" id="deleteCustomerModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteCustomerForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបអតិថិជននេះ?</div>
                <p class="confirm-subtitle" id="deleteCustomerName"></p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteCustomerModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> លុប
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteCustomer(id, name) {
    document.getElementById('deleteCustomerForm').action = '{{ url("/admin/customers") }}/' + id;
    document.getElementById('deleteCustomerName').textContent = name + ' — សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ';
    document.getElementById('deleteCustomerModal').classList.add('open');
}
</script>
@endpush
@endsection
