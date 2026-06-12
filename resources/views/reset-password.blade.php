<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កំណត់ពាក្យសម្ងាត់ | LS Trucking Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe0b2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Kantumruy Pro', 'Poppins', sans-serif;
            color: #1E293B;
        }
        .page-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin-top: 80px;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(255,107,0,0.15), 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #FF6B00, #FF8A3D);
            padding: 36px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 140px; height: 140px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -50px; left: -30px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .header-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }
        .card-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }
        .card-header p {
            color: rgba(255,255,255,0.85);
            font-size: 0.88rem;
            position: relative;
            z-index: 1;
        }
        .card-body { padding: 36px 40px; }

        .alert-error {
            background: #fff0f0;
            border: 1.5px solid #fca5a5;
            color: #dc2626;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .input-group { margin-bottom: 20px; }
        .input-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #1E293B;
            margin-bottom: 8px;
        }
        .input-group label i { color: #FF6B00; }
        .input-wrapper { position: relative; }
        .input-wrapper .icon {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
        }
        .input-wrapper input[type="email"],
        .input-wrapper input[type="password"],
        .input-wrapper input[type="text"] {
            width: 100%;
            padding: 13px 48px 13px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Kantumruy Pro', 'Poppins', sans-serif;
            background: #f8fafc;
            color: #1E293B;
            transition: all 0.3s ease;
            outline: none;
        }
        .input-wrapper input:focus {
            border-color: #FF6B00;
            background: white;
            box-shadow: 0 0 0 4px rgba(255,107,0,0.08);
        }
        .toggle-btn {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 15px;
            padding: 0;
            transition: color 0.3s;
        }
        .toggle-btn:hover { color: #FF6B00; }

        /* Strength segments */
        .strength-segments {
            display: flex;
            gap: 6px;
            margin: 8px 0 4px;
        }
        .seg {
            flex: 1; height: 5px;
            border-radius: 4px;
            background: #e9ecef;
            transition: background 0.3s;
        }
        .seg-weak   { background: #f72585; }
        .seg-median { background: #f8961e; }
        .seg-strong { background: #4ade80; }
        .strength-label { font-size: 0.82rem; color: #64748b; margin-bottom: 4px; }
        .strength-label span { font-weight: 700; }
        .txt-weak   { color: #f72585; }
        .txt-median { color: #f8961e; }
        .txt-strong { color: #22c55e; }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #FF6B00, #FF8A3D);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Kantumruy Pro', 'Poppins', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 8px;
            margin-bottom: 20px;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,107,0,0.35);
        }
        .back-link {
            text-align: center;
            font-size: 14px;
            color: #64748B;
        }
        .back-link a {
            color: #FF6B00;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .back-link a:hover { color: #E55A00; text-decoration: underline; }

        .error-msg {
            color: #f72585;
            font-size: 0.82rem;
            margin-top: 4px;
            display: none;
        }
        .error-msg.show { display: block; }
    </style>
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
