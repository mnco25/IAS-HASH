<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Guest users cannot edit their profile
if ($_SESSION['user_role'] === 'guest') {
    header('Location: dashboard.php?error=guest_restricted');
    exit();
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle AJAX request for password verification
if (isset($_POST['ajax_action']) && $_POST['ajax_action'] === 'verify_current_password') {
    header('Content-Type: application/json');

    $current_password = $_POST['current_password'] ?? '';

    if (empty($current_password)) {
        echo json_encode(['valid' => false, 'message' => 'Password is required']);
        exit();
    }

    // Get user's current password hash from database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();

    if ($user_data && password_verify($current_password, $user_data['password'])) {
        echo json_encode(['valid' => true, 'message' => '✓ Current password is correct']);
    } else {
        echo json_encode(['valid' => false, 'message' => '✗ Current password is incorrect']);
    }
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);

        $errors = [];

        // Validation
        if (empty($name)) {
            $errors[] = "Name is required";
        }

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        // Check if email already exists (excluding current user)
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user_id]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email already exists";
            }
        }

        if (empty($errors)) {
            // Update user profile
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            if ($stmt->execute([$name, $email, $user_id])) {
                // Update session variables
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $success_message = 'Profile updated successfully!';
            } else {
                $error_message = "Failed to update profile. Please try again.";
            }
        } else {
            $error_message = implode('<br>', $errors);
        }
    }

    if ($action === 'update_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $errors = [];

        // Validation
        if (empty($current_password)) {
            $errors[] = "Current password is required";
        }

        if (empty($new_password)) {
            $errors[] = "New password is required";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters long";
        }

        if (empty($confirm_password)) {
            $errors[] = "Please confirm your new password";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match";
        }

        // Verify current password
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($current_password, $user['password'])) {
                $errors[] = "Current password is incorrect";
            }
        }

        if (empty($errors)) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            if ($stmt->execute([$hashed_password, $user_id])) {
                $success_message = 'Password updated successfully!';
            } else {
                $error_message = "Failed to update password. Please try again.";
            }
        } else {
            $error_message = implode('<br>', $errors);
        }
    }
}

// Get current user data
$stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: logout.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/edit_profile.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="dashboard.php" class="nav-logo">🔐 Dashboard</a>
            <div class="nav-links">
                <span class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <span class="role-badge role-<?php echo $_SESSION['user_role']; ?>"><?php echo ucfirst($_SESSION['user_role']); ?></span>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-link logout-btn">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="profile-header">
            <h1>Edit Profile</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['from']) && $_GET['from'] === 'dashboard'): ?>
            <div class="alert alert-info">
                You can edit your profile details below. Changes will be reflected on your dashboard.
            </div>
        <?php endif; ?>

        <div class="profile-sections">
            <!-- Profile Information Section -->
            <div class="profile-card">
                <h2>Profile Information</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Account Role:</label>
                        <div class="role-display">
                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                            <small>Role cannot be changed</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>

            <!-- Password Change Section -->
            <div class="profile-card">
                <h2>Change Password</h2>
                <form method="POST" action="" id="passwordForm">
                    <input type="hidden" name="action" value="update_password">

                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                        <div id="currentPasswordFeedback" class="password-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <small>Password must be at least 6 characters long</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <div id="passwordFeedback" class="password-feedback"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/edit_profile.js"></script>
    <script>
        // Handle anchor links for quick editing
        document.addEventListener('DOMContentLoaded', function() {
            // Handle hash navigation for quick edit links
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const element = document.getElementById(hash);
                if (element) {
                    element.focus();
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    </script>
</body>

</html>