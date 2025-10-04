<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$admin = require_role($db, ['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    json_out(400, ['ok'=>false,'error'=>'ID inválido']);
}

// Buscar utilizador
$stmt = $db->prepare("SELECT u.id, u.name, u.email, u.role_id, r.name AS role 
                      FROM users u JOIN roles r ON r.id=u.role_id
                      WHERE u.id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    json_out(404, ['ok'=>false,'error'=>'Utilizador não encontrado']);
}

$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = (int)($_POST['role'] ?? $user['role_id']);

    if ($name !== '' && $email !== '') {
        $st = $db->prepare("UPDATE users SET name=?, email=?, role_id=? WHERE id=?");
        $st->execute([enc($name), enc($email), $role, $id]);
        $msg = "Utilizador atualizado com sucesso!";
        // Recarregar
        $user['name']  = enc($name);
        $user['email'] = enc($email);
        $user['role_id'] = $role;
    } else {
        $msg = "Preencha todos os campos.";
    }
}

$roles = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Editar Utilizador</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:600px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  input, select { padding:8px; border:1px solid #ccc; border-radius:6px; }
  button { background:#007acc; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
  .msg { margin-top:15px; color:green; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="users.php" class="back">← Voltar à Gestão de Utilizadores</a>
  <h1>Editar Utilizador #<?= $user['id'] ?></h1>
  <?php if ($msg): ?><p class="msg"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

  <form method="post">
    <label>Nome:
      <input type="text" name="name" value="<?= htmlspecialchars(dec($user['name'])) ?>" required>
    </label>
    <label>Email:
      <input type="email" name="email" value="<?= htmlspecialchars(dec($user['email'])) ?>" required>
    </label>
    <label>Role:
      <select name="role">
        <?php foreach ($roles as $r): ?>
          <option value="<?= $r['id'] ?>" <?= $r['id']==$user['role_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <button type="submit">Salvar Alterações</button>
  </form>
</main>
</body>
</html>
