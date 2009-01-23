<?php


// this is not a real test script but a quick stab to help diagnose AD group lookup issues

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
sfContext::createInstance($configuration);


$usage = 'USAGE: php -d extension=php_ldap.dll adGroupTest.php -- [username]';
if (count($argv) != 3) {
  die($usage);
}
#print_r($argv);

include("../lib/bhLDAP.php");

$un = $argv[2];
print "Using username $un\n";
print_r( bhLDAP::checkPassword('testuser', "testpass") );

print_r( bhLDAP::getConfig() );
print_r( bhLDAP::getUserGroups($un, false) );

