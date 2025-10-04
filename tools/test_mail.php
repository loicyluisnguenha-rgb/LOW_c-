<?php
require __DIR__ . '/api/mailer.php';


try {
  send_otp_email("gamesloicy@gmail.com", "Teste", "123456");
  echo "Email enviado com sucesso!";
} catch (Exception $e) {
  echo "Erro ao enviar: " . $e->getMessage();
}
