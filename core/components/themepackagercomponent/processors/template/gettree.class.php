<?php
require_once dirname(dirname(__FILE__)) . '/getTree.class.php';
class tpGetTemplateTreeProcessor extends tpGetTreeProcessor {
    public $classKey = 'modTemplate';
    public $categoryAlias = 'Templates';
    public $elementNodeId = 'template';
    public $defaultSortField = 'templatename';
    public $elementNameField = 'templatename';
}
return 'tpGetTemplateTreeProcessor';
