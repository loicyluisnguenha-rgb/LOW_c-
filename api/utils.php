<?php 
declare(strict_types=1);

/**
 * Cabeçalhos só para a API (rota contém /api/)
 * Assim as páginas .php normais (users.php, items.php, audit.php, dashboard.php)
 * continuam a renderizar como HTML no browser.
 */
if (!headers_sent() && str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
  header('Content-Type: application/json; charset=utf-8');

  // Permite o origin atual (http ou https, conforme a requisição)
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
  header("Access-Control-Allow-Origin: $scheme://$host");
  header("Access-Control-Allow-Credentials: true");

  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') { http_response_code(204); exit; }

function json_input(): array {
  $raw = file_get_contents('php://input');
  $j = json_decode($raw, true);
  return is_array($j) ? $j : [];
}

function json_out(int $code, $data=null): void {
  http_response_code($code);
  echo json_encode($data ?? new stdClass(), JSON_UNESCAPED_UNICODE);
  exit;
}

/** Detecta se a requisição atual está em HTTPS */
function is_https(): bool {
  if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') return true;
  if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') return true;
  return false;
}

/**
 * Aceita token do header Authorization, cookie ou querystring
 */
function bearer_token(): ?string {
  $h = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'] ?? '';
  if (preg_match('/Bearer\s+(.*)$/i', $h, $m)) return $m[1];
  if (!empty($_COOKIE['session_token'])) return $_COOKIE['session_token'];
  if (!empty($_GET['token'])) return $_GET['token'];
  return null;
}

function require_https(): void {
  if (PHP_SAPI === 'cli') return;
  if (is_https()) return;
  if (($_SERVER['HTTP_HOST'] ?? '') === 'localhost') return; // dev local
  json_out(403, ['ok'=>false,'error'=>'Requer HTTPS']);
}

function now_plus_minutes(int $m): string {
  return date('Y-m-d H:i:s', time() + $m*60);
}

function random_token(): string {
  return bin2hex(random_bytes(32));
}

function require_auth(PDO $db): array {
    $token = bearer_token();
    if (!$token) {
        json_out(401, ['ok'=>false,'error'=>'Token ausente']);
    }

    $stmt = $db->prepare("SELECT * FROM sessions WHERE token=? LIMIT 1");
    $stmt->execute([$token]);
    $s = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$s) json_out(401, ['ok'=>false,'error'=>'Sessão inválida']);
    if (strtotime($s['expires_at']) < time()) json_out(401, ['ok'=>false,'error'=>'Sessão expirada']);

    return $s;
}

/* ========= 🔐 Criptografia ========= */
function app_key(): string {
  static $key;
  if ($key) return $key;
  $envFile = __DIR__ . '/../.env';
  if (!is_file($envFile)) throw new RuntimeException(".env não encontrado");

  $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $env = [];
  foreach ($lines as $line) {
    if (preg_match('/^\s*[#;]/', $line)) continue;
    if (!str_contains($line, '=')) continue;
    [$k, $v] = array_map('trim', explode('=', $line, 2));
    $v = trim($v, "\"'");
    $env[$k] = $v;
  }

  $raw = $env['APP_KEY'] ?? '';
  if (str_starts_with($raw,'base64:')) $raw = base64_decode(substr($raw,7));
  if (strlen($raw) !== 32) throw new RuntimeException("APP_KEY inválida");
  return $key = $raw;
}

function enc(string $plaintext): string {
  $key = app_key();
  $iv = random_bytes(12);
  $tag = '';
  $ciphertext = openssl_encrypt($plaintext,'aes-256-gcm',$key,OPENSSL_RAW_DATA,$iv,$tag);
  return base64_encode($iv.$tag.$ciphertext);
}

function dec(?string $encoded): string {
  if (!$encoded) return '';
  $key = app_key();
  $data = base64_decode($encoded,true);
  if ($data === false || strlen($data) < 28) return '';
  $iv  = substr($data,0,12);
  $tag = substr($data,12,16);
  $ct  = substr($data,28);
  return openssl_decrypt($ct,'aes-256-gcm',$key,OPENSSL_RAW_DATA,$iv,$tag) ?: '';
}

/* ========= Audit ========= */
function audit_log(PDO $db, ?int $userId, string $action): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $stmt = $db->prepare("INSERT INTO audit_logs(user_id,action,ip,user_agent) VALUES(?,?,?,?)");
    $stmt->execute([$userId, $action, $ip, $ua]);
}

/* ========= Rate limiting ========= */
function rate_limit(string $action, int $limit=100, int $seconds=60): void {
  $key = sys_get_temp_dir()."/rate_".md5($action.'_'.($_SERVER['REMOTE_ADDR']??''));
  $now = time();
  $data = ['count'=>0,'time'=>$now];
  if (is_file($key)) {
    $data = json_decode(file_get_contents($key),true) ?: $data;
    if ($now - $data['time'] < $seconds) {
      if ($data['count'] >= $limit) json_out(429,['ok'=>false,'error'=>'rate_limited']);
      $data['count']++;
    } else {
      $data = ['count'=>1,'time'=>$now];
    }
  } else {
    $data = ['count'=>1,'time'=>$now];
  }
  file_put_contents($key,json_encode($data));
}

/* ========= Roles ========= */
function require_role(PDO $db, array $allowed): array {
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

    if (!$user) json_out(401, ['ok'=>false,'error'=>'Usuário não encontrado']);

    $role = $user['role'];
    if (!in_array($role, $allowed, true)) {
        json_out(403, ['ok'=>false,'error'=>'Acesso negado: requer role(s) '.implode(',', $allowed)]);
    }

    return [
        'id'    => $user['id'],
        'name'  => dec($user['name']),
        'email' => dec($user['email']),
        'role'  => $role
    ];
}
