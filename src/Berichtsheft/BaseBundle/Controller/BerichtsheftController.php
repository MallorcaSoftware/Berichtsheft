<?php

namespace Berichtsheft\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Berichtsheft\BaseBundle\Worklog\WorklogRetriever;

class BerichtsheftController extends Controller
{
  /**
   * @Template
   * @return array
   */
  public function dashboardAction()
  {
    $user = $this->get('security.context')->getToken()->getUser();
    if($user)
    {
      $worklogs = $this->getWorklogRetriever()->retrieve($user, new \DateTime('2013-10-01'), new \DateTime('2013-10-20'));
      ladybug_dump($worklogs);
    }
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

  /**
   * @return WorklogRetriever
   */
  private function getWorklogRetriever()
  {
    return $this->get('berichtsheft_base.worklog_retriever.jira');
  }
} 