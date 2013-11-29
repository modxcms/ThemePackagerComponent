<?php
$return = true;
if ($transport && $transport->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $transport->xpdo;

             // Maps - here we build up some data and store in placeholder(s) for use later in a cleanup script
            //

            $object_id = $object->get('id');

            // create "portable id" map
            if (!$map = $modx->getPlaceholder('theme_packager_resolver_map')) {
                $map = array();
            }
            if (!array_key_exists('resources', $map)) {
                $map['resources'] == array();
            }
            $map['resources'][$options['native_key']] = $object_id;

            // create parent attribute map
            if (array_key_exists('parent_key', $options)) {
                if (!array_key_exists('resolve_parents', $map)) {
                    $map['resolve_parents'] == array();
                }
                $map['resolve_parents'][$object_id] = $options['parent_key'];
            }

            // cleanup script will access this data later via this placeholder
            $modx->setPlaceholder('theme_packager_resolver_map', $map);

    }
}

return $return;
