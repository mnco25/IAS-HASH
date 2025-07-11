<div align="center">

# 🔐 Secure Authentication System with PHP & MySQL

A robust and modern user authentication system with role-based access control.

---

</div>

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black) ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

</div>

## 📖 Project Overview

This project provides a comprehensive and secure foundation for user authentication in web applications. It features a clean, responsive UI and a secure backend built with PHP and MySQL, making it an ideal starting point for projects that require user management with different access levels.

## ✨ Key Features

- 🔐 **Secure Registration & Login**: Industry-standard password hashing with `password_hash()` and `password_verify()`.
- 👥 **Role-Based Access Control**: Pre-configured Admin, User, and Guest roles with distinct permissions.
- 🛡️ **Session Management**: Secure session handling to protect routes and user data.
- ✅ **Input Validation**: Robust server-side validation and `htmlspecialchars()` to prevent XSS attacks.
- 🔒 **Database Security**: PDO with prepared statements to prevent SQL injection.
- 📱 **Responsive UI**: A modern and intuitive interface that works on any device.
- 👁️ **Guest Access**: Allows users to preview the application with limited privileges.
- ✏️ **Profile Management**: Complete user profile editing with real-time password validation and AJAX verification.
- 📚 **Comprehensive Documentation**: Detailed tutorial and setup guides for beginners.

## 🛠️ Technology Stack

| Technology             | Description                                                   |
| :--------------------- | :------------------------------------------------------------ |
| **PHP**          | Core backend logic, session management, and password hashing. |
| **MySQL**        | Database for storing user and application data.               |
| **HTML5 & CSS3** | For structuring and styling the user interface.               |
| **JavaScript**   | Client-side interactivity and form validation.                |

## 🚀 Getting Started

### Prerequisites

- A local server environment like [Laragon](https://laragon.org/), WAMP, or XAMPP.
- A web browser.
- A database management tool (phpMyAdmin, HeidiSQL, etc.).

### Using Laragon (Recommended)

1. **Install Laragon**: Download and install the **Laragon - Full** edition from the [official website](https://laragon.org/download/).
2. **Start Services**: Launch Laragon and click `Start All`.
3. **Place Project**: Clone or download this project into Laragon's `www` directory (e.g., `C:\laragon\www`).
4. **Database Setup**:
   * Click the `Database` button in Laragon to open HeidiSQL.
   * Create a new database named `user_auth_system`.
   * Import the `db.sql` file into the new database.
5. **Access Application**: Visit `http://hash.test` in your browser.

### Using WAMP/XAMPP

1. **Start Services**: Start your Apache and MySQL services.
2. **Place Project**: Move the project files to your server's root directory (e.g., `C:\wamp64\www\HASH` or `C:\xampp\htdocs\HASH`).
3. **Database Setup**:
   * Open phpMyAdmin (e.g., `http://localhost/phpmyadmin`).
   * Create a new database named `user_auth_system`.
   * Import the `db.sql` file.
4. **Access Application**: Navigate to `http://localhost/HASH/`.

## 📁 Project Structure

```
/HASH
├── 📄 index.php          # Landing page with login/register options
├── 📄 register.php       # User registration form and processing
├── 📄 login.php          # User login form and authentication
├── 📄 dashboard.php      # Protected dashboard with user information
├── 📄 edit_profile.php   # User profile editing with password change
├── 📄 logout.php         # Session termination and logout handling
├── 📄 config.php         # Database configuration and connection
├── 🗃️ db.sql             # Database schema and sample data
├── 📝 README.md          # Project documentation
├── 📚 TUTORIAL.md        # Comprehensive beginner's guide
├── 📁 css/               # Stylesheets directory
│   ├── 📜 common.css     # Global styles and navigation
│   ├── 📜 dashboard.css  # Dashboard-specific styles
│   ├── 📜 edit_profile.css # Profile editing page styles
│   ├── 📜 index.css      # Landing page styles
│   ├── 📜 login.css      # Login page styles
│   └── 📜 register.css   # Registration page styles
└── 📁 js/                # JavaScript files directory
    ├── 📜 edit_profile.js # Profile editing and password validation
    └── 📜 register.js    # Client-side registration validation
```

## 👥 User Roles

| Role                | Permissions                                                              |
| :------------------ | :----------------------------------------------------------------------- |
| 👑**Admin**   | Full system access and can view all dashboard content.                   |
| 👤**User**    | Standard access to a personal dashboard and non-administrative features. |
| 👁️**Guest** | Limited, read-only access to basic dashboard features.                   |

## 🔐 Security Best Practices

- **Password Hashing**: Passwords are never stored in plaintext.
- **SQL Injection Prevention**: Database queries are executed with prepared statements.
- **XSS Prevention**: Output is sanitized with `htmlspecialchars()`.
- **Secure Sessions**: Sessions are properly managed and destroyed on logout.
- **Server-Side Validation**: All user-submitted data is validated on the server.

## 🔧 Customization

- **Adding Roles**: Update the `ENUM` and `SET` values in the database schema and modify the role validation in `register.php`.
- **Profile Features**: The `edit_profile.php` system supports:
  - Real-time password verification via AJAX
  - Separate forms for profile information and password changes
  - Role-based restrictions (guests cannot edit profiles)
  - Email uniqueness validation
- **Styling**: CSS files are organized in the `css/` directory:
  - `common.css` - Global styles, navigation, and shared components
  - `edit_profile.css` - Profile editing interface and form styling
  - Page-specific CSS files for targeted styling modifications
  - Responsive design breakpoints for mobile compatibility
- **JavaScript Enhancement**: Client-side scripts are located in the `js/` directory:
  - `edit_profile.js` - Password validation, AJAX verification, and form interactions
  - `register.js` - Registration form validation and user feedback
  - Easy maintenance and updates with modular structure

---

<div align="center">

*A simple, secure, and scalable authentication system.*

</div>
