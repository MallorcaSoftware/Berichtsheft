<?php

namespace Berichtsheft\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
  /**
   * @Template
   * @return array
   */
  public function userInformationAction()
  {
    $user = $this->get('security.context')->getToken()->getUser();
    return array(
      'user' => $user
    );
  }
}