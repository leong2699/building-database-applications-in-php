<?php
session_start();

// Security check - must have name parameter from login
if (!isset($_GET['name'])) {
    die("Name parameter missing");
}

// Include database connection
require_once "pdo.php";

// Handle logout
if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}

// Handle Add button
$success = false;
$failure = false;

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    
    // Validate make is not empty
    if (strlen($_POST['make']) < 1) {
        $failure = "Make is required";
    }
    // Validate year and mileage are numeric
    else if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $failure = "Mileage and year must be numeric";
    }
    // Validation passed - insert into database
    else {
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));
        $success = "Record inserted";
    }
}

// Retrieve all automobiles from database
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>#GOH HYM LEONG# 97fb62de</title>
    <style>
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tracking Autos for <?= htmlentities($_GET['name']) ?></h1>
        
        <?php
        if ($success !== false) {
            echo('<p class="success">'.htmlentities($success)."</p>\n");
        }
        if ($failure !== false) {
            echo('<p class="error">'.htmlentities($failure)."</p>\n");
        }
        ?>
        
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
            <input type="submit" name="logout" value="Logout"/>
        </form>
        
        <?php if (count($rows) > 0): ?>
        <h2>Automobiles</h2>
        <table>
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Year</th>
                    <th>Mileage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlentities($row['make']) ?></td>
                    <td><?= htmlentities($row['year']) ?></td>
                    <td><?= htmlentities($row['mileage']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
