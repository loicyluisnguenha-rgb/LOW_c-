<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['admin']);

// Apagar conta
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->prepare("DELETE FROM accounts WHERE id=?")->execute([$id]);
    header("Location: accounts.php");
    exit;
}

// Buscar contas + usuários
$rows = $db->query("
    SELECT a.id, u.name, u.email,
           a.balance, a.status, a.created_at
    FROM accounts a
    JOIN users u ON u.id=a.user_id
    ORDER BY a.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Descriptografar
foreach ($rows as &$r) {
    $r['name']  = dec($r['name']);
    $r['email'] = dec($r['email']);
}
unset($r);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Gestão de Contas</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:1000px; margin:auto; background:#fff; padding:20px; border-radius:8px;
               box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:10px; border:1px solid #ccc; text-align:left; }
  thead { background:#007acc; color:#fff; }
  tbody tr:hover { background:#f1f7ff; }
  .actions a { margin-right:8px; text-decoration:none; color:#007acc; }
  .actions a:hover { text-decoration:underline; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
  a.btn { padding:6px 10px; border-radius:4px; text-decoration:none; font-size:14px; }
  a.edit { background:#ffc107; color:#000; }
  a.edit:hover { background:#e0a800; }
  a.delete { background:#dc3545; color:#fff; }
  a.delete:hover { background:#bd2130; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Gestão de Contas</h1>
  <nav style="margin-bottom:15px;">
    <a href="create_account.php">➕ Criar Conta</a>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Saldo</th>
        <th>Status</th>
        <th>Criada em</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?=$r['id']?></td>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=htmlspecialchars($r['email'])?></td>
        <td><?=number_format((float)$r['balance'],2,',','.')?></td>
        <td><?=$r['status']?></td>
        <td><?=$r['created_at']?></td>
        <td class="actions">
          <a href="edit_account.php?id=<?=$r['id']?>" class="btn edit">✏️ Editar</a>
          <a href="accounts.php?delete=<?=$r['id']?>" onclick="return confirm('Tem certeza que deseja apagar esta conta?')" class="btn delete">🗑️ Apagar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>
