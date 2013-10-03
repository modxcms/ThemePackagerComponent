<?php
/*
 * Copyright 2012 by MODX, LLC.
 *
 * This file is part of MODX Vapor.
 *
 * Vapor is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Vapor is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Vapor; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * @var xPDOTransport $transport
 * @var array $options
 * - setup-options setup options php script
 * - enduser_option_merge yes
 * - enduser_install_action_default merge|replace
 * - enduser_option_samplecontent yes
 * - sample_content true
 * - action install
 *
 * @var array $fileMeta
 */
$results = array();

$transport->xpdo->log(xPDO::LOG_LEVEL_INFO, '[truncate] $options: ' . print_r($options, true));

$userOptionReplace = array_key_exists('install_replace', $options) && $options['install_replace'] == 'true';

if ($userOptionReplace && isset($fileMeta['classes'])) {
    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "[truncate] Replace indicated, truncating tables...");
    foreach ($fileMeta['classes'] as $class) {
        $results[$class] = $transport->xpdo->exec('TRUNCATE TABLE ' . $transport->xpdo->getTableName($class));
    }
}
$transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "[truncate] Table truncation results: " . print_r($results, true));
return !array_search(false, $results, true);
