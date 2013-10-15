TP.tree.Templates = function(config) {
    config = config || {};
    Ext.applyIf(
        config,
        {
            id: 'tp-tree-templates'
            ,dataUrl: TP.config.connector_url + '?action=template/getTree'
            ,fields: ['id','name']
            ,data: []
            ,border: false
            ,rootVisible: false
            ,root: {
                nodeType: 'async'
            }
        }
    );
    TP.tree.Templates.superclass.constructor.call(this,config);
}
Ext.extend(TP.tree.Templates,TP.tree.LocalTree,{
    updateSelected: function() {
        var selected = [];
        Ext.each(this.getChecked('id'), function(el){
            ma = el.match(/^n_template_(\d+)$/);
            if (ma != null && ma.length == 2) {
                selected.push(ma[1]);
            }
        });
        Ext.getCmp('tp-tree-templates-selected_ids').setValue(selected.join(','));
        return true;
    }
});

Ext.reg('tp-tree-templates',TP.tree.Templates);
