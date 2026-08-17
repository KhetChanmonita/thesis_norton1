@extends('admin.layouts.admin')
@section('title', 'ការទំនាក់ទំនងសារ')
@section('page-title')<span>គ្រប់គ្រង</span>ការទំនាក់ទំនងសារ@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin_messages.css') }}">
@endpush

@section('content')

{{-- ── Stats ── --}}
<div class="stats-grid msg-stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-envelope-open-text"></i></div>
        <div class="stat-info">
            <div class="val">{{ $messages->total() }}</div>
            <div class="lbl">សារសរុប</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-envelope"></i></div>
        <div class="stat-info">
            <div class="val">{{ $newCount }}</div>
            <div class="lbl">សារថ្មី</div>
        </div>
    </div>
</div>

{{-- ── Search / Filter Toolbar ── --}}
<div class="msg-filter-wrap">
    <form method="GET" class="msg-filter-form">
        <div>
            <label class="msg-filter-label">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ឈ្មោះ / ទូរស័ព្ទ / អ៊ីមែល"
                   class="msg-filter-input">
        </div>

        <div>
            <label class="msg-filter-label">ស្ថានភាព</label>
            <select name="status" class="msg-filter-select">
                <option value="">ទាំងអស់</option>
                <option value="new"     {{ request('status')==='new'     ? 'selected' : '' }}>ថ្មី</option>
                <option value="read"    {{ request('status')==='read'    ? 'selected' : '' }}>បានអាន</option>
                <option value="replied" {{ request('status')==='replied' ? 'selected' : '' }}>បានឆ្លើយតប</option>
            </select>
        </div>

        <button type="submit" class="btn btn-ghost msg-btn-search-pad">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.messages.index') }}" class="btn btn-ghost msg-btn-clear-pad">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

{{-- ── Messages Table ── --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-envelope"></i>
            បញ្ជីសារទាក់ទង
            <span class="msg-count-badge">
                {{ $messages->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>អ្នកផ្ញើ</th>
                    <th>ទំនាក់ទំនង</th>
                    <th>ប្រភេទ</th>
                    <th>សារ</th>
                    <th>ស្ថានភាព</th>
                    <th>ពេលវេលា</th>
                    <th class="msg-actions-th">សកម្មភាព</th>
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
                @forelse($messages as $m)
                <tr>
                    <td>
                        <span class="msg-row-num">
                            {{ ($messages->currentPage() - 1) * $messages->perPage() + $loop->iteration }}
                        </span>
                    </td>
                    <td>
                        <strong class="msg-sender-name">{{ $m->full_name }}</strong>
                        @if($m->company_name)
                            <br><small class="msg-company">{{ $m->company_name }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="msg-phone-row"><i class="fas fa-phone msg-phone-icon"></i> {{ $m->phone }}</div>
                        @if($m->email)
                        <div class="msg-email-row"><i class="fas fa-envelope msg-icon-sm"></i> {{ $m->email }}</div>
                        @endif
                    </td>
                    <td>{{ $inquiryLabel[$m->inquiry_type] ?? $m->inquiry_type }}</td>
                    <td class="msg-message-td">
                        {{ $m->message }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.messages.status', $m->contact_id) }}">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="form-control msg-status-select">
                                <option value="new"     {{ $m->status==='new'     ? 'selected' : '' }}>ថ្មី</option>
                                <option value="read"    {{ $m->status==='read'    ? 'selected' : '' }}>បានអាន</option>
                                <option value="replied" {{ $m->status==='replied' ? 'selected' : '' }}>បានឆ្លើយតប</option>
                            </select>
                        </form>
                    </td>
                    <td class="msg-time-td">
                        {{ $m->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div class="msg-action-cell">
                            <button class="btn btn-danger btn-sm" title="លុប"
                                    onclick="confirmDeleteMessage({{ $m->contact_id }}, {{ json_encode($m->full_name) }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="msg-empty-td">
                        <i class="fas fa-envelope-open msg-empty-icon"></i>
                        <div class="msg-empty-text">មិនមានសារ</div>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.messages.index') }}" class="msg-empty-link">
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
    @if($messages->hasPages())
    <div class="msg-pagination">
        <span class="msg-pagination-info">
            បង្ហាញ {{ $messages->firstItem() }}–{{ $messages->lastItem() }} នៃ {{ $messages->total() }}
        </span>
        <div class="msg-pagination-pages">
            @if($messages->onFirstPage())
                <span class="page-btn msg-page-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $messages->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($messages->getUrlRange(1, $messages->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $messages->currentPage() === $page ? 'active' : '' }}">
                    {{ $page }}
                </a>
            @endforeach
            @if($messages->hasMorePages())
                <a href="{{ $messages->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn msg-page-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Delete Confirm Modal --}}
<div class="modal-overlay confirm-overlay" id="deleteMessageModal">
    <div class="modal-box confirm-modal-box">
        <form id="deleteMessageForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle"><i class="fas fa-trash"></i></div>
                <div class="confirm-title">លុបសារនេះ?</div>
                <p class="confirm-subtitle" id="deleteMessageName"></p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteMessageModal').classList.remove('open')">
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
function confirmDeleteMessage(id, name) {
    document.getElementById('deleteMessageForm').action = '{{ url("/admin/messages") }}/' + id;
    document.getElementById('deleteMessageName').textContent = name + ' — សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ';
    document.getElementById('deleteMessageModal').classList.add('open');
}
</script>
@endpush
@endsection