<?php
$message = $_SESSION['msg'] ?? '';
$messageType = $_SESSION['type'] ?? '';
unset($_SESSION['msg'], $_SESSION['type']);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (isset($data['interests'][$id])) {
        array_splice($data['interests'], $id, 1);
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $_SESSION['msg'] = "Smazáno."; $_SESSION['type'] = "success";
    }
    header("Location: index.php?page=interests");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['interest_val'])) {
    $val = trim($_POST['interest_val']);
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : -1;

    if (!empty($val)) {
        $exists = false;
        foreach($data['interests'] as $i => $v) {
            if (strtolower($v) === strtolower($val) && $i !== $edit_id) $exists = true;
        }

        if ($exists) {
            $_SESSION['msg'] = "Už existuje!"; $_SESSION['type'] = "error";
        } else {
            if ($edit_id >= 0) $data['interests'][$edit_id] = $val;
            else $data['interests'][] = $val;
            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $_SESSION['msg'] = "Uloženo."; $_SESSION['type'] = "success";
        }
    }
    header("Location: index.php?page=interests");
    exit;
}

$e_mode = false; $e_val = "";
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if (isset($data['interests'][$id])) { $e_mode = true; $e_val = $data['interests'][$id]; $curr_id = $id; }
}
?>

<h2>Moje zájmy</h2>
<div class="interests-container">
    <?php foreach ($data['interests'] as $index => $interest): ?>
        <div class="tag" style="background: #3498db; color: white; padding: 10px; border-radius: 20px; display: inline-block; margin: 5px;">
            <?php echo htmlspecialchars($interest); ?>
            <a href="?page=interests&edit=<?php echo $index; ?>" style="color: yellow; margin-left: 10px;">✎</a>
            <a href="?page=interests&delete=<?php echo $index; ?>" style="color: white; margin-left: 5px;">✖</a>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($message): ?>
    <div class="<?php echo $messageType; ?>" style="margin: 10px 0; padding: 10px; border: 1px solid;"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" style="background: #eee; padding: 20px; border-radius: 8px;">
    <h3><?php echo $e_mode ? "Upravit zájem" : "Přidat zájem"; ?></h3>
    <input type="text" name="interest_val" value="<?php echo htmlspecialchars($e_val); ?>" required>
    <?php if ($e_mode): ?>
        <input type="hidden" name="edit_id" value="<?php echo $curr_id; ?>">
    <?php endif; ?>
    <button type="submit"><?php echo $e_mode ? "Uložit" : "Přidat"; ?></button>
    <?php if ($e_mode): ?> <a href="?page=interests">Zrušit</a> <?php endif; ?>
</form>