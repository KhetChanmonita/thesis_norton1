@extends('admin.layouts.admin')
@section('title','គណនីរបស់ខ្ញុំ')
@section('page-title')<span>គ្រប់គ្រង</span>គណនីរបស់ខ្ញុំ@endsection

@section('content')
<div style="max-width:640px;">

    {{-- Hidden avatar-only form --}}
    <form id="avatarForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="user_name" value="{{ $user->user_name }}">
        <input type="hidden" name="email"     value="{{ $user->email }}">
        <input type="hidden" name="phone"     value="{{ $user->phone }}">
        <input type="file"   name="profile_picture" id="avatarInput" accept="image/jpg,image/jpeg,image/png,image/webp">
    </form>

    {{-- Profile header card --}}
    <div style="background:linear-gradient(135deg,#ff6b00,#e55a00);border-radius:16px;padding:28px 28px 24px;
                margin-bottom:24px;display:flex;align-items:center;gap:20px;">
        {{-- Clickable avatar --}}
        <div style="position:relative;width:80px;height:80px;flex-shrink:0;cursor:pointer;" onclick="document.getElementById('avatarInput').click()">
            @if($user->profile_picture)
                <img src="{{ asset($user->profile_picture) }}" id="avatarPreview" alt="avatar"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,0.6);display:block;">
            @else
                <div id="avatarInitials"
                     style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.22);
                            border:3px solid rgba(255,255,255,0.55);display:flex;align-items:center;justify-content:center;
                            font-size:1.8rem;font-weight:800;color:#fff;font-family:'Montserrat',sans-serif;">
                    {{ strtoupper(substr($user->user_name, 0, 2)) }}
                </div>
            @endif
            <div id="avatarOverlay"
                 style="position:absolute;inset:0;border-radius:50%;background:rgba(0,0,0,0.45);
                        display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;pointer-events:none;">
                <i class="fas fa-camera" style="color:#fff;font-size:1.2rem;"></i>
            </div>
        </div>
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:4px;">{{ $user->user_name }}</div>
            <div style="font-size:0.78rem;background:rgba(255,255,255,0.2);color:#fff;
                        padding:3px 12px;border-radius:20px;display:inline-block;margin-bottom:6px;">
                <i class="fas fa-shield-alt" style="margin-right:5px;"></i>អ្នកគ្រប់គ្រង
            </div>
            <div style="font-size:0.72rem;color:rgba(255,255,255,0.7);">
                <i class="fas fa-camera" style="margin-right:4px;"></i>ចុចលើរូបភាពដើម្បីផ្លាស់ប្ដូរ
            </div>
        </div>
    </div>

    {{-- Flash alerts --}}
    @if(session('success'))
        <div id="admFlashOk" style="background:#ecfdf5;color:#065f46;border:1px solid #6ee7b7;border-radius:10px;
                    padding:13px 16px;margin-bottom:16px;font-size:0.88rem;font-weight:600;
                    display:flex;align-items:center;gap:10px;font-family:'Kantumruy Pro',sans-serif;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('reset_sent'))
        <div id="admFlashOk" style="background:#ecfdf5;color:#065f46;border:1px solid #6ee7b7;border-radius:10px;
                    padding:13px 16px;margin-bottom:16px;font-size:0.88rem;font-weight:600;
                    display:flex;align-items:center;gap:10px;font-family:'Kantumruy Pro',sans-serif;">
            <i class="fas fa-envelope-open-text"></i> {{ session('reset_sent') }}
        </div>
    @endif
    @if(session('reset_error'))
        <div style="background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:10px;
                    padding:13px 16px;margin-bottom:16px;font-size:0.88rem;font-weight:600;
                    display:flex;align-items:center;gap:10px;font-family:'Kantumruy Pro',sans-serif;">
            <i class="fas fa-exclamation-circle"></i> {{ session('reset_error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:10px;
                    padding:13px 16px;margin-bottom:16px;font-size:0.88rem;font-weight:600;
                    display:flex;align-items:center;gap:10px;font-family:'Kantumruy Pro',sans-serif;">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Edit form card --}}
    <div style="background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.07);padding:28px 28px 24px;">

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div style="font-size:0.78rem;font-weight:700;color:#ff6b00;text-transform:uppercase;
                        letter-spacing:0.05em;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-user-edit"></i> ព័ត៌មានទូទៅ
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:6px;">ឈ្មោះ</label>
                <input type="text" name="user_name"
                       value="{{ old('user_name', $user->user_name) }}"
                       style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('user_name') ? '#e53e3e' : '#e2e8f0' }};
                              border-radius:10px;font-size:0.88rem;font-family:'Kantumruy Pro',sans-serif;
                              color:#1e293b;outline:none;background:#f8fafc;"
                       placeholder="បញ្ចូលឈ្មោះ">
                @error('user_name')<div style="color:#e53e3e;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:6px;">អ៊ីមែល</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('email') ? '#e53e3e' : '#e2e8f0' }};
                              border-radius:10px;font-size:0.88rem;font-family:'Kantumruy Pro',sans-serif;
                              color:#1e293b;outline:none;background:#f8fafc;"
                       placeholder="example@email.com">
                @error('email')<div style="color:#e53e3e;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:6px;">លេខទូរស័ព្ទ</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;
                              font-size:0.88rem;font-family:'Kantumruy Pro',sans-serif;color:#1e293b;
                              outline:none;background:#f8fafc;"
                       placeholder="0xx xxx xxx">
            </div>

            <div style="height:1px;background:#f1f5f9;margin:20px 0;"></div>

            <div style="font-size:0.78rem;font-weight:700;color:#ff6b00;text-transform:uppercase;
                        letter-spacing:0.05em;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-lock"></i> ផ្លាស់ប្ដូរពាក្យសម្ងាត់ <span style="font-weight:400;color:#94a3b8;">(ស្រេចចិត្ត)</span>
            </div>

            <div style="margin-bottom:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <label style="font-size:0.8rem;font-weight:600;color:#475569;margin:0;">ពាក្យសម្ងាត់បច្ចុប្បន្ន</label>
                    <button type="button" onclick="document.getElementById('admForgotPwModal').style.display='flex'"
                            style="background:none;border:none;padding:0;font-family:'Kantumruy Pro',sans-serif;
                                   font-size:0.78rem;font-weight:600;color:#ff6b00;cursor:pointer;text-decoration:underline;">
                        <i class="fas fa-key" style="margin-right:3px;font-size:0.7rem;"></i>ភ្លេចពាក្យសម្ងាត់?
                    </button>
                </div>
                <div style="position:relative;">
                    <input type="password" name="current_password" id="adm_cur_pw"
                           style="width:100%;padding:10px 44px 10px 14px;border:1.5px solid {{ $errors->has('current_password') ? '#e53e3e' : '#e2e8f0' }};
                                  border-radius:10px;font-size:0.88rem;font-family:'Kantumruy Pro',sans-serif;
                                  color:#1e293b;outline:none;background:#f8fafc;"
                           placeholder="បញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន">
                    <i class="fas fa-eye" onclick="admTogglePw('adm_cur_pw',this)"
                       style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94a3b8;cursor:pointer;"></i>
                </div>
                @error('current_password')<div style="color:#e53e3e;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:6px;">ពាក្យសម្ងាត់ថ្មី</label>
                <div style="position:relative;">
                    <input type="password" name="new_password" id="adm_new_pw"
                           style="width:100%;padding:10px 44px 10px 14px;border:1.5px solid {{ $errors->has('new_password') ? '#e53e3e' : '#e2e8f0' }};
                                  border-radius:10px;font-size:0.88rem;font-family:'Kantumruy Pro',sans-serif;
                                  color:#1e293b;outline:none;background:#f8fafc;"
                           placeholder="យ៉ាងហោចណាស់ ៨ តួ">
                    <i class="fas fa-eye" onclick="admTogglePw('adm_new_pw',this)"
                       style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94a3b8;cursor:pointer;"></i>
                </div>
                @error('new_password')<div style="color:#e53e3e;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:6px;">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                <div style="position:relative;">
                    <input type="password" name="new_password_confirmation" id="adm_conf_pw"
                           style="width:100%;padding:10px 44px 10px 14px;border:1.5px solid #e2e8f0;
                                  border-radius:10px;font-size:0.88rem;font-family:'Kantumruy Pro',sans-serif;
                                  color:#1e293b;outline:none;background:#f8fafc;"
                           placeholder="បញ្ចូលម្ដងទៀត">
                    <i class="fas fa-eye" onclick="admTogglePw('adm_conf_pw',this)"
                       style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94a3b8;cursor:pointer;"></i>
                </div>
            </div>

            <button type="submit"
                    style="padding:12px 32px;background:linear-gradient(135deg,#ff6b00,#e55a00);color:#fff;
                           border:none;border-radius:10px;font-size:0.9rem;font-weight:700;
                           font-family:'Kantumruy Pro',sans-serif;cursor:pointer;transition:opacity 0.2s;">
                <i class="fas fa-save" style="margin-right:8px;"></i> រក្សាទុក
            </button>
        </form>
    </div>
