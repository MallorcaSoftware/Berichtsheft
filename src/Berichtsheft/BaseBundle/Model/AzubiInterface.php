<?php

namespace Berichtsheft\BaseBundle\Model;

interface AzubiInterface
{
  /**
   * @return string
   */
  function getFirstName();

  /**
   * @return string
   */
  function getWorklogRetrieverUsername();

  /**
   * External id is used for worklogretrievers
   * @return string
   */
  function getExternalId();

  /**
   * @return string
   */
  function getSurname();

  /**
   * @return \DateTime
   */
  function getBirthday();

  /**
   * @return string
   */
  function getBirthPlace();

  /**
   * @return string
   */
  function getResidence();

  /**
   * @return string
   */
  function getStreet();

  /**
   * @return string
   */
  function getAusbildungsberuf();

  /**
   * @return \DateTime
   */
  function getAusbildungFrom();

  /**
   * @return \DateTime
   */
  function getAusbildungTo();

  /**
   * @return string
   */
  function getAusbildungResidence();
} 