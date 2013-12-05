<?php

namespace Berichtsheft\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContentController extends Controller
{
  /**
   * @Template
   * @return array
   */
  public function benutzerhandbuchAction()
  {
    return array();
  }
} 