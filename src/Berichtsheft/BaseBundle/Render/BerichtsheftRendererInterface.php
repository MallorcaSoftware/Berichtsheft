<?php

namespace Berichtsheft\BaseBundle\Render;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Model\Berichtsheft;

interface BerichtsheftRendererInterface
{
  /**
   * @param Berichtsheft $berichtsheft
   * @param AzubiInterface $azubi
   * @return OpenTBS
   */
  function renderBerichtsheft(Berichtsheft $berichtsheft, AzubiInterface $azubi);
} 