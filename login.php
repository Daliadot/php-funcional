<?php
session_start();
include ('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        if (isset($_POST['remember'])) {
            setcookie('username', $username, time() + (86400 * 30), "/"); // 30 dias
        }

        header("Location: ../index.php");
    } else {
        echo "Usuário ou senha inválidos!";
    }
}
?>

<form method="POST">
    <label for="username">Usuário</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Senha</label>
    <input type="password" id="password" name="password" required>
    
    <label>
        <input type="checkbox" name="remember"> Lembrar-me
    </label>
    
    <button type="submit">Login</button>
</form>
