<?php

namespace Berichtsheft\BaseBundle\BerichtsheftBuilder;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Model\Berichtsheft;
use Berichtsheft\BaseBundle\Model\BerichtsheftItem;
use Berichtsheft\BaseBundle\Worklog\WorklogRetriever;

class BerichtsheftBuilder implements BerichtsheftBuilderInterface
{
  /**
   * @var WorklogRetriever
   */
  protected $worklog_retriever;

  /**
   * @param WorklogRetriever $worklogRetriever
   */
  function __construct(WorklogRetriever $worklogRetriever)
  {
    $this->worklog_retriever = $worklogRetriever;
  }

  /**
   * @return WorklogRetriever
   */
  public function getWorklogRetriever()
  {
    return $this->worklog_retriever;
  }

  /**
   * @param AzubiInterface $azubi
   * @param int $week
   * @param int $year
   * @param int $number
   * @return mixed
   */
  public function generateBerichtsheft(AzubiInterface $azubi, $week, $year, $number = 1)
  {
    $from = new \DateTime($year . '-W' . str_pad($week, 2, 0) . '-1');
    $to = new \DateTime($year . '-W' . str_pad($week, 2, 0) . '-5');
    $berichtsheft = new Berichtsheft($azubi, $from, $to, $number);

    $worklog_items = $this->getWorklogRetriever()->retrieve($azubi, $from, $to);
    $items = array();
    foreach($worklog_items as $worklogItem)
    {
      $content = $worklogItem->getTitle() . ' - ' . $worklogItem->getComment();
      $item = new BerichtsheftItem($berichtsheft, $content, $worklogItem->getDate());
      $items[] = $item;
    }
    $berichtsheft->setItems($items);
    return $berichtsheft;
  }
}