<?php
require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/../api/utils.php';

$db = DB::pdo();
$token = bearer_token();

if ($token) {
    $db->prepare("DELETE FROM sessions WHERE token=?")->execute([$token]);
}

// limpar cookie
setcookie("session_token", "", time()-3600, "/", "", false, true);

// redireciona para login
header("Location: login.html");
exit;
