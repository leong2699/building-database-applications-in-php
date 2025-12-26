<?php
session_start();

// Security check - must be logged in
if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

// Include database connection
require_once "pdo.php";

// Display success message if exists (flash pattern)
if (isset($_SESSION['success'])) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
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
        <h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>
        
        <?php if (count($rows) > 0): ?>
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
        <?php else: ?>
        <p>No rows found</p>
        <?php endif; ?>
        
        <p>
            <a href="add.php">Add New Entry</a> |
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>
</html>
