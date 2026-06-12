@extends('layouts.app')

@section('title', 'ទំព័រដើម - LS Trucking Service')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-home.css') }}">
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="khmer-welcome-container"></div>

            <div class="responsive-text-center">
                <p>
                    សេវាកម្មដឹកជញ្ជូនទំនិញដែលផ្ដល់ទំនុកចិត្តបំផុតនៅកម្ពុជា។ ក្រុមហ៊ុនយើងមានផ្តល់សេវា<br class="responsive-hide-mobile">
                    ដឹកជញ្ជូនពីរោងចក្រទៅកាន់កំពង់ផែស្វ័យយ័តព្រះសីហនុ និងកំពង់ផែស្វ័យយ័ត<br class="responsive-hide-mobile">
                    ភ្នំពេញ (EXPORT) ហើយយើងក៏ផ្ដល់សេវាដឹកជញ្ជូនពីកំពង់ផែស្វ័យយ័ត<br class="responsive-hide-mobile">
                    ព្រះសីហនុ និងកំពង់ផែស្វ័យយ័តភ្នំពេញ ទៅកាន់រោងចក្រនៅក្នុងស្រុក<br class="responsive-hide-mobile">
                    (IMPORT) ដោយមានគុណភាពនិងសុវត្ថិភាពខ្ពស់បំផុត។
                </p>
            </div>

            <div class="hero-buttons">
                <a href="{{ auth()->check() ? route('trucks_section') : route('login') }}" class="btn-primary">
                    <i class="fas fa-calendar-check"></i>
                    កក់សេវា
                </a>
                <button type="button" class="btn-outline" id="openLearnMore">
                    <i class="fas fa-envelope-open-text"></i>
                    ស្វែងយល់បន្ថែម
                    <span class="btn-arrow"><i class="fas fa-arrow-right"></i></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Service & Why Choose Us sections -->
    @include('partials.services_section')
    @include('partials.whychooseus_section', ['truckCount' => \App\Models\Truck::count()])

    {{-- ===== SUCCESS ALERTS ===== --}}
    @if(session('contact_success'))
        <div id="contactSuccessAlert" style="position:fixed;top:90px;left:50%;transform:translateX(-50%);z-index:999999;background:linear-gradient(135deg,#10b981,#059669);color:#fff;padding:14px 28px;border-radius:12px;box-shadow:0 6px 20px rgba(16,185,129,0.4);font-family:'Kantumruy Pro',sans-serif;font-size:.95rem;font-weight:600;display:flex;align-items:center;gap:10px;max-width:90vw;text-align:center;">
            <i class="fas fa-check-circle" style="font-size:1.2rem;flex-shrink:0;"></i>
            {{ session('contact_success') }}
        </div>
        <script>setTimeout(()=>{ var a=document.getElementById('contactSuccessAlert'); if(a) a.remove(); },6000);</script>
    @endif

    {{-- ===== LEARN MORE / CONTACT MODAL ===== --}}
    <div id="learnMoreOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.62);z-index:100000;align-items:flex-start;justify-content:center;padding:82px 16px 24px;overflow-y:auto;">
        <div style="background:#fff;border-radius:20px;width:100%;max-width:700px;box-shadow:0 24px 80px rgba(0,0,0,0.3);overflow:hidden;margin-bottom:24px;">

            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#FF6B00,#FF9040);padding:22px 28px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:16px;color:#fff;">
                    <div style="background:rgba(255,255,255,0.2);width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-envelope-open-text" style="font-size:24px;"></i>
                    </div>
                    <div>
                        <h2 style="margin:0;font-size:1.2rem;font-weight:700;font-family:'Kantumruy Pro',sans-serif;">ស្វែងយល់បន្ថែម</h2>
                        <p style="margin:4px 0 0;font-size:.82rem;opacity:.88;font-family:'Kantumruy Pro',sans-serif;">ទំនាក់ទំនងយើងខ្ញុំ — យើងនឹងឆ្លើយតបក្នុងរយៈពេល ២៤ ម៉ោង</p>
                    </div>
                </div>
                <button id="closeLearnMore" type="button" style="background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);color:#fff;width:38px;height:38px;border-radius:50%;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Error --}}
            @if($errors->any())
                <div style="margin:16px 28px 0;background:#fff5f5;border:1.5px solid #fed7d7;border-radius:10px;padding:12px 16px;color:#c53030;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('contact.store') }}" style="padding:24px 28px;">
                @csrf

                {{-- Section 1: Personal Info --}}
                <div style="border:1.5px solid #eeeeee;border-radius:14px;overflow:hidden;margin-bottom:18px;">
                    <div style="background:linear-gradient(135deg,#fff6ef,#fff);border-bottom:1.5px solid #eeeeee;padding:11px 18px;font-family:'Kantumruy Pro',sans-serif;font-weight:700;font-size:.9rem;color:#FF6B00;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-user"></i> ព័ត៌មានទំនាក់ទំនង
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;">

                        <div style="padding:13px 18px;border-right:1px solid #f2f2f2;border-bottom:1px solid #f2f2f2;">
                            <label style="font-family:'Kantumruy Pro',sans-serif;font-size:.8rem;font-weight:600;color:#555;display:block;margin-bottom:6px;">
                                ឈ្មោះពេញ <span style="color:#e53e3e;">*</span>
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name', Auth::check() ? Auth::user()->user_name : '') }}"
                                   placeholder="បញ្ចូលឈ្មោះពេញ" required
                                   style="width:100%;padding:9px 13px;border:1.5px solid #e8edf2;border-radius:8px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;box-sizing:border-box;outline:none;">
                        </div>

                        <div style="padding:13px 18px;border-bottom:1px solid #f2f2f2;">
                            <label style="font-family:'Kantumruy Pro',sans-serif;font-size:.8rem;font-weight:600;color:#555;display:block;margin-bottom:6px;">
                                លេខទូរស័ព្ទ <span style="color:#e53e3e;">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}"
                                   placeholder="012 345 678" required
                                   style="width:100%;padding:9px 13px;border:1.5px solid #e8edf2;border-radius:8px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;box-sizing:border-box;outline:none;">
                        </div>

                        <div style="padding:13px 18px;border-right:1px solid #f2f2f2;">
                            <label style="font-family:'Kantumruy Pro',sans-serif;font-size:.8rem;font-weight:600;color:#555;display:block;margin-bottom:6px;">អ៊ីមែល</label>
                            <input type="email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                                   placeholder="example@email.com"
                                   style="width:100%;padding:9px 13px;border:1.5px solid #e8edf2;border-radius:8px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;box-sizing:border-box;outline:none;">
                        </div>

                        <div style="padding:13px 18px;">
                            <label style="font-family:'Kantumruy Pro',sans-serif;font-size:.8rem;font-weight:600;color:#555;display:block;margin-bottom:6px;">ឈ្មោះក្រុមហ៊ុន</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                   placeholder="ឈ្មោះក្រុមហ៊ុន (បើមាន)"
                                   style="width:100%;padding:9px 13px;border:1.5px solid #e8edf2;border-radius:8px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;box-sizing:border-box;outline:none;">
                        </div>

                    </div>
                </div>

                {{-- Section 2: Inquiry --}}
                <div style="border:1.5px solid #eeeeee;border-radius:14px;overflow:hidden;margin-bottom:20px;">
                    <div style="background:linear-gradient(135deg,#fff6ef,#fff);border-bottom:1.5px solid #eeeeee;padding:11px 18px;font-family:'Kantumruy Pro',sans-serif;font-weight:700;font-size:.9rem;color:#FF6B00;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-comment-dots"></i> ប្រភេទ និងខ្លឹមសារសំណួរ
                    </div>

                    <div style="padding:13px 18px;border-bottom:1px solid #f2f2f2;">
                        <label style="font-family:'Kantumruy Pro',sans-serif;font-size:.8rem;font-weight:600;color:#555;display:block;margin-bottom:6px;">
                            ប្រភេទសំណួរ <span style="color:#e53e3e;">*</span>
                        </label>
                        <select name="inquiry_type" required
                                style="width:100%;padding:9px 13px;border:1.5px solid #e8edf2;border-radius:8px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;background:#fff;box-sizing:border-box;outline:none;">
                            <option value="">-- ជ្រើសរើស --</option>
                            <option value="import"      {{ old('inquiry_type')=='import'      ? 'selected' : '' }}>ស្វែងយល់អំពីសេវានាំចូល (Import)</option>
                            <option value="export"      {{ old('inquiry_type')=='export'      ? 'selected' : '' }}>ស្វែងយល់អំពីសេវានាំចេញ (Export)</option>
                            <option value="price"       {{ old('inquiry_type')=='price'       ? 'selected' : '' }}>សំណួរអំពីតម្លៃ</option>
                            <option value="partnership" {{ old('inquiry_type')=='partnership' ? 'selected' : '' }}>ភាពជាដៃគូអាជីវកម្ម</option>
                            <option value="other"       {{ old('inquiry_type')=='other'       ? 'selected' : '' }}>សំណួរផ្សេងៗ</option>
                        </select>
                    </div>

                    <div style="padding:13px 18px;">
                        <label style="font-family:'Kantumruy Pro',sans-serif;font-size:.8rem;font-weight:600;color:#555;display:block;margin-bottom:6px;">
                            សារ / សំណួរ <span style="color:#e53e3e;">*</span>
                        </label>
                        <textarea name="message" rows="4" required
                                  placeholder="សូមបញ្ចូលសំណួរ ឬព័ត៌មានដែលអ្នកចង់ដឹង..."
                                  style="width:100%;padding:9px 13px;border:1.5px solid #e8edf2;border-radius:8px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;box-sizing:border-box;outline:none;resize:vertical;min-height:110px;">{{ old('message') }}</textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <span style="font-family:'Kantumruy Pro',sans-serif;font-size:.76rem;color:#aaa;display:flex;align-items:center;gap:5px;">
                        <i class="fas fa-info-circle" style="color:#FF6B00;"></i>
                        វាលដែលមាន <span style="color:#e53e3e;margin:0 2px;">*</span> ចាំបាច់ត្រូវបំពេញ
                    </span>
                    <div style="display:flex;gap:12px;flex-shrink:0;">
                        <button type="button" id="cancelLearnMore"
                                style="padding:10px 22px;background:transparent;border:2px solid #e2e8f0;border-radius:10px;font-family:'Kantumruy Pro',sans-serif;font-size:.87rem;font-weight:600;color:#666;cursor:pointer;">
                            <i class="fas fa-times"></i> បោះបង់
                        </button>
                        <button type="submit"
                                style="padding:10px 26px;background:linear-gradient(135deg,#FF6B00,#FF9040);border:none;border-radius:10px;font-family:'Kantumruy Pro',sans-serif;font-size:.9rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 4px 16px rgba(255,107,0,0.35);">
                            <i class="fas fa-paper-plane"></i> ផ្ញើសំណួរ
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    {{-- ===== END MODAL ===== --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var overlay    = document.getElementById('learnMoreOverlay');
    var openBtn    = document.getElementById('openLearnMore');
    var closeBtn   = document.getElementById('closeLearnMore');
    var cancelBtn  = document.getElementById('cancelLearnMore');

    function openModal() {
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (openBtn)   openBtn.addEventListener('click', openModal);
    if (closeBtn)  closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    @if($errors->any())
        openModal();
    @endif
});
</script>
@endpush
