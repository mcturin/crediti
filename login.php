<?php
require 'config.php';
require 'jwt_helper.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $token = generate_jwt(['id' => $user['id'], 'username' => $user['username']]);
    echo json_encode([
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'balance' => $user['balance']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['message' => 'Credenziali errate']);
}
?>
