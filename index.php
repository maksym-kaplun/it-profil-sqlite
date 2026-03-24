<?php
session_start();
$filename = 'profile.json';

if (!file_exists($filename)) {
    file_put_contents($filename, json_encode(["name" => "Maksym Kaplun", "role" => "Student", "skills" => ["HTML", "CSS"], "interests" => []]));
}
$data = json_decode(file_get_contents($filename), true);

$page = $_GET['page'] ?? 'home';
$allowed_pages = ['home', 'interests', 'skills'];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 7.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="?page=home">Domů</a>
        <a href="?page=interests">Zájmy</a>
        <a href="?page=skills">Dovednosti</a>
    </nav>

    <main>
        <?php 
        if (in_array($page, $allowed_pages) && file_exists("pages/{$page}.php")) {
            include "pages/{$page}.php";
        } else {
            include "pages/not_found.php";
        }
        ?>
    </main>
</body>
</html>