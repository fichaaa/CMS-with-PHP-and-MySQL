<?php

require '../includes/init.php';

if(!Auth::isLoggedIn()){
  die('You are not authorised for this page');
}

$conn = require '../includes/db.php';

$articles = Articles::getAll($conn);

?>

<?php require '../includes/header.php' ?>

<h3>Articles</h3>
<?php require '../includes/nav.php'; ?>
<p><a href="new-article.php">Create new article</a></p>
<?php if(!$articles): ?>
  <p>There is no articles to show</p>
<?php else: ?>
  <table class="table">

  </thead>
    <thead>
    <tr>
      <th scope="col">Article name</th>
      <th scope="col">Published</th>
    </tr>
    <tbody>
    <?php foreach($articles as $article):?>
      <tr>
        <td>
          <a href="article.php?id=<?=$article['id']?>"><?=htmlspecialchars($article['title'])?></a>
        </td>
        <td>
          <small><?=htmlspecialchars($article['published_at'])?></small>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
<?php require '../includes/footer.php' ?>