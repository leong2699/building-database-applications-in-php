<?php
session_start();

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // php123

// Handle form submission
if (isset($_POST['email']) && isset($_POST['pass'])) {
    
    // Unset any error from session first
    unset($_SESSION['error']);
    
    // Check if email and password are provided
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    }
    
    // Hash the password with salt
    $check = hash('md5', $salt.$_POST['pass']);
    
    // Verify password
    if ($check == $stored_hash) {
        // Login success
        error_log("Login success ".$_POST['email']);
        $_SESSION['name'] = $_POST['email'];
        
        // IMPORTANT: Redirect to index.php and STOP
        header("Location: index.php");
        return;
    } else {
        // Login fail
        error_log("Login fail ".$_POST['email']." $check");
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}

// ONLY output HTML if we haven't redirected
// Display error message
$error_message = '';
if (isset($_SESSION['error'])) {
    $error_message = '<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n";
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>#GOH HYM LEONG# 97fb62de</title>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?= $error_message ?>
        <form method="POST">
            <label for="email">User Name</label>
            <input type="text" name="email" id="email"><br/>
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
