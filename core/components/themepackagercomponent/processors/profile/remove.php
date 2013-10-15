<?php
/**
 * Remove a profile
 *
 * @package themepackagercomponent
 * @subpackage processors
 */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('themepackagercomponent.profile_err_ns'));
$profile = $modx->getObject('tpcProfile',$scriptProperties['id']);
if (empty($profile)) return $modx->error->failure($modx->lexicon('themepackagercomponent.profile_err_nf'));

if ($profile->remove() === false) {
    return $modx->error->failure($modx->lexicon('themepackagercomponent.profile_err_remove'));
}


return $modx->error->success('',$profile);
