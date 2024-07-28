<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require 'config.php';
require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configurações do servidor
    $mail->isSMTP();
    $mail->Host = $config['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['SMTP_USERNAME'];
    $mail->Password = $config['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Capturar dados do formulário
    $nome = filter_var($_POST['nome']);
    $sobrenome = filter_var($_POST['sobrenome']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mensagem = filter_var($_POST['mensagem']);

    // Verificar se o e-mail é válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "E-mail inválido.";
        exit;
    }

    // Configurar remetente e destinatário
    $mail->setFrom($email, "$nome $sobrenome"); // Usa o e-mail do usuário como remetente
    $mail->addAddress('rodrigocorreaneto136@gmail.com'); // Seu e-mail como destinatário

    // Configurar conteúdo do e-mail
    $mail->isHTML(true);
    $mail->Subject = 'Nova mensagem do formulário de contato';
    $mail->Body = "<p><strong>Nome:</strong> $nome $sobrenome</p><p><strong>Email:</strong> $email</p><p><strong>Mensagem:</strong> $mensagem</p>";
    $mail->AltBody = "Nome: $nome $sobrenome\nEmail: $email\nMensagem:\n$mensagem";

    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
}
