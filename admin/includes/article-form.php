<?php if(!empty($article->errors)): ?>
  <ul>
  <?php foreach($article->errors as $error): ?>
    <li><?=$error ?></li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>
<form class="form-container" method="post">
  <div class="form-group ">
    <label class=" col-form-label" for="title">Title</label>
    <input class="form-control " type="text" name="title" id="title" value= "<?= htmlspecialchars($article->title) ?>" placeholder="Enter article title">
  </div>
  <div class="form-group ">
    <label class=" col-form-label" for="content">Content</label>
    <textarea class="form-control" id="content" name="content" rows="10" placeholder="Enter article content"><?= htmlspecialchars($article->content) ?></textarea>
  </div>
  <div class="form-group ">
    <label class=" col-form-label" for="time">Date and Time</label>
    <input type="datetime-local" class="form-control " id="time" name="published_at" cols="20" value=<?= htmlspecialchars($article->published_at) ?>>
  </div>
  <fieldset>
    <legend>Categories</legend>
    <?php foreach($categories as $category): ?>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="category[]" id="<?= $category['id'] ?>" <?php if(in_array($category['id'],$category_ids)) echo 'checked'?> value="<?= $category['id'] ?>">
      <label class="form-check-label" for="<?= $category['id'] ?>"><?= $category['name'] ?> </label>
    </div>
    <?php endforeach; ?>
  </fieldset>
  <button class="btn btn-primary">Submit</button>
</form>