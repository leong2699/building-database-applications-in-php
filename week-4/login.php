<?php
session_start();

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // php123

// Handle form submission
if (isset($_POST['email']) && isset($_POST['pass'])) {
    
    // Validate email and password
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    }
    
    // Check @ sign
    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }
    
    // Check password
    $check = hash('md5', $salt.$_POST['pass']);
    
    if ($check == $stored_hash) {
        // Success - store name in session and redirect
        error_log("Login success ".$_POST['email']);
        $_SESSION['name'] = $_POST['email'];
        header("Location: view.php");
        return;
    } else {
        // Failed login
        error_log("Login fail ".$_POST['email']." $check");
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}

// Display error message using flash pattern
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
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
        <form method="POST">
            <label for="email">Email</label>
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
