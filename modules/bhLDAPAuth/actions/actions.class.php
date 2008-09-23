<?php


require_once(sfConfig::get('sf_plugins_dir').'/sfGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

/**
 *
 * @package    bhLDAPAuthPlugin
 * @subpackage plugin
 * @author     Nathan Vonnahme
 * @version    SVN: $Id: actions.class.php 455 2008-05-27 23:43:18Z nvonnahm $
 */
class bhLDAPAuthActions extends BasesfGuardAuthActions
{

  public function executeSignin()
  {
    bhLDAP::debug("########  hello my actions!");


    $user = $this->getUser();

/*     bhLDAP::debugDump($user, 'the user'); */


    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      bhLDAP::debug("########  a log in attemptf!  signing in (if validation passed) and redirectifying to homepage or wherever");

      $referer = $user->getAttribute('referer', $this->getRequest()->getReferer());
      $user->getAttributeHolder()->remove('referer');

      $signin_url = sfConfig::get('app_sf_guard_plugin_success_signin_url', $referer);

      $this->redirect('' != $signin_url ? $signin_url : '@homepage');
    }
    elseif ($user->isAuthenticated())
    {
      bhLDAP::debug("########  logged in!  redirectifying to homepage");

      $this->redirect('@homepage');
    }
    else
    {
      bhLDAP::debug("########  not a POST!  redirectifying to signin form");
      if ($this->getRequest()->isXmlHttpRequest())
      {
        $this->getResponse()->setHeaderOnly(true);
        $this->getResponse()->setStatusCode(401);

        return sfView::NONE;
      }

      if (!$user->hasAttribute('referer'))
      {
        $user->setAttribute('referer', $this->getRequest()->getReferer());
      }

      if ($this->getModuleName() != ($module = sfConfig::get('sf_login_module')))
      {
        return $this->redirect($module.'/'.sfConfig::get('sf_login_action'));
      }

      $this->getResponse()->setStatusCode(401);
    }
  }
}
