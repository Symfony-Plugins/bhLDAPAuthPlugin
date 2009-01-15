<?php

// $Id: bhLDAP.php 502 2008-07-29 23:19:35Z nvonnahm $
// $URL: https://fai01337.bhs.bannerhealth.com/svn/web/bhLDAPAuthPlugin/lib/bhLDAP.php $
require_once 'adLDAP.php';


class bhLDAP 
{
  protected static $ldap = null;
  protected static $config = null;

  public static function getLDAP() 
  {
    if (self::$ldap === null) {  // memoization
      $c = self::getConfig();
      self::$ldap = new adLDAP($c['adLDAP']);
      self::debugDump(self::$ldap, 'configured adLDAP object');

    }
    return self::$ldap;
  }


  public static function getConfig()
  {
    if (self::$config === null) {  // memoization
      $config = sfYaml::load(sfConfig::get('sf_config_dir').'/LDAPAuth.yml');

      // remove blank values so adLDAP.php will revert to its defaults.
      foreach ($config['adLDAP'] as $k => $v) {
	if ( ! is_array($v)  && ($v == null || preg_match('/^\s*$/',$v))) {
	  unset($config['adLDAP'][$k]);
	}
      }
      self::debugDump($config, 'parsed, cleaned LDAPAuth yaml');
      self::$config = $config;
    }
    return self::$config;
  }


  public static function checkPassword($username, $password)
  {
    self::debug("########  hello bhLDAP::checkPassword()!");
    
    $return = self::getLDAP()->authenticate($username,$password);
    self::debug( "$username password OK? [$return]");

    # authz takes place in apps/frontend/lib/myUser.class.php, 
    # which points to this plugin's lib/user/bhLDAPAuthSecurityUser.class.php

    return $return;

  }


  # this works around a PHP_NOTICE error in adLDAP's user_groups function
  public static function getUserGroups ($username, $recursive=NULL) {
    $ldap = self::getLDAP();

    if ( $recursive == NULL ) { 
      //use the default option if they haven't set it
      $recursive=$ldap->_recursive_groups; 
    } 


    $filter="samaccountname=".$username;
    $fields=array("memberof");
    $sr=ldap_search($ldap->_conn,$ldap->_base_dn,$filter,$fields);
    $entries = ldap_get_entries($ldap->_conn, $sr);

    $groups = $ldap->nice_names($entries[0]['memberof']);
    if ($recursive){
      foreach ($groups as $id => $group_name){
	$extra_groups=$ldap->recursive_groups($group_name);
	$groups=array_merge($groups,$extra_groups);
      }
    }

    return $groups;
  }
  
  # all lowercase, for case-insensitive matching
  public static function getUserGroupsLC ($username, $recursive=NULL) {
    $g = self::getUserGroups($username, $recursive);
    return array_map("strtolower", $g);
  }

  public static function getUserCredentials($user)
  {
    $credentials = array();
    self::debugDump($user, "user");
    $username = $user->getUsername();

    $ldap = self::getLDAP();
    self::debug("looking up user groups for $username");

    // look up credentials using AD groups
    $memberships = self::getUserGroupsLC($username);

    $c = self::getConfig();
    foreach ($c['groupMappings'] as $credential => $ad_groups) {
      foreach ($ad_groups as $group) {
#	if (@$ldap->user_ingroup($username, $group, false)) {
	if ( in_array(strtolower($group), $memberships) ) {
	  $credentials[] = $credential;
	  continue 2;
	}
      }
    }

    self::debugDump($credentials, "credentials for $username");

    return $credentials;
  }


  /**
   * Print a string to the log using 'debug' level.  For printf-style
   * debugging.  
   * 
   * @param      string $m      The string to log
   * @return     nothing
   */ 
  public static function debug ($m) {
    if (sfConfig::has('sf_logging_enabled') && sfConfig::get('sf_logging_enabled'))
      {	
	if ($logger = sfContext::getInstance()->getLogger()) {
	  $logger->debug($m);
	}
      }
    elseif (sfConfig::has('bhLDAP_echo_debugging') && sfConfig::get('bhLDAP_echo_debugging'))
      {
	echo "# $m\n";
      }
    else
      {
// 	echo $m;
      }
  }

  /**
   * Dump a data structure to the log at the 'debug' level.  Uses
   * print_r() formatting.
   * 
   * @param      mixed $v         The variable/data structure to dump
   * @param      string $label    An optional label to print in front of the dump
   * @return     nothing
   */ 
  public static function debugDump ($v, $label = "var dump") {
    self::debug("$label:  " . print_r($v, true));
  }


}

//sfeof
