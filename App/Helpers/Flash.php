<?php

namespace App\Helpers;

use App\Helpers\Session;

class Flash
{
  const _FLASH_ = 'flash';

  private $session;

  private static $instance = null;

  /**
   * Private constructor so nobody else can instantiate it.
   */
  private function __construct()
  {
    $this->session = Session::getInstance();
  }

  /**
   * Retrieve the static instance of the FlashError.
   *
   * @return Flash - Instance of the FlashError.
   */
  public static function getInstance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new Flash();
    }
    return self::$instance;
  }

  /**
   * @param string $flash - Flash error text
   */
  public function set($flash)
  {
    $this->session->set(self::_FLASH_, $flash);
  }

  /**
   * @return string - Flash error text
   */
  public function get()
  {
    $flash = $this->session->get(self::_FLASH_);
    $this->session->remove(self::_FLASH_);

    return $flash;
  }

  public function call()
  {
    var_dump($this->session->get(self::_FLASH_));
  }
}
