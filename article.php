<?php

require 'includes/init.php';

if(isset($_GET['id'])) {
  
  $conn = require 'includes/db.php';

  $article = Articles::getWithCategories($conn, $_GET['id']);

  if(!$article){
    die('There is no article with that ID');
  }
} else {
  die('Not valid URL!');
}

?>


<?php require 'includes/header.php' ?>
<?php require 'includes/nav.php'; ?>
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

<?php require 'includes/footer.php' ?>
