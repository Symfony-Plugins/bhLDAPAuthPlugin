<?php

// $Id: bhLDAP.php 502 2008-07-29 23:19:35Z nvonnahm $
// $URL: https://fai01337.bhs.bannerhealth.com/svn/web/bhLDAPAuthPlugin/lib/bhLDAP.php $


/**
 *
 * @package    bhLDAPAuthPlugin
 * @subpackage plugin
 * @author     Nathan Vonnahme
 * @version    SVN: $Id: bhLDAPAuthRouting.class.php 7636 2008-02-27 18:50:43Z fabien $
 */

class bhLDAPAuthRouting extends sfGuardRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    $r->prependRoute('bh_ldap_signin', '/login', array('module' => 'bhLDAPAuth', 'action' => 'signin'));
    $r->prependRoute('sf_guard_signin', '/login', array('module' => 'bhLDAPAuth', 'action' => 'signin'));

    parent::listenToRoutingLoadConfigurationEvent($event);

  }
}
