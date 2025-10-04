<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Gestão de Items</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:1000px; margin:auto; background:#fff; padding:20px; border-radius:8px;
               box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; margin-top:20px; }
  th, td { padding:10px; border:1px solid #ccc; text-align:left; }
  thead { background:#007acc; color:#fff; }
  tbody tr:hover { background:#f1f7ff; }
  form { margin-top:20px; display:flex; gap:10px; }
  input, textarea { padding:6px; border:1px solid #ccc; border-radius:4px; }
  button { padding:8px 14px; border:none; background:#007acc; color:#fff; border-radius:4px; cursor:pointer; }
  button:hover { background:#005b99; }
</style>
</head>
<body>
<main class="container">
  <h1>Gestão de Items</h1>

  <!-- Formulário para adicionar novo item -->
  <form method="post" action="items.php">
    <input type="text" name="title" placeholder="Título" required>
    <textarea name="notes" placeholder="Notas"></textarea>
    <button type="submit" name="add">Adicionar</button>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Notas</th>
        <th>Criado em</th>
        <th>Atualizado em</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      require_once __DIR__.'/../api/db.php';
      require_once __DIR__.'/../api/utils.php';
      $db = DB::pdo();
      $auth = require_auth($db);

      // --- Criar novo item ---
      if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add'])) {
          $st = $db->prepare("INSERT INTO items (title, notes, owner_id, created_at, updated_at) VALUES (?,?,?,?,?)");
          $now = date('Y-m-d H:i:s');
          $st->execute([$_POST['title'], $_POST['notes'], $auth['user_id'], $now, $now]);
          header("Location: items.php");
          exit;
      }

      // --- Apagar item ---
      if (isset($_GET['delete'])) {
          $id = (int)$_GET['delete'];
          $st = $db->prepare("DELETE FROM items WHERE id=? AND owner_id=?");
          $st->execute([$id, $auth['user_id']]);
          header("Location: items.php");
          exit;
      }

      // --- Listar items ---
      $st = $db->prepare("SELECT * FROM items WHERE owner_id=? ORDER BY created_at DESC");
      $st->execute([$auth['user_id']]);
      $items = $st->fetchAll();
      foreach ($items as $it): ?>
        <tr>
          <td><?=$it['id']?></td>
          <td><?=htmlspecialchars($it['title'])?></td>
          <td><?=htmlspecialchars($it['notes'])?></td>
          <td><?=$it['created_at']?></td>
          <td><?=$it['updated_at']?></td>
          <td>
            <a href="edit_item.php?id=<?=$it['id']?>">Editar</a> |
            <a href="items.php?delete=<?=$it['id']?>" onclick="return confirm('Apagar este item?')">Apagar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>
