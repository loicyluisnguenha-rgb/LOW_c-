<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['admin']);

$id = (int)($_GET['id'] ?? 0);
$account = $db->prepare("
    SELECT a.id, u.name, u.email, a.balance, a.status
    FROM accounts a
    JOIN users u ON u.id=a.user_id
    WHERE a.id=?
");
$account->execute([$id]);
$row = $account->fetch(PDO::FETCH_ASSOC);

if (!$row) { die("Conta não encontrada."); }

// Descriptografar
$row['name']  = dec($row['name']);
$row['email'] = dec($row['email']);

// Atualizar
$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $balance = (float)$_POST['balance'];
    $status  = $_POST['status'];

    $db->prepare("UPDATE accounts SET balance=?, status=? WHERE id=?")
       ->execute([$balance, $status, $id]);

    $msg = "Conta atualizada com sucesso!";
}
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Editar Conta</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:500px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  input, select { padding:8px; border:1px solid #ccc; border-radius:6px; }
  button { background:#007acc; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
  .msg { margin-top:10px; color:green; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="accounts.php" class="back">← Voltar às Contas</a>
  <h1>Editar Conta</h1>
  <?php if ($msg): ?><p class="msg"><?=$msg?></p><?php endif; ?>
  <form method="post">
    <label>Nome: <input value="<?=htmlspecialchars($row['name'])?>" disabled></label>
    <label>Email: <input value="<?=htmlspecialchars($row['email'])?>" disabled></label>
    <label>Saldo: <input type="number" name="balance" step="0.01" value="<?=$row['balance']?>" required></label>
    <label>Status:
      <select name="status">
        <option value="active" <?=$row['status']==='active'?'selected':''?>>Ativa</option>
        <option value="blocked" <?=$row['status']==='blocked'?'selected':''?>>Bloqueada</option>
      </select>
    </label>
    <button type="submit">Salvar</button>
  </form>
</main>
</body>
</html>
