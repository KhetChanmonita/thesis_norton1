@extends('admin.layouts.admin')
@section('title','គណនីរបស់ខ្ញុំ')
@section('page-title')<span>គ្រប់គ្រង</span>គណនីរបស់ខ្ញុំ@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_profile.css') }}">
@endpush

@section('content')
<div class="apf-container">

    {{-- Hidden avatar-only form --}}
    <form id="avatarForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="d-none">
        @csrf
        <input type="hidden" name="user_name" value="{{ $user->user_name }}">
        <input type="hidden" name="email"     value="{{ $user->email }}">
        <input type="hidden" name="phone"     value="{{ $user->phone }}">
        <input type="file"   name="profile_picture" id="avatarInput" accept="image/jpg,image/jpeg,image/png,image/webp">
    </form>

    {{-- Profile header card --}}
    <div class="apf-header-card">
        {{-- Clickable avatar --}}
        <div class="apf-avatar-wrap" onclick="document.getElementById('avatarInput').click()">
            @if($user->profile_picture)
                <img src="{{ asset($user->profile_picture) }}" id="avatarPreview" alt="avatar" class="apf-avatar-img">
            @else
                <div id="avatarInitials" class="apf-avatar-initials">
                    {{ strtoupper(substr($user->user_name, 0, 2)) }}
                </div>
            @endif
            <div id="avatarOverlay" class="apf-avatar-overlay">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        <div>
            <div class="apf-header-name">{{ $user->user_name }}</div>
            <div class="apf-role-badge">
                <i class="fas fa-shield-alt mr-5"></i>អ្នកគ្រប់គ្រង
            </div>
            <div class="apf-hint">
                <i class="fas fa-camera mr-4"></i>ចុចលើរូបភាពដើម្បីផ្លាស់ប្ដូរ
            </div>
        </div>
    </div>

    {{-- Flash alerts --}}
    @if(session('success'))
        <div id="admFlashOk" class="apf-alert apf-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('reset_sent'))
        <div id="admFlashOk" class="apf-alert apf-alert-success">
            <i class="fas fa-envelope-open-text"></i> {{ session('reset_sent') }}
        </div>
    @endif
    @if(session('reset_error'))
        <div class="apf-alert apf-alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('reset_error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="apf-alert apf-alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Edit form card --}}
    <div class="apf-card">

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="apf-section-title">
                <i class="fas fa-user-edit"></i> ព័ត៌មានទូទៅ
            </div>

            <div class="apf-form-group">
                <label class="apf-label">ឈ្មោះ</label>
                <input type="text" name="user_name"
                       value="{{ old('user_name', $user->user_name) }}"
                       class="apf-input {{ $errors->has('user_name') ? 'is-invalid' : '' }}"
                       placeholder="បញ្ចូលឈ្មោះ">
                @error('user_name')<div class="apf-error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="apf-form-group">
                <label class="apf-label">អ៊ីមែល</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="apf-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="example@email.com">
                @error('email')<div class="apf-error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="apf-form-group">
                <label class="apf-label">លេខទូរស័ព្ទ</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       class="apf-input"
                       placeholder="0xx xxx xxx">
            </div>

            <div class="apf-divider"></div>

            <div class="apf-section-title">
                <i class="fas fa-lock"></i> ផ្លាស់ប្ដូរពាក្យសម្ងាត់ <span class="apf-optional">(ស្រេចចិត្ត)</span>
            </div>

            <div class="apf-form-group">
                <div class="apf-pw-row-header">
                    <label class="apf-label">ពាក្យសម្ងាត់បច្ចុប្បន្ន</label>
                    <button type="button" onclick="document.getElementById('admForgotPwModal').style.display='flex'"
                            class="apf-link-btn">
                        <i class="fas fa-key mr-3 apf-icon-sm"></i>ភ្លេចពាក្យសម្ងាត់?
                    </button>
                </div>
                <div class="apf-pw-wrap">
                    <input type="password" name="current_password" id="adm_cur_pw"
                           class="apf-input apf-input-pw {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                           placeholder="បញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន">
                    <i class="fas fa-eye apf-pw-eye" onclick="admTogglePw('adm_cur_pw',this)"></i>
                </div>
                @error('current_password')<div class="apf-error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="apf-form-group">
                <label class="apf-label">ពាក្យសម្ងាត់ថ្មី</label>
                <div class="apf-pw-wrap">
                    <input type="password" name="new_password" id="adm_new_pw"
                           class="apf-input apf-input-pw {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                           placeholder="យ៉ាងហោចណាស់ ៨ តួ">
                    <i class="fas fa-eye apf-pw-eye" onclick="admTogglePw('adm_new_pw',this)"></i>
                </div>
                @error('new_password')<div class="apf-error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="apf-form-group apf-mb-24">
                <label class="apf-label">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                <div class="apf-pw-wrap">
                    <input type="password" name="new_password_confirmation" id="adm_conf_pw"
                           class="apf-input apf-input-pw"
                           placeholder="បញ្ចូលម្ដងទៀត">
                    <i class="fas fa-eye apf-pw-eye" onclick="admTogglePw('adm_conf_pw',this)"></i>
                </div>
            </div>

            <button type="submit" class="apf-submit-btn">
                <i class="fas fa-save mr-8"></i> រក្សាទុក
            </button>
        </form>
    </div>
