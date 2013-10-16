<?php
require_once dirname(dirname(__FILE__)) . '/getTree.class.php';
class tpGetChunkTreeProcessor extends tpGetTreeProcessor {
    public $classKey = 'modChunk';
    public $categoryAlias = 'Chunks';
    public $elementNodeId = 'chunk';
    public $defaultSortField = 'name';
    public $elementNameField = 'name';
}
return 'tpGetChunkTreeProcessor';
