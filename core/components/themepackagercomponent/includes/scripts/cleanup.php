<?php
/** @var modX $modx */
$modx = $transport->xpdo;

//$modx->log(xPDO::LOG_LEVEL_INFO, 'running cleanup resolver');

// ensure there is at least one resource in the site
$query = $modx->newQuery('modResource');
$count = $modx->getCount('modResource', $query);
if ($count == 0) {
    $Resource = $modx->newObject('modResource');
    $Resource->set('pagetitle', 'Home');
    $Resource->save();
    $modx->log(xPDO::LOG_LEVEL_INFO, 'added base resource so tree is not empty');
}
unset ($Resource);

// do "portable id" resolution
if (!function_exists('modx_tpc_portable_id_replace')) {
    function modx_tpc_portable_id_replace($matches) {
        $return = array();
        $map = $GLOBALS['modx']->getPlaceholder('theme_packager_resolver_map');
        if (is_array($matches) && count($matches) > 2) {
            $ids = array_map('trim', explode(',', $matches[2]));
            $index = '';
            switch ($matches[1]) {
                case 'resource':
                case 'r':
                    $index = 'resources';
                    break;

                case 'chunk':
                case 'c':
                    $index = 'chunks';
                    break;

                case 'template':
                case 'tpl':
                case 't':
                    $index = 'templates';
            }
            if (!empty($index) && array_key_exists($index, $map) && count($ids)) {
                foreach ($ids as $id) {
                    if (array_key_exists($id, $map[$index])) {
                        $return[] = $map[$index][$id];
                    }
                }
            }
            if (count($return) == 0) {
                $return = $ids;
            }
        }
        if (count($return) > 0) {
            return implode(',', $return);
        } else {
            return $matches[0];
        }
    }
}

// regex to find portable id snippet in content
$p_id_signature = '/\[\[\!?p_id\?\s*&id=`([a-zA-Z]+)([0-9,\s]+)`\s*\]\]/';

// resource content
$Resources = $modx->getIterator('modResource');
foreach ($Resources as $Resource) {
    $content = $Resource->_fields['content'];
    $newcontent = preg_replace_callback($p_id_signature, 'modx_tpc_portable_id_replace', $content);
    if ($newcontent != $content) {
        $Resource->set('content', $newcontent);
        $Resource->save();
        $modx->log(xPDO::LOG_LEVEL_INFO, "Resource id " . $Resource->get('id') . " updated to replace portable ids.");
    }
}
// Templates
$Templates = $modx->getIterator('modTemplate');
foreach ($Templates as $Template) {
    $content = $Template->_fields['content'];
    $newcontent = preg_replace_callback($p_id_signature, 'modx_tpc_portable_id_replace', $content);
    if ($newcontent != $content) {
        $Template->set('content', $newcontent);
        $Template->save();
        $modx->log(xPDO::LOG_LEVEL_INFO, "Template id " . $Template->get('id') . " updated to replace portable ids.");
    }
}
// Chunks
$Chunks = $modx->getIterator('modChunk');
foreach ($Chunks as $Chunk) {
    $content = $Chunk->_fields['snippet'];
    $newcontent = preg_replace_callback($p_id_signature, 'modx_tpc_portable_id_replace', $content);
    if ($newcontent != $content) {
        $Chunk->set('snippet', $newcontent);
        $Chunk->save();
        $modx->log(xPDO::LOG_LEVEL_INFO, "Chunk id " . $Chunk->get('id') . " updated to replace portable ids.");
    }
}

// Resolve parent relationships stored in resolver map
$map = $modx->getPlaceholder('theme_packager_resolver_map');
if (is_array($map) && array_key_exists('resolve_parents', $map) && is_array($map['resolve_parents'])) {
    foreach ($map['resolve_parents'] as $resource_id => $parent_key) {
        if (!$modx->config['friendly_urls']) {
            unset($parent_key['uri']);
        }
        if ($Resource = $modx->getObject('modResource', $resource_id)) {
            if ($Parent = $modx->getObject('modResource', $parent_key)) {
                $Resource->set('parent', $Parent->get('id'));
                if (!$Resource->save()) {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, "Error saving Resource " . $Resource->get('id') . " while resolving to parent " . $Parent->get('id'));
                }
            } else {
                $modx->log(xPDO::LOG_LEVEL_ERROR, "Error finding parent for Resource " . $Resource->get('id') . " .. with parent attributes " . print_r($parent_key, true));
            }
        }
    }
}



return true;
