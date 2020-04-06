<?php

namespace WebFramework;

use Twig_Environment;
use Twig_Loader_Filesystem;

use WebFramework\ORMUser;
use WebFramework\ORMArticle;
use WebFramework\ORMComment;
use WebFramework\ORMCategory;
use WebFramework\ORMTag;

use App\Helpers\Session;
use App\Helpers\FlashError;
use App\Helpers\Flash;
use WebFramework\Mailer;

class AppController {

  private $twig;
  public $ormUser;
  public $ormArticle;
  public $ormComment;
  public $ormCategory;
  public $session;
  public $flashError;
  public $flash;
  public $mailer;
  public $ormTag;

  public function __construct()
  {
    $loader = new Twig_Loader_Filesystem("../App/Views");
    $twig = new Twig_Environment($loader, []);
    $this->twig = $twig;

    $this->ormUser = ORMUser::getInstance();
    $this->ormArticle = ORMArticle::getInstance();
    $this->ormComment = ORMComment::getInstance();
    $this->ormCategory = ORMCategory::getInstance();
    $this->ormTag = ORMTag::getInstance();
    // TODO: Uncomment this lines in order to link the ORM to the database
    //   specified in the 'db.php' file.
    $this->ormUser->connect(require '../config/db.php');
    $this->ormArticle->connect(require '../config/db.php');
    $this->ormComment->connect(require '../config/db.php');
    $this->ormCategory->connect(require '../config/db.php');
    $this->ormTag->connect(require '../config/db.php');

    $this->session = Session::getInstance();
    $this->flashError = FlashError::getInstance();
    $this->flash = Flash::getInstance();
    $this->mailer = Mailer::getInstance();
  }

  /**
   * Render a view.
   *
   * @param string $view - Path of the view to render
   * @param array $context - Associative array used as a TWIG context. (default: [])
   */
  public function render(string $view, array $context = [])
  {
    echo $this->twig->render($view, $context);
  }

  /**
   * Redirect to an URL.
   *
   * @param string $url - Redirection URL.
   * @param string $status - HTTP status code for the redirection.
   */
  public function redirect(string $url, string $status) {
    header('Location: ' . $url, true, $status);
  }

}
