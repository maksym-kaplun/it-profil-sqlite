<?php
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['add'])) {
        $name = trim($_POST['name']);
        if (empty($name)) {
            $_SESSION['msg'] = "Pole nesmí být prázdné.";
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO interests (name) VALUES (?)");
                $stmt->execute([$name]);
                $_SESSION['msg'] = "Zájem byl přidán.";
            } catch (PDOException $e) {
                $_SESSION['msg'] = "Tento zájem už existuje.";
            }
        }
    }

    if (isset($_POST['delete'])) {
        $stmt = $db->prepare("DELETE FROM interests WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $_SESSION['msg'] = "Zájem byl odstraněn.";
    }

    if (isset($_POST['update'])) {
        $name = trim($_POST['new_name']);
        if (empty($name)) {
            $_SESSION['msg'] = "Pole nesmí být prázdné.";
        } else {
            try {
                $stmt = $db->prepare("UPDATE interests SET name = ? WHERE id = ?");
                $stmt->execute([$name, $_POST['id']]);
                $_SESSION['msg'] = "Zájem byl upraven.";
            } catch (PDOException $e) {
                // Zachytí chybu, pokud se snažíš přejmenovat zájem na něco, co už existuje
                $_SESSION['msg'] = "Tento zájem už existuje.";
            }
        }
    }

    header("Location: index.php");
    exit;
}

$interests = $db->query("SELECT * FROM interests")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Zájmy z databáze</title>
</head>
<body>
    <h1>Moje Zájmy</h1>

    <?php if (isset($_SESSION['msg'])): ?>
        <p class="msg"><strong><?= htmlspecialchars($_SESSION['msg']) ?></strong></p>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Nový zájem">
        <button type="submit" name="add">Přidat</button>
    </form>
    <hr>
    <ul>
        <?php foreach ($interests as $i): ?>
            <li>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $i['id'] ?>">
                    <input type="text" name="new_name" value="<?= htmlspecialchars($i['name']) ?>">
                    <button type="submit" name="update">Upravit</button>
                    <button type="submit" name="delete">Smazat</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>