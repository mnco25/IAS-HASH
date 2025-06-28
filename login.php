<?php
session_start();

$error_message = '';
$email_value = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config.php';

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $email_value = $email; // Store for form repopulation

    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields';
    } else {
        // Check user credentials
        $stmt = $pdo->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $user['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">🔐 Auth System</a>
            <div class="nav-links">
                <a href="index.php" class="nav-link">Home</a>
                <a href="login.php" class="nav-link active">Login</a>
                <a href="register.php" class="nav-link">Register</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Welcome Back</h2>
        <p class="welcome-text">Please sign in to your account</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_value); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>

</html>