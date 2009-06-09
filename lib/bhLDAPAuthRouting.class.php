<?php

// $Id$
// $URL$


/**
 *
 * @package    bhLDAPAuthPlugin
 * @subpackage plugin
 * @author     Nathan Vonnahme
 * @version    SVN: $Id$
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
