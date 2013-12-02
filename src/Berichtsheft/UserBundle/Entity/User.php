<?php

namespace Berichtsheft\UserBundle\Entity;

use Berichtsheft\BaseBundle\Model\AzubiInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Berichtsheft\UserBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements AzubiInterface
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $firstname;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $surname;

  /**
   * @ORM\Column(type="datetime")
   * @var \DateTime
   */
  protected $birthday;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $birthplace;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $residence;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $street;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $ausbildungsberuf;

  /**
   * @ORM\Column(type="datetime")
   * @var \DateTime
   */
  protected $ausbildung_from;

  /**
   * @ORM\Column(type="datetime")
   * @var \DateTime
   */
  protected $ausbildung_to;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string
   */
  protected $ausbildung_residence;

  /**
   * @return string
   */
  public function getFirstName()
  {
    return $this->firstname;
  }

  /**
   * @return string
   */
  public function getSurname()
  {
    return $this->surname;
  }

  /**
   * @return \DateTime
   */
  public function getBirthday()
  {
    return $this->birthday;
  }

  /**
   * @return string
   */
  public function getBirthPlace()
  {
    return $this->birthplace;
  }

  /**
   * @return string
   */
  public function getResidence()
  {
    return $this->residence;
  }

  /**
   * @return string
   */
  public function getStreet()
  {
    return $this->street;
  }

  /**
   * @return string
   */
  public function getAusbildungsberuf()
  {
    return $this->ausbildungsberuf;
  }

  /**
   * @return \DateTime
   */
  public function getAusbildungFrom()
  {
    return $this->ausbildung_from;
  }

  /**
   * @return \DateTime
   */
  public function getAusbildungTo()
  {
    return $this->ausbildung_to;
  }

  /**
   * @return string
   */
  public function getAusbildungResidence()
  {
    return $this->ausbildung_residence;
  }

  /**
   * @param \DateTime $ausbildung_from
   */
  public function setAusbildungFrom($ausbildung_from)
  {
    $this->ausbildung_from = $ausbildung_from;
  }

  /**
   * @param string $ausbildung_residence
   */
  public function setAusbildungResidence($ausbildung_residence)
  {
    $this->ausbildung_residence = $ausbildung_residence;
  }

  /**
   * @param \DateTime $ausbildung_to
   */
  public function setAusbildungTo($ausbildung_to)
  {
    $this->ausbildung_to = $ausbildung_to;
  }

  /**
   * @param string $ausbildungsberuf
   */
  public function setAusbildungsberuf($ausbildungsberuf)
  {
    $this->ausbildungsberuf = $ausbildungsberuf;
  }

  /**
   * @param \DateTime $birthday
   */
  public function setBirthday($birthday)
  {
    $this->birthday = $birthday;
  }

  /**
   * @param string $birthplace
   */
  public function setBirthplace($birthplace)
  {
    $this->birthplace = $birthplace;
  }

  /**
   * @param string $firstname
   */
  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;
  }

  /**
   * @param string $residence
   */
  public function setResidence($residence)
  {
    $this->residence = $residence;
  }

  /**
   * @param string $street
   */
  public function setStreet($street)
  {
    $this->street = $street;
  }

  /**
   * @param string $surname
   */
  public function setSurname($surname)
  {
    $this->surname = $surname;
  }

  /**
   * @return string
   */
  public function getWorklogRetrieverUsername()
  {
    return $this->getUsername();
  }
}