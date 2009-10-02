<?php
DEFINE('_ISO','charset=utf-8');
define('MYSQL_DATE','Y-m-d H:i:s');
global $syspath;
$syspath = "/var/www/includes/";

//include("functions.arrays.php");
//include("functions.cookie.php");
//include("functions.logging.php");
include("functions.strings.php");
include("functions.mysql.php");
//include("functions.pages.php");
//include("functions.images.php");
include("functions.ldap.php");
//include("functions.mail.php");
//include("functions.eds.php");
//include("functions.passwords.php");

/******************
 * Variables
 ******************/
putenv("TZ=US/Eastern");
