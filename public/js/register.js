document.addEventListener('DOMContentLoaded', function() {
    // Password toggle visibility
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.querySelector('.strength-bar');
        const strengthText = document.querySelector('.strength-text span');
        
        // Calculate password strength
        let strength = 0;
        let strengthPercent = 0;
        
        if (password.length >= 8) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        // Update strength bar and text
        strengthPercent = strength * 20;
        strengthBar.style.width = strengthPercent + '%';
        
        if (strength === 0) {
            strengthBar.style.backgroundColor = '#f72585';
            strengthText.textContent = 'None';
            strengthText.style.color = '#f72585';
        } else if (strength <= 2) {
            strengthBar.style.backgroundColor = '#f8961e';
            strengthText.textContent = 'Weak';
            strengthText.style.color = '#f8961e';
        } else if (strength <= 4) {
            strengthBar.style.backgroundColor = '#4cc9f0';
            strengthText.textContent = 'Good';
            strengthText.style.color = '#4cc9f0';
        } else {
            strengthBar.style.backgroundColor = '#4ade80';
            strengthText.textContent = 'Strong';
            strengthText.style.color = '#4ade80';
        }
    });
    
    // Password confirmation check
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        const checkIcon = document.querySelector('.password-match i');
        const matchText = document.querySelector('.match-text');
        
        if (confirmPassword === '') {
            checkIcon.classList.remove('visible');
            matchText.textContent = 'Passwords must match';
            matchText.classList.remove('valid');
        } else if (password === confirmPassword) {
            checkIcon.classList.add('visible');
            matchText.textContent = 'Passwords match';
            matchText.classList.add('valid');
        } else {
            checkIcon.classList.remove('visible');
            matchText.textContent = 'Passwords do not match';
            matchText.classList.remove('valid');
        }
    });
    
    // Form submission
    const registerForm = document.getElementById('registerForm');
    
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const username = document.getElementById('username').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const terms = document.getElementById('terms').checked;
        
        // Simple validation
        let isValid = true;
        let errorMessage = '';
        
        // Username validation
        if (username.length < 3 || username.length > 20) {
            isValid = false;
            errorMessage += 'Username must be between 3 and 20 characters.\n';
        }
        
        // Phone validation (simple)
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$|^[\+]?[1-9][\d]{0,15}[\-]?[\d]{1,16}$/;
        if (!phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''))) {
            isValid = false;
            errorMessage += 'Please enter a valid phone number.\n';
        }
        
        // Password validation
        if (password.length < 8) {
            isValid = false;
            errorMessage += 'Password must be at least 8 characters long.\n';
        }
        
        // Password match validation
        if (password !== confirmPassword) {
            isValid = false;
            errorMessage += 'Passwords do not match.\n';
        }
        
        // Terms agreement validation
        if (!terms) {
            isValid = false;
            errorMessage += 'You must agree to the terms and conditions.\n';
        }
        
        // If form is valid, show success message
        if (isValid) {
            // In a real application, you would submit the form to a server here
            alert('Registration successful! Welcome, ' + username + '!');
            registerForm.reset();
            
            // Reset strength bar
            document.querySelector('.strength-bar').style.width = '0%';
            document.querySelector('.strength-text span').textContent = 'None';
            document.querySelector('.strength-text span').style.color = '#f72585';
            
            // Reset password match indicator
            document.querySelector('.password-match i').classList.remove('visible');
            document.querySelector('.match-text').textContent = 'Passwords must match';
            document.querySelector('.match-text').classList.remove('valid');
        } else {
            alert('Please fix the following errors:\n\n' + errorMessage);
        }
    });
    
    // Social login buttons
    document.querySelector('.social-btn.google').addEventListener('click', function() {
        alert('Google registration would be implemented here.');
    });
    
    document.querySelector('.social-btn.facebook').addEventListener('click', function() {
        alert('Facebook registration would be implemented here.');
    });
});