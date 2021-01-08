<?php

require '../includes/init.php';

if(!Auth::isLoggedIn()){
  die('You are not authorised for this page');
}

if(isset($_GET['id'])) {

  $conn = require '../includes/db.php';

  $article = Articles::getByID($conn, $_GET['id']);

  if(!$article){
    die('There is no article with that ID');
  }
} else {
  die('Not valid URL!');
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  if($article->delete($conn)){
    Url::redirect('/');
  }
}

?>

<?php require '../includes/header.php' ?>
<?php require '../includes/nav.php'; ?>

<form method="post">
  <p>Are you sure you want delete  Article: <span class="font-weight-bold"><?= $article->title ?></span> ?</p>
  <button type="submit" class="btn btn-danger">Delete article</button>
  <a href="article.php?id=<?=$article->id ?>" class="btn btn-secondary">Cancel</a>
</form>


<?php require '../includes/footer.php' ?>