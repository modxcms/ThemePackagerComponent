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
 * ThemePackagerComponent Connector
 *
 * @package themepackagercomponent
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$basePath = $modx->getOption('themepackagercomponent.core_path',null,$modx->getOption('core_path').'components/themepackagercomponent/');
require_once $basePath.'model/themepackagercomponent/themepackagercomponent.class.php';
$modx->tp = new ThemePackagerComponent($modx);

/* handle request */
$path = $modx->getOption('processorsPath',$modx->tp->config,$modx->getOption('core_path').'components/tp/processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));