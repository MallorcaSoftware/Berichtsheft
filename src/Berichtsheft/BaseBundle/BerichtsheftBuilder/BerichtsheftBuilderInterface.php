<?php

namespace Berichtsheft\BaseBundle\BerichtsheftBuilder;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Model\Berichtsheft;
use Berichtsheft\BaseBundle\Worklog\WorklogRetriever;

/**
 * Interface BerichtsheftBuilderInterface
 * @package Berichtsheft\BaseBundle\BerichtsheftBuilder
 */
interface BerichtsheftBuilderInterface
{
  /**
   * @param AzubiInterface $azubi
   * @param int $week
   * @param int $year
   * @param int $number
   * @return Berichtsheft
   */
  function generateBerichtsheft(AzubiInterface $azubi, $week, $year, $number = 1);

  /**
   * @return WorklogRetriever
   */
  function getWorklogRetriever();
} 