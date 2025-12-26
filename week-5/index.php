<?php
session_start();

// Security check
if (!isset($_SESSION['name'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>#GOH HYM LEONG# 97fb62de</title>
    </head>
    <body>
        <div class="container">
            <h1>Welcome to Autos Database</h1>
            <p><a href="login.php">Please log in</a></p>
            <p>Attempt to <a href="add.php">add data</a> without logging in - it should fail.</p>
        </div>
    </body>
    </html>
    <?php
    return;
}

// Include database connection
require_once "pdo.php";

// Display flash messages
if (isset($_SESSION['success'])) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}

// Retrieve all automobiles
$stmt = $pdo->query("SELECT autos_id, make, model, year, mileage FROM autos ORDER BY autos_id");
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
                    <th>Model</th>
                    <th>Year</th>
                    <th>Mileage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlentities($row['make']) ?></td>
                    <td><?= htmlentities($row['model']) ?></td>
                    <td><?= htmlentities($row['year']) ?></td>
                    <td><?= htmlentities($row['mileage']) ?></td>
                    <td>
                        <a href="edit.php?autos_id=<?= $row['autos_id'] ?>">Edit</a> / 
                        <a href="delete.php?autos_id=<?= $row['autos_id'] ?>">Delete</a>
                    </td>
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
