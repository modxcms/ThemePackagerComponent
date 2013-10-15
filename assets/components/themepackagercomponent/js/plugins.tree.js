TP.tree.Plugins = function(config) {
    config = config || {};
    Ext.applyIf(
        config,
        {
            id: 'tp-tree-plugins'
            ,dataUrl: TP.config.connector_url + '?action=plugin/getTree'
            ,fields: ['id','name']
            ,data: []
            ,border: false
            ,rootVisible: false
            ,root: {
                nodeType: 'async'
            }
        }
    );
    TP.tree.Plugins.superclass.constructor.call(this,config);
}
Ext.extend(TP.tree.Plugins,TP.tree.LocalTree,{
    updateSelected: function() {
        var selected = [];
        Ext.each(this.getChecked('id'), function(el){
            ma = el.match(/^n_plugin_(\d+)$/);
            if (ma != null && ma.length == 2) {
                selected.push(ma[1]);
            }
        });
        Ext.getCmp('tp-tree-plugins-selected_ids').setValue(selected.join(','));
        return true;
    }
});

Ext.reg('tp-tree-plugins',TP.tree.Plugins);
