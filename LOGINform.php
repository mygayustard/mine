<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LOGIN</title>
	<link rel="stylesheet" href="LOGIN.CSS">
	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Sacramento&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
    <style>
        .login-error-container {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .login-error {
            color: #ff3333;
            background-color: rgba(255, 51, 51, 0.1);
            border: 1px solid #ff3333;
            border-radius: 5px;
            padding: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            display: inline-block;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $errors = $_SESSION['login_errors'] ?? [];
    unset($_SESSION['login_errors']);
    ?>
    
    <div class="container">
        <div class="description">
            <i class="fa-brands fa-linkedin-in fa-3x"></i>
            <h1>linkedln</h1>
            <h2>sign in</h2>
        </div>
        
        <div class="login-error-container">
            <?php if (!empty($errors)): ?>
                <div class="login-error">
                    <?php 
                    if (isset($errors['username']) || isset($errors['password'])) {
                        echo "Invalid username or password";
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        
        <form action="login.php" method="POST">
            <div class="form-control <?= isset($errors['username']) ? 'error' : '' ?>">
                <fieldset>
                    <legend>Username</legend>
                    <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </fieldset>
                <?php if (isset($errors['username'])): ?>
                    <div class="validation-message"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Updated password field with specific class -->
            <div class="form-control password-field <?= isset($errors['password']) ? 'error' : '' ?>">
                <fieldset>
                    <legend>Password</legend>
                    <input type="password" name="password">
                </fieldset>
                <?php if (isset($errors['password'])): ?>
                    <div class="validation-message"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit">login</button>
            <p>don't have an account?<a href="signup_form.php"> register</a> </p>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset errors
            document.querySelectorAll('.form-control').forEach(control => {
                control.classList.remove('error');
                const message = control.querySelector('.validation-message');
                if (message) message.textContent = '';
            });
            
            // Username validation
            const username = document.querySelector('input[name="username"]');
            if (!username.value.trim()) {
                showError(username, 'Username is required');
                isValid = false;
            }
            
            // Password validation
            const password = document.querySelector('input[name="password"]');
            if (!password.value) {
                showError(password, 'Password is required');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        function showError(input, message) {
            const formControl = input.closest('.form-control');
            formControl.classList.add('error');
            
            let messageEl = formControl.querySelector('.validation-message');
            if (!messageEl) {
                messageEl = document.createElement('div');
                messageEl.className = 'validation-message';
                formControl.appendChild(messageEl);
            }
            messageEl.textContent = message;
        }
    });
    </script>
</body>
</html>