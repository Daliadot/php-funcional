<?php
session_start();
include '../db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os dados do formulário
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Processa o upload de imagem
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_name = time() . "_" . basename($image['name']);
        $upload_path = "../assets/images/$image_name";

        // Faz o upload da imagem
        if (move_uploaded_file($image['tmp_name'], $upload_path)) {
            // Sucesso no upload da imagem
        } else {
            echo "Erro ao fazer upload da imagem.";
            exit;
        }
    }

    // Insere a notícia no banco de dados
    $stmt = $pdo->prepare("INSERT INTO news (title, content, image, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $content, $image_name, $_SESSION['user_id']]);

    // Redireciona o usuário para a página principal após publicar
    header('Location: ../index.php');
    exit;
}

?>

<?php include '../header.php'; ?>

<h1>Publicar uma Notícia</h1>

<!-- Formulário para publicação de notícias -->
<form method="POST" enctype="multipart/form-data">
    <div>
        <label for="title">Título</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div>
        <label for="content">Conteúdo</label>
        <textarea id="content" name="content" rows="5" required></textarea>
    </div>
    <div>
        <label for="image">Imagem</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>
    <div>
        <button type="submit">Publicar Notícia</button>
    </div>
</form>

<?php include '../includes/footer.php'; ?>
