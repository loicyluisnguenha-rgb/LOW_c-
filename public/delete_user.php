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

// Primeiro apagar contas relacionadas (se quiser forçar consistência)
$db->prepare("DELETE FROM accounts WHERE user_id=?")->execute([$id]);

// Depois apagar o utilizador
$db->prepare("DELETE FROM users WHERE id=?")->execute([$id]);

header("Location: users.php");
exit;
