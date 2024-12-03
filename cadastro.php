<?php
include('db.php');

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
        // Escapa o valor de username para evitar SQL Injection
        $username = $conn->real_escape_string($username);
        
        // Verifica se o nome de usuário já está registrado
        $query = ("SELECT * FROM `user` WHERE username = '$username'");
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $error = "Nome de usuário já existe!";
        } else {
            // Criptografa a senha antes de armazená-la
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insere o novo usuário no banco de dados
            $query = "INSERT INTO user (username, password) VALUES ('$username', '$hashed_password')";
            if ($conn->query($query)) {
                // Redireciona o usuário para a página de login
                header("Location: login.php");
                exit;
            } else {
                $error = "Erro ao registrar o usuário: " . $conn->error;
            }
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
