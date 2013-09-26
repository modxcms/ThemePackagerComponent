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
 * @var modSystemSetting $object
 * @var array $fileMeta
 */
if ($object instanceof modSystemSetting && $object->get('key') === 'extension_packages') {
    $extPackages = $object->get('value');
    $extPackages = $transport->xpdo->fromJSON($extPackages);
    if (!is_array($extPackages)) $extPackages = array();
    if (is_array($fileMeta) && array_key_exists('extension_packages', $fileMeta)) {
        $optPackages = $transport->xpdo->fromJSON($fileMeta['extension_packages']);
        if (is_array($optPackages)) {
            $extPackages = array_merge($extPackages, $optPackages);
        }
    }
    if (!empty($extPackages)) {
        foreach ($extPackages as $extPackage) {
            if (!is_array($extPackage)) continue;

            foreach ($extPackage as $packageName => $package) {
                if (!empty($package) && !empty($package['path'])) {
                    $package['tablePrefix'] = !empty($package['tablePrefix']) ? $package['tablePrefix'] : null;
                    $package['path'] = str_replace(array(
                        '[[++core_path]]',
                        '[[++base_path]]',
                        '[[++assets_path]]',
                        '[[++manager_path]]',
                    ),array(
                        $transport->xpdo->config['core_path'],
                        $transport->xpdo->config['base_path'],
                        $transport->xpdo->config['assets_path'],
                        $transport->xpdo->config['manager_path'],
                    ),$package['path']);
                    $transport->xpdo->addPackage($packageName,$package['path'],$package['tablePrefix']);
                    if (!empty($package['serviceName']) && !empty($package['serviceClass'])) {
                        $packagePath = str_replace('//','/',$package['path'].$packageName.'/');
                        $transport->xpdo->getService($package['serviceName'],$package['serviceClass'],$packagePath);
                    }
                }
            }
        }
    }
}