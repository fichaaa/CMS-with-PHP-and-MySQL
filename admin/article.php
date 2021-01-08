<?php

require '../includes/init.php';

if(!Auth::isLoggedIn()){
  die('You are not authorised for this page');
}

if(isset($_GET['id'])) {
  
  $conn = require '../includes/db.php';

  $article = Articles::getWithCategories($conn, $_GET['id']);

  if(!$article){
    die('There is no article with that ID');
  }
} else {
  die('Not valid URL!');
}

?>


<?php require '../includes/header.php' ?>

<?php require '../includes/nav.php'; ?>

<article>
  <legend>Article</legend>
  <?php if($article[0]['image_file']): ?>
    <img src="../uploads/<?= $article[0]['image_file'] ?>"  class="img-fluid" alt="Responsive image" Width="400px"><br>
  <?php endif; ?>

  <?php if($article[0]['category_name']): ?>
    <p> Categories:
    <?php foreach($article as $a): ?>
      <?= $a['category_name'] ?>
    <?php endforeach; ?>
    </p>
  <?php endif ?>
  <h5><?=htmlspecialchars($article[0]['title'])?></h5>
  <p><?=htmlspecialchars($article[0]['content'])?></p>
  <small><?=htmlspecialchars($article[0]['published_at'])?></small>

</article>

<div class="mt-3">
  <a href="edit-article.php?id=<?=$article[0]['id'] ?>" class="btn btn-secondary">Edit article</a>
  <a href="edit-article-image.php?id=<?=$article[0]['id'] ?>" class="btn btn-info">Edit article image</a>
  <a href="delete-article.php?id=<?=$article[0]['id'] ?>" class="btn btn-danger delete">Delete article</a>
</div>

<?php require '../includes/footer.php' ?>
