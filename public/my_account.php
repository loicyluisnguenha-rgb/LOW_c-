<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db=DB::pdo();
$auth=require_auth($db);

$st=$db->prepare("SELECT a.id,a.balance,a.status,a.created_at FROM accounts a WHERE a.user_id=? LIMIT 1");
$st->execute([$auth['user_id']]);
$acc=$st->fetch();
?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <title>Minha Conta</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background:#f6f7fb;
      margin:0;
      padding:20px;
    }
    .container {
      max-width:600px;
      margin:auto;
      background:#fff;
      padding:20px;
      border-radius:8px;
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
    }
    h1 {
      color:#007acc;
      margin-bottom:20px;
    }
    .info p {
      margin:8px 0;
      font-size:15px;
      line-height:1.5;
    }
    .info span {
      font-weight:bold;
      color:#333;
    }
    .status {
      padding:4px 8px;
      border-radius:4px;
      color:#fff;
      font-weight:bold;
    }
    .status.active { background:#2ecc71; }
    .status.blocked { background:#e74c3c; }
    a.back {
      display:inline-block;
      margin-bottom:15px;
      color:#007acc;
      text-decoration:none;
      font-size:14px;
    }
    a.back:hover { text-decoration:underline; }
  </style>
</head>
<body>
<main class="container">
  <!-- 🔗 Link de voltar ao Dashboard -->
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>

  <h1>Minha Conta</h1>

  <div class="info">
  <?php if($acc): ?>
    <p><span>ID Conta:</span> <?=$acc['id']?></p>
    <p><span>Saldo:</span> <?=number_format((float)$acc['balance'],2)?> MZN</p>
    <p><span>Status:</span> <span class="status <?=$acc['status']?>"><?=$acc['status']?></span></p>
    <p><span>Criada em:</span> <?=$acc['created_at']?></p>
  <?php else: ?>
    <p>Você ainda não possui conta.</p>
  <?php endif ?>
  </div>
</main>
</body>
</html>
