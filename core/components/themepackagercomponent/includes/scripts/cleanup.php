<?php

/** @var modX $modx */
$modx = $transport->xpdo;
//$modx->log(xPDO::LOG_LEVEL_INFO, 'running cleanup resolver');
$query = $modx->newQuery('modResource');
$count = $modx->getCount('modResource', $query);
if ($count == 0) {
    $Resource = $modx->newObject('modResource');
    $Resource->set('pagetitle', 'Home');
    $Resource->save();
    $modx->log(xPDO::LOG_LEVEL_INFO, 'added base resource so tree is not empty');
}
return true;
