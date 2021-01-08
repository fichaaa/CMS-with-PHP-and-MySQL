<?php
/**
 * Categories of articles
 * 
 * 
 */
class Categories{
    /**
   * Get all categories from database
   * 
   * @param object $conn Connection to the database
   * 
   * @return array An associative array of all categories
   */
  public static function getAll($conn)
  {
    $sql = "SELECT * FROM category ORDER BY name";

    $result = $conn->query($sql);

    return $result->fetchAll(PDO::FETCH_ASSOC);
  }
}