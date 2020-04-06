<?php


namespace WebFramework;

use \PDO;
define("ENTITY_ALREADY_EXIST", "0");
define("INTERNAL_SERVER_ERROR", "1");
define("RESSOURCE_NOT_FOUND_ERROR", "2");


define("GROUP_USER", "user");
define("GROUP_WRITER", "writer");
define("GROUP_ADMIN", "admin");


class ORMUser {

  private $db;

  private static $instance = null;

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
          self::$instance = new ORMUser();
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
      // $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->db;
      
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
  }


  /**
   * Make a model instance managed by the ORM.
   *
   * @param Model $object - Object that will be persisted.
   */
  //------------------------------CREATE AN USER REGISTRATION  --------------------------
  
  // Phase 1 : creation user
  public function persist($object)
  {
    $stmt = $this->db->prepare("INSERT INTO users (id, username, password, email, usergroup, is_banned, is_activated) VALUES (NULL, :username, :password, :email, :group, :isBanned, :isActivated)"); 
    $stmt->bindValue(":username", $object->getUsername(), PDO::PARAM_STR);
    $stmt->bindValue(':email', $object->getEmail(), PDO::PARAM_STR);
    $stmt->bindValue(":password", $object->getPassword(), PDO::PARAM_STR); 
    $stmt->bindValue(":group", $object->getGroup(), PDO::PARAM_STR);
    $stmt->bindValue(":isBanned", $object->isBanned(), PDO::PARAM_BOOL);
    $stmt->bindValue(":isActivated", $object->isActivated(), PDO::PARAM_BOOL);

    $stmt->execute();
    if ($stmt->errorCode() == "00000") {
      return array("worked"=>true);
    } else if ($stmt->errorCode() == "23000") {
      return array("worked"=>false, "reason"=> ENTITY_ALREADY_EXIST);
    }
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }
  //------------------------------EMAIL VALIDATION- ----------------------------------------------
  // Phase 1 : envoie email
  // inscription -> apres avoir clique sur le lien dans le mail, on passe le champs 'is_activated' en db a 1
  public function validateEmail($email)
  {
    $stmt = $this->db->prepare("UPDATE users SET is_activated = :activated WHERE email = :email");
    $stmt->bindValue(":activated", true, PDO::PARAM_BOOL); 
    $stmt->bindValue(":email", $email, PDO::PARAM_STR); 

    $stmt->execute();
    if ($stmt->errorCode() == "00000") {
      return array("worked"=>true);
    }
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }
//------------------------------DELETE ACCOUNT ----------------------------------------------
  
  public function delete($email)
  {
    $stmt = $this->db->prepare("DELETE FROM users WHERE email = :email LIMIT 1");
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $res = $stmt->execute();

    if ($stmt->errorCode() == "00000") {
      return array("worked"=>true);
    }
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }

  //------------------------------LOGIN-------------------------------------------------------
    // Phase 2 : connexion
  public function login($email)
  {
      $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE email = :email LIMIT 1");
      $stmt->bindValue(":email", $email, PDO::PARAM_STR); 
      $res = $stmt->execute();
    
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }

    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }

    $array = $stmt->fetch();
    $passwrd_in_db = $array["password"]; // recupere mon mdp de la db
    return array("worked"=>true, "password"=>$passwrd_in_db, "username"=> $array["username"],"email"=> $array["email"] );
  }
  // ------------------------------SELECTIONNER TOUTES LES DONNES DE MA DB --------------------------
  public function getCurrentUser($email){
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindValue(":email", $email, PDO::PARAM_STR); 
    $res = $stmt->execute();
  
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }

    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }

    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    return array("worked"=>true, "user"=>$array);
  }

  // ---------------------------RECUPERER TOUTES MES DONNES USERS POUR PHASE 3 ADMIN---------------------

    function listUsers(){
      $stmt = $this->db->prepare("SELECT * FROM users");
      $res = $stmt->execute();
    
      if ($stmt->errorCode() != "00000") {
        return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
      }
  
      if($stmt->rowCount() == 0) {
        return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
      }
  
      $array = $stmt->fetchAll(PDO::FETCH_ASSOC); // tout recuperer dans un tableau
      return array("worked"=>true, "users"=>$array); // me retourne un tableau avec tous ma table users
    }
// ---------------------------ACTIVATE AN USER ---------------------------------------------------------

// Cette fonction prend en parametre un id, l'id du user que tu veux activer + est ce que tu veux l'activer ou le desactiver
public function activateUser($id, $activate_status){
$stmt = $this->db->prepare("UPDATE users SET is_activated = :activated WHERE id = :id LIMIT 1");
        $stmt->bindValue(":activated", $activate_status, PDO::PARAM_BOOL); 
        $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
        $res = $stmt->execute();
      
        if ($stmt->errorCode() != "00000") {
          return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
        }

        if($stmt->rowCount() == 0) {
          return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
        }

        $array = $stmt->fetch(PDO::FETCH_ASSOC); // aller chercher
        return array("worked"=>true, "activate_user"=>$array);
}

// ---------------------------BANN AN USER ---------------------------------------------------------
   
public function bannUser($id, $activate_status){
  $stmt = $this->db->prepare("UPDATE users SET is_banned = :banned WHERE id = :id LIMIT 1");
          $stmt->bindValue(":banned", $activate_status, PDO::PARAM_BOOL); 
          $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
          $res = $stmt->execute();
        
          if ($stmt->errorCode() != "00000") {
            return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
          }
  
          if($stmt->rowCount() == 0) {
            return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
          }
  
          $array = $stmt->fetch(PDO::FETCH_ASSOC);
          return array("worked"=>true, "bann_user"=>$array);
  }
// ---------------------------DELETE AN USER ---------------------------------------------------------
   
public function deleteUser($id){
  $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
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
  
// ---------------------------CHANGE GROUP ---------------------------------------------------------
   
public function changeGroup($id, $new_group){
    $stmt = $this->db->prepare("UPDATE users SET usergroup = :usergroup WHERE id = :id LIMIT 1");
  $stmt->bindValue(":usergroup", $new_group, PDO::PARAM_STR); 
  $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
  $res = $stmt->execute();

  if ($stmt->errorCode() != "00000") {
    return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
  }
  if($stmt->rowCount() == 0) {
    return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
  }
  
  $array = $stmt->fetch(PDO::FETCH_ASSOC);
  return array("worked"=>true);
}
  // ---------------------------GET A USER BY ITS ID --------------------------------------------
  public function getUserByID($id){
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->bindValue(":id", $id, PDO::PARAM_STR); 
    $res = $stmt->execute();
    if ($stmt->errorCode() != "00000") {
      return array("worked"=>false, "reason"=> INTERNAL_SERVER_ERROR);
    }
    if($stmt->rowCount() == 0) {
      return array("worked"=>false, "reason"=> RESSOURCE_NOT_FOUND_ERROR);
    }
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    return array("worked"=>true, "user"=>$array);
  }

  
}

