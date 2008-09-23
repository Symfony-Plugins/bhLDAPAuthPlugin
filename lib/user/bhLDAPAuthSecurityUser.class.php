<?php

/**
 * Class bhLDAPAuthSecurityUser
 *
 *
 *
 * @package bhLDAPAuth symfony plugin

 $Id: bhLDAPAuthSecurityUser.class.php 456 2008-05-28 01:20:59Z nvonnahm $

*/ 


//require_once(sfConfig::get('sf_plugins_dir').'/bhLDAPAuth/lib/bhLDAP.php');


class bhLDAPAuthSecurityUser extends sfGuardSecurityUser
{

public function signIn($user, $remember = false, $con = null)
  {
    $return = parent::signIn($user, $remember);
    bhLDAP::debug("########  hello bhLDAPAuthSecurityUser.class.php signIn()!");

    $this->clearCredentials();



    $credentials = bhLDAP::getUserCredentials($user);

    $this->addCredentials($credentials);

    return($return);
  }

}

//sfeof


