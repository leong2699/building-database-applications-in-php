<?php
session_start();

// Security check
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

// Include database connection
require_once "pdo.php";

// Handle confirmation (POST)
if (isset($_POST['delete']) && isset($_POST['autos_id'])) {
    $stmt = $pdo->prepare('DELETE FROM autos WHERE autos_id = :zip');
    $stmt->execute(array(':zip' => $_POST['autos_id']));
    $_SESSION['success'] = "Record deleted";
    header('Location: index.php');
    return;
}

// Get the automobile to delete
if (!isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT make, model FROM autos WHERE autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header('Location: index.php');
    return;
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$autos_id = $_GET['autos_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>#GOH HYM LEONG# 97fb62de</title>
</head>
<body>
    <div class="container">
        <h1>Confirm: Deleting <?= $make ?> <?= $model ?></h1>
        
        <form method="post">
            <input type="hidden" name="autos_id" value="<?= $autos_id ?>"/>
            <input type="submit" name="delete" value="Delete"/>
            <a href="index.php">Cancel</a>
        </form>
    </div>
</body>
</html>
