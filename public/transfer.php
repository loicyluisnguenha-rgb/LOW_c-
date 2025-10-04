<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['admin']);

$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = (int)($_POST['from_account'] ?? 0);
    $to   = (int)($_POST['to_account'] ?? 0);
    $amount = (float)($_POST['amount'] ?? 0);

    if ($from>0 && $to>0 && $from!=$to && $amount>0) {
        // 🔎 Verificar saldo antes
        $st = $db->prepare("SELECT balance FROM accounts WHERE id=? LIMIT 1");
        $st->execute([$from]);
        $accFrom = $st->fetch(PDO::FETCH_ASSOC);

        if (!$accFrom || $accFrom['balance'] < $amount) {
            $msg = "Saldo insuficiente na conta $from.";
        } else {
            $db->beginTransaction();
            try {
                $db->prepare("UPDATE accounts SET balance=balance-? WHERE id=?")
                   ->execute([$amount,$from]);
                $db->prepare("UPDATE accounts SET balance=balance+? WHERE id=?")
                   ->execute([$amount,$to]);

                $db->prepare("INSERT INTO transactions(account_id,type,amount,description) VALUES(?,?,?,?)")
                   ->execute([$from,'transfer_out',$amount,'Transferência para conta '.$to]);
                $db->prepare("INSERT INTO transactions(account_id,type,amount,description) VALUES(?,?,?,?)")
                   ->execute([$to,'transfer_in',$amount,'Transferência recebida de conta '.$from]);

                $db->prepare("INSERT INTO transfer_logs(from_account_id,to_account_id,amount) VALUES(?,?,?)")
                   ->execute([$from,$to,$amount]);

                $db->commit();
                $msg="Transferência concluída!";
            } catch (Throwable $e) {
                $db->rollBack();
                $msg="Erro na transferência: ".$e->getMessage();
            }
        }
    } else {
        $msg="Dados inválidos.";
    }
}

// lista contas com nome do usuário
$sql = "
    SELECT a.id, u.name
    FROM accounts a
    JOIN users u ON u.id = a.user_id
    ORDER BY a.id
";
$accounts = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Transferências</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:600px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  select, input { padding:8px; border:1px solid #ccc; border-radius:6px; }
  button { background:#007acc; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
  .msg { margin-top:15px; font-weight:bold; }
  .msg.success { color:green; }
  .msg.error { color:red; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Transferência</h1>

  <?php if ($msg): ?>
    <p class="msg <?=str_starts_with($msg,'✅')?'success':'error'?>"><?=htmlspecialchars($msg)?></p>
  <?php endif; ?>

  <form method="post">
    <label>De conta:
      <select name="from_account" required>
        <option value="">-- selecione --</option>
        <?php foreach($accounts as $a): ?>
          <option value="<?=$a['id']?>">
            Conta <?=$a['id']?> - <?=htmlspecialchars(dec($a['name']))?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Para conta:
      <select name="to_account" required>
        <option value="">-- selecione --</option>
        <?php foreach($accounts as $a): ?>
          <option value="<?=$a['id']?>">
            Conta <?=$a['id']?> - <?=htmlspecialchars(dec($a['name']))?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Valor:
      <input type="number" name="amount" step="0.01" min="0.01" required>
    </label>
    <button type="submit">Transferir</button>
  </form>
</main>
</body>
</html>
