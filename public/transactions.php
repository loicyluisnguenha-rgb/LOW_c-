<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['admin','user']); // ✅ Agora vem com role

// Admin vê tudo, user vê só dele
if ($auth['role']==='admin') {
  $st=$db->query("
    SELECT t.id,t.account_id,t.type,t.amount,t.description,t.created_at,u.name
    FROM transactions t
    JOIN accounts a ON a.id=t.account_id
    JOIN users u ON u.id=a.user_id
    ORDER BY t.id DESC
  ");
} else {
  $st=$db->prepare("
    SELECT t.id,t.account_id,t.type,t.amount,t.description,t.created_at,u.name
    FROM transactions t
    JOIN accounts a ON a.id=t.account_id
    JOIN users u ON u.id=a.user_id
    WHERE u.id=?
    ORDER BY t.id DESC
  ");
  $st->execute([$auth['id']]);
}
$tx=$st->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Movimentos</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:1000px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:10px; border:1px solid #ccc; text-align:left; }
  thead { background:#007acc; color:#fff; }
  tbody tr:hover { background:#f1f7ff; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Movimentos</h1>
  <table>
    <thead><tr><th>ID</th><th>Conta</th><th>Utilizador</th><th>Tipo</th><th>Valor</th><th>Descrição</th><th>Data</th></tr></thead>
    <tbody>
      <?php foreach($tx as $t): ?>
      <tr>
        <td><?=$t['id']?></td>
        <td><?=$t['account_id']?></td>
        <td><?=htmlspecialchars(dec($t['name']))?></td>
        <td><?=$t['type']?></td>
        <td><?=number_format((float)$t['amount'],2)?></td>
        <td><?=htmlspecialchars($t['description'])?></td>
        <td><?=$t['created_at']?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</main>
</body>
</html>
