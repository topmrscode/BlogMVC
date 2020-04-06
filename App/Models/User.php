<?php

namespace App\Models;

class User
{

 
  private $id;
  private $username;
  private $email;
  private $password;
  private $group;
  private $is_banned;
  private $is_activated;

  public function getId()
  {
    return $this->id;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function setUsername(string $username)
  {
    $this->username = $username;

    return $this;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail(string $email)
  {
    $this->email = $email;

    return $this;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function setPassword(string $password)
  {
    $this->password = $password;

    return $this;
  }
  public function getGroup()
  {
    return $this->group;
  }

  public function setGroup(string $_group)
  {
    $this->group= $_group;

    return $this;
  }

  public function isBanned()
  {
    return $this->is_banned;
  }

  public function setBannedStatus(bool $is_banned)
  {
    $this->is_banned = $is_banned;

    return $this;
  }
  
  public function isActivated()
  {
    return $this->is_activated;
  }

  public function setActivatedAccount(bool $is_activated)
  {
    $this->is_activated = $is_activated;

    return $this;
  }


  public function validate()
  {
    $err = '';

    if (empty($this->username) || strlen($this->username) <= 3 || strlen($this->username) >= 10) {
      $err = $err . "Invalid 'username' field. Must have between 3 and 10 characters.<br>";
    }
    if (empty($this->email) || preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->email) != 1) {
      $err = $err . "Invalid 'email' field. Wrong format.<br>";
    }
    if (empty($this->password) || strlen($this->password) <=8 || strlen($this->password) >=20) {
      $err = $err . "Invalid 'password' field. Can't be blank. Must have between 8 and 20 characters.<br>";
    }

    if (!empty($err)) {
      throw new \Exception($err);
    }
    return $err;
  }
}
