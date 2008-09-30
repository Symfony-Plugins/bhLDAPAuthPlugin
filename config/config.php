<?php

if (sfConfig::get('app_bh_ldap_plugin_routes_register', true) && in_array('bhLDAPAuth', sfConfig::get('sf_enabled_modules', array())))
{
/*   $r = sfRouting::getInstance(); */

  // prepend our routes
//  $r->appendRoute('bh_ldap_signin', '/login', array('module' => 'bhLDAPAuth', 'action' => 'signin'));
  $this->dispatcher->connect('routing.load_configuration', array('bhLDAPAuthRouting', 'listenToRoutingLoadConfigurationEvent'));
}



//sfeof
