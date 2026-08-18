<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>ចូលគណនី | LS Trucking Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    @include('partials.header')

    <main class="login-container">
        <div class="login-wrapper">

            <!-- Left Panel -->
            <div class="login-left">
                <img src="{{ asset('images/login_and_register_img.jpg') }}" alt="LS Trucking" class="ll-bg-img">
                <!-- <div class="ll-overlay"></div> -->
                <div class="ll-content">
                    <div class="brand">
                        {{-- <div class="brand-icon">LS</div> --}}
                        {{-- <div class="brand-name">TRUCKING<br>SERVICE</div> --}}
                    </div>
                    
                    <h1> <br> សូមស្វាគមន៍<br>មកកាន់ប្រព័ន្ធ</h1>
                    <p>ប្រព័ន្ធគ្រប់គ្រងសេវាកម្មដឹកជញ្ជូនដែលជឿជាក់បំផុតនៅកម្ពុជា</p>
                    <ul class="features-list">
                        <li><i class="fas fa-shield-alt"></i> គណនីដែលមានសុវត្ថិភាពខ្ពស់</li>
                        <li><i class="fas fa-map-marker-alt"></i> តាមដានការដឹកជញ្ជូនជាពេលវេលាពិត</li>
                        <li><i class="fas fa-truck"></i> គ្រប់គ្រងរបស់អ្នកដោយងាយស្រួល</li>
                        <li><i class="fas fa-headset"></i> ការគាំទ្រអតិថិជន 24/7</li>
                    </ul>
                </div>
               
            </div>

            <!-- Right Panel -->
            <div class="login-right">
                <div class="form-container">
                    <div class="form-header">
                        <h2>ចូលគណនី</h2>
                        <p>បញ្ចូលព័ត៌មានរបស់អ្នកដើម្បីចូលទៅកាន់ប្រព័ន្ធ</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" autocomplete="off">
                        @csrf

                        <div class="input-group">
                            <label>
                                <i class="fas fa-envelope"></i>
                                អ៊ីមែល
                            </label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       placeholder="បញ្ចូលអ៊ីមែលរបស់អ្នក"
                                       autocomplete="email" required>
                                <span class="icon"><i class="fas fa-envelope"></i></span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>
                                <i class="fas fa-lock"></i>
                                ពាក្យសម្ងាត់
                            </label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password"
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់"
                                       autocomplete="new-password" required>
                                <span class="icon"><i class="fas fa-key"></i></span>
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">ចងចាំគណនី</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="forgot-password">ភ្លេចពាក្យសម្ងាត់?</a>
                        </div>

                        <button type="submit" class="login-submit-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            ចូលទៅកាន់ប្រព័ន្ធ
                        </button>
                    </form>

                    <div class="register-link">
                        មិនទាន់មានគណនីទេ? <a href="{{ route('register') }}">ចុះឈ្មោះឥឡូវនេះ</a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
            pwd.setAttribute('type', type);
            this.innerHTML = type === 'password'
                ? '<i class="fas fa-eye"></i>'
                : '<i class="fas fa-eye-slash"></i>';
        });
    </script>

    @include('partials.footer')

</body>
</html>