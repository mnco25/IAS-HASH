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
        }

        .header {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            color: #333;
            font-size: 20px;
            font-weight: normal;
        }

        .user-info {
            color: #333;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .welcome-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 30px;
        }

        .user-details {
            background: #f8f9fa;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #495057;
        }

        .detail-value {
            color: #6c757d;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .role-admin {
            background: #dc3545;
            color: white;
        }

        .role-user {
            background: #007bff;
            color: white;
        }

        .role-guest {
            background: #6c757d;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="nav">
            <div class="logo">🔐 Dashboard</div>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <span class="role-badge role-<?php echo $userRole; ?>"><?php echo ucfirst($userRole); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="dashboard-card">
            <h1 class="welcome-title">
                <?php echo ucfirst($userRole) . ' Dashboard'; ?>
            </h1>

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
                    <span class="detail-label">🕒 Session Started:</span>
                    <span class="detail-value"><?php echo date('H:i:s'); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>