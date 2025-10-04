<?php
require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../api/utils.php';

$db = DB::pdo();
$admin = require_role($db, ['admin']);

// Buscar utilizadores e roles
$stmt = $db->query("SELECT u.id, u.name, u.email, r.name as role 
                    FROM users u 
                    JOIN roles r ON u.role_id = r.id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Gestão de Utilizadores</title>
<style>
  body { font-family: Arial, sans-serif; background: #f6f7fb; margin:0; padding:20px; }
  .container { max-width:1000px; margin:auto; background:#fff; padding:20px; border-radius:8px;
               box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:10px; border:1px solid #ccc; text-align:left; }
  thead { background:#007acc; color:#fff; }
  tbody tr:hover { background:#f1f7ff; }
  a.btn { padding:6px 10px; border-radius:4px; text-decoration:none; font-size:14px; }
  a.edit { background:#ffc107; color:#000; }
  a.edit:hover { background:#e0a800; }
  a.delete { background:#dc3545; color:#fff; }
  a.delete:hover { background:#bd2130; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
  nav { margin-bottom:15px; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Gestão de Utilizadores</h1>
  <nav>
    <a href="create_user.php">➕ Criar Utilizador</a>
  </nav>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Role</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($rows as $u): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars(dec($u['name'])) ?></td>
        <td><?= htmlspecialchars(dec($u['email'])) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td>
          <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn edit">✏️ Editar</a>
          <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn delete"
             onclick="return confirm('Tem certeza que deseja apagar este utilizador?');">🗑️ Apagar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>
