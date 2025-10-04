<?php ?>
<!doctype html>
<html lang="pt">
<meta charset="utf-8">
<title>Perfil</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://unpkg.com/missing.css">

<nav class="container"><a href="dashboard.php">← Voltar</a></nav>

<main class="container">
  <h1>Perfil</h1>
  <!--<p><b>Nome:</b> <span id="name"></span></p>-->
  <p><b>Email:</b> <span id="email"></span></p>
  <p><b>ID:</b> <span id="uid"></span></p>
</main>

<script src="js/app.js"></script>
<script>
(async ()=>{
  try{
    const me = await api('/auth/me','GET');
    const plainName  = sessionStorage.getItem('user_name_plain') || '(sem nome)';
    const plainEmail = sessionStorage.getItem('user_email_plain') || '(sem email)';

    //document.getElementById('name').textContent  = plainName;
    document.getElementById('email').textContent = plainEmail;
    document.getElementById('uid').textContent   = me.user.id;
  }catch(e){
    localStorage.removeItem('token');
    location.href='login.html';
  }
})();
</script>
</html>
