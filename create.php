<?php
session_start();
include '..db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ..login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Verificar se o arquivo foi enviado
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $image_name = time() . "_" . $image['name'];
        move_uploaded_file($image['tmp_name'], "../assets/images/$image_name");
    }

    $stmt = $pdo->prepare("INSERT INTO news (title, content, image, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $content, $image_name, $_SESSION['user_id']]);

    header('Location: index.php');
}
?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Título</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Conteúdo</label>
    <textarea id="content" name="content" required></textarea>

    <label for="image">Imagem</label>
    <input type="file" id="image" name="image" required>

    <button type="submit">Publicar Notícia</button>
</form>
