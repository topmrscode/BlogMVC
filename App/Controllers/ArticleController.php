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

// A garder en tete :
// GET /articles/list -> lister les articles -> index
// POST /articles/create -> creer un article -> createArticle
// GET /articles/create -> la vue qui permet d'afficher le formulaire pour creer un article -> createView
// GET /articles/get?id=123 -> la vue qui permet de recuperer un article (et l'afficher) en me servant de l'id -> getArticle

class ArticleController extends AppController
{

  public function indexArticle(Request $request) // render => affiche sur la page
  {
    if(!($this->session->exist("user"))) {
      $this->redirect("/session/create", 302);
      die();
    }

    $resultat = $this->ormArticle->listArticles();
    $categories = $this->ormCategory->listCategories();//x

// remplit le champs autheur 
    for ($i = 0; $i < count($resultat['articles']); $i++) {
      $author = $this->ormUser->getUserByID($resultat['articles'][$i]['author_id']);
      if ($author["worked"] == true) {
        $resultat['articles'][$i]['author_name'] = $author['user']['username'];
      }
  // remplit le titre de la category
      $category = $this->ormCategory->getCategoryById($resultat['articles'][$i]['category_id']);
      if($category['worked']== true){// si on arrive pas a recuperer la category on en met pas dans la colonne category_id
      $resultat['articles'][$i]['category_title'] = $category['category']['title']; // recuperer author_name 
      }
  // remplit les tags 
      $tag = $this->ormArticle->ListTagsForArticle($resultat['articles'][$i]['id']);
      if($tag['worked']== true){
        $tagsString = "";
          if ($tag['worked'] == true && count($tag['tags']) > 0) {
            for ($j = 0; $j < count($tag['tags']); $j++) {
              $tagsString = $tagsString . " " . $tag['tags'][$j]['title'];
            }
            $resultat['articles'][$i]['tags'] = $tagsString; // ca va prendre mon tableau et transformer mon tableau en une chaine de caractere(UTIL POUR LE JS)  
          }
      } 
  }
// -----------------------------GESTION DES FILTRES -------------------------
// 
    if (!empty($request->params["filter"]) && $request->params["filter"] == "do") {
      $start_date = intval(intval($request->params["start"]) / 1000) + 7200; // moment.js me donnes des ms et je veux des secondes donc /1000 et il donne l heure en UTC et en france je suis en UTC + 2 donc je rajoute 7200 secondes (1 h 3600 sec)
      $end_date = intval(intval($request->params["end"]) / 1000) + 7200;

      $title = $request->params["title"];
      $tags = $request->params["tags"];
      $category = $request->params["category"];


      foreach($resultat['articles'] as $key => $value) {
        if (!empty(trim($tags)) && strpos($value['tags'], trim($tags)) === false) { 
          unset($resultat['articles'][$key]); // vire du tableau l article 
          // j ai DONNER a chaque category une valeur , celle de son id et a all = -1
        } else if ($value['category_id'] != $category && $category !== "-1") { 
          unset($resultat['articles'][$key]);
        } else if (!empty(trim($title)) && strpos($value['title'], trim($title)) === false) {
          unset($resultat['articles'][$key]);
        } else if (strtotime($value['created_at']) < $start_date) {
          unset($resultat['articles'][$key]);
        } else if (strtotime($value['created_at']) > $end_date) {
          unset($resultat['articles'][$key]);
        }
      }
    }
// -----------------------------
    return $this->render('article/index.html.twig', ['base' => $request->base,
      'error' => $this->flashError, 'flash' => $this->flash, 'user'=>$this->session->get("user"),'articles'=>$resultat['articles'],
      'is_admin'=> $this->session->get("user")["usergroup"] === GROUP_ADMIN,
      'is_writer'=> $this->session->get("user")["usergroup"] === GROUP_WRITER,
      'categories'=>$categories['categories']]);//x
      
       die();
  }
//-------------------------------------MANAGE ARTICLE --------------------------------------------
  public function manageArticle(Request $request) {
    if ($this->session->get('user')["usergroup"] != GROUP_ADMIN && $this->session->get('user')["usergroup"] != GROUP_WRITER) {
      $this->redirect("/article/index", 302);
      die();
    }
 
    $resultat = $this->ormArticle->listArticles();
    if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/article/index', 302);
      die();
    }

