<?php

require '../includes/init.php';

if(!Auth::isLoggedIn()){
  die('You are not authorised for this page');
}

if(isset($_GET['id'])){

  $conn = require '../includes/db.php';

  $article = Articles::getByID($conn, $_GET['id']);

  if(!$article){
    die('There is no article with that ID');
  }
} else{
  die('Not valid URL!');
}

$category_ids = array_column($article->getCategory($conn), 'id');

$categories = Categories::getAll($conn);


if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $article->title = $_POST['title'];
  $article->content = $_POST['content'];
  $article->published_at = $_POST['published_at'];

  $category_ids = $_POST['category'] ?? [];
  

  if($article->update($conn)){

    $article->setCategories($conn, $category_ids);

    Url::redirect("/admin/article.php?id=$article->id");
  }
}


?>

<?php require '../includes/header.php' ?>
<h3>Edit an article</h3>

<?php require '../includes/nav.php'; ?>
<?php require 'includes/article-form.php' ?>

<?php require '../includes/footer.php' ?>