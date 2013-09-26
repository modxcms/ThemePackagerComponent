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
 * @var modMediaSource $object
 * @var array $fileMeta
 */
$resolved = false;
if ($object instanceof modFileMediaSource && isset($fileMeta['target']) && !empty($fileMeta['target'])) {
    if ($object->initialize()) {
        $basePath = $fileMeta['target'];
        $properties = $object->getProperties(true);
        if (isset($fileMeta['targetRelative']) && !empty($fileMeta['targetRelative'])) {
            $properties['basePathRelative']['value'] = true;
            $properties['baseUrlRelative']['value'] = true;
            $properties['baseUrl']['value'] = ltrim($basePath, '/');
        }
        if (isset($fileMeta['targetPrepend']) && !empty($fileMeta['targetPrepend'])) {
            $properties['basePath']['value'] = eval($fileMeta['targetPrepend']) . ltrim($basePath, '/');
        } else {
            $properties['basePath']['value'] = $basePath;
        }
        if ($object->setProperties($properties)) {
            $resolved = $object->save();
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error saving media source properties: " . print_r($object->getPropertyList(), true));
        }
    } else {
        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error initializing media source!");
    }
} else {
    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Resolver attached to invalid media source with options: " . print_r($fileMeta, true));
}
return $resolved;
