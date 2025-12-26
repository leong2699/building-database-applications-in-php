<?php
session_start();

// Security check
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

// Include database connection
require_once "pdo.php";

// Handle form submission (UPDATE)
if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && 
    isset($_POST['mileage']) && isset($_POST['autos_id'])) {
    
    // Validate all fields are present
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || 
        strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }
    // Validate year and mileage are numeric
    else if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }
    // Validation passed - update database
    else {
        $stmt = $pdo->prepare('UPDATE autos SET make=:make, model=:model, year=:year, mileage=:mileage WHERE autos_id=:autos_id');
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'],
            ':autos_id' => $_POST['autos_id']
        ));
        $_SESSION['success'] = "Record edited";
        header("Location: index.php");
        return;
    }
}

// Get the automobile to edit
if (!isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header('Location: index.php');
    return;
}

// Display error message
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}

// Safely escape data for display
$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>#GOH HYM LEONG# 97fb62de</title>
</head>
<body>
    <div class="container">
        <h1>Editing Automobile</h1>
        
        <form method="post">
            <p>Make: 
                <input type="text" name="make" size="40" value="<?= $make ?>"/>
            </p>
            <p>Model: 
                <input type="text" name="model" size="40" value="<?= $model ?>"/>
            </p>
            <p>Year: 
                <input type="text" name="year" value="<?= $year ?>"/>
            </p>
            <p>Mileage: 
                <input type="text" name="mileage" value="<?= $mileage ?>"/>
            </p>
            <input type="hidden" name="autos_id" value="<?= $autos_id ?>"/>
            <input type="submit" value="Save"/>
            <a href="index.php">Cancel</a>
        </form>
    </div>
</body>
</html>
