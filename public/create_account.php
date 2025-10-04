<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['admin']);

$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $balance = (float)($_POST['balance'] ?? 0);
    $status  = $_POST['status'] ?? 'active';

    if ($user_id > 0) {
        $stmt = $db->prepare("INSERT INTO accounts (user_id,balance,status) VALUES (?,?,?)");
        $stmt->execute([$user_id, $balance, $status]);
        $msg = "Conta criada com sucesso!";
    } else {
        $msg = "Selecione um utilizador válido.";
    }
}

// Buscar utilizadores e decriptar os campos
$users = $db->query("SELECT id, name, email FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as &$u) {
    $u['name']  = dec($u['name']);
    $u['email'] = dec($u['email']);
}
unset($u);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Criar Conta</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:600px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  select, input { padding:8px; border:1px solid #ccc; border-radius:6px; }
  button { background:#007acc; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
  .msg { margin-top:15px; color:green; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="accounts.php" class="back">← Voltar à Gestão de Contas</a>
  <h1>Criar Conta</h1>
  <?php if ($msg): ?><p class="msg"><?=htmlspecialchars($msg)?></p><?php endif; ?>

  <form method="post">
    <label>Utilizador:
      <select name="user_id" required>
        <option value="">-- selecione --</option>
        <?php foreach($users as $u): ?>
          <option value="<?=$u['id']?>">#<?=$u['id']?> - <?=htmlspecialchars($u['name'])?> (<?=htmlspecialchars($u['email'])?>)</option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Saldo inicial:
      <input type="number" name="balance" step="0.01" value="0.00" required>
    </label>
    <label>Status:
      <select name="status">
        <option value="active">Ativa</option>
        <option value="blocked">Bloqueada</option>
      </select>
    </label>
    <button type="submit">Criar Conta</button>
  </form>
</main>
</body>
</html>
