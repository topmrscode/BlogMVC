<?php


namespace WebFramework;

use \PDO;

class ORMArticle {

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
          self::$instance = new ORMArticle();
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

// ---------------------------CREATE ARTICLE GROUP ---------------------------------------------------------
  /**
   * Synchronize each managed models with the database.
   */
  public function createArticle($object) {
    $stmt = $this->db->prepare("INSERT INTO articles (id, title, header, content, category_id, author_id, image) VALUES (NULL, :title, :header, :content, :category_id, :author_id, :image)"); 
    $stmt->bindValue(":title", $object->getTitle(), PDO::PARAM_STR);
    $stmt->bindValue(':header', $object->getHeader(), PDO::PARAM_STR);
    $stmt->bindValue(':content', $object->getContent(), PDO::PARAM_STR);
    $stmt->bindValue(":category_id", $object->getCategoryId(), PDO::PARAM_STR); 
    $stmt->bindValue(":author_id", $object->getAuthor(), PDO::PARAM_STR); 
    $stmt->bindValue(":image", $object->getImage(), PDO::PARAM_STR);

    $stmt->execute();
    $article_id = $this->db->lastInsertId();
    if ($stmt->errorCode() == "00000") {
      return array("worked"=>true, "article_id"=>$article_id); // mon tableau associatif contient 2 clefs lune worked aui contient true et la deuxiemme article ai contient le resultat de ma requete
    } else if ($stmt->errorCode() == "23000") {
      return array("worked"=>false, "reason"=> ENTITY_ALREADY_EXIST);
    }
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }

  // ---------------------------DELETE AN ARTICLE ---------------------------------------------------------     
  public function deleteArticle($id){
    $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id LIMIT 1");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }
    return array("worked"=>true);
  }

  // ---------------------------MODIFY AN ARTICLE ---------------------------------------------------------     
  public function modifyArticle($id, $title, $header, $content, $category_id, $image){
    $stmt = $this->db->prepare("UPDATE articles SET title = :title, header = :header, category_id = :category_id, content = :content, image = :image WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $stmt->bindValue(":title", $title, PDO::PARAM_STR); 
    $stmt->bindValue(":header", $header, PDO::PARAM_STR); 
    $stmt->bindValue(":category_id", $category_id, PDO::PARAM_STR); 
    $stmt->bindValue(":content", $content, PDO::PARAM_STR); 
    $stmt->bindValue(":image", $image, PDO::PARAM_STR); 
    $res = $stmt->execute();
    var_dump($res);
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    return array("worked"=>true);
  }
    
  // ---------------------------LIST ALL ARTICLES---------------------------------------------------------     
  public function listArticles(){
    $stmt = $this->db->prepare("SELECT * FROM articles ORDER BY created_at DESC");
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }

    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array("worked"=>true, "articles"=>$array);
  }

  // ---------------------------GET AN ARTICLE BY ITS ID --------------------------------------------
  public function getArticleByID($id){
    $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id LIMIT 1");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    return array("worked"=>true, "articleById"=>$array);
  }

  // ---------------------------ASSOCIATE TAG USING TAGNAME --------------------------------------------
  // obj; on a ajouter dans notre table Article tag larticle_id et pour un meme article_id plusieurs tag_id en une seule et meme requete 
  public function associateTagByName($article_id, $tag_name){
    $stmt = $this->db->prepare("INSERT INTO articleTag (id, article_id, tag_id) 
    VALUES (NULL, :article_id, (SELECT id FROM tags WHERE title = :tag_title LIMIT 1))");
    $stmt->bindValue(":article_id", $article_id, PDO::PARAM_STR); 
    $stmt->bindValue(":tag_title", $tag_name, PDO::PARAM_STR); 

    $res = $stmt->execute();
    // var_dump($stmt->errorInfo());
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    return array("worked"=>true, "articleTagName"=>$array);
  }
// ---------------------------LISTER LES TAGS POUR CHQUE ARTICLE --------------------------------------------
  public function ListTagsForArticle($article_id) {
    $stmt = $this->db->prepare("SELECT title FROM tags WHERE id IN (SELECT tag_id FROM articleTag WHERE article_id = :article_id)");
    $stmt->bindValue(":article_id", $article_id, PDO::PARAM_STR); 
    $res = $stmt->execute();
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }
    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array("worked"=>true, "tags"=>$array);
  }

  public function cleanTags($article_id) {
    $stmt = $this->db->prepare("DELETE FROM articleTag WHERE article_id = :article_id");
    $stmt->bindValue(":article_id", $article_id, PDO::PARAM_STR); 
    $res = $stmt->execute();

    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    return array("worked"=>true);
  }
}

