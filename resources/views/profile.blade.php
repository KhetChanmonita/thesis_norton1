<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>គណនីរបស់ខ្ញុំ | LS Trucking Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>
    @include('partials.header')

    <div class="profile-page">
        <div class="profile-container">
            <div class="profile-card">

                {{-- Clickable avatar header --}}
                <div class="profile-header">
                    <form id="avatarForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="prf-avatar-form">
                        @csrf
                        <input type="hidden" name="user_name" value="{{ $user->user_name }}">
                        <input type="hidden" name="email"     value="{{ $user->email }}">
                        <input type="hidden" name="phone"     value="{{ $user->phone }}">
                        <input type="file"   name="profile_picture" id="avatarInput" accept="image/jpg,image/jpeg,image/png,image/webp">
                    </form>
                    <div class="avatar-wrap" onclick="document.getElementById('avatarInput').click()">
                        @if($user->profile_picture)
                            <img src="{{ asset($user->profile_picture) }}" alt="avatar" class="avatar-img" id="avatarPreview">
                        @else
                            <div class="avatar-initials" id="avatarInitials">{{ strtoupper(substr($user->user_name, 0, 2)) }}</div>
                        @endif
                        <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
                    </div>
                    <h2>{{ $user->user_name }}</h2>
                    <span class="profile-role-badge">
                        {{ $user->role === 'admin' ? 'អ្នកគ្រប់គ្រង' : 'អ្នកប្រើប្រាស់' }}
                    </span>
                    <div class="img-hint"><i class="fas fa-camera"></i>ចុចលើរូបភាពដើម្បីផ្លាស់ប្ដូរ</div>
                </div>

                <div class="profile-body">
                    @if(session('success'))
                        <div class="alert alert-success" id="flash-ok">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('reset_sent'))
                        <div class="alert alert-success" id="flash-ok">
                            <i class="fas fa-envelope-open-text"></i> {{ session('reset_sent') }}
                        </div>
                    @endif
                    @if(session('reset_error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> {{ session('reset_error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="prf-main-form">
                        @csrf

                        <div class="section-title">
                            <i class="fas fa-user-edit"></i> ព័ត៌មានទូទៅ
                        </div>

                        <div class="form-group">
                            <label>ឈ្មោះ</label>
                            <input type="text" name="user_name"
                                   value="{{ old('user_name', $user->user_name) }}"
                                   class="{{ $errors->has('user_name') ? 'is-invalid' : '' }}"
                                   placeholder="បញ្ចូលឈ្មោះ">
                            @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>អ៊ីមែល</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="example@email.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="0xx xxx xxx">
                        </div>

                        <div class="divider"></div>

                        <div class="section-title">
                            <i class="fas fa-lock"></i> ផ្លាស់ប្ដូរពាក្យសម្ងាត់
                        </div>

                        <div class="form-group">
                            <div class="prf-pw-label-row">
                                <label>ពាក្យសម្ងាត់បច្ចុប្បន្ន</label>
                                <button type="button" onclick="document.getElementById('forgotPwModal').style.display='flex'"
                                        class="prf-forgot-btn">
                                    <i class="fas fa-key"></i>ភ្លេចពាក្យសម្ងាត់?
                                </button>
                            </div>
                            <div class="pw-toggle">
                                <input type="password" name="current_password" id="cur_pw"
                                       class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន">
                                <i class="fas fa-eye pw-eye" onclick="togglePw('cur_pw',this)"></i>
                            </div>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>ពាក្យសម្ងាត់ថ្មី</label>
                            <div class="pw-toggle">
                                <input type="password" name="new_password" id="new_pw"
                                       class="{{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                                       placeholder="យ៉ាងហោចណាស់ ៨ តួ">
                                <i class="fas fa-eye pw-eye" onclick="togglePw('new_pw',this)"></i>
                            </div>
                            @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                            <div class="pw-toggle">
                                <input type="password" name="new_password_confirmation" id="conf_pw"
                                       placeholder="បញ្ចូលម្ដងទៀត">
                                <i class="fas fa-eye pw-eye" onclick="togglePw('conf_pw',this)"></i>
                            </div>
                        </div>

                        <div class="divider"></div>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> រក្សាទុក
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Forgot Password Modal --}}
    <div id="forgotPwModal" class="prf-modal-overlay" style="display:none;">
        <div class="prf-modal-box">

            {{-- Modal header --}}
            <div class="prf-modal-header">
                <div class="prf-modal-header-left">
                    <div class="prf-modal-key-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <div class="prf-modal-title">ភ្លេចពាក្យសម្ងាត់</div>
                        <div class="prf-modal-subtitle">កំណត់ពាក្យសម្ងាត់ឡើងវិញតាមអ៊ីមែល</div>
                    </div>
                </div>
                <button onclick="document.getElementById('forgotPwModal').style.display='none'"
                        type="button"
                        class="prf-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="prf-modal-body">
                <div class="prf-modal-info">
                    <i class="fas fa-envelope"></i>
                    យើងនឹងផ្ញើតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ទៅអ៊ីមែល<br>
                    <strong class="prf-modal-email">{{ Auth::user()->email }}</strong><br>
                    <span class="prf-modal-note">
                        <i class="fas fa-clock"></i>តំណភ្ជាប់មានសុពលភាព ៦០ នាទី
                    </span>
                </div>

                <div class="prf-modal-actions">
                    <button type="button"
                            onclick="document.getElementById('forgotPwModal').style.display='none'"
                            class="prf-modal-btn-cancel">
                        <i class="fas fa-times"></i>បោះបង់
                    </button>
                    <form method="POST" action="{{ route('profile.forgot-password') }}" class="prf-modal-form">
                        @csrf
                        <button type="submit" class="prf-modal-btn-submit">
                            <i class="fas fa-paper-plane"></i>ផ្ញើអ៊ីមែល
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePw(id, icon) {
        var inp = document.getElementById(id);
        if (inp.type === 'password') { inp.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
        else { inp.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
    }

    // Auto-submit avatar form when file chosen
    document.getElementById('avatarInput').addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;
        // Show instant preview
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('avatarPreview');
            var initials = document.getElementById('avatarInitials');
            if (preview) {
                preview.src = e.target.result;
            } else {
                // Replace initials div with img
                var wrap = document.querySelector('.avatar-wrap');
                var overlay = wrap.querySelector('.avatar-overlay');
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'avatar-img';
                img.id = 'avatarPreview';
                if (initials) wrap.replaceChild(img, initials);
                wrap.insertBefore(img, overlay);
            }
        };
        reader.readAsDataURL(this.files[0]);
        document.getElementById('avatarForm').submit();
    });

    var ok = document.getElementById('flash-ok');
    if (ok) setTimeout(function(){ ok.style.transition='opacity .4s'; ok.style.opacity='0'; setTimeout(function(){ ok.style.display='none'; },400); }, 5000);

    // Close forgot-password modal on backdrop click
    document.getElementById('forgotPwModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
    </script>
</body>
</html>
