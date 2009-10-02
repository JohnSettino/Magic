<?php
define('DEBUG_EMAIL','developer@example.com');

global $MYSQL_OPTS;
$MYSQL_OPTS = array(
  'host' => '127.0.0.1',
  'user' => 'db_user',
  'password' => 'dbpassword',
  'domain_name' => 'example.com'
);

global $LDAP_OPTS;
$LDAP_OPTS = array(
  'readserver' => '127.0.0.1',
  'writeserver'=> '127.0.0.2',
  'mandn' => "cn=User,dc=example,dc=com",
  'manpw' => "password",
  'basedn'=> "ou=People,dc=example,dc=com",
  'groupdn'=>"ou=Group,dc=example,dc=com"
);

$cfg = array();
$cfg['ldap'] = $LDAP_OPTS;
$cfg['mysql'] = $MYSQL_OPTS;
global $cfg;
?>
