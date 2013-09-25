<?php
/*
 * MODX Siphon
 *
 * Copyright 2012 by MODX, LLC.
 * All rights reserved.
 */
define('SIPHON_BASE_PATH', dirname(dirname(__FILE__)) . '/model/');
define('SIPHON_APP_ID', 'siphon');

class SiphonLoader {
    public static function registerAutoload() {return spl_autoload_register(array(__CLASS__, 'autoload'));}
    public static function unregisterAutoload() {return spl_autoload_unregister(array(__CLASS__, 'autoload'));}
    public static function autoload($class) {
        @include SIPHON_BASE_PATH . strtr($class, '\\', '/') . '.php';
    }
}

if (!function_exists('isCli')) {
    function isCli() {
        return php_sapi_name() == 'cli' || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0);
    }
}
/* use spl_autoload without throwing exceptions */
spl_autoload_extensions(".php");
spl_autoload_register(array('SiphonLoader', 'autoload'), false);

/* get config.php if it exists */
$config = file_exists(SIPHON_BASE_PATH . 'config.php') ? include SIPHON_BASE_PATH . 'config.php' : array();

if (isCli()) {
    try {
        /* discard script name from argv */
        array_shift($argv);
        /* get a Siphon\Request instance */
        $siphon = new \Siphon\Request(array_merge($config, \Siphon\Request::parseArguments($argv)));
        /* switch user if requested */
        $siphon->switchUser();
        /* handle the CLI request */
        $siphon->handle();
        /* output the results */
        echo implode("\n", $siphon->getResults()) . "\n";
        exit(0);
    } catch (\Siphon\RequestException $e) {
        echo $e->getMessage() . "\n" . implode("\n", $e->getResults()) . "\n";
        exit(1);
    } catch (\Siphon\SiphonException $e) {
        echo $e->getMessage() . "\n";
        exit(1);
    } catch (\Exception $e) {
        echo $e->getMessage() . "\n";
        exit(1);
    }
}
