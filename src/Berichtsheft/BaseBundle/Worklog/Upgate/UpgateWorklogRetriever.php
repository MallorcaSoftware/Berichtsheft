<?php

namespace Berichtsheft\BaseBundle\Worklog\Upgate;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Worklog\WorklogItem;
use Berichtsheft\BaseBundle\Worklog\WorklogRetriever;

class UpgateWorklogRetriever extends WorklogRetriever
{

  /**
   * @var string
   */
  protected $api_username;

  /**
   * @var string
   */
  protected $api_password;

  /**
   * @param string $api_username
   * @param string $api_password
   */
  public function __construct($api_username, $api_password)
  {
    $this->api_username = $api_username;
    $this->api_password = $api_password;
  }

  /**
   * @param AzubiInterface $user
   * @param \DateTime $from
   * @param \DateTime $to
   * @return WorklogItem[]
   */
  public function retrieve(AzubiInterface $user, \DateTime $from, \DateTime $to)
  {
    $items = array();
    $trainee_id = $user->getExternalId();
    $report_calendar_week = $from->format('W');
    $report_year = $from->format('Y');

    $data = $this->getData($trainee_id, $report_calendar_week, $report_year);
    if($data)
    {
      foreach($data as $jsonItem)
      {
        if(isset($jsonItem->headline) && isset($jsonItem->datum))
        {
          $worklogItem = new WorklogItem('upgate', html_entity_decode($jsonItem->headline), new \DateTime($jsonItem->datum));
          $worklogItem->setComment(html_entity_decode($jsonItem->report));
          $items[] = $worklogItem;
        }
      }
    }

    return $items;
  }


  /**
   * @param int $trainee_id
   * @param int $report_calendar_week
   * @param int $report_year
   * @return array
   */
  public function getData($trainee_id, $report_calendar_week, $report_year)
  {
    $url = 'http://' . $this->api_username . ':' . $this->api_password . '@azubi-berichtsheft.cranz.uptrade.de/v2/api.php?trainee_id=' . $trainee_id . '&report_calendar_week=' . $report_calendar_week . '&report_year=' . $report_year;
    $content = file_get_contents($url);
    return json_decode($content);
  }
}