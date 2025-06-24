// Registration form JavaScript functions

function toggleGuestMode() {
    const guestRadio = document.querySelector('input[name="role"][value="guest"]');
    const guestFields = document.querySelectorAll('.guest-field');
    const guestMessage = document.getElementById('guestMessage');
    const submitButton = document.getElementById('submitButton');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    if (guestRadio.checked) {
        // Hide form fields and show guest message
        guestFields.forEach(field => {
            field.style.display = 'none';
        });
        guestMessage.style.display = 'block';
        submitButton.textContent = 'Access Now';

        // Remove required attributes for guest access
        nameInput.removeAttribute('required');
        emailInput.removeAttribute('required');
        passwordInput.removeAttribute('required');
        confirmPasswordInput.removeAttribute('required');
    } else {
        // Show form fields and hide guest message
        guestFields.forEach(field => {
            field.style.display = 'block';
        });
        guestMessage.style.display = 'none';
        submitButton.textContent = 'Create Account';

        // Add required attributes back
        nameInput.setAttribute('required', 'required');
        emailInput.setAttribute('required', 'required');
        passwordInput.setAttribute('required', 'required');
        confirmPasswordInput.setAttribute('required', 'required');
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const feedback = document.getElementById('passwordFeedback');

    if (confirmPassword === '') {
        confirmPasswordInput.classList.remove('password-match', 'password-mismatch');
        feedback.className = 'password-feedback';
        feedback.textContent = '';
        return;
    }

    if (password === confirmPassword) {
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

// Initialize the form state on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleGuestMode();

    // Add event listeners for password matching
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
});
