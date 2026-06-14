@extends('layouts.app')

@section('title', 'ទំព័រដើម - LS Trucking Service')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
        <div id="contactSuccessAlert" class="hm-success-alert">
            <i class="fas fa-check-circle hm-success-icon"></i>
            {{ session('contact_success') }}
        </div>
        <script>setTimeout(()=>{ var a=document.getElementById('contactSuccessAlert'); if(a) a.remove(); },6000);</script>
    @endif

    {{-- ===== LEARN MORE / CONTACT MODAL ===== --}}
    <div id="learnMoreOverlay" class="hm-modal-overlay">
        <div class="hm-modal-box">

            {{-- Header --}}
            <div class="hm-modal-header">
                <div class="hm-modal-header-info">
                    <div class="hm-modal-icon">
                        <i class="fas fa-envelope-open-text hm-modal-icon-i"></i>
                    </div>
                    <div>
                        <h2 class="hm-modal-title">ស្វែងយល់បន្ថែម</h2>
                        <p class="hm-modal-subtitle">ទំនាក់ទំនងយើងខ្ញុំ — យើងនឹងឆ្លើយតបក្នុងរយៈពេល ២៤ ម៉ោង</p>
                    </div>
                </div>
                <button id="closeLearnMore" type="button" class="hm-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Error --}}
            @if($errors->any())
                <div class="hm-error-box">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('contact.store') }}" class="hm-form">
                @csrf

                {{-- Section 1: Personal Info --}}
                <div class="hm-section">
                    <div class="hm-section-header">
                        <i class="fas fa-user"></i> ព័ត៌មានទំនាក់ទំនង
                    </div>
                    <div class="hm-grid-2">

                        <div class="hm-cell-br-bb">
                            <label class="hm-label">
                                ឈ្មោះពេញ <span class="hm-required">*</span>
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name', Auth::check() ? Auth::user()->user_name : '') }}"
                                   placeholder="បញ្ចូលឈ្មោះពេញ" required
                                   class="hm-input">
                        </div>

                        <div class="hm-cell-bb">
                            <label class="hm-label">
                                លេខទូរស័ព្ទ <span class="hm-required">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}"
                                   placeholder="012 345 678" required
                                   class="hm-input">
                        </div>

                        <div class="hm-cell-br">
                            <label class="hm-label">អ៊ីមែល</label>
                            <input type="email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                                   placeholder="example@email.com"
                                   class="hm-input">
                        </div>

                        <div class="hm-cell">
                            <label class="hm-label">ឈ្មោះក្រុមហ៊ុន</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                   placeholder="ឈ្មោះក្រុមហ៊ុន (បើមាន)"
                                   class="hm-input">
                        </div>

                    </div>
                </div>

                {{-- Section 2: Inquiry --}}
                <div class="hm-section-mb20">
                    <div class="hm-section-header">
                        <i class="fas fa-comment-dots"></i> ប្រភេទ និងខ្លឹមសារសំណួរ
                    </div>

                    <div class="hm-cell-bb">
                        <label class="hm-label">
                            ប្រភេទសំណួរ <span class="hm-required">*</span>
                        </label>
                        <select name="inquiry_type" required
                                class="hm-input hm-select">
                            <option value="">-- ជ្រើសរើស --</option>
                            <option value="import"      {{ old('inquiry_type')=='import'      ? 'selected' : '' }}>ស្វែងយល់អំពីសេវានាំចូល (Import)</option>
                            <option value="export"      {{ old('inquiry_type')=='export'      ? 'selected' : '' }}>ស្វែងយល់អំពីសេវានាំចេញ (Export)</option>
                            <option value="price"       {{ old('inquiry_type')=='price'       ? 'selected' : '' }}>សំណួរអំពីតម្លៃ</option>
                            <option value="partnership" {{ old('inquiry_type')=='partnership' ? 'selected' : '' }}>ភាពជាដៃគូអាជីវកម្ម</option>
                            <option value="other"       {{ old('inquiry_type')=='other'       ? 'selected' : '' }}>សំណួរផ្សេងៗ</option>
                        </select>
                    </div>

                    <div class="hm-cell">
                        <label class="hm-label">
                            សារ / សំណួរ <span class="hm-required">*</span>
                        </label>
                        <textarea name="message" rows="4" required
                                  placeholder="សូមបញ្ចូលសំណួរ ឬព័ត៌មានដែលអ្នកចង់ដឹង..."
                                  class="hm-input hm-textarea">{{ old('message') }}</textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="hm-footer">
                    <span class="hm-footer-note">
                        <i class="fas fa-info-circle hm-icon-orange"></i>
                        វាលដែលមាន <span class="hm-required-inline">*</span> ចាំបាច់ត្រូវបំពេញ
                    </span>
                    <div class="hm-footer-actions">
                        <button type="button" id="cancelLearnMore" class="hm-btn-cancel">
                            <i class="fas fa-times"></i> បោះបង់
                        </button>
                        <button type="submit" class="hm-btn-submit">
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
