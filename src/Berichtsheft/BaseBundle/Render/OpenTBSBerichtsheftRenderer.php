<?php

namespace Berichtsheft\BaseBundle\Render;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Berichtsheft\BaseBundle\Model\Berichtsheft;
use MBence\OpenTBSBundle\Services\OpenTBS;
use AppKernel;

class OpenTBSBerichtsheftRenderer implements BerichtsheftRendererInterface
{

  const TBS_TEMPLATE_PATH = 'data/berichtsheft_template.odt';

  /**
   * @var OpenTBS
   */
  protected $openTBS;

  /**
   * @var AppKernel
   */
  protected $appKernel;

  /**
   * @param AppKernel $appKernel
   * @param OpenTBS $openTBS
   */
  public function __construct(AppKernel $appKernel, OpenTBS $openTBS)
  {
    $this->appKernel = $appKernel;
    $this->openTBS = $openTBS;
  }

  /**
   * @param Berichtsheft $berichtsheft
   * @param AzubiInterface $azubi
   * @return OpenTBS
   */
  public function renderBerichtsheft(Berichtsheft $berichtsheft, AzubiInterface $azubi)
  {
    $openTBS = $this->getOpenTBS();
    $this->getOpenTBSTemplatePath();
    $openTBS->LoadTemplate($this->getOpenTBSTemplatePath());

    $items = array();
    foreach($berichtsheft->getItems() as $item)
    {
      $time_spent = ($item->getTimeSpentSeconds() / 60);
      if($time_spent == 0)
      {
        $time_spent = 120;
      }
      $items[] = array(
        'date' => $item->getDate()->format('D') . ', '. $item->getDate()->format('d.m.Y'),
        'description' => utf8_decode($item->getContent()),
        'time_spent' => $time_spent . ' Minuten'
      );
    }

    $openTBS->MergeBlock('blk2', $items);
    $openTBS->MergeField('azubi', array(
      'name' => $azubi->getSurname(),
      'firstname' => $azubi->getFirstName(),
      'birthday' => $azubi->getBirthday()->format('d.m.Y'),
      'birthplace' => $azubi->getBirthPlace(),
      'residence' => $azubi->getResidence(),
      'street' => $azubi->getStreet(),
      'ausbildungsberuf' => $azubi->getAusbildungsberuf(),
      'ausbildungszeitraum' => 'vom ' . $azubi->getAusbildungFrom()->format('d.m.Y') . ' bis ' . $azubi->getAusbildungTo()->format('d.m.Y'),
      'ausbildungresidence' => $azubi->getAusbildungResidence()
    ));
    $openTBS->MergeField('berichtsheft', array(
      'number' => $berichtsheft->getNumber(),
      'from' => $berichtsheft->getFrom()->format('d.m.Y'),
      'to' => $berichtsheft->getTo()->format('d.m.Y')
    ));

    return $openTBS;
  }

  /**
   * returns path to template file
   * @return string
   */
  protected function getOpenTBSTemplatePath()
  {
    return $this->appKernel->getRootDir() . '/' . self::TBS_TEMPLATE_PATH;
  }

  /**
   * @return OpenTBS
   */
  protected function getOpenTBS()
  {
    return $this->openTBS;
  }
}