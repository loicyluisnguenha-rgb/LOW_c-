<?php
declare(strict_types=1);
require_once __DIR__.'/../api/db.php';
require_once __DIR__.'/../api/utils.php';

$db = DB::pdo();
$auth = require_role($db, ['admin']); // só admin pode aceder
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Criar Utilizador</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:450px; margin:auto; background:#fff; padding:20px; border-radius:8px;
               box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:20px; }
  form { display:flex; flex-direction:column; gap:12px; }
  input, select { padding:10px; border:1px solid #ccc; border-radius:6px; }
  button { background:#007acc; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
  .msg { margin-top:10px; }
  a.back { display:inline-block; margin-bottom:15px; color:#007acc; text-decoration:none; }
  a.back:hover { text-decoration:underline; }
</style>
</head>
<body>
<main class="container">
  <a href="dashboard.php" class="back">← Voltar ao Dashboard</a>
  <h1>Criar Novo Utilizador</h1>
  <form id="fCreate">
    <label>Nome <input type="text" name="name" required></label>
    <label>Email <input type="email" name="email" required></label>
    <label>Senha <input type="password" name="password" required></label>
    <label>Role
      <select name="role_id" required>
        <option value="2">User</option>
        <option value="1">Admin</option>
      </select>
    </label>
    <button type="submit">Criar</button>
  </form>
  <p class="msg" id="msg"></p>
</main>

<script src="js/app.js"></script>
<script>
const $ = (s, el=document)=>el.querySelector(s);

$('#fCreate').addEventListener('submit', async (ev)=>{
  ev.preventDefault();
  $('#msg').textContent = '';
  const payload = Object.fromEntries(new FormData(ev.target));
  payload.role_id = parseInt(payload.role_id,10); // garantir numérico
  try {
    const r = await api('/auth/register','POST', payload);
    if (r.ok) {
      $('#msg').style.color = "green";
      $('#msg').textContent = "Utilizador criado com sucesso!";
      ev.target.reset();
    }
  } catch(err) {
    $('#msg').style.color = "red";
    $('#msg').textContent = "Erro: " + err.message;
  }
});
</script>
</body>
</html>
