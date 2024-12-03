<?php
include('header.php');
include('db.php');

// Consulta ao banco de dados com MySQLi
$query = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($query); // Executa a consulta usando MySQLi

// Verifica se há notícias
if ($result->num_rows > 0): 
    // Busca todos os itens de notícias
    $news = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($news as $news_item): ?>
        <div>
            <h2><?= htmlspecialchars($news_item['titulo']); ?></h2>

            <?php 
            // Gerando o caminho da imagem
            $image_path = "assets/images/" . htmlspecialchars($news_item['imagem']);
            
            // Depuração: Exibe o caminho gerado da imagem
            echo "<p>Caminho da imagem: " . $image_path . "</p>"; 

            // Verifica se a imagem existe no diretório
            if (file_exists($image_path)): ?>
                <img src="<?= $image_path ?>" alt="Imagem da notícia" width="300">
            <?php else: ?>
                <img src="assets/images/default-image.jpg" alt="Imagem padrão" width="300">
            <?php endif; ?>

            <p><?= nl2br(htmlspecialchars($news_item['conteúdo'])); ?></p>

            <?php 
            // Formata a data para um formato legível
            $created_at = new DateTime($news_item['created_at']);
            $formatted_date = $created_at->format('d/m/Y H:i:s');
            ?>

            <p><small>Publicado em <?= $formatted_date; ?></small></p>
        </div>
    <?php endforeach;
else: ?>
    <p>Nenhuma notícia encontrada.</p>
<?php endif; ?>

<?php include 'footer.php'; ?>