</div>

{{-- Forgot Password Modal --}}
<div id="admForgotPwModal" class="apf-modal-overlay">
    <div class="apf-modal-box">

        {{-- Header --}}
        <div class="apf-modal-header">
            <div class="apf-modal-header-info">
                <div class="apf-modal-icon">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <div class="apf-modal-title">ភ្លេចពាក្យសម្ងាត់</div>
                    <div class="apf-modal-subtitle">កំណត់ពាក្យសម្ងាត់ឡើងវិញតាមអ៊ីមែល</div>
                </div>
            </div>
            <button onclick="document.getElementById('admForgotPwModal').style.display='none'"
                    type="button"
                    class="apf-modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="apf-modal-body">
            <div class="apf-modal-info">
                <i class="fas fa-envelope text-orange mr-6"></i>
                យើងនឹងផ្ញើតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ទៅអ៊ីមែល<br>
                <strong class="apf-modal-email">{{ Auth::user()->email }}</strong><br>
                <span class="apf-modal-note">
                    <i class="fas fa-clock mr-3"></i>តំណភ្ជាប់មានសុពលភាព ៦០ នាទី
                </span>
            </div>

            <div class="apf-modal-actions">
                <button type="button"
                        onclick="document.getElementById('admForgotPwModal').style.display='none'"
                        class="apf-modal-btn apf-modal-btn-cancel">
                    <i class="fas fa-times mr-5"></i>បោះបង់
                </button>
                <form method="POST" action="{{ route('profile.forgot-password') }}" class="apf-modal-form-flex">
                    @csrf
                    <button type="submit" class="apf-modal-btn apf-modal-btn-send">
                        <i class="fas fa-paper-plane"></i>ផ្ញើអ៊ីមែល
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function admTogglePw(id, icon) {
    var inp = document.getElementById(id);
    if (inp.type === 'password') { inp.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
    else { inp.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
}

// Hover overlay on avatar
var avatarWrap = document.querySelector('[onclick="document.getElementById(\'avatarInput\').click()"]');
var overlay    = document.getElementById('avatarOverlay');
if (avatarWrap && overlay) {
    avatarWrap.addEventListener('mouseenter', function(){ overlay.style.opacity='1'; });
    avatarWrap.addEventListener('mouseleave', function(){ overlay.style.opacity='0'; });
}

// Close forgot-password modal on backdrop click
var admModal = document.getElementById('admForgotPwModal');
if (admModal) {
    admModal.addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
}

// Auto-dismiss flash alert
var admFlash = document.getElementById('admFlashOk');
if (admFlash) {
    setTimeout(function() {
        admFlash.style.transition = 'opacity .4s';
        admFlash.style.opacity = '0';
        setTimeout(function() { admFlash.style.display = 'none'; }, 400);
    }, 5000);
}

// Auto-submit avatar form on file select
document.getElementById('avatarInput').addEventListener('change', function() {
    if (!this.files || !this.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview  = document.getElementById('avatarPreview');
        var initials = document.getElementById('avatarInitials');
        if (preview) {
            preview.src = e.target.result;
        } else if (initials) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.id = 'avatarPreview';
            img.className = 'apf-avatar-img';
            initials.parentNode.replaceChild(img, initials);
        }
    };
    reader.readAsDataURL(this.files[0]);
    document.getElementById('avatarForm').submit();
});
</script>
@endsection
