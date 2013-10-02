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
 * @var array $fileMeta
 */
$results = array();

$userOptionReplace = array_key_exists('install_replace', $options) && !is_null($options['install_replace']);
$transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Install options: " . print_r($options, true));
$transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Install attributes: " . print_r($attributes, true));

if ($userOptionReplace && isset($fileMeta['classes'])) {
    foreach ($fileMeta['classes'] as $class) {
        if (!in_array($class, array('modUser', 'modUserProfile', 'modUserGroup', 'modUserGroupMember', 'modUserSetting', 'modSession'))) {
            $results[$class] = $transport->xpdo->exec('TRUNCATE TABLE ' . $transport->xpdo->getTableName($class));
        }
    }
}
$transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Table truncation results: " . print_r($results, true));
return !array_search(false, $results, true);
