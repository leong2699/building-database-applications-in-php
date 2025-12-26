<?php
// Start session
session_start();

// Initialize variables
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // hash of 'php123' with salt

// Handle form submission
if (isset($_POST['who']) && isset($_POST['pass'])) {
    
    // Check if email and password are provided
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    }
    
    // Check if email contains @
    if (strpos($_POST['who'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }
    
    // Hash the password with salt
    $check = hash('md5', $salt.$_POST['pass']);
    
    // Verify password
    if ($check == $stored_hash) {
        // Login success
        error_log("Login success ".$_POST['who']);
        
        // Redirect to autos.php with name parameter
        header("Location: autos.php?name=".urlencode($_POST['who']));
        return;
    } else {
        // Login fail
        error_log("Login fail ".$_POST['who']." $check");
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}

// Get error message if exists
$error = isset($_SESSION['error']) ? $_SESSION['error'] : false;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>GOH HYM LEONG</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
        if ($error !== false) {
            echo('<p class="error">'.htmlentities($error)."</p>\n");
        }
        ?>
        <form method="POST">
            <label for="who">Email</label>
            <input type="text" name="who" id="who"><br/>
            <label for="pass">Password</label>
            <input type="password" name="pass" id="pass"><br/>
            <input type="submit" value="Log In">
            <a href="index.php">Cancel</a>
        </form>
        <p>
            For a password hint, view source and find a password hint
            in the HTML comments.
            <!-- Password is php123 -->
        </p>
    </div>
</body>
</html>
