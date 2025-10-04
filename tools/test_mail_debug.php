<?php
declare(strict_types=1);

require __DIR__ . '/lib/vendor/autoload.php'; // carrega PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$log = '';

try {
    $mail = new PHPMailer(true);

    // Configuração básica (a partir do .env)
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USER');
    $mail->Password   = getenv('SMTP_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = getenv('SMTP_PORT') ?: 587;
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom(getenv('SMTP_FROM'), getenv('SMTP_FROM_NAME') ?: 'Simple OTP CRUD');

    // Debug detalhado no browser
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) use (&$log) {
        $log .= "[".date('Y-m-d H:i:s')."] [$level] $str\n";
    };

    // Destinatário de teste
    $mail->addAddress('teuemaildestino@gmail.com', 'Teste Debug');
    $mail->isHTML(true);
    $mail->Subject = 'Teste SMTP debug';
    $mail->Body    = "Este é um email de teste com debug SMTP ativo";
    $mail->AltBody = "Teste SMTP";

    $mail->send();
    echo "<h2>Email enviado com sucesso!</h2>";
} catch (Throwable $e) {
    echo "<h2>Erro ao enviar: </h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

// Mostrar o log SMTP completo
echo "<h3>Log SMTP:</h3><pre>" . htmlspecialchars($log) . "</pre>";
