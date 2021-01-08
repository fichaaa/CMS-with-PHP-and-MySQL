<?php
/**
 * Get User
 */
class User{

  public $id;
  public $username;
  public $password;

  /**
   * Authenticate user from database
   * 
   * @param object $conn Connection to the database
   * @param string $username Username
   * @param string $password Password
   * 
   * @return boolean true if verified, false otherwise
   */
  public static function authenticate($conn, $username, $password)
  {
    $sql = "SELECT * FROM users WHERE username = :username";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

    $stmt->execute();

    if($user = $stmt->fetch()) {
      return password_verify($password,$user->password);
    }


    // if($result){
    // if(password_verify($password, $result['password'])){
    //   $_SESSION['is_logged_in'] = true;
    // }
  
    // }
  }
}