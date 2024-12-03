<?php
session_start();
include('db.php');

// Verifica se o cookie 'botao' está definido, caso contrário, inicializa como 'branco'
$botao = isset($_COOKIE['botao']) ? $_COOKIE['botao'] : 'white';

// Verifica se o formulário foi enviado e altera a cor
if (isset($_POST["botao"])) {
    // Alterna entre 'branco' e 'preto' ao clicar no botão
    $botao = ($botao == 'white') ? 'black' : 'white';
    // Define o cookie por 2 minutos
    setcookie('botao', $botao, time() + 120, '/');
    // Atualiza a página para refletir a mudança de cor

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Notícias</title>
</head>
<body >
    <header>
  
        <nav class='flex-container'>
     
            <a href="create.php">Criar Notícia</a>

                <a href="cadastro.php">criar conta</a>

            <?php if (isset($_SESSION['id_user'])): ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
        <style>
        body {
            background-color: <?php echo $botao; ?>;
            color: <?php echo $botao == 'black' ? 'white' : 'black'; ?>;
            transition: background-color 0.5s, color 0.5s;
        }
        .botao {
            border-radius: 10px;
            background-color: <?php echo $botao == 'black' ? 'white' : 'black'; ?> ;
            color: <?php echo $botao; ?>;
            align: 'center'
        }
        a {
            color: #000000;
            text-decoration: none;
        }

        html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}
.flex-container{
display: flex;
justify-content: space-around;
}

.canto-direito {
    position: absolute;
    right: 10px;
    bottom: 10px;
}

.de{
    text-align: center;
    margin: 0;
}
    </style>
</head>
<body>
<hr>
<h1 class='de'>
<a href="index.php">Portal do povo<a>
</h1>
<hr>
<form action="" method="post">
     <input class="botao" type="submit" value="Clicar" name="botao">
</form>
    </header>
