<?php

namespace Berichtsheft\BaseBundle\Controller;

use MBence\OpenTBSBundle\Services\OpenTBS;
use Berichtsheft\BaseBundle\Model\Berichtsheft;
use Berichtsheft\BaseBundle\Model\AzubiInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Berichtsheft\BaseBundle\BerichtsheftBuilder\BerichtsheftBuilderInterface;
use Berichtsheft\BaseBundle\Render\BerichtsheftRendererInterface;

class BerichtsheftController extends Controller
{

  /**
   * @Template
   * @return array
   */
  public function dashboardAction()
  {
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
    $berichtsheft = $this->getBerichtsheft();

    $week = ($berichtsheft->getFrom()->format('W') == 52) ? 1 : $berichtsheft->getFrom()->format('W') + 1;
    $year = $berichtsheft->getFrom()->format('Y');

    $form = $this->createFormBuilder(array())
      ->setAction($this->generateUrl('berichtsheft_base_generate'))
      ->add('number', 'hidden', array(
        'data' => $berichtsheft->getNumber() + 1
      ))
      ->add('week', 'hidden', array(
        'data' => $week
      ))
      ->add('year', 'hidden', array(
        'data' => $year
      ))
      ->add('generate', 'submit', array(
        'label' => 'nächste Woche'
      ))
      ->getForm();
    return array(
      'berichtsheft' => $berichtsheft,
      'form' => $form->createView()
    );
  }

  /**
   * @Template
   * @param Request $request
   * @return array
   */
  public function timetableAction(Request $request)
  {
    $azubi = $this->getCurrentAzubi();
    $ausbildungFrom = $azubi->getAusbildungFrom();
    $ausbildungTo = $azubi->getAusbildungTo();

    return array();
  }

  /**
   * @param Request $request
   * @return Response
   */
  public function pdfAction(Request $request)
  {
    $berichtsheft = $this->getBerichtsheft();
    $html = $this->renderView('BerichtsheftBaseBundle:Berichtsheft:export.html.twig', array(
      'berichtsheft' => $berichtsheft
    ));

    return new Response(
      $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
      200,
      array(
        'Content-Type'          => 'application/pdf',
        'Content-Disposition'   => 'attachment; filename="file.pdf"'
      )
    );
  }

  /**
   * Todo auslagern der Generierung in einer Klasse
   * @param Request $request
   * @return Response
   */
  public function tbsAction(Request $request)
  {
    $berichtsheft = $this->getBerichtsheft();
    $azubi = $this->getCurrentAzubi();

    $openTBS = $this->getBerichtsheftRenderer()->renderBerichtsheft($berichtsheft, $azubi);
    $openTBS->Show(OPENTBS_DOWNLOAD, $berichtsheft->getFileName() . '.odt');
  }

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function generateAction(Request $request)
  {
    $session = $this->get('session');
    $user = $this->get('security.context')->getToken()->getUser();
    $form = $this->getGenerationForm();
    $form->handleRequest($request);
    if($form->isValid())
    {
      $data = $form->getData();
      $berichtsheft = $this->getBerichtsheftBuilder()->generateBerichtsheft($user, $data['week'], $data['year'], $data['number']);
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
   * @Template
   * @param Request $request
   * @return array
   */
  public function userInformationAction(Request $request)
  {
    return array(
      'user' => $this->getCurrentAzubi()
    );
  }

  /**
   * @return AzubiInterface
   */
  protected function getCurrentAzubi()
  {
    return $this->get('security.context')->getToken()->getUser();
  }

  /**
   * @return BerichtsheftBuilderInterface
   */
  private function getBerichtsheftBuilder()
  {
    return $this->get('berichtsheft_base.berichtsheft_builder');
  }

  /**
   * @return BerichtsheftRendererInterface
   */
  private function getBerichtsheftRenderer()
  {
    return $this->get('berichtsheft_base.berichtsheft_renderer.open_tbs');
  }

  /**
   * @return Berichtsheft
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   */
  public function getBerichtsheft()
  {
    $session = $this->get('session');
    $berichtsheft = $session->get('berichtsheft');

    if(!$berichtsheft)
    {
      throw $this->createNotFoundException('Berichtsheft nicht gefunden');
    }
    return $berichtsheft;
  }

  /**
   * @return \Symfony\Component\Form\Form
   */
  private function getGenerationForm($number = 1, $week = 0, $year = 0)
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
                      'label' => 'Ausbildungsnachweis Nr.',
                      'data' => $number
                    ))
      ->add('week', 'choice', array(
                    'required' => true,
                    'choices' => $weeks,
                    'label' => 'Kalenderwoche',
                    'data' => $week
                  ))
      ->add('year', 'choice', array(
                    'required' => true,
                    'choices' => $years,
                    'label' => 'Jahr',
                    'data' => $year
                  ))
      ->add('generate', 'submit', array(
                        'label' => 'erstellen'
                      ))
      ->getForm();
    return $form;
  }
} 