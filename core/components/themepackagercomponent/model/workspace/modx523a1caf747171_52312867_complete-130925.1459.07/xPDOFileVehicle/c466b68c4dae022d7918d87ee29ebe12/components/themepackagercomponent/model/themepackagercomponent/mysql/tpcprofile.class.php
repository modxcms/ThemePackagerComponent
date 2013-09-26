<?php
/**
 * @package themepackagercomponent
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/tpcprofile.class.php');
class tpcProfile_mysql extends tpcProfile {}
?>