<?php

namespace App\Models;

class Article
{
  public $id;
  public $title;
  public $header;
  public $content;
  public $author;
  public $image;
  public $category_id;


  

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

  

  public function getHeader()
  {
    return $this->header;
  }

  public function setHeader(string $header)
  {
    $this->header = $header;

    return $this;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setContent(string $content)
  {
    $this->content = $content;

    return $this;
  }

  public function getAuthor()
  {
    return $this->author;
  }

  public function setAuthor(string $author)
  {
    $this->author = $author;

    return $this;
  }
  public function getImage()
  {
    return $this->image;
  }

  public function setImage(string $_image)
  {
    $this->image= $_image;

    return $this;
  }
  public function getCategoryId()
  {
    return $this->category_id;
  }

  public function setCategoryId(string $category_id)
  {
    $this->category_id = $category_id;

    return $this;
  }

}



  

