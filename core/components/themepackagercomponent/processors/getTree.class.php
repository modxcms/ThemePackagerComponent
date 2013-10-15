<?php
class tpGetTreeProcessor extends modObjectGetListProcessor {
    public $classKey = 'modTemplate';
    public $categoryAlias = 'Templates';
    public $elementNodeId = 'template';
    public $defaultSortField = 'name';
    public $elementNameField = 'templatename';

    public function process() {
        $Categories = array();
        $elementList = array();
        $ElementQuery = $this->modx->newQuery($this->classKey);
        $ElementQuery->sortby('parent');
        $Elements = $this->modx->getCollectionGraph($this->classKey, '{"Category":{}}', $ElementQuery);
        foreach ($Elements as $Element) {
            if (isset($Element->Category) && is_object($Element->Category)) {
                $cat_id = $Element->Category->get('id');
                $Category = $Element->Category;
                if (!array_key_exists($this->categoryAlias, $Category)) {
                    $Category->{$this->categoryAlias} = array();
                }
                if (!array_key_exists($cat_id, $Categories)) {
                    $Categories[$cat_id] = $Category;
                }
            } else {
                $elementList[] = $this->prepareNode($Element);
            }
        }
        foreach ($Categories as $Category) {
            $elementList[] = $this->prepareNode($Category);
        }
        return $this->toJSON(array_values($elementList));
    }

    protected function prepareNode($obj) {
        $class = $obj->_class;
        if ($class == $this->classKey) {
            return array(
                'text' => $obj->get($this->elementNameField),
                'id' => "n_{$this->elementNodeId}_" . $obj->get('id'),
                'leaf' => true,
                'type' => $this->elementNodeId,
                'cls' => "icon-{$this->elementNodeId}",
                'checked'=> false
            );
        } elseif ($class == 'modCategory') {
            $children = array();
            foreach ($obj->{$this->categoryAlias} as $Element) {
                $children[] = $this->prepareNode($Element);
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

