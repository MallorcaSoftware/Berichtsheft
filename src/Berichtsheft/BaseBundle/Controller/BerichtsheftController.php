<?php

namespace Berichtsheft\BaseBundle\Controller;

use Berichtsheft\BaseBundle\Model\Berichtsheft;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Berichtsheft\BaseBundle\Worklog\WorklogRetriever;
use Symfony\Component\HttpFoundation\Request;

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
      //$worklogs = $this->getWorklogRetriever()->retrieve($user, new \DateTime('2013-10-01'), new \DateTime('2013-10-20'));
      //ladybug_dump($worklogs);
    }
    return array(
      'form' => $this->getGenerationForm()->createView()
    );
  }

  /**
   * @Template
   * @param Request $request
   * @return array
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   */
  public function showAction(Request $request)
  {
    $session = $this->get('session');
    $berichtsheft = $session->get('berichtsheft');
    if(!$berichtsheft)
    {
      throw $this->createNotFoundException('Berichtsheft nicht gefunden');
    }
    ladybug_dump($berichtsheft);
    return array(
      'berichtsheft' => $berichtsheft
    );
  }

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function generateAction(Request $request)
  {
    $user = $this->get('security.context')->getToken()->getUser();
    $form = $this->getGenerationForm();
    $form->handleRequest($request);
    if($form->isValid())
    {
      $data = $form->getData();
      $from = new \DateTime('11.03.1991');
      $to = new \DateTime('11.04.1991');
      $berichtsheft = new Berichtsheft($user, $from, $to);
      $session = $this->get('session');
      $session->set('berichtsheft', $berichtsheft);
      return $this->redirect($this->generateUrl('berichtsheft_base_show'));
    }
    return $this->redirect($this->generateUrl('berichtsheft_base_dashboard'));
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

  /**
   * @return \Symfony\Component\Form\Form
   */
  private function getGenerationForm()
  {
    $weeks = array();
    for($i = 1; $i <= 52; $i++)
    {
      $weeks[$i] = $i;
    }
    $currentYear = date('Y');
    $years = array();
    for($i = 0; $i <= 3; $i++)
    {
      $years[$currentYear - $i] = $currentYear - $i;
    }
    $form = $this->createFormBuilder(array())
      ->setAction($this->generateUrl('berichtsheft_base_generate'))
      ->add('number', 'integer', array(
                      'required' => true,
                      'label' => 'Ausbildungsnachweis Nr.'
                    ))
      ->add('week', 'choice', array(
                    'required' => true,
                    'choices' => $weeks,
                    'label' => 'Kalenderwoche'
                  ))
      ->add('year', 'choice', array(
                    'required' => true,
                    'choices' => $years,
                    'label' => 'Jahr'
                  ))
      ->add('generate', 'submit', array(
                        'label' => 'erstellen'
                      ))
      ->getForm();
    return $form;
  }
} 