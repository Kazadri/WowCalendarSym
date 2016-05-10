<?php
namespace WC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WCUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}