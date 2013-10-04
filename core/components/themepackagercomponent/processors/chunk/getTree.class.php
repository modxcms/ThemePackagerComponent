<?php
class tpGetChunkTreeProcessor extends modObjectGetListProcessor {
    public $classKey = 'modChunk';
    public $defaultSortField = 'name';

    public function process() {
        $Categories = array();
        $ChunkQuery = $this->modx->newQuery('modChunk');
        $ChunkQuery->sortby('parent');
        $Chunks = $this->modx->getCollectionGraph('modChunk', '{"Category":{}}', $ChunkQuery);
        foreach ($Chunks as $Chunk) {
            if (isset($Chunk->Category) && is_object($Chunk->Category)) {
                $cat_id = $Chunk->Category->get('id');
                //$cat_parent_id = $Chunk->Category->get('parent');
                $Category = $Chunk->Category;
                if (!array_key_exists('Chunks', $Category)) {
                    $Category->Chunks = array();
                }
                $Category->Chunks[] = $Chunk;
                if (!array_key_exists($cat_id, $Categories)) {
                    $Categories[$cat_id] = $Category;
                }
            } else {
                $chunkList[] = $this->prepareNode($Chunk);
            }
        }
        foreach ($Categories as $Category) {
            $chunkList[] = $this->prepareNode($Category);
        }
        return $this->toJSON(array_values($chunkList));
    }

    protected function prepareNode($obj) {
        $class = $obj->_class;
        if ($class == 'modChunk') {
            return array(
                'text' => $obj->get('name'),
                'id' => 'n_chunk_' . $obj->get('id'),
                'leaf' => true,
                'type' => 'chunk',
                'cls' => 'icon-chunk',
                'checked'=> false
            );
        } elseif ($class == 'modCategory') {
            $children = array();
            foreach ($obj->Chunks as $Chunk) {
                $children[] = $this->prepareNode($Chunk);
            }
            return array(
                'text' => $obj->get('category'),
                'id' => 'n_category_' . $obj->get('id'),
                'leaf' => false,
                'type' => 'category',
                'cls' => 'icon-category',
                'children'=> $children,
                'checked'=> false,
                'expanded'=> true
            );
        }
    }
}
return 'tpGetChunkTreeProcessor';
