TP.tree.Snippets = function(config) {
    config = config || {};
    Ext.applyIf(
        config,
        {
            id: 'tp-tree-snippets'
            ,dataUrl: TP.config.connector_url + '?action=snippet/getTree'
            ,fields: ['id','name']
            ,data: []
            ,border: false
            ,rootVisible: false
            ,root: {
                nodeType: 'async'
            }
        }
    );
    TP.tree.Snippets.superclass.constructor.call(this,config);
}
Ext.extend(TP.tree.Snippets,TP.tree.LocalTree,{
    updateSelected: function() {
        var selected = [];
        Ext.each(this.getChecked('id'), function(el){
            ma = el.match(/^n_snippet_(\d+)$/);
            if (ma != null && ma.length == 2) {
                selected.push(ma[1]);
            }
        });
        Ext.getCmp('tp-tree-snippets-selected_ids').setValue(selected.join(','));
        return true;
    }
});

Ext.reg('tp-tree-snippets',TP.tree.Snippets);
