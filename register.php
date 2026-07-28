<?php
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['message' => 'Dati mancanti']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
try {
    $stmt->execute([$username, $hashed]);
    echo json_encode(['message' => 'Registrazione completata']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['message' => 'Username già in uso']);
}
?>
