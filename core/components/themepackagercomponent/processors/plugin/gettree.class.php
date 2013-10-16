<?php
require_once dirname(dirname(__FILE__)) . '/getTree.class.php';
class tpGetPluginTreeProcessor extends tpGetTreeProcessor {
    public $classKey = 'modPlugin';
    public $categoryAlias = 'Plugins';
    public $elementNodeId = 'plugin';
    public $defaultSortField = 'name';
    public $elementNameField = 'name';
}
return 'tpGetPluginTreeProcessor';