    $categories = $this->ormCategory->listCategories();
    $nbElem = count($resultat['articles']);
    for ($i = 0; $i < $nbElem; $i++) { // on veut afficher tous les articles si on est admin et si on est writter afficher que les notre
      if ($this->session->get('user')["usergroup"] != GROUP_ADMIN &&
        $resultat['articles'][$i]['author_id'] != $this->session->get('user')["id"]) {
          unset($resultat['articles'][$i]);
      } else {
        $tag = $this->ormArticle->ListTagsForArticle($resultat['articles'][$i]['id']); // recupere tous les tags pour un article donne
        $category = $this->ormCategory->getCategoryById($resultat['articles'][$i]['category_id']);
        if($category['worked']== true){// si on arrive pas a recuperer la category on en met pas dans la colonne category_id
          $resultat['articles'][$i]['category_title'] = $category['category']['title']; // recuperer author_name 
        }
        $author = $this->ormUser->getUserById($resultat['articles'][$i]['author_id']);
        // -----------correction de beug lie a twig evite les retours a la ligne--------------
        $resultat['articles'][$i]['header'] = trim(preg_replace('/\s+/', ' ', $resultat['articles'][$i]['header']));
        $resultat['articles'][$i]['content'] = trim(preg_replace('/\s+/', ' ', $resultat['articles'][$i]['content']));
        $resultat['articles'][$i]['title'] = trim(preg_replace('/\s+/', ' ', $resultat['articles'][$i]['title']));
        $resultat['articles'][$i]['image'] = trim(preg_replace('/\s+/', ' ', $resultat['articles'][$i]['image']));
        if ($author['worked'] == true) {
          $resultat['articles'][$i]['author_name'] = $author['user']['username']; // recuperer author_name 
        }

        $tagsString = "";
        if ($tag['worked'] == true && count($tag['tags']) > 0) {
          for ($j = 0; $j < count($tag['tags']); $j++) {
            $tagsString = $tagsString . " " . $tag['tags'][$j]['title'];
          }
          $resultat['articles'][$i]['tags'] = $tagsString; // ca va prendre mon tableau et transformer mon tableau en une chaine de caractere(UTIL POUR LE JS)  
        } 
      }
    }
    return $this->render('article/manage.html.twig', ['base' => $request->base,
    'error' => $this->flashError, 'flash' => $this->flash, 'user' => $this->session->get("user"), 'articles'=>$resultat['articles'],
    'categories'=>$categories['categories']]);
    die();
  }
//-------------------------------------CREATE ARTICLE --------------------------------------------
  public function createArticle(Request $request) {
    
    $article = new Article();
    $article->setTitle($request->params['title']);
    $article->setHeader($request->params['header']);
    $article->setContent($request->params['content']);
    $article->setCategoryId($request->params['category_id']);
    $article->setAuthor($this->session->get('user')["id"]); 
    $article->setImage($request->params['image']);
    
    $resultat = $this->ormArticle->createArticle($article);
// ----------------------RECUPERER LES TAGS--------------------
// etape 1 : "#1 #2 #3 " => ["#1", "#2", "#3"];
// etape 2 : on met tout en minuscule et on rajoute les # manquants
// etape 3 : on insere chaque tag 1 par 1 tans notre table de tag
// etape 4 : on ajoute chaque tag dans notre table associative ArticleTag (SYSTSEME MANY TO MANY = un artcile peut avoir plusisuers tag et un tag peut appartenir a plusieur article)

    $tag = $request->params['tags']; // recuperer les tags du formulaire 
    $tag_array = explode(" ", $tag); // on a separer nos tags par des espaces, tag_array[0]=#nature,tag_array[1]=#bibou
    for($i = 0; $i<count($tag_array); $i++) {
      if(!empty($tag_array[$i])){
        $tag_array[$i] = strtolower($tag_array[$i]); // on met tous les caracteres en minuscule
        if($tag_array[$i][0]!= "#"){ // on gere les mots sans # 
          $tag_array[$i] = "#".$tag_array[$i];
        }
        $this->ormTag->createTag($tag_array[$i]);
        $this->ormArticle->associateTagByName($resultat['article_id'], $tag_array[$i]);
      }
    }
    if ($resultat["worked"] == false) {
        $this->flashError->set("internal server error");
    }
    $this->redirect("/article/index", 302);
    die();
  }

  //-------------------------------------MODIFY ARTICLE --------------------------------------------
  public function modifyArticle(Request $request) {
    if ($this->session->get('user')["usergroup"] != GROUP_ADMIN && $this->session->get('user')["usergroup"] != GROUP_WRITER) {
      $this->redirect("/article/index", 302);
      die();
    }
    $id = $request->params["id"];
    if (empty($id)) {
      $this->redirect("/article/index", 302);
      die();
    }

    $title = $request->params['title'];
    $header = $request->params['header'];
    $category_id = $request->params['category_id'];
    $content = $request->params['content'];
    $image = $request->params['image'];


    $tags = $request->params['tags'];
    $tag_array = explode(" ", $tags); // on a separer nos tags par des espaces, tag_array[0]=#nature,tag_array[1]=#bibou
    $this->ormArticle->cleanTags($id);
    for($i = 0; $i<count($tag_array); $i++) {
      if(!empty($tag_array[$i])){
        $tag_array[$i] = strtolower($tag_array[$i]); // on met tous les caracteres en minuscule
        if($tag_array[$i][0]!= "#"){ // on gere les mots sans # 
          $tag_array[$i] = "#".$tag_array[$i];
        }
        $this->ormTag->createTag($tag_array[$i]);
        $this->ormArticle->associateTagByName($id, $tag_array[$i]);
      }
    }

    $resultat = $this->ormArticle->modifyArticle($id, $title, $header, $content, $category_id, $image);
    if ($resultat["worked"] == false) {
        $this->flashError->set("internal server error");
    }
    $this->redirect("/article/manage", 302);
    die();
  }

  //-------------------------------------DELETE ARTICLE --------------------------------------------
