<?php
/**
 * ThemePackagerComponent
 *
 * Copyright 2013 by Mike Schell <mike@modx.com> for MODX, LLC
 *
 * This file is part of ThemePackagerComponent.
 *
 * ThemePackagerComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThemePackagerComponent is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ThemePackagerComponent; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package themepackagercomponent
 */
/**
 * Builds the package and exports it.
 *
 * @package themepackagercomponent
 * @subpackage processors
 */
/* if downloading the file last exported */
if (!empty($_REQUEST['download'])) {
    $file = $_REQUEST['download'];
    sleep(.5); /* to make sure not to go too fast */
    $d = $modx->getOption('core_path').'packages/'.$_REQUEST['download'];
    $f = $d.'.transport.zip';

    if (!is_file($f)) {
        //$modx->log(xPDO::LOG_LEVEL_ERROR, "No file to download at $f");
        return '';
    }

    $o = file_get_contents($f);
    $bn = basename($file);
    //$modx->log(xPDO::LOG_LEVEL_ERROR, "File found: $f .. size: " . strlen($f));

    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=\"{$bn}.transport.zip\"");

    /* remove package files now that we are through */
    @unlink($f);
    $modx->cacheManager->deleteTree($d.'/',true,false,array());

    return $o;
}

/* verify form */
if (empty($_POST['category'])) $modx->error->addField('category',$modx->lexicon('themepackagercomponent.category_err_ns'));
if (empty($_POST['version'])) $modx->error->addField('version',$modx->lexicon('themepackagercomponent.version_err_nf'));
if (empty($_POST['release'])) $modx->error->addField('release',$modx->lexicon('themepackagercomponent.release_err_nf'));

/* if any errors, return and dont proceed */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* get version, release, files */
$version = $_POST['version'];
$release = $_POST['release'];

/* format package name */
$name = $_POST['category'];
$name = str_replace(array(' ','-','.','*','!','@','#','$','%','^','&','_'),'',$name);
$name_lower = strtolower($name);

/* define file paths and string replacements */
$directories = $_POST['directories'];
$cachePath = $modx->getOption('core_path').'cache/';
$pathLookups = array(
    'sources' => array(
        '{base_path}',
        '{core_path}',
        '{assets_path}',
    ),
    'targets' => array(
        $modx->getOption('base_path',null,MODX_BASE_PATH),
        $modx->getOption('core_path',null,MODX_CORE_PATH),
        $modx->getOption('assets_path',null,MODX_ASSETS_PATH),
    )
);

$params = array(
    'version'=> $version
    ,'release'=> $release
    ,'name'=> $name
    ,'name_lower'=> $name_lower
    ,'directories'=> $directories
    ,'cachePath'=> $cachePath
    ,'pathLookups'=> $pathLookups
    ,'s3' => array(
        'key' => '',
        'secretKey' => ''
    ),
);

$everything = $modx->getOption('everything', $_POST, 'no');
$params = array_merge($_POST, $params);

$builderClass = 'Modx_tpcVaporBuilder';
$builder = $modx->getService('tpcBuilder', 'Modx_tpcVaporBuilder', $modx->tp->config['corePath'] . 'model/themepackagercomponent/', $params);
/** @var modError $result */
$response = $builder->handle();
return $response;
