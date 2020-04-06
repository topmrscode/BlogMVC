<?php

namespace App\Controllers;
use WebFramework\AppController;
use WebFramework\Router;
use WebFramework\Request;
use \Firebase\JWT\JWT;

use App\Models\User;
use App\Models\GROUP_USER;
use App\Models\GROUP_WRITER;
use App\Models\GROUP_ADMIN;
use WebFramework\ENTITY_ALREADY_EXIST;
use WebFramework\INTERNAL_SERVER_ERROR;


class SessionController extends AppController
{
  public function createView(Request $request) // render => affiche sur la page
  {
    if($this->session->exist("user")) {
      $this->redirect("/article/index", 302);
      die();
  }

    return $this->render('session/create.html.twig', ['base' => $request->base,
      'error' => $this->flashError, 'flash' => $this->flash]);
  }

// ------------------------------ CREATION DE LA SECCION --------------------
  public function create(Request $request) {  // recuperer les donners du formulaires
    $email = $request->params["email"];
    $password = $request->params["password"];

    // verifier quils ne soient pas vide
    if (empty($email) || empty($password)){
      $this->flashError->set("error");
      $this->redirect("/session/create", 302);
      die();
    }
    // appeler methode login de mon ORM pour recuperer le mote de passe et verifier quil y a une entree en db
    $resultat = $this->ormUser->login($email);
    $error_message = "";
    if ($resultat["worked"] == false) {
      if ($resultat["reason"] === RESSOURCE_NOT_FOUND_ERROR){
        $error_message = "Invalid Email, try again";
      }
      else {
        $error_message = "internal server error";
      }

      $this->flashError->set($error_message);
      $this->redirect('/session/create', '302');
      die();
    }
    // GESTION DES ERREURS
    if (password_verify($password, $resultat["password"]) == false){
      $this->flashError->set("Invalid password");
      $this->redirect('/session/create', '302');
      die(); 
    }

    $resultat = $this->ormUser->getCurrentUser($email);
    $error_message = "";
    if ($resultat["worked"] == false) {
      $error_message = "internal server error";

      $this->flashError->set($error_message);
      $this->redirect('/session/create', '302');
      die();
    }
    if ($resultat['user']["is_banned"] == true) {
      $this->flashError->set("you are banned, you can't login");
      $this->redirect('/session/create', '302');
      die();
    }
    if ($resultat['user']["is_activated"] == false) {
      $this->flashError->set("your account is not activated, please contact the support");
      $this->redirect('/session/create', '302');
      die();
    }

    $this->session->set("user", $resultat["user"]);
    $this->redirect('/article/index', '302');
    die();
  }

  // -------------------------- SE DECONNECTER -------------------------
  public function delete(Request $request) 
  {  // recuperer les donners du formulaires
    if(!($this->session->exist("user"))) {
      $this->redirect("/session/create", 302);
      die();
    }

    $this->session->remove("user");
    $this->flash->set("You are now logged out !");
    $this->redirect("/session/create", 302);
    die();
  }
}
