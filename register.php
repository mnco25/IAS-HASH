<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config.php';

    $role = isset($_POST['role']) ? $_POST['role'] : 'user';

    // Handle guest access
    if ($role === 'guest') {
        session_start();
        $_SESSION['user_id'] = 0; // Temporary guest ID
        $_SESSION['user_name'] = 'Guest User';
        $_SESSION['user_email'] = 'guest@system.local';
        $_SESSION['user_role'] = 'guest';

        // Redirect to dashboard with welcome message
        header('Location: dashboard.php?welcome=guest');
        exit();
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $errors = [];

    // Validation for non-guest users
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your password";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Validate role
    if (!in_array($role, ['admin', 'user', 'guest'])) {
        $errors[] = "Invalid account type selected";
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already exists";
        }
    }

    if (empty($errors)) {
        // Hash password and insert user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashedPassword, $role])) {
            $successMessage = 'Registration successful! You can now <a href="login.php">login</a>.';
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            border: 1px solid #e0e0e0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .password-match {
            border-color: #28a745 !important;
        }

        .password-mismatch {
            border-color: #dc3545 !important;
        }

        .password-feedback {
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .password-feedback.match {
            color: #28a745;
            display: block;
        }

        .password-feedback.mismatch {
            color: #dc3545;
            display: block;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: normal;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .radio-group {
            margin-top: 8px;
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .radio-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 15px 10px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
            min-height: 80px;
        }

        .radio-label:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
        }

        .radio-label input[type="radio"] {
            display: none;
        }

        .radio-text {
            font-weight: normal;
            color: #333;
            font-size: 14px;
            margin-top: 5px;
        }

        .radio-label input[type="radio"]:checked+.radio-text {
            font-weight: bold;
            color: #007bff;
        }

        .radio-label:has(input[type="radio"]:checked) {
            background-color: #e3f2fd;
            border-color: #007bff;
        }

        .radio-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .guest-hidden {
            display: none;
        }

        .guest-message {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
            display: none;
        }

        .navbar {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .nav-logo {
            color: #333;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            color: #666;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: #f8f9fa;
            color: #333;
        }

        .nav-link.active {
            background: #007bff;
            color: white;
        }

        body {
            padding-top: 70px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">🔐 Auth System</a>
            <div class="nav-links">
                <a href="index.php" class="nav-link">Home</a>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="nav-link active">Register</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Create Account</h2>

        <?php
        // Display success message
        if (isset($successMessage)) {
            echo '<div class="alert alert-success">' . $successMessage . '</div>';
        }

        // Display errors
        if (isset($errors) && !empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert alert-error">' . htmlspecialchars($error) . '</div>';
            }
        }
        ?>

        <form method="POST" action="" id="registrationForm">
            <div class="guest-message" id="guestMessage">
                <strong>👁️ Guest Access</strong><br>
                Click "Access Now" to enter as a guest with limited access. No registration required!
            </div>

            <div class="form-group guest-field" id="nameField">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>

            <div class="form-group guest-field" id="emailField">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group guest-field" id="passwordField">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <small style="color: #666; font-size: 12px;">Password must be at least 6 characters long</small>
            </div>

            <div class="form-group guest-field" id="confirmPasswordField">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword">
                <div id="passwordFeedback" class="password-feedback"></div>
            </div>
            <div class="form-group">
                <label>Account Type:</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="role" value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'checked' : ''; ?> onchange="toggleGuestMode()">
                        <div class="radio-icon">👑</div>
                        <span class="radio-text">Admin</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="role" value="user" <?php echo (!isset($_POST['role']) || $_POST['role'] == 'user') ? 'checked' : ''; ?> required onchange="toggleGuestMode()">
                        <div class="radio-icon">👤</div>
                        <span class="radio-text">User</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="role" value="guest" <?php echo (isset($_POST['role']) && $_POST['role'] == 'guest') ? 'checked' : ''; ?> onchange="toggleGuestMode()">
                        <div class="radio-icon">👁️</div>
                        <span class="radio-text">Guest</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn" id="submitButton">Create Account</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
    <script>
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

            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        });
    </script>
</body>

</html>