
<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user) {

        // Reba niba account ifunze
        if ($user['locked_until'] !== null &&
            strtotime($user['locked_until']) > time()) {

            $remaining = ceil(
                (strtotime($user['locked_until']) - time()) / 60
            );

            $message = "Account is blocked. Try again after $remaining minutes.";

        } else {

            // Niba igihe cyo gufungwa cyarangiye
            if ($user['locked_until'] !== null) {
                $reset = $pdo->prepare(
                    "UPDATE users SET failed_attempts = 0,
                    locked_until = NULL WHERE id = ?"
                );
                $reset->execute([$user['id']]);

                $user['failed_attempts'] = 0;
            }

            // Kugenzura password
            if (password_verify($password, $user['password'])) {

                $reset = $pdo->prepare(
                    "UPDATE users SET failed_attempts = 0,
                    locked_until = NULL WHERE id = ?"
                );
                $reset->execute([$user['id']]);

                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];

                header("Location: form.php");
                exit;

            } else {

                $attempts = $user['failed_attempts'] + 1;

                if ($attempts >= 5) {

                    $lock = $pdo->prepare(
                        "UPDATE users SET failed_attempts = 3,
                        locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                        WHERE id = ?"
                    );

                    $lock->execute([$user['id']]);

                    $message = "Too many wrong attempts! Account blocked for 5 minutes.";

                } else {

                    $update = $pdo->prepare(
                        "UPDATE users SET failed_attempts = ?
                        WHERE id = ?"
                    );

                    $update->execute([$attempts, $user['id']]);

                    $remaining = 3 - $attempts;

                    $message = "Wrong password! You have $remaining attempts remaining.";
                }
            }
        }

    } else {
        $message = "Wrong email or password";
    }
}
?>

<h2>Login</h2>

<p style="color:red;">
    <?= htmlspecialchars($message) ?>
</p>

<form method="POST" action="index.php">

    <label>Email:</label>
    <input type="email" name="email" required>

    <br><br>

    <label>Password:</label>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit">Login</button>

</form>
