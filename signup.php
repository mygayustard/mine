<?php
session_start(); // Start session for error passing
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'users';

$conn = new mysqli('localhost', 'root', '', 'users');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = sanitize_input($_POST['firstname']);
    $lastname = sanitize_input($_POST['lastname']);
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    
    $errors = [];
    
    if (empty($firstname)) $errors['firstname'] = "First name is required";
    if (empty($lastname)) $errors['lastname'] = "Last name is required";
    
    if (empty($username)) {
        $errors['username'] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
        $errors['username'] = "Must be 4-20 characters (letters, numbers, underscores only)";
    }
    
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    }
    
    $check_username = $conn->prepare("SELECT id FROM people WHERE username = ?");
    $check_username->bind_param("s", $username);
    $check_username->execute();
    $check_username->store_result();
    
    if ($check_username->num_rows > 0) {
        $errors['username'] = "Username already exists";
    }
    $check_username->close();
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO people (firstname, lastname, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $username, $hashed_password);
        
        if ($stmt->execute()) {
            $_SESSION['registration_success'] = true;
            header("Location: LOGINform.php");
            exit();
        } else {
            $errors['general'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    
    // Store errors in session to display on form
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: signup.php");
    exit();
}

// For GET requests, clear any previous errors
unset($_SESSION['errors']);
unset($_SESSION['form_data']);

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conn->close();
?>