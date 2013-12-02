<?php

namespace Berichtsheft\BaseBundle\Model;

class Berichtsheft
{
  /**
   * @var AzubiInterface
   */
  private $azubi;

  /**
   * @var \DateTime
   */
  private $from;

  /**
   * @var \DateTime
   */
  private $to;

  /**
   * @var BerichtsheftItem[]
   */
  private $items;

  /**
   * @var string
   */
  private $comment;

  /**
   * @param AzubiInterface $azubi
   * @param \DateTime $from
   * @param \DateTime $to
   */
  function __construct(AzubiInterface $azubi, \DateTime $from, \DateTime $to)
  {
    $this->azubi = $azubi;
    $this->from = $from;
    $this->to = $to;
  }


  /**
   * @param \Berichtsheft\BaseBundle\Model\AzubiInterface $azubi
   */
  public function setAzubi($azubi)
  {
    $this->azubi = $azubi;
  }

  /**
   * @return \Berichtsheft\BaseBundle\Model\AzubiInterface
   */
  public function getAzubi()
  {
    return $this->azubi;
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
   * @param \DateTime $from
   */
  public function setFrom($from)
  {
    $this->from = $from;
  }

  /**
   * @return \DateTime
   */
  public function getFrom()
  {
    return $this->from;
  }

  /**
   * @param \DateTime $to
   */
  public function setTo($to)
  {
    $this->to = $to;
  }

  /**
   * @return \DateTime
   */
  public function getTo()
  {
    return $this->to;
  }

  /**
   * @param \Berichtsheft\BaseBundle\Model\BerichtsheftItem[] $items
   */
  public function setItems($items)
  {
    $this->items = $items;
  }

  /**
   * @return \Berichtsheft\BaseBundle\Model\BerichtsheftItem[]
   */
  public function getItems()
  {
    return $this->items;
  }

} 