<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');

if (!headers_sent()) {
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");
    header("Content-Security-Policy: default-src 'none'; frame-ancestors 'none'; base-uri 'none';");
}

require_once __DIR__.'/utils.php';
require_once __DIR__.'/AuthController.php';
require_once __DIR__.'/ItemsController.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
rate_limit('api_global', 200, 60);

$path = $_GET['route'] ?? ($_SERVER['PATH_INFO'] ?? '');
if (!$path) {
    $uri    = $_SERVER['REQUEST_URI'] ?? '/';
    $script = dirname($_SERVER['SCRIPT_NAME']);
    $path   = substr($uri, strlen($script));
}
$path = parse_url($path, PHP_URL_PATH);
$path = preg_replace('#^/simple-otp-crud/api#', '', $path);
$path = preg_replace('#^/api#', '', $path);
$path = '/'.ltrim($path, '/');

try {
    // Auth
    if ($path === '/auth/register'   && $method === 'POST') return AuthController::register();
    if ($path === '/auth/login'      && $method === 'POST') return AuthController::loginStart();
    if ($path === '/auth/verify-otp' && $method === 'POST') return AuthController::verifyOtp();
    if ($path === '/auth/me'         && $method === 'GET')  return AuthController::me();   
    if ($path === '/auth/logout'     && $method === 'POST') return AuthController::logout();

    // Items
    if ($path === '/items' && $method === 'GET')  return ItemsController::index();
    if ($path === '/items' && $method === 'POST') return ItemsController::create();

    if (preg_match('#^/items/(\d+)$#', $path, $m)) {
        $id = (int)$m[1];
        if ($method === 'PUT')    return ItemsController::update($id);
        if ($method === 'DELETE') return ItemsController::delete($id);
    }

    json_out(404, ['ok'=>false,'error'=>'route_not_found','path'=>$path,'method'=>$method]);
} catch (Throwable $e) {
    json_out(500, [
        'ok'=>false,
        'error'=>'fatal',
        'detail'=>$e->getMessage(),
        'trace'=>$e->getTraceAsString()
    ]);
}
