<?php
/**
 * Articles from database
 */
class Articles{

  public $id;
  public $title;
  public $content;
  public $published_at;
  public $image_file;
  public $errors = [];
  
  /**
   * Get all articles from database
   * 
   * @param object $conn Connection to the database
   * 
   * @return array An associative array of all articles
   */
  public static function getAll($conn)
  {
    $sql = "SELECT * FROM articles ORDER BY id DESC";

    $result = $conn->query($sql);

    return $result->fetchAll(PDO::FETCH_ASSOC);
  }
  /**
   * Get total number of articles in database
   * 
   * @param object $conn Connection to the database
   * 
   * @return integer The total number of records
   */
  public static function getTotal($conn)
  {
    return $conn->query('SELECT COUNT(*) FROM articles')->fetchColumn();
  }

  /**
   * Get limited articles at selected page
   * 
   * @param object $conn Connection to the database
   * @param integer $limit Number of articles to show
   * @param integer $offset number Articles to skip
   * 
   * @return array An associative array of results
   */

  public static function getByPage($conn, $limit, $offset)
  {
    $sql = "SELECT a.*, category.name AS category_name
    FROM (SELECT *
    FROM articles
    ORDER BY published_at
    LIMIT :limit
    OFFSET :offset) AS a
    LEFT JOIN article_category
    ON a.id = article_category.article_id
    LEFT JOIN category
    ON article_category.category_id = category.id";

    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
 
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $articles = [];

    $previous_id = null;

    foreach($results as $row) {
      $article_id = $row['id'];

      if($article_id != $previous_id){
        $row['category_names'] = [];

        $articles[$article_id] = $row;

      }

      $articles[$article_id]['category_names'][] = $row['category_name'];

      $previous_id = $article_id;
    } 

    return $articles;
  }

  /**
   * Get article with unique ID
   * 
   * @param object $conn Connection to the database
   * @param integer $id Unique id of article
   * 
   * 
   */
  public static function getByID($conn,$id)
  {
    $sql = "SELECT * FROM articles WHERE id = :id";

    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Articles');

    if($stmt->execute()){
      return $stmt->fetch();
    }
  }

  /**
   * Get category of selected article
   * 
   * @param object $conn Connection to the database
   * @return array An associative array of article id's with categories
   */
  public function getCategory($conn) {
     $sql = "SELECT category.* FROM category JOIN article_category ON category.id = article_category.category_id WHERE article_category.article_id = :id";

     $stmt = $conn->prepare($sql);

     $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
     $stmt->execute();

     return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

  /**
   * Get articles with categories
   * 
   * @param $conn Connection to the database
   * @param $id Unique ID of data
   * 
   * @return array of articles with categories
   */

  public static function getWithCategories($conn, $id)
  {
    $sql = "SELECT articles.*, category.name AS category_name FROM articles LEFT JOIN article_category ON articles.id = article_category.article_id LEFT JOIN category ON article_category.category_id = category.id WHERE articles.id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Set the article categories
   * 
   * @param object $conn Connection to the database
   * @param array $ids Category IDs
   * 
   * @return void
   */
  public function setCategories($conn, $ids)
  {
    if ($ids) {
      $sql = "INSERT IGNORE INTO article_category(article_id, category_id) VALUES";

      $values = [];
      foreach ($ids as $id) {
        $values[] = "({$this->id}, ?)";
      }

      $sql .= implode(", ", $values);

      $stmt = $conn->prepare($sql);

 
      foreach($ids as $i => $id) {
        $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
      }

      $stmt->execute();
    }
    $sql = "DELETE FROM article_category WHERE article_id = {$this->id}";

    if ($ids) {
      $placeholders = array_fill(0, count($ids), '?');
      $sql .= " AND category_id NOT IN(" . implode(", ", $placeholders) . ")";
    }

    $stmt = $conn->prepare($sql);

    foreach($ids as $i => $id) {
      $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
    }

    $stmt->execute();

  }

  /**
   * Update database input
   * 
   * @param object $conn Connection to the database
   * 
   * @return boolean true if executed, false otherwise
   */
  public function update($conn)
  {
    if($this->validate()){
      $sql = "UPDATE articles SET title = :title, content = :content, published_at = :published_at WHERE id = :id";

      $stmt = $conn->prepare($sql);

      $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
      $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
      $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
      $stmt->bindValue(':published_at', $this->published_at, PDO::PARAM_STR);

      return $stmt->execute();
    } else {
      return false;
    }
    
  }

  /**
   * Insert a record into database
   * 
   * @param object $conn Connection to the database;
   * 
   * @return boolean true if executed, null otherwise
   */

  public function create($conn)
  { 
    if($this->validate()){
      $sql = "INSERT INTO articles(title, content, published_at) VALUES (:title, :content, :published_at)";

      $stmt = $conn->prepare($sql);

      $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
      $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
      $stmt->bindValue(':published_at', $this->published_at, PDO::PARAM_STR);

      if($stmt->execute()){
        $this->id = $conn->lastInsertId();
        return true;
      }
    }
  }

  /**
   * Delete article from database
   * 
   * @param object $conn Connection to the database
   * 
   * @return boolean True if executed false otherwise;
   */
  public function delete($conn)
  {
    $sql = "DELETE FROM articles WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
    
    return $stmt->execute();
  }

  /**
   * Validate inputs
   * 
   * @return boolean True if no errors, false otherwise
   */
  protected function validate()
  {
    if($this->title == ''){
      $this->errors[] = 'Title is mandatory';
    }
    if($this->content == ''){
      $this->errors[] = 'Content is mandatory';
    }
    if($this->published_at == ''){
      $this->errors[] = 'Date and time is mandatory';
    }

    return empty($this->errors);
  }

  /**
   * Connect image file to database article
   * 
   * @param object $conn Connection to the database
   * @param string $filename Name of the file
   * 
   * @return boolean True if executed, false otherwise
   */
  public function setImageFile($conn, $filename) {
    $sql = "UPDATE articles SET image_file = :image_file WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->bindValue(':image_file', $filename, PDO::PARAM_STR);

    return $stmt->execute();
  }
}

