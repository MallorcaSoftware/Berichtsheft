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
  private $timeSpentSeconds = 0;

  /**
   * @var string
   */
  private $comment = '';

  /**
   * @var \DateTime
   */
  private $date;

  /**
   * @param int $id
   * @param string $title
   */
  function __construct($id, $title, \DateTime $date)
  {
    $this->id = $id;
    $this->title = $title;
    $this->date = $date;
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
   * @param int $timeSpentSeconds
   */
  public function setTimeSpentSeconds($timeSpentSeconds)
  {
    $this->timeSpentSeconds = $timeSpentSeconds;
  }

  /**
   * @return int
   */
  public function getTimeSpentSeconds()
  {
    return $this->timeSpentSeconds;
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

  /**
   * @param \DateTime $date
   */
  public function setDate($date)
  {
    $this->date = $date;
  }

  /**
   * @return \DateTime
   */
  public function getDate()
  {
    return $this->date;
  }

} 