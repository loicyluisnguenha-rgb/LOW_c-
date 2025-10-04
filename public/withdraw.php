<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['user','admin']);

$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (float)($_POST['amount'] ?? 0);

    if ($amount > 0) {
        // Buscar saldo do utilizador
        $st = $db->prepare("SELECT id,balance FROM accounts WHERE user_id=? LIMIT 1");
        $st->execute([$auth['id']]);
        $acc = $st->fetch(PDO::FETCH_ASSOC);

        if (!$acc || $acc['balance'] < $amount) {
            $msg = "Saldo insuficiente para levantar.";
        } else {
            $db->beginTransaction();
            try {
                $db->prepare("UPDATE accounts SET balance=balance-? WHERE id=?")
                   ->execute([$amount,$acc['id']]);
                $db->prepare("INSERT INTO transactions(account_id,type,amount,description) VALUES(?,?,?,?)")
                   ->execute([$acc['id'],'withdraw',$amount,'Levantamento']);
                $db->commit();
                $msg="Levantamento efetuado com sucesso.";
            } catch (Throwable $e) {
                $db->rollBack();
                $msg="Erro no levantamento: ".$e->getMessage();
            }
        }
    } else {
        $msg="Valor inválido.";
    }
}

?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Levantamento</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:600px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  input, textarea { padding:8px; border:1px solid #ccc; border-radius:6px; }
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
  <h1>Levantamento</h1>

  <?php if ($msg): ?><p class="msg"><?=htmlspecialchars($msg)?></p><?php endif; ?>

  <form method="post">
    <label>Valor:
      <input type="number" name="amount" step="0.01" required>
    </label>
    <label>Descrição:
      <textarea name="description"></textarea>
    </label>
    <button type="submit">Levantar</button>
  </form>
</main>
</body>
</html>
