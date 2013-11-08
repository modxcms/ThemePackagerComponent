<?php
$return = true;
if ($transport && $transport->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $transport->xpdo;
            if (array_key_exists('parent_key', $options) && !empty($options['parent_key'])) {
                $parent_key = $options['parent_key'];
                if (!$modx->config['friendly_urls']) {
                    unset($parent_key['uri']);
                }
                if ($Parent = $modx->getObject('modResource', $parent_key)) {
                    $object->set('parent', $Parent->get('id'));
                    $return = $object->save();
                } else {
                    $return = false;
                }
            }
    }
}

return $return;
