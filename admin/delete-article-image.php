<?php
require '../includes/init.php';


if( ! Auth::isLoggedIn()){
  die('You are not authorised to access this page.');
}

if(isset($_GET['id'])){

  $conn = require '../includes/db.php';

  $article = Articles::getByID($conn, $_GET['id']);


  if(!$article){
    die('article not found');
  }
} else {
  die('id not supplied, article not found');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

  if($article->setImageFile($conn, null)) {
    $previous_image = $article->image_file;

    if($previous_image) {
      unlink("../uploads/$previous_image");
    }
    Url::redirect("/admin/article.php?id={$article->id}");
  }

}

?>

<?php require '../includes/header.php' ?>
<?php require '../includes/nav.php'; ?>

<form method="post">
  <h3>Are you sure you want to delete image from article: <br> <span class="font-weight-bold"><?=htmlspecialchars($article->title)?></span></h3>

  <button type="submit" class="btn btn-danger">Delete</button>

  <a href="edit-article-image.php?id=<?= $article->id ?>" class="btn btn-secondary">Cancel</a>
</form>


<?php require '../includes/footer.php' ?>