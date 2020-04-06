<?php


namespace WebFramework;

use \PDO;

class ORMComment {

  private $db;

  private static $instance = null;

  /**
   * Private constructor so nobody else can instantiate it.
   */
  private function __construct()
  {
  }

  /**
   * Retrieve the static instance of the ORM.
   *
   * @return ORM - Instance of the ORM
   */
  public static function getInstance()
  {
      if (is_null(self::$instance)) {
          self::$instance = new ORMComment();
      }

      return self::$instance;
  }
//----------------------------- CONNECT TO A DATABASE  --------------------------------------
  /**
   * @param array $config - Database configuration
   * @return PDO - Instance of PDO used to interact with the connected DB.
   */
  public function connect(array $config)
  {
    try {
      $this->db = new PDO(
        "{$config['driver']}:host={$config['host']};dbname={$config['dbname']}",
        $config['username'],
        $config['password'],
        $config['options']
      );
      return $this->db;
      
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
  }

// ---------------------------CREATE COMMENT --------------------------------------------

public function createComment($object) {
    $stmt = $this->db->prepare("INSERT INTO comments (id, author_id, article_id, content) VALUES (NULL, :author_id, :article_id, :content)"); 
    $stmt->bindValue(":author_id", $object->getAuthorId(), PDO::PARAM_STR); 
    $stmt->bindValue(':article_id', $object->getArticleId(), PDO::PARAM_STR);
    $stmt->bindValue(':content', $object->getContentComment(), PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    return array("worked"=>true);
  }

  // ---------------------------LIST ALL COMMENTS---------------------------------------------------------     
  public function listComments(){
    $stmt = $this->db->prepare("SELECT * FROM comments");
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }

    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array("worked"=>true, "comments"=>$array);
  }

  // ---------------------------LIST COMMENT BY ARTICLE ID--------------------------------------------------------     
  public function listCommentsByArticleId($id){
    $stmt = $this->db->prepare("SELECT * FROM comments WHERE article_id = :article_id  ");
    $stmt->bindValue(":article_id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
  
    if($stmt->rowCount() == 0) {
      return array("worked"=>true, "comments"=> array());
    }
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }

    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array("worked"=>true, "comments"=>$array);
  }

    
  // ---------------------------DELETE A COMMENT ---------------------------------------------------------     
  public function deleteComment($id){
    $stmt = $this->db->prepare("DELETE FROM Comments WHERE id = :id LIMIT 1");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    // if($stmt->rowCount() == 0) {
    //   return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    // }
    return array("worked"=>true);
  }

  // ---------------------------MODIFY A comment ---------------------------------------------------------     
  public function modifyComment($id, $content){
    $stmt = $this->db->prepare("UPDATE Comments SET content = :content WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $stmt->bindValue(":content", $content, PDO::PARAM_STR); 
    $res = $stmt->execute();
    console.log($res);

    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    // if($stmt->rowCount() == 0) {
    //   return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    // }
    return array("worked"=>true);
  }

  // ---------------------------DELETE A COMMENT for ARTICLE ---------------------------------------------------------     
  public function deleteCommentForArticle($id){
    $stmt = $this->db->prepare("DELETE FROM Comments WHERE article_id = :article_id");
    $stmt->bindValue(":article_id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    //--------------------PAS BESOIN CAR DELETE RENVOIR RIEN --------------
    // if($stmt->rowCount() == 0) {
    //   return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    // } 
    //---------------------
    return array("worked"=>true);
  }

}
