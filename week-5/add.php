<?php
session_start();

// Security check
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

// Include database connection
require_once "pdo.php";

// Handle form submission
if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    
    // Validate all fields are present
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || 
        strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = "All fields are required";
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
        $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES (:make, :model, :year, :mileage)');
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage']
        ));
        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    }
}

// Display error message
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
        <h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>
        
        <form method="post">
            <p>Make: 
                <input type="text" name="make" size="40"/>
            </p>
            <p>Model: 
                <input type="text" name="model" size="40"/>
            </p>
            <p>Year: 
                <input type="text" name="year"/>
            </p>
            <p>Mileage: 
                <input type="text" name="mileage"/>
            </p>
            <input type="submit" value="Add"/>
            <a href="index.php">Cancel</a>
        </form>
    </div>
</body>
</html>
