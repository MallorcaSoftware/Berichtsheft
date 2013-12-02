<?php

namespace Berichtsheft\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BerichtsheftUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
