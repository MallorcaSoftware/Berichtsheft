<?php

namespace Berichtsheft\BaseBundle\Worklog;

use Berichtsheft\BaseBundle\Model\AzubiInterface;

/**
 * Holder for multiple worklog retrievers
 * Class ChainWorklogRetriever
 * @package Berichtsheft\BaseBundle\Worklog
 */
class ChainWorklogRetriever extends WorklogRetriever
{

  /**
   * @var WorklogRetriever[]
   */
  protected $worklogRetrievers;

  /**
   * @param \Berichtsheft\BaseBundle\Worklog\WorklogRetriever[] $worklogRetrievers
   */
  public function setWorklogRetrievers($worklogRetrievers)
  {
    $this->worklogRetrievers = $worklogRetrievers;
  }

  /**
   * @return \Berichtsheft\BaseBundle\Worklog\WorklogRetriever[]
   */
  public function getWorklogRetrievers()
  {
    return $this->worklogRetrievers;
  }

  /**
   * @param WorklogRetriever $worklogRetriever
   */
  public function addWorklogRetriever(WorklogRetriever $worklogRetriever)
  {
    $this->worklogRetrievers[] = $worklogRetriever;
  }

  /**
   * @param AzubiInterface $user
   * @param \DateTime $from
   * @param \DateTime $to
   * @return WorklogItem[]
   */
  public function retrieve(AzubiInterface $user, \DateTime $from, \DateTime $to)
  {
    $worklogItems = array();
    foreach($this->getWorklogRetrievers() as $worklogRetriever)
    {
      $worklogItems = array_merge($worklogItems, $worklogRetriever->retrieve($user, $from, $to));
    }
    return $worklogItems;
  }
}