public function deleteArticle($request){

    if ($this->session->get('user')["usergroup"] != GROUP_ADMIN && $this->session->get('user')["usergroup"] != GROUP_WRITER) {
        $this->redirect("/article/index", 302);
        die();
    }
    $id = $request->params["id"];
    
    $resultat = $this->ormArticle->deleteArticle($id);
    $resultat1=$this->ormComment->deleteCommentForArticle($id);
    if ($resultat["worked"] == false || $resultat1["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/article/manage', 302);
      die();
    }
    $this->redirect('/article/manage', 302);
    die();
  }

   //-------------------------------------VIEW ARTICLE --------------------------------------------

   public function viewArticle($request){

    $id = $request->params["id"];
    
    $resultat = $this->ormArticle->GetArticleById($id);
    if ($resultat["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/article/index', 302); // si ca marche pas on redirige
      die();
    }

    $author = $this->ormUser->getUserByID($resultat['articleById']['author_id']);
    if ($author["worked"] == true) {
      $resultat['articleById']['author_name'] = $author['user']['username'];
    }
    $category = $this->ormCategory->getCategoryById($resultat['articleById']['category_id']);
    if($category['worked']== true){// si on arrive pas a recuperer la category on en met pas dans la colonne category_id
      $resultat['articleById']['category_title'] = $category['category']['title']; // recuperer category title    
     }
   

    $resultatComments = $this->ormComment->listCommentsByArticleId($id);
    if ($resultatComments["worked"] == false) {
      $this->flashError->set("internal server error");
      $this->redirect('/article/index', 302); // si ca marche pas on redirige
      die();
    }
    
// recuperer lid de l autheur de chaue comment 
// resultatomments = tous les commentaires de l article et j iterer sur les artciles et je prends l autheur id,
// obj : recuperer pour chaque comment le mom de l autheur en fonction de son id 
    for ($i = 0; $i<count($resultatComments['comments']); $i++) {
      $author = $this->ormUser->getUserById($resultatComments['comments'][$i]['author_id']);
        if ($author["worked"] == true) {
          $resultatComments['comments'][$i]['author_name'] = $author['user']['username'];
        }
    }

    // si ca marche on affiche ====> render
    return $this->render('article/view.html.twig', ['base' => $request->base,
    'error' => $this->flashError, 'flash' => $this->flash, 'user' => $this->session->get("user"), 'viewArticle'=>$resultat['articleById'], 'comments'=>$resultatComments['comments'],
    'id_user'=>$this->session->get("user")["id"]]);
    die();
  
   }
}
