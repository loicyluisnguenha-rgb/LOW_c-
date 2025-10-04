<?php
require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../api/utils.php';

$db = DB::pdo();
$admin = require_role($db, ['admin']);

$stmt = $db->query("SELECT a.id, a.action, a.ip, a.user_agent, a.created_at, u.name 
                    FROM audit_logs a 
                    LEFT JOIN users u ON a.user_id = u.id
                    ORDER BY a.created_at DESC LIMIT 50");
$logs = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Logs de Auditoria</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:1100px; margin:auto; background:#fff; padding:20px; border-radius:8px;
               box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; font-size:14px; }
  th, td { padding:8px; border:1px solid #ddd; text-align:left; }
  thead { background:#007acc; color:#fff; }
  tbody tr:hover { background:#f9fcff; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Logs de Auditoria</h1>
  <table>
    <thead>
      <tr><th>ID</th><th>Utilizador</th><th>Ação</th><th>IP</th><th>Agente</th><th>Data</th></tr>
    </thead>
    <tbody>
      <?php foreach($logs as $l): ?>
      <tr>
        <td><?= $l['id'] ?></td>
        <td><?= htmlspecialchars(dec($l['name'] ?? '')) ?></td>
        <td><?= htmlspecialchars($l['action']) ?></td>
        <td><?= htmlspecialchars($l['ip']) ?></td>
        <td><?= htmlspecialchars($l['user_agent']) ?></td>
        <td><?= htmlspecialchars($l['created_at']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>
