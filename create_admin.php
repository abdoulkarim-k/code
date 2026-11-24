<?php
require 'db.php';

$email = 'admin@gmail.com';
$password = '12345';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "INSERT INTO users (email, password) VALUES (?, ?)"
);

$stmt->execute([$email, $hash]);

echo "User inserted successfully!";
?>