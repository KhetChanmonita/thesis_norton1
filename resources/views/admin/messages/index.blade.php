@extends('admin.layouts.admin')
@section('title', 'ការទំនាក់ទំនងសារ')
@section('page-title')<span>គ្រប់គ្រង</span>ការទំនាក់ទំនងសារ@endsection

@section('content')

@if(session('success'))
<div style="background:#ecfdf5;border:1.5px solid #6ee7b7;color:#065f46;padding:12px 18px;
            border-radius:10px;margin-bottom:18px;font-size:0.85rem;font-weight:600;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ── Stats ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:24px;">
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
<div style="margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ស្វែងរក</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ឈ្មោះ / ទូរស័ព្ទ / អ៊ីមែល"
                   style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                          font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;width:240px;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:700;color:#475569;display:block;margin-bottom:5px;">ស្ថានភាព</label>
            <select name="status"
                    style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:9px;
                           font-family:'Kantumruy Pro',sans-serif;font-size:0.85rem;background:#fff;outline:none;">
                <option value="">ទាំងអស់</option>
                <option value="new"     {{ request('status')==='new'     ? 'selected' : '' }}>ថ្មី</option>
                <option value="read"    {{ request('status')==='read'    ? 'selected' : '' }}>បានអាន</option>
                <option value="replied" {{ request('status')==='replied' ? 'selected' : '' }}>បានឆ្លើយតប</option>
            </select>
        </div>

        <button type="submit" class="btn btn-ghost" style="padding:9px 18px;">
            <i class="fas fa-search"></i> ស្វែងរក
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.messages.index') }}" class="btn btn-ghost" style="padding:9px 14px;">
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
            <span style="font-family:'Montserrat',sans-serif;font-size:0.78rem;font-weight:700;
                         background:#f1f5f9;color:#64748b;padding:2px 10px;border-radius:50px;margin-left:4px;">
                {{ $messages->total() }}
            </span>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>អ្នកផ្ញើ</th>
                    <th>ទំនាក់ទំនង</th>
                    <th>ប្រភេទ</th>
                    <th>សារ</th>
                    <th>ស្ថានភាព</th>
                    <th>ពេលវេលា</th>
                    <th style="text-align:center;">សកម្មភាព</th>
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
                        <strong style="color:#1e293b;">{{ $m->full_name }}</strong>
                        @if($m->company_name)
                            <br><small style="color:#94a3b8;">{{ $m->company_name }}</small>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:0.83rem;"><i class="fas fa-phone" style="color:#FF6B00;font-size:0.7rem;"></i> {{ $m->phone }}</div>
                        @if($m->email)
                        <div style="font-size:0.75rem;color:#94a3b8;"><i class="fas fa-envelope" style="font-size:0.7rem;"></i> {{ $m->email }}</div>
                        @endif
                    </td>
                    <td>{{ $inquiryLabel[$m->inquiry_type] ?? $m->inquiry_type }}</td>
                    <td style="max-width:320px;white-space:normal;font-size:0.83rem;color:#475569;">
                        {{ $m->message }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.messages.status', $m->contact_id) }}">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="form-control" style="padding:5px 10px;font-size:0.78rem;width:auto;">
                                <option value="new"     {{ $m->status==='new'     ? 'selected' : '' }}>ថ្មី</option>
                                <option value="read"    {{ $m->status==='read'    ? 'selected' : '' }}>បានអាន</option>
                                <option value="replied" {{ $m->status==='replied' ? 'selected' : '' }}>បានឆ្លើយតប</option>
                            </select>
                        </form>
                    </td>
                    <td style="font-size:0.8rem;color:#475569;white-space:nowrap;">
                        {{ $m->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div style="display:flex;justify-content:center;">
                            <form method="POST" action="{{ route('admin.messages.destroy', $m->contact_id) }}"
                                  onsubmit="return confirm('លុបសារនេះ?')">
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
                    <td colspan="7" style="text-align:center;padding:52px;color:#94a3b8;">
                        <i class="fas fa-envelope-open" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.25;"></i>
                        <div style="font-size:0.9rem;">មិនមានសារ</div>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.messages.index') }}"
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
    @if($messages->hasPages())
    <div style="padding:14px 22px;border-top:1px solid #f1f5f9;
                display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:0.8rem;color:#94a3b8;">
            បង្ហាញ {{ $messages->firstItem() }}–{{ $messages->lastItem() }} នៃ {{ $messages->total() }}
        </span>
        <div style="display:flex;gap:6px;">
            @if($messages->onFirstPage())
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
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
                <span class="page-btn" style="opacity:0.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
