<?php
/**
 * chunk resolver
 *
 * @var xPDOObject $object
 * @var integer $options['native_key'] original id of chunk
 *
 */
$return = true;
if ($transport && $transport->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $transport->xpdo;

            // create "portable id" map
            if (!$map = $modx->getPlaceholder('theme_packager_resolver_map')) {
                $map = array();
            }
            if (!array_key_exists('chunks', $map)) {
                $map['chunks'] == array();
            }
            $map['chunks'][$options['native_key']] = $object->get('id');
            $modx->setPlaceholder('theme_packager_resolver_map', $map);
    }
}

return $return;
