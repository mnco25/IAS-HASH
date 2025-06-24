<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Authentication System</title>
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
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            border: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 32px;
        }

        .description {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
            line-height: 1.6;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            font-weight: normal;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .features {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .feature-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .feature-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .feature-text {
            font-size: 14px;
            color: #666;
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
                <a href="index.php" class="nav-link active">Home</a>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="nav-link">Register</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="logo">🔐</div>
        <h1>Authentication System</h1>
        <p class="description">
            Secure PHP & MySQL authentication with modern security practices.
        </p>

        <div class="features">
            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <div class="feature-text">Password Hashing</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🛡️</div>
                    <div class="feature-text">Session Security</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✅</div>
                    <div class="feature-text">Input Validation</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>