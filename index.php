<?php
include ('header.php');
include ('db.php');

$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$news = $stmt->fetchAll();


foreach ($news as $news_item):?>
  
    <div>
        <h2><?= htmlspecialchars($news_item['title']); ?></h2>
        <img src="assets/images/<?= htmlspecialchars($news_item['image']); ?>" alt="Imagem da notícia" width="300">
        <p><?= nl2br(htmlspecialchars($news_item['content'])); ?></p>
        <p><small>Publicado em <?= $news_item['created_at']; ?></small></p>
    </div>
<?php endforeach; ?>

<?php include 'footer.php'; ?>

