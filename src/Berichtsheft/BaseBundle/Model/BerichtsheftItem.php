<?php

namespace Berichtsheft\BaseBundle\Model;



class BerichtsheftItem
{
  /**
   * @var Berichtsheft
   */
  private $berichtsheft;

  /**
   * @var \DateTime
   */
  private $date;

  /**
   * @var string
   */
  private $content;

  /**
   * @var int
   */
  private $timeSpentSeconds = 0;

  /**
   * @param Berichtsheft $berichtsheft
   * @param $content
   * @param \DateTime $date
   */
  function __construct(Berichtsheft $berichtsheft, $content, \DateTime $date)
  {
    $this->berichtsheft = $berichtsheft;
    $this->content = $content;
    $this->date = $date;
  }


  /**
   * @param \Berichtsheft\BaseBundle\Model\Berichtsheft $berichtsheft
   */
  public function setBerichtsheft($berichtsheft)
  {
    $this->berichtsheft = $berichtsheft;
  }

  /**
   * @return \Berichtsheft\BaseBundle\Model\Berichtsheft
   */
  public function getBerichtsheft()
  {
    return $this->berichtsheft;
  }

  /**
   * @param string $content
   */
  public function setContent($content)
  {
    $this->content = $content;
  }

  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
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

} 