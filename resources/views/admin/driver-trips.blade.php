@extends('admin.layouts.admin')
@section('title','ដំណើររបស់ខ្ញុំ')
@section('page-title')<span>ដំណើររបស់ខ្ញុំ</span>@endsection

@section('content')
<style>
    .dt-hero { background:linear-gradient(135deg,#FF6B00,#ff9040); border-radius:16px; padding:24px 28px; color:#fff; margin-bottom:24px; display:flex; align-items:center; gap:20px; }
    .dt-hero-icon { width:64px; height:64px; background:rgba(255,255,255,.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; }
    .dt-hero-name { font-family:'Montserrat',sans-serif; font-size:1.4rem; font-weight:800; }
    .dt-hero-sub  { font-size:.85rem; opacity:.85; margin-top:2px; }
    .dt-truck-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.2); border-radius:20px; padding:4px 12px; font-size:.8rem; font-weight:600; margin-top:8px; }
    .dt-card { background:#fff; border-radius:14px; padding:18px 20px; border:1.5px solid #f1f5f9; box-shadow:0 2px 8px rgba(0,0,0,.05); margin-bottom:14px; display:flex; align-items:center; gap:18px; }
    .dt-card-num { width:40px; height:40px; border-radius:50%; background:#fff3e8; color:#FF6B00; display:flex; align-items:center; justify-content:center; font-weight:800; font-family:'Montserrat',sans-serif; font-size:.9rem; flex-shrink:0; }
    .dt-card-body { flex:1; }
    .dt-card-date { font-family:'Montserrat',sans-serif; font-weight:700; color:#1e293b; font-size:.9rem; }
    .dt-card-loc  { font-size:.8rem; color:#64748b; margin-top:3px; }
    .dt-card-truck { font-size:.78rem; color:#FF6B00; font-weight:600; margin-top:4px; }
    .dt-status { padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
    .dt-empty { text-align:center; padding:60px 20px; color:#94a3b8; }
    .dt-empty-icon { font-size:3rem; margin-bottom:12px; display:block; }
</style>

{{-- Driver hero card --}}
@if($driver)
<div class="dt-hero">
    <div class="dt-hero-icon"><i class="fas fa-id-badge"></i></div>
    <div>
        <div class="dt-hero-name">{{ $driver->full_name }}</div>
        <div class="dt-hero-sub">{{ $driver->phone ?? '—' }} &nbsp;·&nbsp; {{ ucfirst($driver->status) }}</div>
        @if($driver->truck)
        <div class="dt-truck-badge">
            <i class="fas fa-truck"></i>
            {{ $driver->truck->truck_name }} ({{ $driver->truck->plate_number }})
        </div>
        @endif
    </div>
</div>
@else
<div style="background:#fff3e8;border-radius:12px;padding:16px 20px;margin-bottom:20px;color:#c2410c;font-size:.9rem;">
    <i class="fas fa-exclamation-triangle"></i>
    គណនីរបស់អ្នកមិនទាន់ត្រូវបានភ្ជាប់ជាមួយប្រវត្តិអ្នកបើកបរទេ។ សូមទំនាក់ទំនងអ្នកគ្រប់គ្រង។
</div>
@endif

{{-- Schedule list --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-route"></i> កាលវិភាគការដឹក
            <span class="bks-count-badge">{{ $schedules->count() }}</span>
        </div>
    </div>
    <div style="padding:16px 20px;">
        @forelse($schedules as $i => $s)
        @php
            $isPast = $s->date_of_truck_available && \Carbon\Carbon::parse($s->date_of_truck_available)->isPast();
        @endphp
        <div class="dt-card">
            <div class="dt-card-num">{{ $i + 1 }}</div>
            <div class="dt-card-body">
                <div class="dt-card-date">
                    <i class="fas fa-calendar-alt" style="color:#FF6B00;"></i>
                    {{ $s->date_of_truck_available ? \Carbon\Carbon::parse($s->date_of_truck_available)->format('d/m/Y') : '—' }}
                </div>
                @if($s->location_truck)
                <div class="dt-card-loc">
                    <i class="fas fa-map-marker-alt"></i> {{ $s->location_truck }}
                </div>
                @endif
                @if($s->truck)
                <div class="dt-card-truck">
                    <i class="fas fa-truck"></i> {{ $s->truck->truck_name }} — {{ $s->truck->plate_number }}
                </div>
                @endif
            </div>
            <div>
                <span class="dt-status" style="{{ $isPast ? 'background:#f1f5f9;color:#94a3b8;' : 'background:#f0fdf4;color:#059669;' }}">
                    {{ $isPast ? 'បានកន្លង' : 'នៅខាងមុខ' }}
                </span>
            </div>
        </div>
        @empty
        <div class="dt-empty">
            <i class="fas fa-calendar-times dt-empty-icon"></i>
            <div>មិនមានកាលវិភាគ</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
