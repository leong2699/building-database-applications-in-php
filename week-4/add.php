<?php
session_start();

// Security check
if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

// Include database connection
require_once "pdo.php";

// Handle form submission
if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    
    // Validate make is not empty
    if (strlen($_POST['make']) < 1) {
        $_SESSION['error'] = "Make is required";
        header("Location: add.php");
        return;
    }
    // Validate year and mileage are numeric
    else if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
    }
    // Validation passed - insert into database
    else {
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
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
        <h1>Add Automobile</h1>
        
        <form method="post">
            <p>Make: 
                <input type="text" name="make" size="40"/>
            </p>
            <p>Year: 
                <input type="text" name="year"/>
            </p>
            <p>Mileage: 
                <input type="text" name="mileage"/>
            </p>
            <input type="submit" value="Add"/>
            <a href="view.php">Cancel</a>
        </form>
    </div>
</body>
</html>
