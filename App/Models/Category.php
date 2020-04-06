<?php

namespace App\Models;

class Category
{
  public $id;
  public $title;
  

  public function getId()
  {
    return $this->id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle(string $title)
  {
    $this->title = $title;

    return $this;
  }

}