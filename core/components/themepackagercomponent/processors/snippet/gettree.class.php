<?php
require_once dirname(dirname(__FILE__)) . '/getTree.class.php';
class tpGetSnippetTreeProcessor extends tpGetTreeProcessor {
    public $classKey = 'modSnippet';
    public $categoryAlias = 'Snippets';
    public $elementNodeId = 'snippet';
    public $defaultSortField = 'name';
    public $elementNameField = 'name';
}
return 'tpGetSnippetTreeProcessor';
