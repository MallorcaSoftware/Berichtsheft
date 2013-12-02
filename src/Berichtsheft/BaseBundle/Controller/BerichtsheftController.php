<?php

namespace Berichtsheft\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BerichtsheftController extends Controller
{
  /**
   * @Template
   * @return array
   */
  public function dashboardAction()
  {
    return array();
  }

  /**
   * @Template
   * @return array
   */
  public function greetingAction()
  {
    $user = $this->get('security.context')->getToken()->getUser();
    return array(
      'user' => $user
    );
  }
} 