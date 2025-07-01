<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';

// Get user role
$userRole = $_SESSION['user_role'] ?? 'user';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="dashboard.php" class="nav-logo">🔐 Dashboard</a>
            <div class="nav-links">
                <span class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <span class="role-badge role-<?php echo $userRole; ?>"><?php echo ucfirst($userRole); ?></span>
                <a href="logout.php" class="nav-link logout-btn">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'guest_restricted'): ?>
            <div class="alert alert-error">
                Guest users cannot edit their profile. Please register for a full account to access this feature.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['welcome']) && $_GET['welcome'] === 'guest'): ?>
            <div class="alert alert-info">
                Welcome, Guest! You have limited access. Consider registering for a full account to unlock all features.
            </div>
        <?php endif; ?>

        <div class="dashboard-card">
            <div class="dashboard-header">
                <h1 class="welcome-title">
                    <?php echo ucfirst($userRole) . ' Dashboard'; ?>
                </h1>
                <?php if ($userRole !== 'guest'): ?>
                    <a href="edit_profile.php" class="edit-profile-btn">✏️ Edit Profile</a>
                <?php endif; ?>
            </div>

            <div class="user-details">
                <h3 style="margin-bottom: 20px; color: #333;">Your Account Information</h3>

                <div class="detail-item">
                    <span class="detail-label">👤 Full Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">📧 Email Address:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">🆔 User ID:</span>
                    <span class="detail-value">#<?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">👑 Role:</span>
                    <span class="detail-value">
                        <span class="role-badge role-<?php echo $userRole; ?>"><?php echo ucfirst($userRole); ?></span>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">🔐 Password:</span>
                    <span class="detail-value">••••••••</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">🕒 Session Started:</span>
                    <span class="detail-value"><?php echo date('H:i:s'); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>