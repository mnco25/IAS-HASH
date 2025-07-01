// Edit Profile JavaScript functions

function checkCurrentPassword() {
    const currentPassword = document.getElementById('current_password').value;
    const currentPasswordInput = document.getElementById('current_password');
    const feedback = document.getElementById('currentPasswordFeedback');

    if (currentPassword === '') {
        currentPasswordInput.classList.remove('password-match', 'password-mismatch');
        feedback.className = 'password-feedback';
        feedback.textContent = '';
        return;
    }

    // Show loading state
    feedback.className = 'password-feedback';
    feedback.textContent = 'Checking...';
    
    // Make AJAX request to verify current password
    const formData = new FormData();
    formData.append('ajax_action', 'verify_current_password');
    formData.append('current_password', currentPassword);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            currentPasswordInput.classList.remove('password-mismatch');
            currentPasswordInput.classList.add('password-match');
            feedback.className = 'password-feedback match';
            feedback.textContent = data.message;
        } else {
            currentPasswordInput.classList.remove('password-match');
            currentPasswordInput.classList.add('password-mismatch');
            feedback.className = 'password-feedback mismatch';
            feedback.textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Error verifying password:', error);
        currentPasswordInput.classList.remove('password-match');
        currentPasswordInput.classList.add('password-mismatch');
        feedback.className = 'password-feedback mismatch';
        feedback.textContent = '✗ Unable to verify password';
    });
}

function checkPasswordMatch() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const confirmPasswordInput = document.getElementById('confirm_password');
    const feedback = document.getElementById('passwordFeedback');

    if (confirmPassword === '') {
        confirmPasswordInput.classList.remove('password-match', 'password-mismatch');
        feedback.className = 'password-feedback';
        feedback.textContent = '';
        return;
    }

    if (newPassword === confirmPassword) {
        confirmPasswordInput.classList.remove('password-mismatch');
        confirmPasswordInput.classList.add('password-match');
        feedback.className = 'password-feedback match';
        feedback.textContent = '✓ Passwords match';
    } else {
        confirmPasswordInput.classList.remove('password-match');
        confirmPasswordInput.classList.add('password-mismatch');
        feedback.className = 'password-feedback mismatch';
        feedback.textContent = '✗ Passwords do not match';
    }
}

function validatePasswordStrength() {
    const password = document.getElementById('new_password').value;
    const passwordInput = document.getElementById('new_password');
    
    if (password.length === 0) {
        passwordInput.classList.remove('valid', 'invalid');
        return;
    }
    
    if (password.length >= 6) {
        passwordInput.classList.remove('invalid');
        passwordInput.classList.add('valid');
    } else {
        passwordInput.classList.remove('valid');
        passwordInput.classList.add('invalid');
    }
}

function validateEmail() {
    const email = document.getElementById('email').value;
    const emailInput = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email.length === 0) {
        emailInput.classList.remove('valid', 'invalid');
        return;
    }
    
    if (emailRegex.test(email)) {
        emailInput.classList.remove('invalid');
        emailInput.classList.add('valid');
    } else {
        emailInput.classList.remove('valid');
        emailInput.classList.add('invalid');
    }
}

function validateName() {
    const name = document.getElementById('name').value.trim();
    const nameInput = document.getElementById('name');
    
    if (name.length === 0) {
        nameInput.classList.remove('valid', 'invalid');
        return;
    }
    
    if (name.length >= 2) {
        nameInput.classList.remove('invalid');
        nameInput.classList.add('valid');
    } else {
        nameInput.classList.remove('valid');
        nameInput.classList.add('invalid');
    }
}

// Form submission handlers
function handleProfileFormSubmit(event) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value;
    
    if (name.length < 2) {
        event.preventDefault();
        alert('Please enter a valid name (at least 2 characters)');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        event.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
    
    return true;
}

function handlePasswordFormSubmit(event) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPasswordInput = document.getElementById('current_password');
    
    if (currentPassword.length === 0) {
        event.preventDefault();
        alert('Please enter your current password');
        return false;
    }
    
    // Check if current password validation has passed
    if (!currentPasswordInput.classList.contains('password-match')) {
        event.preventDefault();
        alert('Please enter a valid current password');
        return false;
    }
    
    if (newPassword.length < 6) {
        event.preventDefault();
        alert('New password must be at least 6 characters long');
        return false;
    }
    
    if (newPassword !== confirmPassword) {
        event.preventDefault();
        alert('New passwords do not match');
        return false;
    }
    
    if (currentPassword === newPassword) {
        event.preventDefault();
        alert('New password must be different from current password');
        return false;
    }
    
    return true;
}

// Initialize the form validation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Password matching validation
    const currentPasswordInput = document.getElementById('current_password');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (currentPasswordInput) {
        // Add debounced event listener for current password validation
        let currentPasswordTimeout;
        currentPasswordInput.addEventListener('input', function() {
            clearTimeout(currentPasswordTimeout);
            currentPasswordTimeout = setTimeout(checkCurrentPassword, 500); // 500ms delay
        });
    }
    
    if (newPasswordInput && confirmPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            validatePasswordStrength();
            checkPasswordMatch();
        });
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
    
    // Profile form validation
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    
    if (nameInput) {
        nameInput.addEventListener('input', validateName);
    }
    
    if (emailInput) {
        emailInput.addEventListener('input', validateEmail);
    }
    
    // Form submission handlers
    const profileForm = document.querySelector('form[action=""][method="POST"]:has(input[name="action"][value="update_profile"])');
    const passwordForm = document.getElementById('passwordForm');
    
    if (profileForm) {
        profileForm.addEventListener('submit', handleProfileFormSubmit);
    }
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', handlePasswordFormSubmit);
    }
    
    // Auto-hide success/error messages after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });
});
