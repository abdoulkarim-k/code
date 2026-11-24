<?php
require 'db.php';

$email = "user@gmail.com";
$password = "123456";

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, password, failed_attempts, locked_until)
        VALUES (?, ?, 0, NULL)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$email, $hash]);

echo "User created successfully!";
?>