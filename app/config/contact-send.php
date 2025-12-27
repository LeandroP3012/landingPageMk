<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    http_response_code(400);
    exit;
}

$to = 'mail@dominio.com';
$subject = 'Nuevo mensaje desde la web';

$body = "
Nombre: $name
Correo: $email

Mensaje:
$message
";

$headers = "From: Web <no-reply@dominio.com>\r\n";
$headers .= "Reply-To: $email\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo 'OK';
} else {
    http_response_code(500);
}
