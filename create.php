<?php
session_start();
include('db.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']); // Sanitiza o título
    $conteudo = trim($_POST['conteudo']); // Sanitiza o conteúdo

    // Inicializa o nome da imagem como vazio
    $imagem_nome = "";

    // Verifica se a imagem foi enviada e processa o upload
    if (isset($_FILES['imagem'])) {
        $imagem = $_FILES['imagem'];

        // Verifica o erro de upload
        if ($imagem['error'] !== UPLOAD_ERR_OK) {
            die("Erro ao fazer upload da imagem. Código de erro: " . $imagem['error']);
        }

        // Valida o tipo de arquivo da imagem (aceitando apenas imagens)
        $imagem_tipo = mime_content_type($imagem['tmp_name']);
        if (!in_array($imagem_tipo, ['image/jpeg', 'image/png', 'image/gif'])) {
            die("Tipo de arquivo inválido. Apenas imagens JPEG, PNG ou GIF são permitidas.");
        }

        // Limita o tamanho do arquivo (por exemplo, 2MB)
        $imagem_tamanho = $imagem['size'];
        if ($imagem_tamanho > 2 * 1024 * 1024) { // 2MB
            die("O tamanho da imagem excede o limite de 2MB.");
        }

        // Gera um nome único para a imagem
        $imagem_nome = time() . "_" . basename($imagem['name']); 
        
        // Caminho absoluto para o diretório de imagens
        $upload_dir = __DIR__ . "/../assets/images/";
        
        // Verifica se o diretório de destino existe, caso contrário cria
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                die("Falha ao criar o diretório de imagens.");
            }
        }

        // Caminho completo do arquivo para o upload
        $upload_path = $upload_dir . $imagem_nome;

        // Move o arquivo para o diretório de destino
        if (!move_uploaded_file($imagem['tmp_name'], $upload_path)) {
            die("Erro ao fazer upload da imagem. Verifique as permissões do diretório.");
        }
    }

    // Insere os dados na tabela `news` com base no `id` do usuário
    $query = "INSERT INTO news (id_user, titulo, conteúdo, imagem) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Associa os parâmetros e executa a consulta
    $stmt->bind_param("isss", $_SESSION['id'], $titulo, $conteudo, $imagem_nome);
    if ($stmt->execute()) {
        // Redireciona para a página inicial após inserir
        header('Location: index.php');
        exit;
    } else {
        die("Erro ao inserir os dados: " . $stmt->error);
    }

    $stmt->close();
}
?>

<!-- Formulário para criar uma notícia -->
<form method="POST" enctype="multipart/form-data">
    <label for="titulo">Título</label>
    <input type="text" id="titulo" name="titulo" required placeholder="Digite o título da notícia">

    <label for="conteudo">Conteúdo</label>
    <textarea id="conteudo" name="conteudo" required placeholder="Digite o conteúdo da notícia"></textarea>

    <label for="imagem">Imagem</label>
    <input type="file" id="imagem" name="imagem" required>

    <button type="submit">Publicar</button>
</form>
