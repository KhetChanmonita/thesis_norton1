<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចុះឈ្មោះ - LS Trucking Service</title>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Kantumruy+Pro:wght@400;500;600&family=Poppins:wght@300;400;500;600;700&family=Battambang:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Header CSS first so its !important rules win -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <!-- Include your custom register CSS -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    @include('partials.header')
    
    <main class="register-container">
        <div class="register-wrapper">
            <div class="register-left">
                <div class="left-content">
                    <h1>ចូលរួមជាមួយពួកយើង</h1>
                    <p>បង្កើតគណនីដើម្បីទទួលបានលក្ខណៈពិសេស និងមាតិកាតាមបំណង។</p>
                    <ul class="features-list">
                        <li><i class="fas fa-check-circle"></i> គណនីដែលមានសុវត្ថិភាពជាមួយការអ៊ិនគ្រីប</li>
                        <li><i class="fas fa-check-circle"></i> សមត្ថភាពចូលទៅកាន់មាតិកាប្លេហ្សាំ</li>
                        <li><i class="fas fa-check-circle"></i> ការណែនាំដែលប្តូរតាមបំណង</li>
                        <li><i class="fas fa-check-circle"></i> ការគាំទ្រអតិថិជន 24/7</li>
                    </ul>
                    <div class="image-placeholder">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>
            
            <div class="register-right">
                <div class="form-container">
                    <div class="form-header">
                        <h2>បង្កើតគណនី</h2>
                        <p>បំពេញព័ត៌មានរបស់អ្នកដើម្បីចាប់ផ្តើម</p>
                    </div>
                    
                    @if ($errors->any())
                        <div style="background:#fff0f0;border:1px solid #f72585;border-radius:8px;padding:12px 16px;margin-bottom:15px;color:#c0143c;font-size:0.9rem;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="username" name="user_name" value="{{ old('user_name') }}" placeholder="បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់" required>
                            <div class="error-message" id="usernameError"></div>
                        </div>

                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="បញ្ចូលលេខទូរស័ព្ទ" required>
                            <div class="error-message" id="phoneError"></div>
                        </div>

                        <div class="form-group">
                            <div class="password-input">
                                <input type="password" id="password" name="password" placeholder="បញ្ចូលពាក្យសម្ងាត់" required>
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar"></div>
                                <div class="strength-text">កម្លាំងពាក្យសម្ងាត់៖ <span>ទទេ</span></div>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>

                        <div class="form-group">
                            <div class="password-input">
                                <input type="password" id="confirmPassword" name="password_confirmation" placeholder="បញ្ចូលពាក្យសម្ងាត់ម្តងទៀត" required>
                                <button type="button" class="toggle-password" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-match">
                                <i class="fas fa-check-circle hidden"></i>
                                <span class="match-text">ពាក្យសម្ងាត់ត្រូវតែដូចគ្នា</span>
                            </div>
                            <div class="error-message" id="confirmPasswordError"></div>
                        </div>

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
                        
                        <div class="divider">
                            <span>ឬ</span>
                        </div>
                        
                        <div class="login-link">
                            មានគណនីរួចហើយ? <a href="{{ route('login') }}">ចូលប្រើ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="main-footer">
        <div class="container">
            <p>&copy; ២០២៣ LS Trucking Service. រក្សាសិទ្ធិគ្រប់យ៉ាង។ | <a href="#">គោលការណ៍ភាពឯកជន</a> | <a href="#">លក្ខខណ្ឌសេវាកម្ម</a></p>
        </div>
    </footer>
    
    <!-- Include your custom register JavaScript -->
    <script>
        // Update JavaScript messages to Khmer
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle visibility
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
            
            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
            
            // Password strength text updates
            const passwordInputEl = document.getElementById('password');
            if (passwordInputEl) {
                passwordInputEl.addEventListener('input', function() {
                    const password = this.value;
                    const strengthBar = document.querySelector('.strength-bar');
                    const strengthText = document.querySelector('.strength-text span');
                    
                    let strength = 0;
                    
                    if (password.length >= 8) strength += 1;
                    if (/[A-Z]/.test(password)) strength += 1;
                    if (/[a-z]/.test(password)) strength += 1;
                    if (/[0-9]/.test(password)) strength += 1;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                    
                    strengthBar.style.width = (strength * 20) + '%';
                    
                    // Update text in Khmer
                    if (strength === 0) {
                        strengthBar.style.backgroundColor = '#f72585';
                        strengthText.textContent = 'ទទេ';
                    } else if (strength <= 2) {
                        strengthBar.style.backgroundColor = '#f8961e';
                        strengthText.textContent = 'ខ្សោយ';
                    } else if (strength <= 4) {
                        strengthBar.style.backgroundColor = '#4cc9f0';
                        strengthText.textContent = 'មធ្យម';
                    } else {
                        strengthBar.style.backgroundColor = '#4ade80';
                        strengthText.textContent = 'ខ្លាំង';
                    }
                });
            }
            
            // Password match indicator updates in Khmer
            const confirmPasswordInputEl = document.getElementById('confirmPassword');
            if (confirmPasswordInputEl) {
                confirmPasswordInputEl.addEventListener('input', function() {
                    const password = document.getElementById('password').value;
                    const confirmPassword = this.value;
                    const checkIcon = document.querySelector('.password-match i');
                    const matchText = document.querySelector('.match-text');
                    
                    if (confirmPassword === '') {
                        checkIcon.classList.remove('visible');
                        matchText.textContent = 'ពាក្យសម្ងាត់ត្រូវតែដូចគ្នា';
                        matchText.classList.remove('valid');
                    } else if (password === confirmPassword) {
                        checkIcon.classList.add('visible');
                        matchText.textContent = 'ពាក្យសម្ងាត់ដូចគ្នា';
                        matchText.classList.add('valid');
                    } else {
                        checkIcon.classList.remove('visible');
                        matchText.textContent = 'ពាក្យសម្ងាត់មិនដូចគ្នា';
                        matchText.classList.remove('valid');
                    }
                });
            }
            
            // Form submission validation messages in Khmer
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                // Clear error messages when user starts typing
                const inputs = registerForm.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        const errorId = this.id + 'Error';
                        const errorElement = document.getElementById(errorId);
                        if (errorElement) {
                            errorElement.classList.remove('show');
                            errorElement.textContent = '';
                        }
                        
                        // Special case for terms checkbox
                        if (this.id === 'terms') {
                            const termsError = document.getElementById('termsError');
                            if (termsError) {
                                termsError.classList.remove('show');
                                termsError.textContent = '';
                            }
                        }
                    });
                });
                
                registerForm.addEventListener('submit', function(e) {
                    const username = document.getElementById('username').value;
                    const phone = document.getElementById('phone').value;
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;
                    const terms = document.getElementById('terms').checked;
                    
                    let isValid = true;
                    
                    // Clear all error messages
                    const errorMessages = document.querySelectorAll('.error-message');
                    errorMessages.forEach(error => {
                        error.classList.remove('show');
                        error.textContent = '';
                    });
                    
                    // Username: at least 3 characters
                    if (username.trim().length < 3) {
                        isValid = false;
                        const usernameError = document.getElementById('usernameError');
                        if (usernameError) {
                            usernameError.textContent = 'ឈ្មោះអ្នកប្រើប្រាស់ត្រូវមានយ៉ាងហោចណាស់ ៣ តួអក្សរ';
                            usernameError.classList.add('show');
                        }
                    }
                    
                    // Phone validation - Cambodian phone format
                    const phoneRegex = /^(0|\+855)?\d{8,9}$/;
                    if (!phoneRegex.test(phone.replace(/\s+/g, ''))) {
                        isValid = false;
                        const phoneError = document.getElementById('phoneError');
                        if (phoneError) {
                            phoneError.textContent = 'សូមបញ្ចូលលេខទូរស័ព្ទដែលត្រឹមត្រូវ (ឧ. 098787687, 98787687, +85598787687)';
                            phoneError.classList.add('show');
                        }
                    }
                    
                    // Password validation
                    if (password.length < 8) {
                        isValid = false;
                        const passwordError = document.getElementById('passwordError');
                        if (passwordError) {
                            passwordError.textContent = 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៨ តួអក្សរ';
                            passwordError.classList.add('show');
                        }
                    } else if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
                        isValid = false;
                        const passwordError = document.getElementById('passwordError');
                        if (passwordError) {
                            passwordError.textContent = 'ពាក្យសម្ងាត់ត្រូវតែមានអក្សរធំ អក្សរតូច និងលេខ';
                            passwordError.classList.add('show');
                        }
                    }
                    
                    // Password match validation
                    if (password !== confirmPassword) {
                        isValid = false;
                        const confirmPasswordError = document.getElementById('confirmPasswordError');
                        if (confirmPasswordError) {
                            confirmPasswordError.textContent = 'ពាក្យសម្ងាត់មិនដូចគ្នាទេ';
                            confirmPasswordError.classList.add('show');
                        }
                    }
                    
                    // Terms agreement validation
                    if (!terms) {
                        isValid = false;
                        const termsError = document.getElementById('termsError');
                        if (termsError) {
                            termsError.textContent = 'អ្នកត្រូវតែយល់ព្រមជាមួយលក្ខខណ្ឌ និងគោលការណ៍';
                            termsError.classList.add('show');
                        }
                    }
                    
                    if (!isValid) {
                        e.preventDefault(); // stop submission only when invalid
                    }
                    // if isValid → form submits normally to server (POST /register)
                });
            }
        });
    </script>
</body>
</html>