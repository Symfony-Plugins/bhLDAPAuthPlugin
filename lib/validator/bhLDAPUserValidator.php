<?php

/* require_once 'lib/bhLDAP.php'; */
/* require_once 'lib/adLDAP.php'; */

class bhLDAPUserValidator extends sfGuardUserValidator
{
  public function execute(&$value, &$error)
  {
    bhLDAP::debug("########  hello bhLDAPUserValidator::execute()!");


    $password_field = $this->getParameterHolder()->get('password_field');
    $password = $this->getContext()->getRequest()->getParameter($password_field);

    $remember = false;
    $remember_field = $this->getParameterHolder()->get('remember_field');
    $remember = $this->getContext()->getRequest()->getParameter($remember_field);

    $username = $value;


    $user = sfGuardUserPeer::retrieveByUsername($username);

    if (! $user) { // pretend the user exists, then check AD password
      $user = new sfGuardUser;
      $user->setUsername($username);
      $user->setSalt('unused');
      $user->setPassword('unused');
    }

    // user exists?
    if ($user)
    {
      // password is ok?
      if ($user->checkPassword($password))
      {
	if ($user->isNew()) {
	  $user->save();
	  $user = sfGuardUserPeer::retrieveByUsername($username);
	}

/* 	$this->getContext()->getUser()->setCulture('en_US'); */
        $this->getContext()->getUser()->signIn($user, $remember);

        return true;
      }
      else {
	$user->delete();
      }
    }

    $error = $this->getParameterHolder()->get('username_error');

    return false;
  }
}

//sfeof
