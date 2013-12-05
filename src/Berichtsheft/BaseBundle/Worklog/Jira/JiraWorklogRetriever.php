<?php

namespace Berichtsheft\BaseBundle\Worklog\Jira;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Worklog\WorklogItem;
use Berichtsheft\BaseBundle\Worklog\WorklogRetriever;
use JiraApiBundle\Service\IssueService;
use JiraApiBundle\Service\ProjectService;
use JiraApiBundle\Service\SearchService;

class JiraWorklogRetriever extends WorklogRetriever
{

  /**
   * @var \JiraApiBundle\Service\IssueService
   */
  private $issueService;

  /**
   * @var \JiraApiBundle\Service\ProjectService
   */
  private $projectService;

  /**
   * @var \JiraApiBundle\Service\SearchService
   */
  private $searchService;

  /**
   * @param IssueService $issueService
   * @param ProjectService $projectService
   * @param SearchService $searchService
   */
  public function __construct(IssueService $issueService, ProjectService $projectService, SearchService  $searchService)
  {
    $this->issueService   = $issueService;
    $this->projectService = $projectService;
    $this->searchService  = $searchService;
  }

  /**
   * @return \JiraApiBundle\Service\IssueService
   */
  public function getIssueService()
  {
    return $this->issueService;
  }

  /**
   * @return \JiraApiBundle\Service\ProjectService
   */
  public function getProjectService()
  {
    return $this->projectService;
  }

  /**
   * @return \JiraApiBundle\Service\SearchService
   */
  public function getSearchService()
  {
    return $this->searchService;
  }

  /**
   * @param AzubiInterface $user
   * @param \DateTime $from
   * @param \DateTime $to
   * @return WorklogItem[]
   */
  public function retrieve(AzubiInterface $user, \DateTime $from, \DateTime $to)
  {
    $worklogs = array();
    $username = $user->getWorklogRetrieverUsername();
    $result = $this->getSearchService()->search(array(
      'jql' => '(assignee = ' . $username . ' OR assignee WAS ' . $username . ' OR reporter = ' . $username . ')
      AND (createdDate > '. $from->format('Y-m-d') .' OR updatedDate > '. $from->format('Y-m-d') .')
      AND (createdDate < '. $to->format('Y-m-d') .' OR updatedDate < '. $to->format('Y-m-d') .')'
    ));
    if(isset($result['issues']))
    {
      foreach($result['issues'] as $issue)
      {
        $id = $issue['key'];
        $description = $issue['fields']['summary'];
        $ticket = $this->getIssueService()->get($id);
        if($ticket && isset($ticket['fields']) && isset($ticket['fields']['worklog']))
        {
          foreach($ticket['fields']['worklog']['worklogs'] as $worklog_item)
          {
            $worklog = new WorklogItem($id, $description, new \DateTime($worklog_item['created']));
            $worklog->setComment($worklog_item['comment']);
            $worklog->setTimeSpentSeconds($worklog_item['timeSpentSeconds']);
            $worklogs[] = $worklog;
          }
        }
      }
    }
    return $worklogs;
  }
}