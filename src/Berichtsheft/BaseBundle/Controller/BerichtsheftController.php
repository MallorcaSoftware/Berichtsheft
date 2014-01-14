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

class BerichtsheftController extends Controller
{

  const TBS_TEMPLATE_PATH = 'data/berichtsheft_template.odt';

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
    return array(
      'berichtsheft' => $berichtsheft
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

    $openTBS = $this->getOpenTBS();
    $this->getOpenTBSTemplatePath();
    $openTBS->LoadTemplate($this->getOpenTBSTemplatePath());

    $items = array();
    foreach($berichtsheft->getItems() as $item)
    {
      $items[] = array(
        'date' => $item->getDate()->format('d.m.Y'),
        'description' => utf8_decode($item->getContent()),
        'time_spent' => ($item->getTimeSpentSeconds() / 60) . ' Minuten'
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
   * @return OpenTBS
   */
  protected function getOpenTBS()
  {
    return $this->get('opentbs');
  }

  /**
   * returns path to template file
   * @return string
   */
  protected function getOpenTBSTemplatePath()
  {
    return $this->get('kernel')->getRootDir() . '/' . self::TBS_TEMPLATE_PATH;
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
                      'label' => 'Ausbildungsnachweis Nr.',
                      'data' => '1'
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