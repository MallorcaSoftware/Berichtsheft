<?php

namespace Berichtsheft\BaseBundle\Worklog;

class WorklogItem
{
  /**
   * @var int
   */
  private $id;

  /**
   * @var string
   */
  private $title;

  /**
   * @var int
   */
  private $hours = 0;

  /**
   * @var string
   */
  private $comment = '';

  /**
   * @param int $id
   * @param string $title
   */
  function __construct($id, $title)
  {
    $this->id = $id;
    $this->title = $title;
  }

  /**
   * @param string $comment
   */
  public function setComment($comment)
  {
    $this->comment = $comment;
  }

  /**
   * @return string
   */
  public function getComment()
  {
    return $this->comment;
  }

  /**
   * @param int $hours
   */
  public function setHours($hours)
  {
    $this->hours = $hours;
  }

  /**
   * @return int
   */
  public function getHours()
  {
    return $this->hours;
  }

  /**
   * @param int $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param string $title
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }


} 