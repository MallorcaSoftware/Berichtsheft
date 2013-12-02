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
//    $issueService = $this->get('jira_api.issue');
//    ladybug_dump($issueService->get('TICK-52'));
//    $searchService = $this->get('jira_api.search');
//    $searchService->search(
//      array(
//        'jql' => '(assignee = currentUser() OR assignee was currentUser() OR reporter = currentUser()) AND createdDate > "2013-10-04"',
//      )
//    );
//    ladybug_dump($searchService);
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