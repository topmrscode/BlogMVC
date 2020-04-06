<?php

namespace App\Helpers;

use Exception;

class Session
{
  private static $instance = null;

  /**
   * Private constructor so nobody else can instantiate it.
   */
  private function __construct()
  {
    session_start();
  }

  /**
   * Retrieve the static instance of the Session
   *
   * @return Session - Instance of the Session
   */
  public static function getInstance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new Session();
    }
    return self::$instance;
  }

  /**
   * Retrieve the value of an attribute.
   *
   * @param string $attribute - Name of the attribute containing the requested value.
   *
   * @return string - The value associated with the specified attribute.
   */
  public function get($attribute)
  {
    if ($this->exist($attribute)) {
      return $_SESSION[$attribute];
    }

    return false;
  }

  /**
   * Verify if an attribute is existing and set.
   *
   * @param string $attribute - Attribute the verify.
   *
   * @return bool - True if existing and set, else false.
   */
  public function exist($attribute)
  {
    return (isset($_SESSION[$attribute]) && $_SESSION[$attribute] != "");
  }

  /**
   * Add or update an attribute with a value.
   *
   * @param string $attribute - Name of the attribute to set
   * @param string $value - Value of the attribute
   */
  public function set($attribute, $value)
  {
    $_SESSION[$attribute] = $value;
  }

  /**
   * Remove an attribute.
   *
   * @param string $attribute - Name of the attribute to remove
   *
   * @return bool - True if the attribute was removed, else false
   */
  public function remove($attribute)
  {
    if ($this->exist($attribute)) {
      unset($_SESSION[$attribute]);

      return true;
    }

    return false;
  }

  /**
   * Retrieve every attribute and their values.
   *
   * @return mixed - Associative array containing every elements of the Session.
   */
  public function getValues()
  {
    return $_SESSION;
  }

  /**
   * Destroy the class by unsetting every attributes.
   */
  public function destroy()
  {
    session_destroy();

    foreach ($_SESSION as $key => $value) {
      unset($value);
    }
  }
}
