<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Dotenv\Dotenv;

// 🔹 Carregar variáveis do .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad(); // não lança erro se .env não existir

/**
 * Cria e devolve um PHPMailer configurado com base nas variáveis do .env
 */
function make_mailer(): PHPMailer {
  $mail = new PHPMailer(true);
  $mail->SMTPDebug = SMTP::DEBUG_OFF; 
  $mail->Debugoutput = 'error_log';

  $host     = $_ENV['SMTP_HOST']     ?? null;
  $user     = $_ENV['SMTP_USER']     ?? null;
  $pass     = $_ENV['SMTP_PASS']     ?? null;
  $from     = $_ENV['SMTP_FROM']     ?? $user;
  $fromName = $_ENV['SMTP_FROM_NAME'] ?? 'Simple OTP CRUD';

  if ($host && $user && $pass) {
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $user;
    $mail->Password   = $pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)($_ENV['SMTP_PORT'] ?? 587);
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom($from, $fromName);
  }

  return $mail;
}

/**
 * Envia o OTP por email via Gmail SMTP
 */
function send_otp_email(string $to, string $name, string $code): void {
  $host = $_ENV['SMTP_HOST'] ?? null;
  if (!$host) {
    error_log("[DEV] OTP para {$to}: {$code}");
    return;
  }

  $mail = make_mailer();
  $mail->addAddress($to, $name ?: $to);
  $mail->isHTML(true);
  $mail->Subject = 'Seu código OTP';
  $mail->Body    = "<p>Olá <b>".htmlspecialchars($name ?: $to)."</b>,</p>
                    <p>Seu código OTP é: <b>{$code}</b></p>
                    <p>Válido por 10 minutos.</p>";
  $mail->AltBody = "Seu código OTP: {$code} (válido por 10 minutos)";

  $mail->send();
}
