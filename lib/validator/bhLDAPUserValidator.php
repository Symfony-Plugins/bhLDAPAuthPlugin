<?php

/* require_once 'lib/bhLDAP.php'; */
/* require_once 'lib/adLDAP.php'; */

class bhLDAPUserValidator extends sfGuardValidatorUser
{


  protected function doClean($values)
  {
    $username = isset($values[$this->getOption('username_field')]) ? $values[$this->getOption('username_field')] : '';
    $password = isset($values[$this->getOption('password_field')]) ? $values[$this->getOption('password_field')] : '';
    $remember = isset($values[$this->getOption('rememeber_checkbox')]) ? $values[$this->getOption('rememeber_checkbox')] : '';

    bhLDAP::debug("########  hello bhLDAPUserValidator::doClean()!");
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
        return array_merge($values, array('user' => $user));
      }
      else {
	$user->delete();
      }
    }

    if ($this->getOption('throw_global_error'))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    throw new sfValidatorErrorSchema($this, array($this->getOption('username_field') => new sfValidatorError($this, 'invalid')));
  }


}
//sfeof
