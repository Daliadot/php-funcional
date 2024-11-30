<?php
session_start();
include ('db.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Valida se as senhas coincidem
    if ($password !== $confirm_password) {
        $error = "As senhas não coincidem!";
    } else {
        // Verifica se o nome de usuário já está registrado
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            $error = "Nome de usuário já existe!";
        } else {
            // Criptografa a senha antes de armazená-la
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insere o novo usuário no banco de dados
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            // Redireciona o usuário para a página de login
            header("Location: login.php");
            exit;
        }
    }
}

?>

<?php include 'header.php'; ?>

<h1>Cadastrar Novo Usuário</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<!-- Formulário de cadastro -->
<form method="POST">
    <div>
        <label for="username">Nome de Usuário</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Confirmar Senha</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <button type="submit">Cadastrar</button>
    </div>
</form>

<?php include 'footer.php'; ?>
