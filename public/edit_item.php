<?php
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';
$db = DB::pdo();
$auth = require_auth($db);

$id = (int)($_GET['id'] ?? 0);
$st = $db->prepare("SELECT * FROM items WHERE id=? AND owner_id=?");
$st->execute([$id, $auth['user_id']]);
$item = $st->fetch();

if (!$item) { die("Item não encontrado"); }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $st = $db->prepare("UPDATE items SET title=?, notes=?, updated_at=? WHERE id=? AND owner_id=?");
    $st->execute([$_POST['title'], $_POST['notes'], date('Y-m-d H:i:s'), $id, $auth['user_id']]);
    header("Location: items.php");
    exit;
}
?>
<!doctype html>
<html lang="pt">
<head><meta charset="utf-8"><title>Editar Item</title></head>
<body>
<h1>Editar Item</h1>
<form method="post">
  <input type="text" name="title" value="<?=htmlspecialchars($item['title'])?>" required>
  <textarea name="notes"><?=htmlspecialchars($item['notes'])?></textarea>
  <button type="submit">Salvar</button>
</form>
</body>
</html>
