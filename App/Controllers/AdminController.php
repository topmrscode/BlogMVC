<?php

namespace App\Controllers;
use WebFramework\AppController;
use WebFramework\Router;
use WebFramework\Request;
use \Firebase\JWT\JWT;

use App\Models\User;
use App\Models\Category;
use WebFramework\GROUP_ADMIN;
use WebFramework\GROUP_WRITER;
use WebFramework\GROUP_USER;
use WebFramework\ENTITY_ALREADY_EXIST;
use WebFramework\INTERNAL_SERVER_ERROR;

class AdminController extends AppController
{
  public function index(Request $request) // render => affiche sur la page
  {
    if(!($this->session->exist("user"))) {
      $this->redirect("/session/create", 302);
      die();
    }
    if($this->session->get("user")["usergroup"] != GROUP_ADMIN) {
      $this->redirect("/article/index", 302);
      die();
    }

    return $this->render('admin/index.html.twig', ['base' => $request->base,
      'error' => $this->flashError, 'flash' => $this->flash, 'user'=>$this->session->get("user")]);
  }
//-------------------------------------DISPLAY USERS --------------------------------------------

  public function users(Request $request) {  // recuperer les donners du formulaires
    if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
      $this->redirect("/article/index", 302);
      die();
    }

    $resultat = $this->ormUser->listUsers();
    if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/article/index', 302);
      die();
    }
    return $this->render('admin/users.html.twig', ['base' => $request->base,
      'error' => $this->flashError, 'flash' => $this->flash, 'user'=>$this->session->get("user"), 'users'=>$resultat["users"]]);
    die();
  }
//-------------------------------------ACTIVATE USERS --------------------------------------------
  public function activateUser($request){

    if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
      $this->redirect("/article/index", 302);
      die();
    }

    // je recupere id 123
    //  avec $request->params["id"]
    // je le passe en parametre a mon activateUser
    // En plus je recupere le current_status (0 ou 1) et je  donnes l'inverse 
    // a mon activateUser pour qu'il change le status
    $id = $request->params["id"];
    $current_status = $request->params["current_status"];
    $new_status = false;
    if ($current_status == 0) {
      $new_status = true;
    }
    $resultat = $this->ormUser->activateUser($id, $new_status);
    if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/admin/users', 302);
      die();
    }

    $this->redirect('/admin/users', 302);
    die();
  }
//-------------------------------------BANN USERS --------------------------------------------
public function bannUser($request){

  if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
    $this->redirect("/article/index", 302);
    die();
  }

  $id = $request->params["id"];
  $current_status = $request->params["current_status"];
  $new_status = false;
  if ($current_status == 0) {
    $new_status = true;
  }
  $resultat = $this->ormUser->bannUser($id, $new_status);
  if ($resultat["worked"] == false) {
    $this->flashError->set("internal server error");
    $this->redirect('/admin/users', 302);
    die();
  }

  $this->redirect('/admin/users', 302);
  die();
}
//-------------------------------------DELETE USERS --------------------------------------------
public function deleteUser($request){

  if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
    $this->redirect("/article/index", 302);
    die();
  }
  $id = $request->params["id"];
  
  $resultat = $this->ormUser->deleteUser($id);
  if ($resultat["worked"] == false) {
    $this->flashError->set("internal server error");
    $this->redirect('/admin/users', 302);
    die();
  }

  $this->redirect('/admin/users', 302);
  die();
}
//-------------------------------------CHANGE GROUP --------------------------------------------
public function changeGroupUser($request){

  if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
    $this->redirect("/article/index", 302);
    die();
  }

  $id = $request->params["id"];
  $new_group = $request->params["new_group"];
  
  $resultat = $this->ormUser->changeGroup($id, $new_group);
  if ($resultat["worked"] == false) {
    $this->flashError->set("internal server error");
    $this->redirect('/admin/users', 302);
    die();
  }

  $this->redirect('/admin/users', 302);
  die();
}
//-------------------------------------create USERS --------------------------------------------
public function createUser(Request $request) { 
  $user = new User();
  $user->setUsername($request->params['username']);
  $user->setEmail($request->params['email']);
  $user->setPassword($request->params['password']); // == $_POST
  $user->setGroup($request->params['usergroup']);
  $user->setBannedStatus(false);
  $user->setActivatedAccount(false);

  try {
    $user->validate();
  } catch (\Exception $e) {
    $this->flashError->set($e->getMessage());
    $this->redirect('/admin/users', '302');
    return;
  }

  $_passwd = password_hash($request->params['password'], PASSWORD_DEFAULT); 
  $user->setPassword($_passwd);


  $resultat = $this->ormUser->persist($user);
  if ($resultat["worked"] == false) {
    if ($resultat["reason"] === ENTITY_ALREADY_EXIST){
      $this->flashError->set("email already exist");
    }
    else {
      $this->flashError->set("internal server error");
    }
  }
    $this->flash->set("You have created an user");

    $this->redirect('/admin/users', '302');
    
    die();
}
//-------------------------------------DISPLY CATEGORY --------------------------------------------
public function categories(Request $request) {  // recuperer les donners du formulaires
  if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
    $this->redirect("/article/index", 302);
    die();
  }

  $resultat = $this->ormCategory->listCategories();
  if ($resultat["worked"] == false) {
    $this->flashError->set("internal server error");
    $this->redirect('/article/index', 302);
    die();
  }
  return $this->render('admin/category.html.twig', ['base' => $request->base,
    'error' => $this->flashError, 'flash' => $this->flash, 'user'=>$this->session->get("user"), 'categories'=>$resultat["categories"]]);
  die();
}
//-------------------------------------CREATE CATEGORY --------------------------------------------

  public function createCategory(Request $request) { 
    $category = new Category();
    $category->setTitle($request->params['name']);
    var_dump($category);

    $resultat = $this->ormCategory->CreateCategory($category);
    if ($resultat["worked"] == false) {
      if ($resultat["reason"] === ENTITY_ALREADY_EXIST){
        $this->flashError->set("category already exist");
      }
      else {
        $this->flashError->set("internal server error");
      }
      die();
    }

      $this->flash->set("You have created a category");
      $this->redirect('/admin/category', '302');
      die();
  }

//-------------------------------------MODIFY CATEGORY--------------------------------------------
public function modifyCategory(Request $request) {
  if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
    $this->redirect("/article/index", 302);
    die();
  }
  $id = $request->params["id"];
  if (empty($id)) {
    $this->redirect("/article/index", 302);
    die();
  }

  $title = $request->params['name'];

  $resultat = $this->ormCategory->modifyCategory($id, $title);
  if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
  }
  $this->redirect("/admin/category", 302);
  die();
}

//-------------------------------------DELETE CATEGORY --------------------------------------------
public function deleteCategory($request){

  if ($this->session->get('user')["usergroup"] != GROUP_ADMIN) {
    $this->redirect("/article/index", 302);
    die();
  }
  $id = $request->params["id"];
  
  $resultat = $this->ormCategory->deleteCategory($id);
  if ($resultat["worked"] == false) {
    $this->flashError->set("internal server error");
  }

  $this->redirect('/admin/category', 302);
  die();
}
}