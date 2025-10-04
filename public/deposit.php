<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_auth($db);
$admin = require_role($db, ['admin']);

$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountId = (int)($_POST['account_id'] ?? 0);
    $amount    = (float)($_POST['amount'] ?? 0);
    $desc      = trim($_POST['description'] ?? '');

    if ($accountId > 0 && $amount > 0) {
        $db->beginTransaction();

        // Atualiza saldo
        $db->prepare("UPDATE accounts SET balance = balance + ? WHERE id=?")
           ->execute([$amount, $accountId]);

        // Regista transação
        $db->prepare("INSERT INTO transactions(account_id,type,amount,description) VALUES(?,?,?,?)")
           ->execute([$accountId,'deposit',$amount,$desc]);

        $db->commit();
        $msg = "Depósito de $amount efetuado com sucesso!";
    } else {
        $msg = "Dados inválidos.";
    }
}

$st = $db->query("
  SELECT a.id, u.name, u.email, a.balance
  FROM accounts a
  JOIN users u ON u.id=a.user_id
  ORDER BY a.id DESC
");
$accounts = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Depósito em Conta</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:600px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  input, select, textarea { padding:8px; border:1px solid #ccc; border-radius:6px; }
  button { background:#007acc; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
  .msg { margin-top:15px; color:green; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Depósito</h1>

  <?php if ($msg): ?>
    <p class="msg"><?=htmlspecialchars($msg)?></p>
  <?php endif; ?>

  <form method="post">
    <label>Conta:
      <select name="account_id" required>
        <?php foreach ($accounts as $a): ?>
          <option value="<?=$a['id']?>">
            [<?=$a['id']?>] <?=htmlspecialchars(dec($a['name']))?> (<?=htmlspecialchars(dec($a['email']))?>) - Saldo: <?=number_format((float)$a['balance'],2)?>
          </option>
        <?php endforeach ?>
      </select>
    </label>
    <label>Valor:
      <input type="number" name="amount" step="0.01" required>
    </label>
    <label>Descrição:
      <textarea name="description"></textarea>
    </label>
    <button type="submit">Depositar</button>
  </form>
</main>
</body>
</html>
