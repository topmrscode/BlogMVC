<?php

namespace App\Controllers;
use WebFramework\AppController;
use WebFramework\Router;
use WebFramework\Request;

//library to encode and decode JSON Web Tokens (JWT) in PHP, see : https://github.com/firebase/php-jwt
use \Firebase\JWT\JWT;
// -----------------------

use App\Models\User;
use WebFramework\ENTITY_ALREADY_EXIST;
use WebFramework\INTERNAL_SERVER_ERROR;

class UserController extends AppController
{
  public function createView(Request $request)
  {
    if($this->session->exist("user")) {
      $this->redirect("/", 302);
      die();
    }

    return $this->render('user/create.html.twig', ['base' => $request->base,
      'error' => $this->flashError, 'flash' => $this->flash]);
  }

// 1. on recupere les donnes du formulaires et on creer un nouvel utilisateur 
  public function create(Request $request) { 
    $user = new User();
    $user->setUsername($request->params['username']);
    $user->setEmail($request->params['email']);
    $user->setPassword($request->params['password']); // == $_POST
    $user->setGroup(GROUP_USER);
    $user->setBannedStatus(false);
    $user->setActivatedAccount(false);

// 2. on verifie les contraintes des champs et le password confirmation
    try {
      $user->validate();
      if ($user->getPassword() != $request->params['password_conf']) {
        $err = "Invalid 'password' field. Can't be blank. Must have between 8 and 20 characters.<br>";
        throw new \Exception($err); 
      }
    } catch (\Exception $e) {
      $this->flashError->set($e->getMessage());
      $this->redirect('/user/create', '302');
      return;
    }
    // 3. on hash le password
    $_passwd = password_hash($request->params['password'], PASSWORD_DEFAULT); 
    $user->setPassword($_passwd);

    // 4. on instancie notre user en db
    $resultat = $this->ormUser->persist($user);
    if ($resultat["worked"] == false) {
      if ($resultat["reason"] === ENTITY_ALREADY_EXIST){
        echo "email already exist, try again";
      }
      else {
        echo "internal server error";
      }

      $this->flashError->set($e->getMessage());
      $this->redirect('/user/create', '302');
      die();
    }
    

// ------------------------------ GENERER UNE CHAINE DE CARACTERE EN CODE --------------------------------//
// OBJ: SEND A EMAIL CONFIRMATION LINK
// encode JSON Web Tokens (JWT) in PHP
    $key = "key";
    $payload = array(
        "email" => $user->getEmail(),
        "created_at" => time(),
    );

    $jwt = JWT::encode($payload, $key);
    $this->mailer->sendRegisterMail($user->getEmail(), $user->getUsername(), $jwt);

    $this->flash->set("user created, please confirm you adress email");
    $this->redirect('/user/create', '302');
    die();
  }
  // ----------------------------- VALIDATION DE L EMAIL/ DECODE JWT ET REDIRECTION VERS LOGIN ------------------
  public function validatedEmail(Request $request){
//to decode JSON Web Tokens (JWT) in PHP : utilisation de la lib github 
    $decoded = JWT::decode($request->params["token"], "key", array('HS256'));
    $decoded_array = (array) $decoded;
    $resultat = $this->ormUser->validateEmail($decoded_array["email"]);
    if ($resultat["worked"] == false) {
      echo "internal server error";
      die();
    }
    $this->flash->set("You have confirmed you adress email");
    $this->redirect("/session/create", 302);
  }

  //--------------------------- SUPPRIMER LE COMPTE -------------------------------
// 1. si l user n est pas connecte redirection vers login
  public function delete(Request $request) { 
    if(!($this->session->exist("user"))) {
      $this->flashError->set("You must be connected before trying to do that.");
      $this->redirect("/session/create", 302);
      die();
    }
// 2. supprimer le compte en db (appel de la fonction orm user)
    $resultat = $this->ormUser->delete($this->session->get("user")["email"]);
    if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/', 302);
      die();
    }
    // SUPRESSION DE la seccions voir helper
    $this->session->remove("user");
    $this->flash->set("Your account has been deleted !");
    $this->redirect('/user/create', 302);
    die();
  }
}
