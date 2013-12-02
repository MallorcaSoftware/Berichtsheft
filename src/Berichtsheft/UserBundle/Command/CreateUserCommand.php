<?php

namespace Berichtsheft\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Berichtsheft\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;

class CreateUserCommand extends ContainerAwareCommand
{

  public static $text_fields = array(
    'username',
    'email',
    'password',
    'firstname',
    'surname',
    'birthday',
    'birthplace',
    'residence',
    'street',
    'ausbildungsberuf',
    'ausbildung_from',
    'ausbildung_to',
    'ausbildung_residence'
  );


  protected function configure()
  {
    $definition = array(
      new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
      new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
    );

    foreach(self::$text_fields as $text_field)
    {
      $definition[] = new InputArgument($text_field, InputArgument::REQUIRED, 'The ' . $text_field);
    }

    $this
      ->setName('berichtsheft:user:create')
      ->setDescription('Create a user.')
      ->setDefinition($definition)
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $username             = $input->getArgument('username');
    $email                = $input->getArgument('email');
    $password             = $input->getArgument('password');
    $inactive             = $input->getOption('inactive');
    $superadmin           = $input->getOption('super-admin');
    $firstname            = $input->getArgument('firstname');
    $surname              = $input->getArgument('surname');
    $birthday             = $input->getArgument('birthday');
    $birthplace           = $input->getArgument('birthplace');
    $residence            = $input->getArgument('residence');
    $street               = $input->getArgument('street');
    $ausbildungsberuf     = $input->getArgument('ausbildungsberuf');
    $ausbildung_from      = $input->getArgument('ausbildung_from');
    $ausbildung_to        = $input->getArgument('ausbildung_to');
    $ausbildung_residence = $input->getArgument('ausbildung_residence');

    $user = $this->getUserManager()->createUser();
    /**
     * @var User $user
     */
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPlainPassword($password);
    $user->setEnabled(true);
    $user->setSuperAdmin((Boolean) $superadmin);
    $user->setFirstname($firstname);
    $user->setSurname($surname);
    $user->setBirthday(new \DateTime($birthday));
    $user->setBirthplace($birthplace);
    $user->setResidence($residence);
    $user->setStreet($street);
    $user->setAusbildungsberuf($ausbildungsberuf);
    $user->setAusbildungFrom(new \DateTime($ausbildung_from));
    $user->setAusbildungTo(new \DateTime($ausbildung_to));
    $user->setAusbildungResidence($ausbildung_residence);

    $this->getUserManager()->updateUser($user);

    $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
  }

  protected function interact(InputInterface $input, OutputInterface $output)
  {
    foreach(self::$text_fields as $field)
    {
      if (!$input->getArgument($field)) {
        $inputField = $this->getHelper('dialog')->askAndValidate(
          $output,
          'Please choose a ' . $field . ':',
          function($inputField) {
            if (empty($inputField)) {
              throw new \Exception('Username can not be empty');
            }

            return $inputField;
          }
        );
        $input->setArgument($field, $inputField);
      }
    }
  }

  /**
   * @return UserManagerInterface
   */
  private function getUserManager()
  {
    return $this->getContainer()->get('fos_user.user_manager');
  }
} 