<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កំណត់ពាក្យសម្ងាត់ | LS Trucking Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="page-main">
        <div class="card">
            <div class="card-header">
                <div class="header-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1>កំណត់ពាក្យសម្ងាត់ថ្មី</h1>
                <p>បញ្ចូលពាក្យសម្ងាត់ថ្មីដែលមានសុវត្ថិភាពសម្រាប់គណនីរបស់អ្នក</p>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- New Password -->
                    <div class="input-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> ពាក្យសម្ងាត់ថ្មី
                        </label>
                        <div class="input-wrapper">
                            <span class="icon"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password"
                                   placeholder="បញ្ចូលពាក្យសម្ងាត់ថ្មី" required>
                            <button type="button" class="toggle-btn" id="togglePw">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="strength-segments">
                            <div class="seg" id="seg1"></div>
                            <div class="seg" id="seg2"></div>
                            <div class="seg" id="seg3"></div>
                        </div>
                        <div class="strength-label">
                            កម្លាំងពាក្យសម្ងាត់៖ <span id="strengthText">—</span>
                        </div>
                        <div class="error-msg" id="pwError"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="input-group">
                        <label for="password_confirmation">
                            <i class="fas fa-lock"></i> បញ្ជាក់ពាក្យសម្ងាត់
                        </label>
                        <div class="input-wrapper">
                            <span class="icon"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="បញ្ចូលពាក្យសម្ងាត់ម្តងទៀត" required>
                            <button type="button" class="toggle-btn" id="toggleCpw">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-msg" id="cpwError"></div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-check-circle"></i>
                        រក្សាទុកពាក្យសម្ងាត់ថ្មី
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅចូលគណនី
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle visibility
        function setupToggle(btnId, inputId) {
            const btn = document.getElementById(btnId);
            const inp = document.getElementById(inputId);
            if (!btn || !inp) return;
            btn.addEventListener('click', function () {
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                this.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
            });
        }
        setupToggle('togglePw',  'password');
        setupToggle('toggleCpw', 'password_confirmation');

        // Password strength
        const pwInput      = document.getElementById('password');
        const strengthText = document.getElementById('strengthText');
        const segs = [document.getElementById('seg1'), document.getElementById('seg2'), document.getElementById('seg3')];
        const SYMBOL_RE = /[!@#$%^&*()\-_=+\[\]{}|;:,.<>?\/\\~`'"]/;

        function calcScore(pw) {
            let s = 0;
            if (pw.length >= 8)     s++;
            if (/[A-Z]/.test(pw))   s++;
            if (/[a-z]/.test(pw))   s++;
            if (/[0-9]/.test(pw))   s++;
            if (SYMBOL_RE.test(pw)) s++;
            return s;
        }

        function updateUI(score) {
            segs.forEach(s => s.className = 'seg');
            if (score === 0) { strengthText.textContent = '—'; strengthText.className = ''; }
            else if (score <= 2) { segs[0].classList.add('seg-weak'); strengthText.textContent = 'ខ្សោយ'; strengthText.className = 'txt-weak'; }
            else if (score <= 4) { segs[0].classList.add('seg-median'); segs[1].classList.add('seg-median'); strengthText.textContent = 'មធ្យម'; strengthText.className = 'txt-median'; }
            else { segs.forEach(s => s.classList.add('seg-strong')); strengthText.textContent = 'ខ្លាំង'; strengthText.className = 'txt-strong'; }
        }

        if (pwInput) pwInput.addEventListener('input', () => updateUI(calcScore(pwInput.value)));

        // Form validation
        document.getElementById('resetForm').addEventListener('submit', function (e) {
            let valid = true;
            const pw  = document.getElementById('password').value;
            const cpw = document.getElementById('password_confirmation').value;

            document.querySelectorAll('.error-msg').forEach(el => { el.textContent = ''; el.classList.remove('show'); });

            if (pw.length < 8) {
                document.getElementById('pwError').textContent = 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៨ តួអក្សរ';
                document.getElementById('pwError').classList.add('show');
                valid = false;
            } else {
                const missing = [];
                if (!/[A-Z]/.test(pw))       missing.push('អក្សរធំ');
                if (!/[a-z]/.test(pw))       missing.push('អក្សរតូច');
                if (!/[0-9]/.test(pw))       missing.push('លេខ');
                if (!SYMBOL_RE.test(pw))     missing.push('សញ្ញា');
                if (missing.length) {
                    document.getElementById('pwError').textContent = 'ខ្វះ: ' + missing.join(', ');
                    document.getElementById('pwError').classList.add('show');
                    valid = false;
                }
            }

            if (pw !== cpw) {
                document.getElementById('cpwError').textContent = 'ពាក្យសម្ងាត់មិនដូចគ្នា';
                document.getElementById('cpwError').classList.add('show');
                valid = false;
            }

            if (!valid) e.preventDefault();
        });
    });
    </script>
</body>
</html>