</div>

{{-- Forgot Password Modal --}}
<div id="admForgotPwModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:999999;
            align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:20px;width:100%;max-width:420px;
                box-shadow:0 20px 60px rgba(0,0,0,0.25);overflow:hidden;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#ff6b00,#e55a00);padding:22px 24px;
                    display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:14px;color:#fff;">
                <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;
                            display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:1rem;font-family:'Kantumruy Pro',sans-serif;">ភ្លេចពាក្យសម្ងាត់</div>
                    <div style="font-size:0.78rem;opacity:0.85;font-family:'Kantumruy Pro',sans-serif;">កំណត់ពាក្យសម្ងាត់ឡើងវិញតាមអ៊ីមែល</div>
                </div>
            </div>
            <button onclick="document.getElementById('admForgotPwModal').style.display='none'"
                    type="button"
                    style="background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.35);
                           color:#fff;width:34px;height:34px;border-radius:50%;font-size:14px;
                           cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:28px 24px;">
            <div style="background:#fff8f3;border:1.5px solid #fed7aa;border-radius:12px;
                        padding:16px;margin-bottom:22px;font-family:'Kantumruy Pro',sans-serif;font-size:0.88rem;color:#7c2d12;">
                <i class="fas fa-envelope" style="color:#ff6b00;margin-right:6px;"></i>
                យើងនឹងផ្ញើតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ទៅអ៊ីមែល<br>
                <strong style="color:#ff6b00;word-break:break-all;">{{ Auth::user()->email }}</strong><br>
                <span style="font-size:0.8rem;opacity:0.8;margin-top:4px;display:inline-block;">
                    <i class="fas fa-clock" style="margin-right:3px;"></i>តំណភ្ជាប់មានសុពលភាព ៦០ នាទី
                </span>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button"
                        onclick="document.getElementById('admForgotPwModal').style.display='none'"
                        style="flex:1;padding:12px;background:transparent;border:2px solid #e2e8f0;
                               border-radius:10px;font-family:'Kantumruy Pro',sans-serif;font-size:0.87rem;
                               font-weight:600;color:#64748b;cursor:pointer;">
                    <i class="fas fa-times" style="margin-right:5px;"></i>បោះបង់
                </button>
                <form method="POST" action="{{ route('profile.forgot-password') }}" style="flex:1;">
                    @csrf
                    <button type="submit"
                            style="width:100%;padding:12px;background:linear-gradient(135deg,#ff6b00,#e55a00);
                                   border:none;border-radius:10px;font-family:'Kantumruy Pro',sans-serif;
                                   font-size:0.87rem;font-weight:700;color:#fff;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;gap:8px;
                                   box-shadow:0 4px 14px rgba(255,107,0,0.35);">
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
            img.style.cssText = 'width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,0.6);display:block;';
            initials.parentNode.replaceChild(img, initials);
        }
    };
    reader.readAsDataURL(this.files[0]);
    document.getElementById('avatarForm').submit();
});
</script>
@endsection