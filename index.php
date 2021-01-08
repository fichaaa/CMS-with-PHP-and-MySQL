<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

// $articles = Articles::getAll($conn);

$paginator = new Paginator($_GET['page'] ?? 1, 3 , Articles::getTotal($conn));

$articles = Articles::getByPage($conn, $paginator->limit, $paginator->offset);




?>

<?php require 'includes/header.php' ?>

<h3>Articles</h3>

<?php require 'includes/nav.php'; ?>

<?php if(!$articles): ?>
  <p>There is no articles to show</p>
<?php else: ?>
  <ul>
  <?php foreach($articles as $article):?>
    <li>
      <h5><a href="article.php?id=<?=$article['id']?>"><?=htmlspecialchars($article['title'])?></a></h5>
      <?php if($article['category_names']) : ?>
        <p>Categories:
          <?php foreach($article['category_names'] as $name) : ?>
            <?= htmlspecialchars($name); ?>
          <?php endforeach; ?>
        </p>
      <?php endif; ?>
   
      <p><?=htmlspecialchars($article['content'])?></p>
      <small><?=htmlspecialchars($article['published_at'])?></small>
    </li>
  <?php endforeach; ?>
  </ul>
  <nav aria-label="Page navigation example">
  <ul class="pagination">
  <li class="page-item <?php if(!$paginator->previous):?> disabled <?php endif;?>"><a class="page-link" href="/?page=<?=$paginator->previous ?>">Previous</a></li>
    <li class="page-item <?php if(!$paginator->next):?> disabled <?php endif;?>"><a class="page-link" href="/?page=<?=$paginator->next ?>">Next</a></li>
  </ul>
</nav>

  <?php endif; ?>
<?php require 'includes/footer.php' ?>