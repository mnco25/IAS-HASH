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
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/register.css">
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
                <div class="radio-group"> <label class="radio-label">
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
    <script src="js/register.js"></script>
</body>

</html>