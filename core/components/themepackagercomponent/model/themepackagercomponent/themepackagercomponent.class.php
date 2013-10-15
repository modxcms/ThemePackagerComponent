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
 * ThemePackagerComponent main class file.
 *
 * @package themepackagercomponent
 */
class ThemePackagerComponent {
    public $modx = null;
    public $config = array();

    /**
     * Default constructor for ThemePackagerComponent
     *
     * @constructor
     * @param modX &$modx A reference to a modX instance.
     * @param array $config (optional) Configuration properties.
     * @return themepackagercomponent
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $modx->getOption('themepackagercomponent.core_path',null,$modx->getOption('core_path').'components/themepackagercomponent/');
        $assetsUrl = $modx->getOption('themepackagercomponent.assets_url',null,$modx->getOption('assets_url').'components/themepackagercomponent/');

        $this->config = array_merge(array(
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',
            'includesPath' => $corePath.'includes/',

            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'connectorUrl' => $assetsUrl.'connector.php',
        ),$config);

        $this->modx->addPackage('themepackagercomponent',$this->config['modelPath']);
        $this->modx->lexicon->load('themepackagercomponent:default');
    }

    /**
     * Runs the ThemePackagerComponent manager pages
     *
     * @access public
     * @return string The output HTML
     */
    public function initialize() {

        // get non-core files in root
        $root_files = array();
        if ($root_scan = scandir(MODX_BASE_PATH)) {
            foreach ($root_scan as $root_file) {
                if (!in_array($root_file, array('readme.txt', 'license.txt', 'changelog.txt', 'core', 'assets', 'connectors', 'manager', 'index.php', 'ht.access', 'config.core.php', 'robots.txt')) && $root_file[0] !== '.') {
                    $root_files[] = $root_file;
                }
            }
        }
        $this->config['rootFiles'] = $root_files;

        $viewHeader = include $this->config['controllersPath'].'mgr/header.php';
        $this->modx->regClientCSS($this->config['cssUrl'].'mgr.css');

        $f = $this->config['controllersPath'].'mgr/home.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
}