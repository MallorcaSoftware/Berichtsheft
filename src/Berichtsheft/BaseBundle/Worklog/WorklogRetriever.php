<?php

namespace Berichtsheft\BaseBundle\Worklog;

use Berichtsheft\BaseBundle\Model\AzubiInterface;

abstract class WorklogRetriever
{
  /**
   * @param AzubiInterface $user
   * @param \DateTime $from
   * @param \DateTime $to
   * @return WorklogItem[]
   */
  abstract function retrieve(AzubiInterface $user, \DateTime $from, \DateTime $to);
} 