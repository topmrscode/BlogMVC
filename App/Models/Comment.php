<?php

namespace App\Models;

class Comment
{
  public $id;
  public $contentComment;
  public $authorId;
  public $articleId;

  public function getId()
  {
    return $this->id;
  }

  public function getAuthorId()
  {
    return $this->authorId;
  }

  public function setAuthorId(string $author_id)
  {
    $this->authorId = $author_id;

    return $this;
  }

  public function getArticleId()
  {
    return $this->articleId;
  }

  public function setArticleId(string $article_id)
  {
    $this->articleId = $article_id;

    return $this;
  }

  public function getContentComment()
  {
    return $this->contentComment;
  }

  public function setContentComment(string $contentComment)
  {
    $this->contentComment = $contentComment;

    return $this;
  }
  

}

