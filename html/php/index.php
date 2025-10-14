<?php
// index.php

// Set a title for the page
$title = "Welcome to My PHP Site";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
            color: #333;
        }
        h1 {
            color: #007BFF;
        }
        .content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1><?= $title ?></h1>
        <p>Hello, world! This is a simple PHP page.</p>

        <p>Current date and time: <?= date("Y-m-d H:i:s") ?></p>
    </div>
</body>
</html>
