<?php

require '../includes/init.php';

if(!Auth::isLoggedIn()){
  die('You are not authorised for this page');
}

$article = new Articles();

$category_ids = [];

$conn = require '../includes/db.php';

$categories = Categories::getAll($conn);

if($_SERVER['REQUEST_METHOD'] === "POST"){

  $article->title = $_POST['title'];
  $article->content = $_POST['content'];
  $article->published_at = $_POST['published_at'];

  $category_ids = $_POST['category'] ?? [];
  
  if($article->create($conn)){

    $article->setCategories($conn, $category_ids);
    
    Url::redirect("/admin/article.php?id=$article->id");
  }
}
?>


<?php require '../includes/header.php' ?>
<h3>Enter a new article </h3>

<?php require '../includes/nav.php'; ?>
<?php require 'includes/article-form.php'?>


<?php require '../includes/footer.php' ?>