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



  try {
    if(empty($_FILES)){
      throw new Exception('Invalid upload');
    };
    switch($_FILES['image']['error']){
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_INI_SIZE:
        throw new Exception('File is too large (from the server).');
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new Exception('You have to upload a file.');
        break;
      default:
        throw new Exception('An error occured.');
    }
    if($_FILES['image']['size'] > 1000000) {
      throw new Exception('File is too large. Upload a file less than 1MB');
    }

    $mime_types = ['image/jpeg', 'image/png', 'image/gif'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $mime_type = finfo_file($finfo,$_FILES['image']['tmp_name']);

    if ( ! in_array($mime_type,$mime_types)) {
      throw new Exception ('Invalid file type.');
    }

    $pathinfo = pathinfo($_FILES['image']['name']);
    
    $base = $pathinfo['filename'];

    $base = preg_replace('/[^A-Za-z0-9_-]/', '_', $base);

    $base = mb_substr($base, 0, 200);
  
    $filename = $base . '.' . $pathinfo['extension']; 

    $destination = "../uploads/$filename";

    $i = 1;
  
    while(file_exists($destination)) {
      $filename  = $base . "_$i." . $pathinfo['extension'];
      $destination = "../uploads/$filename";
      $i++;
    }

    if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {

      $previous_image = $article->image_file;

      var_dump($previous_image);

      if($article->setImageFile($conn, $filename)) {
        if($previous_image) {
          unlink("../uploads/$previous_image");
        }
        Url::redirect("/admin/article.php?id={$article->id}");
      
      }

    } else {
      throw new Exception('Unable to move uploaded file.');
    }


  } catch (Exception $e) {
    echo $e->getMessage();
  }
}

?>

<?php require '../includes/header.php'; ?>
<?php require '../includes/nav.php'; ?>
<h3> Upload a image </h3>
<form method="post" enctype="multipart/form-data">
<div class="form-group" >
<?php if($article->image_file): ?>
  <img src="../uploads/<?= $article->image_file ?>"  class="img-fluid" alt="Responsive image" Width="400px"><br>
<?php endif; ?>
  <input type="file" id="image" name="image"><br>
  <button type="submit" class="btn btn-primary mt-3">Upload</button>
  <a class="btn btn-secondary mt-3" href="edit-article.php?id=<?= $article->id ?>">Cancel </a>
<?php if($article->image_file): ?>
  <a class="btn btn-danger mt-3 delete" href="delete-article-image.php?id=<?= $article->id ?>">Delete </a>
<?php endif; ?>



</div>

</form>
<?php require '../includes/footer.php'; ?>