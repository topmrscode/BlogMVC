<?php


namespace WebFramework;

use \PDO;

class ORMCategory {

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
          self::$instance = new ORMCategory();
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

  //----------------------------- LIST ALL CATAGEORIES --------------------------------------

  public function listCategories(){
    $stmt = $this->db->prepare("SELECT * FROM categories");
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }

    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array("worked"=>true, "categories"=>$array);
  }

  //----------------------------- CREATE A CATEGORY  --------------------------------------

  public function CreateCategory($object){
    $stmt = $this->db->prepare("INSERT INTO categories (id, title) VALUES (NULL, :title)"); 
    $stmt->bindValue(":title", $object->getTitle(), PDO::PARAM_STR);

    $stmt->execute();
    if ($stmt->errorCode() == "00000") {
      return array("worked"=>true);
    } else if ($stmt->errorCode() == "23000") {
      return array("worked"=>false, "reason"=> ENTITY_ALREADY_EXIST);
    }
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }

// ---------------------------GET A CATEGORY BY ITS ID --------------------------------------------
public function getCategoryByID($id){
    $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    return array("worked"=>true, "category"=>$array);
  }

// ---------------------------MODIFY A CATEGORY --------------------------------------------------------     
public function modifyCategory($id, $title){
  $stmt = $this->db->prepare("UPDATE categories SET title = :title WHERE id = :id");
  $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
  $stmt->bindValue(":title", $title, PDO::PARAM_STR); 
  $res = $stmt->execute();

  if ($stmt->errorCode() != "00000") {
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }
  if($stmt->rowCount() == 0) {
    return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
  }
  return array("worked"=>true);
}  
// ---------------------------DELETE A CATEGORY ---------------------------------------------------------     
public function deleteCategory($id){
  $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id LIMIT 1");
  $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
  $res = $stmt->execute();

  if ($stmt->errorCode() != "00000") {
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }
  return array("worked"=>true);
}
}