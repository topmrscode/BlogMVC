<?php

namespace App\Controllers;
use WebFramework\AppController;
use WebFramework\Router;
use WebFramework\Request;
use \Firebase\JWT\JWT;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use WebFramework\GROUP_ADMIN;
use WebFramework\GROUP_WRITER;
use WebFramework\GROUP_USER;
use WebFramework\ENTITY_ALREADY_EXIST;
use WebFramework\INTERNAL_SERVER_ERROR;

class CommentController extends AppController
{
    public function indexComment(Request $request) // render => affiche sur la page
  {
    if(!($this->session->exist("user"))) {
      $this->redirect("/session/create", 302);
      die();
    }

    $resultat = $this->ormComment->listComments();
    if ($resultat["worked"] == false) {
        $this->flashError->set("internal server error");
        $this->redirect('/article/index', 302);
        die();
    }

    for ($i = 0; $i < count($resultat['comments']); $i++) {
        $author = $this->ormUser->getUserByID($resultat['comments'][$i]['author_id']);
        if ($author["worked"] == true) {
          $resultat['comments'][$i]['author_name'] = $author['user']['username'];
        }
      }

      for ($i = 0; $i < count($resultat['comments']); $i++) {
        $article = $this->ormArticle->getArticleByID($resultat['comments'][$i]['article_id']);
        if ($article["worked"] == true) {
          $resultat['comments'][$i]['article_title'] = $article['articleById']['title'];
        }
      }

      return $this->render('article/index.html.twig', ['base' => $request->base,
      'error' => $this->flashError, 'flash' => $this->flash, 'user'=>$this->session->get("user"),'articles'=>$resultat['articles'], 'comments'=>$resultat['comments'],
      'is_admin'=> $this->session->get("user")["usergroup"] === GROUP_ADMIN,
      'is_writer'=> $this->session->get("user")["usergroup"] === GROUP_WRITER,
      'id_of_user'=>$this->session->get("user")["id"]]);
    die();
  }
// -----------------------------CREATE COMMENT  ----------------------------------------------

  public function createComment(Request $request) {
    $comment = new comment();
    $comment->setContentComment($request->params['comment']);
    $comment->setAuthorId($this->session->get('user')["id"]); 
    $comment->setArticleId($request->params['article_id']);
  

    $resultat = $this->ormComment->createComment($comment);
    $this->redirect('/article/view?id=' . $request->params['article_id'], 302);
    die();
  }
//-------------------------------------DELETE COMMENT --------------------------------------------
public function deleteComment($request){

  $id = $request->params["id"];
  $art_id = $request->params["article_id"];
  
  $resultat = $this->ormComment->deleteComment($id);
  if ($resultat["worked"] == false) {
    $this->flashError->set("internal server error");
    $this->redirect('/article/view', 302);
    die();
  }

  $this->redirect('/article/view?id='.$art_id, 302);
  die();
}
//-------------------------------------MODIFY COMMENT --------------------------------------------
public function modifyComment(Request $request) {
  $id = $request->params["id"];
  $art_id = $request->params["article_id"];
  if (empty($id) || empty($art_id)) {
    $this->redirect('/article', 302);
    die();
  }
  $content = $request->params['content'];
  $resultat = $this->ormComment->modifyComment($id, $content);
  if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
  }
  $this->redirect('/article/view?id='.$art_id, 302);
  die();
  }
  }



