document.addEventListener('DOMContentLoaded', function() {
    // Set document language
    document.documentElement.lang = 'km';
    
    // DOM Elements
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const loginBtn = document.getElementById('loginBtn');
    const forgotPassword = document.getElementById('forgotPassword');
    const signupLink = document.getElementById('signupLink');
    const rememberCheckbox = document.getElementById('remember');
    
    // Check for saved credentials
    checkSavedCredentials();
    
    // Toggle password visibility
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
            
            // Add animation
            this.style.transform = 'translateY(-50%) scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'translateY(-50%) scale(1)';
            }, 200);
        });
    }
    
    // Form validation on input
    [emailInput, passwordInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                validateInput(this);
            });
            
            input.addEventListener('blur', function() {
                validateInput(this, true);
            });
        }
    });
    
    // Form submission
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all inputs
            const emailValid = validateInput(emailInput, true);
            const passwordValid = validateInput(passwordInput, true);
            
            if (!emailValid || !passwordValid) {
                showError('សូមបំពេញព័ត៌មានឲ្យបានត្រឹមត្រូវ!');
                return;
            }
            
            // Save credentials if "Remember Me" is checked
            if (rememberCheckbox.checked) {
                localStorage.setItem('savedEmail', emailInput.value);
                localStorage.setItem('savedRemember', 'true');
            } else {
                localStorage.removeItem('savedEmail');
                localStorage.removeItem('savedRemember');
            }
            
            // Show loading state
            startLoading();
            
            // Simulate API call
            simulateLogin(emailInput.value, passwordInput.value);
        });
    }
    
    // Forgot password
    if (forgotPassword) {
        forgotPassword.addEventListener('click', function(e) {
            e.preventDefault();
            showForgotPasswordModal();
        });
    }
    
    // Sign up link
    if (signupLink) {
        signupLink.addEventListener('click', function(e) {
            e.preventDefault();
            showSignupModal();
        });
    }
    
    // Social login buttons
    document.querySelectorAll('.social-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.classList.contains('facebook') ? 'Facebook' : 'Google';
            showNotification(`កំពុងភ្ជាប់ជាមួយ ${type}...`);
        });
    });
    
    // Language selector
    const langBtn = document.querySelector('.lang-btn');
    if (langBtn) {
        langBtn.addEventListener('click', function() {
            showLanguageSelector();
        });
    }
    
    // Functions
    function validateInput(input, showError = false) {
        const value = input.value.trim();
        const wrapper = input.parentElement;
        
        // Remove existing error/success classes
        wrapper.classList.remove('error', 'success');
        
        if (!value) {
            if (showError) {
                wrapper.classList.add('error');
                showInputError(input, 'សូមបំពេញព័ត៌មាននេះ!');
            }
            return false;
        }
        
        // Email validation
        if (input.type === 'text' && input.id === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9]{9,10}$/;
            
            if (!emailRegex.test(value) && !phoneRegex.test(value.replace(/\s/g, ''))) {
                if (showError) {
                    wrapper.classList.add('error');
                    showInputError(input, 'សូមបញ្ចូលអ៊ីមែល ឬលេខទូរស័ព្ទឲ្យបានត្រឹមត្រូវ!');
                }
                return false;
            }
        }
        
        // Password validation
        if (input.type === 'password') {
            if (value.length < 6) {
                if (showError) {
                    wrapper.classList.add('error');
                    showInputError(input, 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោច ៦ តួអក្សរ!');
                }
                return false;
            }
        }
        
        wrapper.classList.add('success');
        return true;
    }
    
    function showInputError(input, message) {
        // Remove existing error message
        const existingError = input.parentElement.querySelector('.error-message');
        if (existingError) existingError.remove();
        
        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errorDiv.style.cssText = `
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        `;
        
        input.parentElement.appendChild(errorDiv);
    }
    
    function showError(message) {
        // Create notification
        const notification = document.createElement('div');
        notification.className = 'error-notification';
        notification.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">&times;</button>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 400px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    function showSuccess(message) {
        const notification = document.createElement('div');
        notification.className = 'success-notification';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">&times;</button>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 400px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    function startLoading() {
        loginBtn.innerHTML = '<div class="spinner"></div> កំពុងចូល...';
        loginBtn.disabled = true;
        
        // Add loading animation to form
        loginForm.style.opacity = '0.8';
        loginForm.style.pointerEvents = 'none';
    }
    
    function stopLoading() {
        loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> ចូលទៅកាន់ប្រព័ន្ធ';
        loginBtn.disabled = false;
        
        // Remove loading animation
        loginForm.style.opacity = '1';
        loginForm.style.pointerEvents = 'auto';
    }
    
    function simulateLogin(email, password) {
        // Simulate API delay
        setTimeout(() => {
            // In real app, this would be an API call
            // For demo purposes, we'll simulate successful login with demo credentials
            const demoCredentials = {
                email: 'admin@trucking.com',
                password: 'password123'
            };
            
            if (email === demoCredentials.email && password === demoCredentials.password) {
                showSuccess('ចូលប្រើជោគជ័យ! កំពុងផ្លាស់ប្តូរទៅទំព័រដើម...');
                
                // Redirect after success
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);
            } else {
                showError('ឈ្មោះអ្នកប្រើប្រាស់ ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ!');
                stopLoading();
                
                // Add shake animation to form
                loginForm.style.animation = 'shake 0.5s ease';
                setTimeout(() => {
                    loginForm.style.animation = '';
                }, 500);
            }
        }, 2000);
    }
    
    function checkSavedCredentials() {
        const savedEmail = localStorage.getItem('savedEmail');
        const savedRemember = localStorage.getItem('savedRemember');
        
        if (savedEmail && savedRemember === 'true' && emailInput) {
            emailInput.value = savedEmail;
            rememberCheckbox.checked = true;
            validateInput(emailInput);
        }
    }
    
    function showForgotPasswordModal() {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <h3><i class="fas fa-key"></i> កំណត់ពាក្យសម្ងាត់ឡើងវិញ</h3>
                <p>បញ្ចូលអ៊ីមែលរបស់អ្នកដើម្បីទទួលបានតំណកំណត់ពាក្យសម្ងាត់ឡើងវិញ</p>
                <div class="input-wrapper" style="margin: 20px 0;">
                    <input type="email" id="resetEmail" placeholder="អ៊ីមែលរបស់អ្នក">
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                </div>
                <div class="modal-actions">
                    <button class="modal-btn primary" onclick="sendResetLink()">
                        <i class="fas fa-paper-plane"></i> ផ្ញើតំណ
                    </button>
                    <button class="modal-btn secondary" onclick="this.closest('.modal-overlay').remove()">
                        <i class="fas fa-times"></i> បោះបង់
                    </button>
                </div>
            </div>
        `;
        
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        `;
        
        document.body.appendChild(modal);
    }
    
    function showSignupModal() {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <h3><i class="fas fa-user-plus"></i> ចុះឈ្មោះគណនីថ្មី</h3>
                <p>លក្ខណៈពិសេសចុះឈ្មោះនឹងមាននៅពេលខាងមុខ!</p>
                <p style="margin-top: 10px; font-size: 14px; color: #666;">
                    សូមទាក់ទងក្រុមជំនួយរបស់យើងតាមរយៈ: <br>
                    <i class="fas fa-phone"></i> +855 12 345 678 <br>
                    <i class="fas fa-envelope"></i> support@trucking.com
                </p>
                <div class="modal-actions" style="margin-top: 20px;">
                    <button class="modal-btn secondary" onclick="this.closest('.modal-overlay').remove()">
                        <i class="fas fa-times"></i> យល់ព្រម
                    </button>
                </div>
            </div>
        `;
        
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        `;
        
        document.body.appendChild(modal);
    }
    
    function showLanguageSelector() {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <h3><i class="fas fa-globe"></i> ជ្រើសរើសភាសា</h3>
                <div class="language-options">
                    <button class="lang-option active" onclick="changeLanguage('km')">
                        <span>🇰🇭</span>
                        <span>ភាសាខ្មែរ</span>
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="lang-option" onclick="changeLanguage('en')">
                        <span>🇺🇸</span>
                        <span>English</span>
                    </button>
                    <button class="lang-option" onclick="changeLanguage('zh')">
                        <span>🇨🇳</span>
                        <span>中文</span>
                    </button>
                </div>
                <div class="modal-actions">
                    <button class="modal-btn secondary" onclick="this.closest('.modal-overlay').remove()">
                        <i class="fas fa-times"></i> បិទ
                    </button>
                </div>
            </div>
        `;
        
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        `;
        
        document.body.appendChild(modal);
    }
    
    // Global functions for modals
    window.sendResetLink = function() {
        const emailInput = document.getElementById('resetEmail');
        if (emailInput && emailInput.value) {
            showSuccess('តំណកំណត់ពាក្យសម្ងាត់ឡើងវិញត្រូវបានផ្ញើទៅអ៊ីមែលរបស់អ្នក!');
            document.querySelector('.modal-overlay').remove();
        } else {
            showError('សូមបញ្ចូលអ៊ីមែលរបស់អ្នក!');
        }
    };
    
    window.changeLanguage = function(lang) {
        // In real app, this would change the language
        showNotification(`កំពុងប្តូរភាសាទៅ ${lang === 'km' ? 'ភាសាខ្មែរ' : lang === 'en' ? 'English' : '中文'}...`);
        document.querySelector('.modal-overlay').remove();
    };
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 400px;
            width: 90%;
            animation: slideInUp 0.3s ease;
        }
        
        @keyframes slideInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-content h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modal-content p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .modal-btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .modal-btn.primary {
            background: linear-gradient(135deg, #ff7e00, #ff5500);
            color: white;
        }
        
        .modal-btn.secondary {
            background: #e2e8f0;
            color: #2c3e50;
        }
        
        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .language-options {
            margin: 20px 0;
        }
        
        .lang-option {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .lang-option.active {
            border-color: #ff7e00;
            background: #fff7ed;
        }
        
        .lang-option:hover {
            border-color: #ff7e00;
            transform: translateY(-2px);
        }
        
        .lang-option span:first-child {
            font-size: 20px;
        }
        
        .input-wrapper.error {
            border-color: #ef4444 !important;
        }
        
        .input-wrapper.success {
            border-color: #10b981 !important;
        }
    `;
    document.head.appendChild(style);
    
    // Add initial animation
    setTimeout(() => {
        document.querySelector('.login-container').style.opacity = '1';
        document.querySelector('.login-container').style.transform = 'translateY(0)';
    }, 100);
});