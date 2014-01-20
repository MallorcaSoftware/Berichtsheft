<?php

namespace Berichtsheft\BaseBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Berichtsheft\BaseBundle\BerichtsheftBuilder\BerichtsheftBuilderInterface;
use Berichtsheft\BaseBundle\Model\AzubiInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Berichtsheft\BaseBundle\Render\BerichtsheftRendererInterface;

class GenerateCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('berichtsheft:generate')
      ->setDescription('Generate Berichtshefte')
      ->addArgument('username', InputArgument::REQUIRED, 'Which User?')
      ->addArgument('start_year', InputArgument::REQUIRED, 'Which Year?')
      ->addArgument('start_week', InputArgument::REQUIRED, 'Which Year?')
      ->addArgument('start_number', InputArgument::REQUIRED, 'Which Number?')
      ->addArgument('end_number', InputArgument::REQUIRED, 'How many Numbers?')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $username = $input->getArgument('username');
    $azubi = $this->getUserManager()->findUserBy(array(
      'username' => $username
    ));
    $start_year = $input->getArgument('start_year');
    $start_week = $input->getArgument('start_week');
    $start_number = $input->getArgument('start_number');
    $end_number = $input->getArgument('end_number');

    $path = $this->getContainer()->get('kernel')->getRootDir() . '/data/';


    for($i = 0; $i <= $end_number - $start_number; $i++)
    {
      $berichtsheft = $this->getBerichtsheftBuilder()->generateBerichtsheft($azubi, $start_week + $i, $start_year, $start_number + $i);
      $openTBS = $this->getBerichtsheftRenderer()->renderBerichtsheft($berichtsheft, $azubi);
      $openTBS->Show(OPENTBS_FILE, $path . $berichtsheft->getFileName() . '.odt');
    }
  }

  /**
   * @return UserManagerInterface
   */
  private function getUserManager()
  {
    return $this->getContainer()->get('fos_user.user_manager');
  }

  /**
   * @return BerichtsheftBuilderInterface
   */
  private function getBerichtsheftBuilder()
  {
    return $this->getContainer()->get('berichtsheft_base.berichtsheft_builder');
  }

  /**
   * @return BerichtsheftRendererInterface
   */
  private function getBerichtsheftRenderer()
  {
    return $this->getContainer()->get('berichtsheft_base.berichtsheft_renderer.open_tbs');
  }
} 