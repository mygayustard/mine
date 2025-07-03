<?php
session_start();

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'users';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    $errors = [];
    
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }
    
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        // Prepare SQL to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, password FROM people WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password against hashed password in database
            if (password_verify($password, $user['password'])) {
                // Authentication successful - set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to dashboard or home page
                header("Location: index.html");
                exit();
            } else {
                $errors['password'] = "Invalid password";
            }
        } else {
            $errors['username'] = "Username not found";
        }
        $stmt->close();
    }
    
    // Store errors in session to display on form
    $_SESSION['login_errors'] = $errors;
    header("Location: LOGINform.php");
    exit();
}

$conn->close();
?>