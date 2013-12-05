<?php

namespace Berichtsheft\BaseBundle\BerichtsheftBuilder;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Model\Berichtsheft;

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
   * @return mixed
   */
  function generateBerichtsheft(AzubiInterface $azubi, $week, $year, $number = 1);
} 