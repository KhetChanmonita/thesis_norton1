<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចុះឈ្មោះ - LS Trucking Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="register-container">
        <div class="register-wrapper" style="max-width: 1200px;max-height: 1000px; margin: 40px auto;">

            <!-- Left panel -->
            <div class="register-left">
                <div class="left-content">
                    <div class="rl-brand">
                        <div class="rl-brand-icon">LS</div>
                        <span>TRUCKING<br>SERVICE</span>
                    </div>
                    <h1>ចូលរួមជាមួយ<br>ពួកយើង</h1>
                    <p>ប្រព័ន្ធគ្រប់គ្រងសេវាកម្មដឹកជញ្ជូនដែលជឿជាក់បំផុតនៅកម្ពុជា</p>
                    <ul class="features-list">
                        <li><i class="fas fa-shield-alt"></i> គណនីដែលមានសុវត្ថិភាពខ្ពស់</li>
                        <li><i class="fas fa-map-marker-alt"></i> តាមដានការដឹកជញ្ជូនជាពេលវេលាពិត</li>
                        <li><i class="fas fa-truck"></i> ការស្នើសុំដឹកជញ្ជូនតាមអ៊ីនធឺណិត</li>
                        <li><i class="fas fa-headset"></i> ការគាំទ្រអតិថិជន 24/7</li>
                    </ul>
                </div>
                <div class="rl-bottom-img">
                    <img src="{{ asset('images/register.jpg') }}" alt="LS Trucking">
                </div>
            </div>
            

            <!-- Right panel -->
            <div class="register-right">
                <div class="form-container">
                    <div class="form-header">
                        <h2>បង្កើតគណនី</h2>
                        <p>បំពេញព័ត៌មានរបស់អ្នកដើម្បីចាប់ផ្តើម</p>
                    </div>

                    @if ($errors->any())
                        <div class="server-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Username -->
                        <div class="input-group">
                            <label for="username">
                                <i class="fas fa-user"></i> ឈ្មោះអ្នកប្រើប្រាស់
                            </label>
                            <div class="input-wrapper">
                                <input type="text" id="username" name="user_name"
                                       value="{{ old('user_name') }}"
                                       placeholder="បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់" required>
                                <span class="icon"><i class="fas fa-user"></i></span>
                            </div>
                            <div class="error-message" id="usernameError"></div>
                        </div>

                        <!-- Email -->
                        <div class="input-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> អ៊ីមែល
                            </label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="បញ្ចូលអ៊ីមែលរបស់អ្នក" required>
                                <span class="icon"><i class="fas fa-envelope"></i></span>
                            </div>
                            <div class="error-message" id="emailError"></div>
                        </div>

                        <!-- Password -->
                        <div class="input-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> ពាក្យសម្ងាត់
                            </label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password"
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់" required>
                                <span class="icon"><i class="fas fa-lock"></i></span>
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-segments">
                                    <div class="seg" id="seg1"></div>
                                    <div class="seg" id="seg2"></div>
                                    <div class="seg" id="seg3"></div>
                                </div>
                                <div class="strength-label">
                                    កម្លាំងពាក្យសម្ងាត់៖ <span id="strengthText">—</span>
                                </div>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-group">
                            <label for="confirmPassword">
                                <i class="fas fa-lock"></i> បញ្ជាក់ពាក្យសម្ងាត់
                            </label>
                            <div class="input-wrapper">
                                <input type="password" id="confirmPassword" name="password_confirmation"
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់ម្តងទៀត" required>
                                <span class="icon"><i class="fas fa-lock"></i></span>
                                <button type="button" class="toggle-password" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-match">
                                <i class="fas fa-check-circle" id="matchIcon"></i>
                                <span class="match-text" id="matchText">ពាក្យសម្ងាត់ត្រូវតែដូចគ្នា</span>
                            </div>
                            <div class="error-message" id="confirmPasswordError"></div>
                        </div>

                        <!-- Terms -->
                        <div class="form-group terms-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                ខ្ញុំយល់ព្រមជាមួយ <a href="#">លក្ខខណ្ឌសេវាកម្ម</a> និង <a href="#">គោលការណ៍ភាពឯកជន</a>
                            </label>
                            <div class="error-message" id="termsError"></div>
                        </div>

                        <button type="submit" class="register-btn">
                            <i class="fas fa-user-plus"></i> ចុះឈ្មោះ
                        </button>

                        <div class="divider"><span>ឬ</span></div>

                        <div class="login-link">
                            មានគណនីរួចហើយ? <a href="{{ route('login') }}">ចូលប្រើ</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    @include('partials.footer')

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ── Toggle password visibility ── */
        function setupToggle(btnId, inputId) {
            const btn = document.getElementById(btnId);
            const inp = document.getElementById(inputId);
            if (!btn || !inp) return;
            btn.addEventListener('click', function () {
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                this.innerHTML = show
                    ? '<i class="fas fa-eye-slash"></i>'
                    : '<i class="fas fa-eye"></i>';
            });
        }
        setupToggle('togglePassword',        'password');
        setupToggle('toggleConfirmPassword', 'confirmPassword');

        /* ── Password strength ── */
        const passwordInput = document.getElementById('password');
        const strengthText  = document.getElementById('strengthText');
        const segs = [
            document.getElementById('seg1'),
            document.getElementById('seg2'),
            document.getElementById('seg3'),
        ];

        const SYMBOL_RE = /[!@#$%^&*()\-_=+\[\]{}|;:,.<>?\/\\~`'"]/;

        function calcScore(pw) {
            let s = 0;
            if (pw.length >= 8)       s++;
            if (/[A-Z]/.test(pw))     s++;
            if (/[a-z]/.test(pw))     s++;
            if (/[0-9]/.test(pw))     s++;
            if (SYMBOL_RE.test(pw))   s++;
            return s;
        }

        function updateStrengthUI(score) {
            segs.forEach(seg => (seg.className = 'seg'));
            if (score === 0) {
                strengthText.textContent = '—';
                strengthText.className   = '';
            } else if (score <= 2) {
                segs[0].classList.add('seg-weak');
                strengthText.textContent = 'ខ្សោយ';
                strengthText.className   = 'txt-weak';
            } else if (score === 3 || score === 4) {
                segs[0].classList.add('seg-median');
                segs[1].classList.add('seg-median');
                strengthText.textContent = 'មធ្យម';
                strengthText.className   = 'txt-median';
            } else {
                segs[0].classList.add('seg-strong');
                segs[1].classList.add('seg-strong');
                segs[2].classList.add('seg-strong');
                strengthText.textContent = 'ខ្លាំង';
                strengthText.className   = 'txt-strong';
            }
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                updateStrengthUI(calcScore(this.value));
            });
        }

        /* ── Password match indicator ── */
        const confirmInput = document.getElementById('confirmPassword');
        const matchIcon    = document.getElementById('matchIcon');
        const matchText    = document.getElementById('matchText');

        if (confirmInput) {
            confirmInput.addEventListener('input', function () {
                const pw  = passwordInput ? passwordInput.value : '';
                const cpw = this.value;
                if (cpw === '') {
                    matchIcon.style.opacity = '0';
                    matchText.textContent   = 'ពាក្យសម្ងាត់ត្រូវតែដូចគ្នា';
                    matchText.classList.remove('valid');
                } else if (pw === cpw) {
                    matchIcon.style.opacity = '1';
                    matchText.textContent   = 'ពាក្យសម្ងាត់ដូចគ្នា';
                    matchText.classList.add('valid');
                } else {
                    matchIcon.style.opacity = '0';
                    matchText.textContent   = 'ពាក្យសម្ងាត់មិនដូចគ្នា';
                    matchText.classList.remove('valid');
                }
            });
        }

        /* ── Clear individual field error on input ── */
        document.querySelectorAll('#registerForm input').forEach(inp => {
            inp.addEventListener('input', function () {
                const err = document.getElementById(this.id + 'Error');
                if (err) { err.textContent = ''; err.classList.remove('show'); }
            });
        });

        /* ── Form submit validation ── */
        const form = document.getElementById('registerForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                let valid = true;
                document.querySelectorAll('.error-message').forEach(el => {
                    el.textContent = ''; el.classList.remove('show');
                });

                const username = document.getElementById('username').value.trim();
                const email    = document.getElementById('email').value.trim();
                const pw       = document.getElementById('password').value;
                const cpw      = document.getElementById('confirmPassword').value;
                const terms    = document.getElementById('terms').checked;

                if (username.length < 3) {
                    showError('usernameError', 'ឈ្មោះអ្នកប្រើប្រាស់ត្រូវមានយ៉ាងហោចណាស់ ៣ តួអក្សរ');
                    valid = false;
                }

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showError('emailError', 'សូមបញ្ចូលអ៊ីមែលដែលត្រឹមត្រូវ (ឧ. name@example.com)');
                    valid = false;
                }

                if (pw.length < 8) {
                    showError('passwordError', 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៨ តួអក្សរ');
                    valid = false;
                } else {
                    const missing = [];
                    if (!/[A-Z]/.test(pw))         missing.push('អក្សរធំ (A-Z)');
                    if (!/[a-z]/.test(pw))         missing.push('អក្សរតូច (a-z)');
                    if (!/[0-9]/.test(pw))         missing.push('លេខ (0-9)');
                    if (!SYMBOL_RE.test(pw))       missing.push('សញ្ញា (!@#$...)');
                    if (missing.length > 0) {
                        showError('passwordError', 'ខ្វះ: ' + missing.join(', '));
                        valid = false;
                    }
                }

                if (pw !== cpw) {
                    showError('confirmPasswordError', 'ពាក្យសម្ងាត់មិនដូចគ្នាទេ');
                    valid = false;
                }

                if (!terms) {
                    showError('termsError', 'អ្នកត្រូវតែយល់ព្រមជាមួយលក្ខខណ្ឌ និងគោលការណ៍');
                    valid = false;
                }

                if (!valid) e.preventDefault();
            });
        }

        function showError(id, msg) {
            const el = document.getElementById(id);
            if (el) { el.textContent = msg; el.classList.add('show'); }
        }
    });
    </script>
</body>
</html>
