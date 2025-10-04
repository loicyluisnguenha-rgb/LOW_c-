<?php 
declare(strict_types=1);

require_once __DIR__.'/db.php';
require_once __DIR__.'/utils.php';
require_once __DIR__.'/mailer.php';

class AuthController {

  // ================= Register =================
public static function register(): void {
    require_https();
    rate_limit("register", 5, 60);

    $db = DB::pdo();
    $in = json_input();
    $name  = trim($in['name']  ?? '');
    $email = trim($in['email'] ?? '');
    $pass  = (string)($in['password'] ?? '');
    $roleId = (int)($in['role_id'] ?? 2); // padrão "user"

    if ($name===''||$email===''||$pass==='') {
      json_out(400,['ok'=>false,'error'=>'Nome, email e senha são obrigatórios']); return;
    }
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
      json_out(400,['ok'=>false,'error'=>'Email inválido']); return;
    }
    if (mb_strlen($pass)<6) {
      json_out(400,['ok'=>false,'error'=>'Senha deve ter pelo menos 6 caracteres']); return;
    }

    // Evitar duplicados
    $st=$db->query("SELECT id,email FROM users");
    while($u=$st->fetch()){
      if(dec($u['email'])===$email){
        json_out(409,['ok'=>false,'error'=>'Email já cadastrado']); return;
      }
    }

    $hash=password_hash($pass,PASSWORD_BCRYPT);
    $db->prepare("INSERT INTO users (name,email,password_hash,failed_attempts,last_failed,role_id) VALUES (?,?,?,?,NULL,?)")
       ->execute([enc($name),enc($email),$hash,0,$roleId]);

    $uid=(int)$db->lastInsertId();

    // 🚫 Não gera OTP no registo
    audit_log($db, $uid, "Novo registo (admin)");

    json_out(201,['ok'=>true,'message'=>'Conta criada com sucesso.','user_id'=>$uid,'role_id'=>$roleId]);
}


  // ================= Login Start =================
  public static function loginStart(): void {
    require_https();
    rate_limit("login", 10, 60);

    $db=DB::pdo();
    $in=json_input();
    $email=trim($in['email']??'');
    $password=(string)($in['password']??'');

    if ($email===''||$password==='') {
      json_out(400,['ok'=>false,'error'=>'Email e senha são obrigatórios']); return;
    }

    // Buscar utilizador (email decriptado)
    $user=null;
    $st=$db->query("SELECT * FROM users");
    while($u=$st->fetch()){
      if(dec($u['email'])===$email){ $user=$u; break; }
    }
    if(!$user){
      json_out(401,['ok'=>false,'error'=>'Credenciais inválidas']); return;
    }

    // Bloqueio por tentativas
    if ($user['failed_attempts'] >= 5 && strtotime($user['last_failed']) > strtotime('-15 minutes')) {
      json_out(429,['ok'=>false,'error'=>'Muitas tentativas falhadas']); return;
    }

    // Verificação da senha
    if (!password_verify($password,$user['password_hash'])) {
      $db->prepare("UPDATE users SET failed_attempts=failed_attempts+1,last_failed=NOW() WHERE id=?")
         ->execute([$user['id']]);
      audit_log($db,null,"Falha de login: $email");
      json_out(401,['ok'=>false,'error'=>'Credenciais inválidas']); return;
    }

    // Reset contador
    $db->prepare("UPDATE users SET failed_attempts=0,last_failed=NULL WHERE id=?")->execute([$user['id']]);

    // Criar OTP
    $otp=strval(random_int(100000,999999));
    $exp=now_plus_minutes(5);
    $db->prepare("INSERT INTO otp_codes (user_id,code,expires_at,used) VALUES (?,?,?,0)")
       ->execute([$user['id'],$otp,$exp]);
    @send_otp_email($email,dec($user['name']),$otp);

    audit_log($db,$user['id'],"Login iniciado (OTP enviado)");

    json_out(200,[
      'ok'=>true,
      'message'=>'OTP enviado ao email',
      'user_id'=>$user['id'],
      'role_id'=>$user['role_id']
    ]);
  }

  // ================= Verify OTP =================
  public static function verifyOtp(): void {
    require_https();
    rate_limit("verify_otp", 10, 60);

    $db=DB::pdo();
    $in=json_input();
    $email=trim($in['email']??'');
    $code =trim($in['code'] ??'');

    if ($email===''||$code==='') {
      json_out(400,['ok'=>false,'error'=>'Email e código são obrigatórios']); return;
    }

    // Buscar utilizador
    $user=null;
    $st=$db->query("SELECT * FROM users");
    while($u=$st->fetch()){
      if(dec($u['email'])===$email){ $user=$u; break; }
    }
    if(!$user){ json_out(401,['ok'=>false,'error'=>'Usuário não encontrado']); return; }

    // Último OTP
    $st=$db->prepare("SELECT * FROM otp_codes WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $st->execute([$user['id']]);
    $otp=$st->fetch();
    if(!$otp || $otp['used'] || strtotime($otp['expires_at'])<time() || $otp['code']!==$code){
      json_out(401,['ok'=>false,'error'=>'OTP inválido ou expirado']); return;
    }
    $db->prepare("UPDATE otp_codes SET used=1 WHERE id=?")->execute([$otp['id']]);

    // Criar sessão (expira em 1h)
    $token = random_token();
    $exp   = date('Y-m-d H:i:s', time() + 3600);
    $db->prepare("INSERT INTO sessions(user_id,token,expires_at) VALUES(?,?,?)")
       ->execute([$user['id'], $token, $exp]);

    // ✅ Setar cookie HttpOnly para todo o domínio
    setcookie("session_token", $token, [
      'expires'  => time()+3600,
      'path'     => "/",                // universal
      'secure'   => is_https(),         // localhost=false; produção=true
      'httponly' => true,
      'samesite' => 'Lax'
    ]);

    audit_log($db,$user['id'],"OTP verificado e sessão criada");
    json_out(200, ['ok'=>true]);
  }

  // ================= Me =================
  public static function me(): void {
    $db = DB::pdo();
    $auth = require_auth($db);

    $st = $db->prepare("
        SELECT u.id, u.name, u.email, r.name AS role
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE u.id = ?
        LIMIT 1
    ");
    $st->execute([$auth['user_id']]);
    $user = $st->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        json_out(404, ['ok'=>false,'error'=>'Usuário não encontrado']);
    }

    json_out(200, [
      'ok'=>true,
      'user'=>[
        'id'    => $user['id'],
        'name'  => dec($user['name']),
        'email' => dec($user['email']),
        'role'  => $user['role']
      ]
    ]);
  }

  // ================= Logout =================
public static function logout(): void {
    $db = DB::pdo();
    $token = bearer_token() ?? ($_COOKIE['session_token'] ?? '');

    if ($token) {
        $st = $db->prepare("SELECT user_id FROM sessions WHERE token=? LIMIT 1");
        $st->execute([$token]);
        $sess = $st->fetch();

        // Apaga a sessão da BD
        $db->prepare("DELETE FROM sessions WHERE token=?")->execute([$token]);

        // Limpa o cookie
        setcookie("session_token", "", time()-3600, "/", "", false, true);

        // Destrói a sessão PHP (se existir)
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_unset();
            session_destroy();
        }

        if ($sess) {
            audit_log($db, (int)$sess['user_id'], "Logout");
        } else {
            audit_log($db, null, "Logout (sessão não encontrada)");
        }
    }

    json_out(200, ['ok'=>true,'message'=>'Sessão terminada']);
}
}
