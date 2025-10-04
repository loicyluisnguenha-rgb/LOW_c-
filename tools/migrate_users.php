<?php
declare(strict_types=1);
require __DIR__ . '/api/db.php';
require __DIR__ . '/api/utils.php';

$db = pdo();
$st = $db->query("SELECT id,name,email FROM users");
$cnt=0;
while($u=$st->fetch()){
  $id=(int)$u['id'];
  $name=$u['name'];
  $email=$u['email'];

  // heurística: se já for base64 válido e >=40 chars, assume cifrado
  $isEncrypted = (bool)(preg_match('/^[A-Za-z0-9+\/=]+$/',$email) && strlen($email)>=40);
  if($isEncrypted){ echo "ID $id já cifrado\n"; continue; }

  echo "Migrando user $id ($email)\n";
  $db->prepare("UPDATE users SET name=?, email=? WHERE id=?")
     ->execute([enc($name), enc($email), $id]);
  $cnt++;
}
echo "Migração concluída. Alterados: $cnt\n";
