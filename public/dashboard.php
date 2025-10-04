<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<style>
  body { font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px; }
  .container { max-width:900px; margin:auto; background:#fff; padding:20px;
               border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  h1 { color:#007acc; margin-bottom:10px; }
  nav a { margin-right:12px; text-decoration:none; color:#007acc; font-weight:bold; }
  nav a:hover { text-decoration:underline; }
  button { background:#007acc; color:#fff; border:none; padding:8px 14px; border-radius:6px; cursor:pointer; }
  button:hover { background:#005fa3; }
</style>
</head>
<body>
<main class="container">
  <h1 id="welcome">Carregando...</h1>
  <p id="role"></p>
  <nav id="menu"></nav>
  <form id="logoutForm"><button type="submit">Sair</button></form>
</main>

<script src="js/app.js"></script>
<script>
(async () => {
  try {
    const r = await api('/auth/me','GET');
    if (!r.ok) throw new Error("Sessão inválida");

    document.getElementById('welcome').textContent = "Bem-vindo, " + r.user.name;
    document.getElementById('role').innerHTML = "O seu papel é: <b>" + r.user.role + "</b>";

    const nav = document.getElementById('menu');
    if (r.user.role === 'admin') {
      nav.innerHTML = `
        <a href="users.php">Gestão de Utilizadores</a> |
        <a href="accounts.php">Gestão de Contas</a> |
        <a href="deposit.php">Depósitos</a> |
        <a href="transfer.php">Transferências</a> |
        <a href="transactions.php">Transações</a> |
        <a href="audit.php">Logs de Auditoria</a>
      `;
    } else {
      nav.innerHTML = `
        <a href="my_account.php">Minha Conta</a> |
        <a href="withdraw.php">Levantamento</a> |
        <a href="transactions.php">Meus Movimentos</a>
      `;
    }
  } catch (err) {
    document.getElementById('welcome').textContent = "Sessão inválida, faça login novamente.";
    console.error(err);
    setTimeout(()=>location.href="login.html",1500);
  }
})();

// Logout com destruição total da sessão
document.getElementById('logoutForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  try {
    await api('/auth/logout','POST');
  } catch(err) {
    console.warn("Erro ao sair:", err.message);
  }
  // limpa qualquer cache do navegador
  localStorage.clear();
  sessionStorage.clear();
  location.replace("login.html"); // impede voltar com botão "voltar"
});
</script>
</body>
</html>
