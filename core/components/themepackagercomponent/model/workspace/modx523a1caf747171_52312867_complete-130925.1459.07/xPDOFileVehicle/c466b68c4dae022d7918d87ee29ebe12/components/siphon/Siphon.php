<?php
/*
 * MODX Siphon
 *
 * Copyright 2012 by MODX, LLC.
 * All rights reserved.
 */
define('SIPHON_BASE_PATH', dirname(__FILE__) . '/');
define('SIPHON_APP_ID', 'siphon');

require_once dirname(__FILE__) . '/SiphonLoader.php';

try {
    /* get config.php if it exists */
    $config = file_exists(SIPHON_BASE_PATH . 'config.php') ? include SIPHON_BASE_PATH . 'config.php' : array();
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
