# 🎓 Complete Beginner's Guide to PHP Authentication System

## 📚 Table of Contents

1. [Introduction](#introduction)
2. [PHP & Database Basics for Beginners](#php--database-basics-for-beginners)
3. [System Overview](#system-overview)
4. [Database Structure Explained](#database-structure-explained)
5. [Understanding PHP Syntax](#understanding-php-syntax)
6. [File-by-File Deep Dive](#file-by-file-deep-dive)
7. [Security Features Explained](#security-features-explained)
8. [User Flow Walkthrough](#user-flow-walkthrough)
9. [Configuration Guide](#configuration-guide)
10. [Testing the System](#testing-the-system)
11. [Common Issues & Solutions](#common-issues--solutions)

## 🌟 Introduction

This tutorial will help you understand how a modern PHP authentication system works. We'll break down every component, explain how the database interacts with each file, and show you the security measures that protect user data.

**What you'll learn:**

- How PHP sessions work
- Database interactions with PDO
- Password security and hashing
- Role-based access control
- Form validation and security

**Prerequisites:** Basic understanding of HTML and willingness to learn PHP concepts.

## 🔤 PHP & Database Basics for Beginners

### What is PHP?

PHP is a server-side programming language that runs on the web server (not in your browser). It's perfect for creating dynamic websites that interact with databases.

**Key PHP Concepts:**

```php
<?php
// This tells the server "start interpreting PHP code"
echo "Hello World!";  // 'echo' displays text on the webpage
?>
```

### PHP Variables - Storing Information

Variables in PHP start with a dollar sign `$`:

```php
<?php
$name = "John";           // String (text)
$age = 25;                // Integer (number)
$isLoggedIn = true;       // Boolean (true/false)
$price = 19.99;           // Float (decimal number)

// Display variables
echo "Hello " . $name;    // Combines text and variable
echo "Age: $age";         // Variables work inside double quotes
?>
```

### Understanding Arrays - Lists of Data

Arrays store multiple values in one variable:

```php
<?php
// Simple array (like a list)
$fruits = ["apple", "banana", "orange"];
echo $fruits[0];  // Displays "apple" (arrays start at 0)

// Associative array (like a dictionary)
$user = [
    "name" => "John Doe",
    "email" => "john@example.com",
    "age" => 25
];
echo $user["name"];  // Displays "John Doe"
?>
```

### What is a Database?

A database is like a digital filing cabinet that stores information in organized tables. Think of it like an Excel spreadsheet with rows and columns.

**Basic Database Concepts:**

- **Table**: Like a spreadsheet (e.g., "users" table)
- **Row**: One record (e.g., one user's information)
- **Column**: One piece of information (e.g., "email" column)
- **Primary Key**: Unique identifier for each row (like an ID number)

### SQL - Talking to the Database

SQL (Structured Query Language) is how we communicate with databases:

```sql
-- Create a new user (INSERT)
INSERT INTO users (name, email) VALUES ('John', 'john@email.com');

-- Find a user (SELECT)
SELECT * FROM users WHERE email = 'john@email.com';

-- Update a user (UPDATE)
UPDATE users SET name = 'John Smith' WHERE id = 1;

-- Delete a user (DELETE)
DELETE FROM users WHERE id = 1;
```

### PHP and MySQL Working Together

Here's how PHP talks to a MySQL database:

```php
<?php
// 1. Connect to database
$pdo = new PDO("mysql:host=localhost;dbname=myapp", $username, $password);

// 2. Prepare a safe query (prevents hacking)
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");

// 3. Execute the query with data
$stmt->execute([$userEmail]);

// 4. Get the results
$user = $stmt->fetch();  // Gets one row
// OR
$allUsers = $stmt->fetchAll();  // Gets all rows
?>
```

### Understanding Sessions - Remembering Users

Sessions allow the website to remember information about a user as they navigate between pages:

```php
<?php
session_start();  // Start session management

// Store information
$_SESSION['user_name'] = 'John';
$_SESSION['user_id'] = 123;
$_SESSION['is_admin'] = true;

// Retrieve information (on any page)
echo "Welcome " . $_SESSION['user_name'];

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    echo "User is logged in";
} else {
    echo "User is not logged in";
}

// Remove session data (logout)
session_destroy();
?>
```

### Form Handling - Getting User Input

When a user submits a form, PHP can process that data:

**HTML Form:**
```html
<form method="POST" action="process.php">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>
```

**PHP Processing:**
```php
<?php
// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];  // Gets the username field
    $password = $_POST['password'];  // Gets the password field
    
    // Do something with the data
    echo "Username: " . $username;
}
?>
```

### Include Files - Reusing Code

Instead of repeating code, we can include other PHP files:

```php
<?php
// Include database connection
require_once 'config.php';  // Includes config.php file

// Include functions
include 'functions.php';

// The difference:
// require_once: File MUST exist, include only once
// include: File optional, continue if missing
?>
```

### Error Handling - Dealing with Problems

```php
<?php
try {
    // Try to connect to database
    $pdo = new PDO("mysql:host=localhost;dbname=myapp", $user, $pass);
    echo "Connected successfully";
} catch (PDOException $e) {
    // If connection fails, show error
    echo "Connection failed: " . $e->getMessage();
}
?>
```

### Security Basics - Protecting Your Application

**1. Never Trust User Input:**
```php
<?php
// WRONG - Dangerous!
$query = "SELECT * FROM users WHERE email = '$email'";

// RIGHT - Safe with prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
?>
```

**2. Escape Output:**
```php
<?php
// WRONG - Can be hacked with XSS
echo "Hello " . $_POST['name'];

// RIGHT - Escaped output
echo "Hello " . htmlspecialchars($_POST['name']);
?>
```

**3. Hash Passwords:**
```php
<?php
// WRONG - Never store plain passwords!
$password = $_POST['password'];
// Store $password in database

// RIGHT - Always hash passwords
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
// Store $hashedPassword in database

// To verify later:
if (password_verify($_POST['password'], $hashedPassword)) {
    echo "Password is correct!";
}
?>
```

## 🏗️ System Overview

Our authentication system consists of these main components:

```
User Journey: Registration → Login → Dashboard → Logout
Database: MySQL with a single 'users' table
Security: Password hashing, session management, input validation
Roles: Admin, User, Guest (with different permissions)
```

## 🗄️ Database Structure Explained

### Understanding Our Database Setup

Before diving into the code, let's understand how our database is organized. Think of a database like a digital filing cabinet, and our `users` table is like one drawer in that cabinet.

### The `users` Table - Step by Step

Our authentication system uses a single MySQL table called `users`. Let's break down what each part means:

```sql
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,           
  `name` varchar(100) NOT NULL,               
  `email` varchar(150) NOT NULL,              
  `password` varchar(255) NOT NULL,           
  `role` enum('admin','user','guest') DEFAULT 'user',  
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,  
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)                
)
```

Let's explain each line in simple terms:

#### Column Definitions

**1. `id` Column:**
```sql
`id` int NOT NULL AUTO_INCREMENT
```
- `id`: The name of this column
- `int`: Stores whole numbers (1, 2, 3, 4...)
- `NOT NULL`: This field cannot be empty
- `AUTO_INCREMENT`: MySQL automatically assigns the next number (1, 2, 3...)

**Think of it like:** Each user gets a unique ID number, like a student ID card.

**2. `name` Column:**
```sql
`name` varchar(100) NOT NULL
```
- `name`: Stores the user's full name
- `varchar(100)`: Can store text up to 100 characters
- `NOT NULL`: Name is required (can't be empty)

**Real example:** "John Smith", "Mary Johnson"

**3. `email` Column:**
```sql
`email` varchar(150) NOT NULL
```
- `email`: Stores the user's email address
- `varchar(150)`: Can store text up to 150 characters
- `NOT NULL`: Email is required

**Real example:** "john@example.com", "mary.johnson@email.com"

**4. `password` Column:**
```sql
`password` varchar(255) NOT NULL
```
- `password`: Stores the encrypted password
- `varchar(255)`: Needs 255 characters because encrypted passwords are very long
- `NOT NULL`: Password is required

**Important:** We NEVER store actual passwords like "123456". Instead, we store something like: `$2y$10$eImiTXuWVxfM37uY4JANjQ==`

**5. `role` Column:**
```sql
`role` enum('admin','user','guest') DEFAULT 'user'
```
- `role`: Determines what the user can do
- `enum('admin','user','guest')`: Only allows these three values
- `DEFAULT 'user'`: If no role is specified, make them a 'user'

**Real examples:**
- `admin`: Can manage the system
- `user`: Regular user with standard access
- `guest`: Limited access, temporary user

**6. `created_at` Column:**
```sql
`created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
```
- `created_at`: Records when the account was created
- `timestamp`: Stores date and time
- `DEFAULT CURRENT_TIMESTAMP`: Automatically sets to current date/time

**Real example:** "2025-06-30 14:30:25" (June 30, 2025 at 2:30 PM)

**7. `updated_at` Column:**
```sql
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```
- `updated_at`: Records when the account was last modified
- `ON UPDATE CURRENT_TIMESTAMP`: Automatically updates whenever the row changes

#### Database Constraints Explained

**Primary Key:**
```sql
PRIMARY KEY (`id`)
```
This means the `id` column is the main identifier. No two users can have the same ID.

**Unique Key:**
```sql
UNIQUE KEY `email` (`email`)
```
This ensures no two users can have the same email address. If someone tries to register with an existing email, the database will reject it.

### Sample Data Examples

Here's what actual data looks like in our table:

| id | name | email | password | role | created_at | updated_at |
|--|--|--|--|--|--|--|
| 1 | John Smith | john@email.com | $2y$10$eImiTXuW... | user | 2025-06-30 10:00:00 | 2025-06-30 10:00:00 |
| 2 | Admin User | admin@site.com | $2y$10$zXcVBnM2... | admin | 2025-06-30 11:15:30 | 2025-06-30 11:15:30 |
| 3 | Guest User | guest@temp.com | $2y$10$aBcDeFgH... | guest | 2025-06-30 12:45:15 | 2025-06-30 12:45:15 |

### Why This Structure Works

**1. Security:**
- Passwords are hashed (encrypted) - hackers can't read them
- Email uniqueness prevents duplicate accounts
- Role system controls access levels

**2. Efficiency:**
- ID numbers are fast for database lookups
- Fixed-size columns optimize storage
- Timestamps help with debugging and auditing

**3. Scalability:**
- Simple structure can handle thousands of users
- Easy to add more columns later if needed
- Standard MySQL features ensure compatibility

### Database Operations We'll Use

Throughout our application, we'll perform these basic operations:

**1. CREATE (Insert new user):**
```sql
INSERT INTO users (name, email, password, role) 
VALUES ('John Doe', 'john@email.com', '$2y$10$...', 'user');
```

**2. READ (Find a user):**
```sql
SELECT id, name, email, role FROM users WHERE email = 'john@email.com';
```

**3. UPDATE (Change user info):**
```sql
UPDATE users SET name = 'John Smith' WHERE id = 1;
```

**4. DELETE (Remove user):**
```sql
DELETE FROM users WHERE id = 1;
```

**Our application mainly uses CREATE and READ operations for authentication.**

## � Understanding PHP Syntax

Before we dive into our authentication files, let's understand the PHP syntax patterns you'll see throughout the code.

### PHP File Structure

Every PHP file that contains PHP code starts and ends with PHP tags:

```php
<?php
// PHP code goes here
?>
```

**Important Notes:**
- If a file is pure PHP (no HTML), you can omit the closing `?>`
- PHP code runs on the server before the page is sent to the browser
- You can mix PHP and HTML in the same file

### Common PHP Patterns in Our Project

#### 1. Including Files

```php
<?php
require_once 'config.php';
// This includes the config.php file exactly once
// 'require_once' means: "I NEED this file, and include it only once"
?>
```

**Variations you'll see:**
- `require`: File is mandatory, stop if not found
- `include`: File is optional, continue if not found  
- `require_once`/`include_once`: Include only once, prevent duplicates

#### 2. Conditional Statements

```php
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // This runs only when a form is submitted
    echo "Form was submitted!";
} else {
    // This runs when page is just loaded normally
    echo "Just viewing the page";
}
?>
```

**Breaking it down:**
- `$_SERVER['REQUEST_METHOD']`: How the page was accessed (GET or POST)
- `== 'POST'`: Comparing if the method equals "POST"
- GET = normal page load, POST = form submission

#### 3. Superglobal Variables

PHP has special built-in variables available everywhere:

```php
<?php
// Form data from user
$username = $_POST['username'];    // Data from form submission
$page = $_GET['page'];             // Data from URL (?page=home)

// Session data (remembering user between pages)
$userId = $_SESSION['user_id'];    // Data stored in session

// Server information
$method = $_SERVER['REQUEST_METHOD'];  // How page was accessed
?>
```

#### 4. Database Operations Pattern

Every database operation in our project follows this pattern:

```php
<?php
// 1. Include database connection
require_once 'config.php';

// 2. Prepare a safe SQL query
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");

// 3. Execute with actual data
$stmt->execute([$email]);

// 4. Get results
$user = $stmt->fetch();        // Get one row
// OR
$allUsers = $stmt->fetchAll(); // Get all rows
?>
```

**Why this pattern?**
- **Prepared statements** prevent SQL injection attacks
- The `?` is a placeholder that gets safely replaced with actual data
- `$pdo` comes from our config.php file

#### 5. Form Processing Pattern

Most of our PHP files follow this structure:

```php
<?php
// Step 1: Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Step 2: Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Step 3: Validate data
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    
    // Step 4: Process if no errors
    if (empty($errors)) {
        // Do something (login, register, etc.)
    }
}
?>

<!-- HTML form goes here -->
<form method="POST">
    <input type="email" name="email">
    <input type="password" name="password">
    <button type="submit">Submit</button>
</form>
```

#### 6. Session Management Pattern

```php
<?php
session_start();  // Always call this first

// Store data in session
$_SESSION['user_id'] = 123;
$_SESSION['user_name'] = 'John';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is NOT logged in
    header('Location: login.php');  // Redirect to login
    exit();  // Stop running this page
}

// Get session data
$userName = $_SESSION['user_name'];

// Remove session data
unset($_SESSION['user_id']);    // Remove specific item
session_destroy();              // Remove everything
?>
```

#### 7. Error Handling Patterns

```php
<?php
$errors = [];  // Array to collect error messages

// Validation
if (empty($name)) {
    $errors[] = "Name is required";
}

if (empty($email)) {
    $errors[] = "Email is required";
}

// Check if we have any errors
if (!empty($errors)) {
    // Display errors to user
    foreach ($errors as $error) {
        echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
    }
}
?>
```

#### 8. Safe Output Pattern

```php
<?php
// WRONG - Dangerous! Could allow hackers to inject code
echo $_POST['username'];

// RIGHT - Safe output that prevents XSS attacks
echo htmlspecialchars($_POST['username']);

// In our code you'll see:
echo htmlspecialchars($_SESSION['user_name']);
?>
```

### PHP Functions You'll See

#### String Functions
```php
<?php
$email = "  john@example.com  ";
$cleanEmail = trim($email);           // Removes spaces: "john@example.com"
$length = strlen($password);          // Gets length of password
$upperName = ucfirst($role);          // Capitalizes first letter: "Admin"
?>
```

#### Array Functions
```php
<?php
$roles = ['admin', 'user', 'guest'];
$isValid = in_array($userRole, $roles);  // Check if role exists in array
$isEmpty = empty($errors);               // Check if array is empty
$count = count($users);                  // Count items in array
?>
```

#### Database Functions (PDO)
```php
<?php
// Prepare query
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");

// Execute with data
$stmt->execute([$email]);

// Get results
$user = $stmt->fetch();               // One row as associative array
$allUsers = $stmt->fetchAll();        // All rows as array of arrays
$rowCount = $stmt->rowCount();        // Number of affected rows
?>
```

#### Security Functions
```php
<?php
// Password hashing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Password verification
$isCorrect = password_verify($inputPassword, $hashedPassword);

// Safe output
$safeText = htmlspecialchars($userInput);

// Email validation
$isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
?>
```

### Control Structures You'll See

#### If/Else Statements
```php
<?php
if ($user && password_verify($password, $user['password'])) {
    // Login successful
    $_SESSION['user_id'] = $user['id'];
} else {
    // Login failed
    $error = 'Invalid credentials';
}
?>
```

#### Loops (for displaying errors)
```php
<?php
foreach ($errors as $error) {
    echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
}
?>
```

#### Switch Statement (for roles)
```php
<?php
switch ($userRole) {
    case 'admin':
        echo "Welcome, Administrator!";
        break;
    case 'user':
        echo "Welcome, User!";
        break;
    case 'guest':
        echo "Welcome, Guest!";
        break;
    default:
        echo "Unknown role";
}
?>
```

Now you're ready to understand the PHP code in our authentication system! Each pattern above will appear multiple times throughout our files.

## 📁 File-by-File Deep Dive

Now that you understand PHP basics, let's examine each file in our authentication system. We'll go through them in the order a user would typically interact with them.

### 1. `config.php` - Database Connection Hub

**Purpose:** This is the foundation file that connects our application to the MySQL database.

#### Complete Code Breakdown:

```php
<?php
// Database configuration for WAMP
$host = 'localhost';        
$dbname = 'user_auth_system';  
$username = 'root';         
$password = '';             

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

#### Line-by-Line Explanation:

**Lines 3-6: Database Configuration**
```php
$host = 'localhost';        // Where is the database? (usually localhost for local development)
$dbname = 'user_auth_system';  // What database do we want to connect to?
$username = 'root';         // Database username (root is default for local)
$password = '';             // Database password (empty for local development)
```

**Think of this like:** You're telling PHP where to find your database, like giving someone your address.

**Lines 8-11: Creating the Connection**
```php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
```

**Breaking it down:**
- `new PDO(...)`: Creates a new database connection object
- `PDO`: PHP Data Objects - a secure way to talk to databases
- `"mysql:host=$host;dbname=$dbname"`: Connection string (like a phone number for the database)
- `setAttribute(...)`: Sets how errors are handled
- `try/catch`: If connection fails, show error message instead of crashing

**What `$pdo` becomes:** A powerful object that lets us safely query the database.

#### How Other Files Use This:

Every other PHP file includes this file with:
```php
require_once 'config.php';
```

After including, they can use `$pdo` to talk to the database:
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
```

---

### 2. `index.php` - Welcome Landing Page

**Purpose:** The homepage that welcomes visitors and provides navigation.

#### Complete Code Analysis:

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Authentication System</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
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
    <!-- Rest of HTML content -->
</body>
</html>
```

#### Key Points for Beginners:

**No PHP Logic:** This file is pure HTML because it doesn't need to process any data or interact with the database.

**Navigation Structure:**
- **Home**: Current page (marked with 'active' class)
- **Login**: Takes users to login.php
- **Register**: Takes users to register.php

**CSS Files:**
- `common.css`: Styles shared across all pages (navigation, buttons, etc.)
- `index.css`: Styles specific to the homepage

**User Journey:** This is typically the first page visitors see. From here, they can choose to login (existing users) or register (new users).

---

### 3. `register.php` - User Registration System

**Purpose:** Handles new user registration with three account types: Admin, User, and Guest.

This file is more complex because it handles both displaying the form AND processing the submitted data.

#### PHP Logic Section (Top of File):

```php
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
```

#### Detailed Breakdown:

**Step 1: Check if Form was Submitted**
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
```
This only runs when the form is submitted, not when the page is first loaded.

**Step 2: Get Database Connection**
```php
require_once 'config.php';
```
Includes our database connection so we can interact with the `users` table.

**Step 3: Handle Guest Access (Special Case)**
```php
if ($role === 'guest') {
    session_start();
    $_SESSION['user_id'] = 0;
    $_SESSION['user_name'] = 'Guest User';
    $_SESSION['user_email'] = 'guest@system.local';
    $_SESSION['user_role'] = 'guest';
    header('Location: dashboard.php?welcome=guest');
    exit();
}
```

**Why Guest is Special:**
- Guests don't need to provide real information
- No database record is created
- Temporary session data is created instead
- Immediately redirected to dashboard

**Step 4: Get and Clean Form Data**
```php
$name = trim($_POST['name']);           // Remove extra spaces
$email = trim($_POST['email']);         // Remove extra spaces  
$password = $_POST['password'];         // Keep as-is for hashing
$confirmPassword = $_POST['confirmPassword'];
```

**Step 5: Validation**
```php
$errors = [];  // Array to collect all error messages

if (empty($name)) {
    $errors[] = "Name is required";
}
```

**The validation checks:**
- Name cannot be empty
- Email cannot be empty and must be valid format
- Password cannot be empty and must be at least 6 characters
- Confirm password must match original password
- Role must be one of the allowed values

**Step 6: Check for Duplicate Email**
```php
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    $errors[] = "Email already exists";
}
```

**What this does:**
- Searches database for existing user with same email
- `rowCount() > 0` means email is already taken
- Prevents duplicate accounts

**Step 7: Create New User (if no errors)**
```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$name, $email, $hashedPassword, $role])) {
    $successMessage = 'Registration successful!';
}
```

**Security Note:** `password_hash()` converts "123456" into something like "$2y$10$eImiTXuW..." which is impossible to reverse.

#### HTML Form Section:

The bottom half of the file contains the HTML form that displays to users. Key features:

**Dynamic Error Display:**
```php
<?php
if (isset($errors) && !empty($errors)) {
    foreach ($errors as $error) {
        echo '<div class="alert alert-error">' . htmlspecialchars($error) . '</div>';
    }
}
?>
```

**Form Field Persistence:**
```php
<input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
```
This keeps the user's input if there were validation errors.

**Role Selection:**
```php
<input type="radio" name="role" value="admin">
<input type="radio" name="role" value="user" checked>
<input type="radio" name="role" value="guest">
```
Three radio buttons for account types, with 'user' selected by default.

---

### 4. `login.php` - User Authentication

**Purpose:** Verifies user credentials and creates secure sessions.

#### PHP Logic Section:

```php
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
```

#### Step-by-Step Authentication Process:

**Step 1: Start Session Management**
```php
session_start();
```
Must be called before using `$_SESSION` variables.

**Step 2: Initialize Variables**
```php
$error_message = '';  // For displaying login errors
$email_value = '';    // For keeping email in form if login fails
```

**Step 3: Process Form Submission**
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
```
Only runs when login form is submitted.

**Step 4: Basic Validation**
```php
if (empty($email) || empty($password)) {
    $error_message = 'Please fill in all fields';
}
```
Ensures both email and password were provided.

**Step 5: Database Lookup**
```php
$stmt = $pdo->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
```

**What this does:**
- Searches database for user with provided email
- Returns user record as associative array
- `$user['id']`, `$user['name']`, etc.

**Step 6: Password Verification**
```php
if ($user && password_verify($password, $user['password'])) {
```

**Breaking it down:**
- `$user`: Checks if user was found in database
- `password_verify()`: Safely compares submitted password with stored hash
- Both conditions must be true for login success

**Step 7: Create Session (Login Success)**
```php
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = $user['role'];
header('Location: dashboard.php');
exit();
```

**What happens:**
- User information is stored in session
- User is redirected to dashboard
- `exit()` stops further code execution

**Step 8: Handle Login Failure**
```php
} else {
    $error_message = 'Invalid email or password';
}
```

**Security Note:** We don't specify whether email or password was wrong - this prevents attackers from knowing if an email exists in our system.

#### HTML Form Features:

**Error Display:**
```php
<?php if (!empty($error_message)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>
```

**Email Persistence:**
```php
<input type="email" name="email" value="<?php echo htmlspecialchars($email_value); ?>">
```
Keeps the email field filled if login fails.

---

### 5. `dashboard.php` - Protected User Area

**Purpose:** Displays user information and provides role-based content. This page is protected - only logged-in users can access it.

#### PHP Logic Section:

```php
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
```

#### Access Control Breakdown:

**Step 1: Start Session**
```php
session_start();
```
Required to read session data.

**Step 2: Check Authentication**
```php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
```

**What this does:**
- Checks if `user_id` exists in session
- If not logged in, redirects to login page
- `exit()` prevents unauthorized users from seeing any content

**Step 3: Get User Role**
```php
$userRole = $_SESSION['user_role'] ?? 'user';
```
Uses null coalescing operator (`??`) - if role doesn't exist, default to 'user'.

#### Role-Based Content Display:

The HTML section uses PHP to show different content based on user role:

**Dynamic Title:**
```php
<h1><?php echo ucfirst($userRole) . ' Dashboard'; ?></h1>
```
- Admin sees: "Admin Dashboard"
- User sees: "User Dashboard"  
- Guest sees: "Guest Dashboard"

**User Information Display:**
```php
<span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
<span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
<span>#<?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
```

**Security Note:** All output is wrapped in `htmlspecialchars()` to prevent XSS attacks.

**Role Badge:**
```php
<span class="role-badge role-<?php echo $userRole; ?>">
    <?php echo ucfirst($userRole); ?>
</span>
```
CSS classes provide different styling for each role.

---

### 6. `logout.php` - Session Termination

**Purpose:** Securely ends user sessions and redirects to login page.

#### Complete Code:

```php
<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>
```

#### Security Process Explained:

**Step 1: Start Session**
```php
session_start();
```
Must start session to access and destroy it.

**Step 2: Clear Session Variables**
```php
session_unset();
```
Removes all session variables (user_id, user_name, etc.)

**Step 3: Destroy Session**
```php
session_destroy();
```
Completely destroys the session file on the server.

**Step 4: Redirect**
```php
header('Location: login.php');
exit();
```
Sends user back to login page and stops script execution.

**Why Both unset() and destroy()?**
- `session_unset()`: Clears the variables
- `session_destroy()`: Removes the session file
- Together they ensure complete logout

---

### 7. `js/register.js` - Client-Side Enhancement

**Purpose:** Improves user experience with interactive features for the registration form.

#### Key JavaScript Functions:

**Guest Mode Toggle:**
```javascript
function toggleGuestMode() {
    const guestRadio = document.querySelector('input[name="role"][value="guest"]');
    
    if (guestRadio.checked) {
        // Hide form fields for guest access
        guestFields.forEach(field => {
            field.style.display = 'none';
        });
        submitButton.textContent = 'Access Now';
    } else {
        // Show form fields for regular registration
        guestFields.forEach(field => {
            field.style.display = 'block';
        });
        submitButton.textContent = 'Create Account';
    }
}
```

**Real-time Password Validation:**
- Checks password strength as user types
- Confirms password match
- Provides immediate feedback

**Important:** This is client-side validation only. Server-side validation in PHP is still required for security.

Now you understand how each file works together to create a secure authentication system!

---

## 🔒 Security Features Explained

Understanding security is crucial for any authentication system. Let's break down each security measure in simple terms.

### 1. Password Security - Never Store Plain Text

#### The Problem with Plain Text Passwords

**BAD Example (Never do this!):**
```php
// WRONG - Storing password as-is
$password = "123456";
// Store $password directly in database
```

If someone hacks your database, they can see all passwords immediately.

#### Our Solution: Password Hashing

**GOOD Example (What we do):**
```php
// RIGHT - Hash the password before storing
$plainPassword = "123456";
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
// Result: "$2y$10$eImiTXuWVxfM37uY4JANjOePKo3bJhQsBj1pHE.uF9CwN5UelAi8W"
```

#### How Password Hashing Works

**Registration Process:**
1. User types password: `"mypassword123"`
2. PHP hashes it: `"$2y$10$eImiTXuW..."`
3. Database stores the hash, never the original

**Login Process:**
1. User types password: `"mypassword123"`
2. PHP gets stored hash from database
3. `password_verify("mypassword123", "$2y$10$eImiTXuW...")` returns `true`
4. If passwords match, login succeeds

#### Why This is Secure

**One-Way Process:**
- You can turn `"password"` into `"$2y$10$eImiTXuW..."`
- You CANNOT turn `"$2y$10$eImiTXuW..."` back into `"password"`
- Even if hackers steal the database, passwords are useless

**Unique Salts:**
- Same password creates different hashes each time
- `"123456"` might become `"$2y$10$ABC..."` for one user
- `"123456"` might become `"$2y$10$XYZ..."` for another user
- Prevents rainbow table attacks

### 2. SQL Injection Prevention

#### The Problem

**BAD Example (Vulnerable to SQL Injection):**
```php
// WRONG - Dangerous concatenation
$email = $_POST['email'];
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($connection, $query);
```

**What a hacker could do:**
- Input: `'; DROP TABLE users; --`
- Query becomes: `SELECT * FROM users WHERE email = ''; DROP TABLE users; --'`
- This deletes your entire users table!

#### Our Solution: Prepared Statements

**GOOD Example (What we use):**
```php
// RIGHT - Prepared statements with placeholders
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
```

#### How Prepared Statements Work

**Step 1: Prepare the Query**
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
```
The `?` is a placeholder for data that will come later.

**Step 2: Execute with Data**
```php
$stmt->execute([$email]);
```
PHP safely inserts the email value where the `?` is.

**Why This is Secure:**
- SQL query and data are sent separately to database
- Database treats data as literal values, not as SQL commands
- No matter what the user types, it's treated as data, not code

### 3. Cross-Site Scripting (XSS) Prevention

#### The Problem

**BAD Example (Vulnerable to XSS):**
```php
// WRONG - Direct output of user data
echo "Welcome " . $_POST['name'];
```

**What a hacker could do:**
- Input: `<script>alert('Hacked!');</script>`
- Output: `Welcome <script>alert('Hacked!');</script>`
- Browser executes the script, showing popup

#### Our Solution: Output Escaping

**GOOD Example (What we use):**
```php
// RIGHT - Escaped output
echo "Welcome " . htmlspecialchars($_POST['name']);
```

#### How htmlspecialchars() Works

**Input:** `<script>alert('Hacked!');</script>`
**Output:** `&lt;script&gt;alert('Hacked!');&lt;/script&gt;`

**What happens:**
- `<` becomes `&lt;`
- `>` becomes `&gt;`
- Browser displays the text instead of executing it as code

### 4. Session Security

#### How Sessions Work

**Session Basics:**
1. User logs in successfully
2. Server creates unique session ID (like a ticket number)
3. Session ID is sent to user's browser as a cookie
4. User's information is stored on server, linked to session ID
5. Every request includes the session ID to identify the user

#### Our Session Implementation

**Creating Sessions (login.php):**
```php
session_start();  // Initialize session system
$_SESSION['user_id'] = $user['id'];      // Store user ID
$_SESSION['user_name'] = $user['name'];  // Store user name
$_SESSION['user_email'] = $email;        // Store email
$_SESSION['user_role'] = $user['role'];  // Store role
```

**Checking Sessions (dashboard.php):**
```php
session_start();  // Start session to read data
if (!isset($_SESSION['user_id'])) {
    // No session = not logged in
    header('Location: login.php');
    exit();
}
```

**Destroying Sessions (logout.php):**
```php
session_start();     // Start to access session
session_unset();     // Clear all session variables
session_destroy();   // Destroy session file on server
```

#### Why Sessions are Secure

**Server-Side Storage:**
- User data is stored on the server, not in the browser
- Browser only gets a random session ID
- Even if someone steals the session ID, they can't see the actual data

### 5. Input Validation

#### Our Validation Strategy

**Example from register.php:**
```php
$errors = [];  // Collect all validation errors

// Check if name is provided
if (empty($name)) {
    $errors[] = "Name is required";
}

// Check if email is provided and valid
if (empty($email)) {
    $errors[] = "Email is required";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

// Check password requirements
if (empty($password)) {
    $errors[] = "Password is required";
} elseif (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long";
}
```

### Security Checklist

✅ **Passwords are hashed** with `password_hash()`
✅ **SQL injection prevented** with prepared statements  
✅ **XSS prevented** with `htmlspecialchars()`
✅ **Sessions managed** securely
✅ **Input validated** on server-side
✅ **Error messages** don't leak information

## 🔄 User Flow Walkthrough

### Registration Process
1. **User visits `register.php`**
2. **Fills out form** (name, email, password, role)
3. **JavaScript validation** provides immediate feedback
4. **Form submission** sends data to same page
5. **PHP validation** checks all inputs server-side
6. **Email uniqueness check** queries database
7. **Password hashing** secures the password
8. **Database insertion** creates new user record
9. **Success message** confirms registration

### Login Process
1. **User visits `login.php`**
2. **Enters credentials** (email and password)
3. **Form submission** sends data for verification
4. **Database lookup** finds user by email
5. **Password verification** checks against hash
6. **Session creation** stores user information
7. **Redirect to dashboard** shows protected content

### Dashboard Access
1. **Session check** verifies user is logged in
2. **Role detection** determines user permissions
3. **Content display** shows appropriate information
4. **Navigation options** based on user role

### Logout Process
1. **User clicks logout link**
2. **Session destruction** clears all user data
3. **Redirect to login** prevents unauthorized access

## ⚙️ Configuration Guide

### Database Setup
1. **Create database:**
   ```sql
   CREATE DATABASE user_auth_system;
   ```

2. **Import schema:**
   ```bash
   mysql -u root -p user_auth_system < db.sql
   ```

3. **Verify tables:**
   ```sql
   USE user_auth_system;
   SHOW TABLES;
   DESCRIBE users;
   ```

### Server Configuration
1. **Local development** (Laragon/XAMPP):
   - Place files in `www` or `htdocs` directory
   - Start Apache and MySQL services
   - Access via `http://localhost/HASH/`

2. **Production setup**:
   - Update `config.php` with production database credentials
   - Enable HTTPS for secure password transmission
   - Set proper file permissions (644 for files, 755 for directories)

### Environment Variables (Production)
```php
// config.php for production
$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'user_auth_system';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
```

## 🧪 Testing the System

### Manual Testing Steps

1. **Registration Testing:**
   - Try registering with valid data
   - Test with duplicate email
   - Test with weak passwords
   - Test guest access
   - Verify role assignments

2. **Login Testing:**
   - Test with correct credentials
   - Test with wrong password
   - Test with non-existent email
   - Test session persistence

3. **Dashboard Testing:**
   - Verify role-based content
   - Test direct URL access without login
   - Check session data display

4. **Security Testing:**
   - Try SQL injection in forms
   - Test XSS attempts in input fields
   - Verify password hashing in database

### Database Verification Queries
```sql
-- Check all users
SELECT id, name, email, role, created_at FROM users;

-- Verify password hashing
SELECT email, LEFT(password, 20) AS password_start FROM users;

-- Check role distribution
SELECT role, COUNT(*) as count FROM users GROUP BY role;
```

## 🚨 Common Issues & Solutions

### Database Connection Issues
**Problem:** "Connection failed" error
**Solutions:**
- Check database server is running
- Verify database name exists
- Confirm credentials in `config.php`
- Check MySQL port (default 3306)

### Session Issues
**Problem:** Users get logged out immediately
**Solutions:**
- Check session configuration in PHP
- Verify `session_start()` is called
- Check browser cookie settings
- Ensure proper session cleanup

### Password Issues
**Problem:** Login fails with correct password
**Solutions:**
- Verify password was hashed during registration
- Check for extra spaces in form fields
- Ensure password_verify() is used correctly
- Test with a fresh user account

### Permission Issues
**Problem:** Files not accessible or writable
**Solutions:**
- Set proper file permissions (644/755)
- Check web server user ownership
- Verify directory structure is correct
- Check .htaccess restrictions

### Role-Based Access Issues
**Problem:** Users see wrong content for their role
**Solutions:**
- Verify role enum values in database
- Check session role assignment
- Test role detection logic
- Verify database role field

## 🎯 Summary

This authentication system demonstrates:

1. **Secure user management** with proper password hashing
2. **Database interactions** using prepared statements
3. **Session-based authentication** for user state management
4. **Role-based access control** for different user types
5. **Input validation** and security best practices
6. **Modern PHP development** patterns and techniques

**Key Takeaways:**
- Always hash passwords before storing
- Use prepared statements for database queries
- Validate input on both client and server side
- Implement proper session management
- Design with security in mind from the start

This system provides a solid foundation for any web application requiring user authentication. You can extend it by adding features like password reset, email verification, user profiles, or more complex role permissions.
