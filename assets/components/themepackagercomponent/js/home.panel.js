TP.panel.Home = function(config) {
    config = config || {};

    // create site root file checkboxen
    var fs = {};
    if(TP.config.rootFiles) {
        var items = [];
        items.push({
            border: false
            ,html: '<p>Pick any files from your site root that you wish to be included in your Theme.</p>'
        });
        Ext.each(TP.config.rootFiles, function(f){
            items.push({
                xtype: 'checkbox'
                ,id: 'rootfile-' + f.replace(' ', '_')
                ,name: 'rootFiles[]'
                ,boxLabel: f
                ,inputValue: f
            });
        });
        fs = {
            xtype: 'fieldset'
            ,items: items
        }
    }

    Ext.apply(config,{
        id: 'tp-panel-home'
        ,url: TP.config.connector_url
        ,baseParams: {
            action: 'build'
        }
        ,border: false
        ,baseCls: 'modx-formpanel'
        ,fileUpload: true
        ,items: [{
            html: '<h2>'+_('themepackagercomponent')+'</h2>'
            ,border: false
            ,id: 'tp-home-header'
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,border: true
            ,autoDestroy: false
            ,deferredRender: false
            ,defaults: { border: false ,autoHeight: true, bodyStyle: 'padding: 15px;' }
            ,items: [{
                title: _('themepackagercomponent.package')
                ,draggable: false
                ,layout: 'form'
                ,labelWidth: 200
                ,items: [{
                    html: _('themepackagercomponent.intro_msg')
                    ,border: false
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('themepackagercomponent.package_name')
                    ,description: _('themepackagercomponent.package_name_desc')
                    ,name: 'category'
                    ,id: 'tp-category-name'
                    ,value: _('themepackagercomponent.mypackage')
                },{
                    xtype: 'textfield'
                    ,name: 'version'
                    ,fieldLabel: _('themepackagercomponent.version')
                    ,description: _('themepackagercomponent.version_desc')
                    ,value: '1.0'
                },{
                    xtype: 'textfield'
                    ,name: 'release'
                    ,fieldLabel: _('themepackagercomponent.release')
                    ,description: _('themepackagercomponent.release_desc')
                    ,value: 'beta1'
                },{
                    xtype: 'checkbox'
                    ,id: 'tpc-package-everything-checkbox'
                    ,name: 'everything'
                    ,fieldLabel: _('themepackagercomponent.everything')
                    ,description: _('themepackagercomponent.everything_desc')
                    ,inputValue: 'yes'
                    ,checked: false
                    ,listeners: {
                        check: {
                            fn: function (el, checked) {
                                if (checked) {
                                    Ext.getCmp('tpc-tab-templates').disable();
                                    Ext.getCmp('tpc-tab-chunks').disable();
                                    Ext.getCmp('tpc-tab-subpackages').disable();
                                    Ext.getCmp('tpc-tab-directories').disable();
                                    Ext.getCmp('tpc-tab-resources').disable();
                                    //Ext.getCmp('tpc-enduser-option-samplecontent').setValue(true).disable();
                                } else {
                                    Ext.getCmp('tpc-tab-templates').enable();
                                    Ext.getCmp('tpc-tab-chunks').enable();
                                    Ext.getCmp('tpc-tab-subpackages').enable();
                                    Ext.getCmp('tpc-tab-directories').enable();
                                    Ext.getCmp('tpc-tab-resources').enable();
                                    //Ext.getCmp('tpc-enduser-option-samplecontent').enable();
                                }
                            }
                            ,scope: this
                        }
                    }
                },{
                    xtype: 'fieldset'
                    ,id: 'tpc-fieldset-install-options'
                    ,title: _('themepackagercomponent.install_options_title')
                    ,autoHeight: true
                    ,columnWidth: 0.5
                    ,collapsed: false
                    ,defaults: { border: false ,autoHeight: true }
                    ,items: [
                        {
                            html: _('themepackagercomponent.enduser_option_desc')
                        },{
                            xtype: 'checkbox'
                            ,id: 'tpc-enduser_option_merge-checkbox'
                            ,name: 'enduser_option_merge'
                            ,fieldLabel: _('themepackagercomponent.enduser_option_merge_label')
                            ,description: _('themepackagercomponent.enduser_option_merge_desc')
                            ,checked: true
                            ,inputValue: 'yes'
//                            ,listeners: {
//                                check: {
//                                    fn: function (el, checked) {
//                                        if (checked) {
//                                            Ext.getCmp('tpc-default-merge-option-picker').hide();
//                                            Ext.getCmp('tpc-default-merge-option-picker-merge').hide();
//                                            Ext.getCmp('tpc-default-merge-option-picker-replace').hide();
//                                        } else {
//                                            Ext.getCmp('tpc-default-merge-option-picker').show();
//                                            Ext.getCmp('tpc-default-merge-option-picker-merge').show();
//                                            Ext.getCmp('tpc-default-merge-option-picker-replace').show();
//                                        }
//                                    }
//                                    ,scope: this
//                                }
//                            }
                        },{
                            xtype: 'radiogroup'
                            ,id: 'tpc-default-merge-option-picker'
                            ,fieldLabel: 'Default install action'
                            ,hidden: false
                            ,description: 'Specify whether your Theme package will Merge with the user\'s site on install, or completely replace it.'
                            ,width: 300
                            ,defaults: {labelSeparator: ''}
                            ,items: [
                                {
                                    xtype: 'radio'
                                    ,id: 'tpc-default-merge-option-picker-merge'
                                    ,name: 'enduser_install_action_default'
                                    ,boxLabel: 'Merge'
                                    ,inputValue: 'merge'
                                    ,checked: true
                                }, {
                                    xtype: 'radio'
                                    ,id: 'tpc-default-merge-option-picker-replace'
                                    ,name: 'enduser_install_action_default'
                                    ,boxLabel: 'Replace'
                                    ,inputValue: 'replace'
                                }
                            ]
                        },{
                            xtype: 'checkbox'
                            ,id: 'tpc-enduser-option-samplecontent'
                            ,name: 'enduser_option_samplecontent'
                            ,fieldLabel: _('themepackagercomponent.enduser_option_samplecontent_label')
                            ,description: _('themepackagercomponent.enduser_option_samplecontent_desc')
                            ,checked: true
                            ,inputValue: 'yes'
                        },{
                            xtype: 'radiogroup'
                            ,id: 'tpc-default-samplecontent-picker'
                            ,fieldLabel: 'Sample Content default'
                            ,hidden: false
                            ,description: 'Specify whether the Theme install will default to adding your sample content or not.'
                            ,width: 300
                            ,defaults: {labelSeparator: ''}
                            ,items: [
                                {
                                    xtype: 'radio'
                                    ,id: 'tpc-default-samplecontent-picker-yes'
                                    ,name: 'enduser_install_samplecontent_default'
                                    ,boxLabel: 'Yes'
                                    ,inputValue: 'yes'
                                    ,checked: true
                                }, {
                                    xtype: 'radio'
                                    ,id: 'tpc-default-samplecontent-picker-no'
                                    ,name: 'enduser_install_samplecontent_default'
                                    ,boxLabel: 'No'
                                    ,inputValue: 'no'
                                }
                            ]
                        }
                    ]
                },{
                    xtype: 'fieldset'
                    ,id: 'tpc-fieldset-advanced'
                    ,title: _('themepackagercomponent.advanced_title')
                    ,autoHeight: true
                    ,columnWidth: 0.5
                    ,collapsible: true
                    ,collapsed: true
                    ,titleCollapse: true
                    ,defaults: { border: false ,autoHeight: true }
                    ,items: [
                        {
                            xtype: 'textfield'
                            ,id: 'tpc-advanced-settings'
                            ,name: 'settings'
                            ,grow: true
                            ,growMin: 250
                            ,growMax: 1000
                            ,fieldLabel: _('themepackagercomponent.advanced.settings')
                            ,description: _('themepackagercomponent.advanced.settings_desc')
                        }
                    ]
                }]
            },{
                title: _('themepackagercomponent.templates')
                ,id: 'tpc-tab-templates'
                ,disabled: false
                ,deferredRender: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [
                    {
                        html: _('themepackagercomponent.templates.intro_msg')
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-templates-selected_ids'
                        ,name: 'tp_template_ids'
                        ,value: ''
                    },{
                        xtype: 'tp-tree-templates'
                        ,id: 'tp-tree-templates-panel'
                        ,listeners: {
                            checkchange: function(el,checked) {
                                if (el.childNodes.length > 0) {
                                    for (var child in el.childNodes) {
                                        if (!isNaN(child)) {
                                            el.childNodes[child].ui.checkbox.checked = checked
                                            el.childNodes[child].attributes.checked = checked
                                        }
                                    }
                                }
                                this.updateSelected();
                            }
                        }
                    }
                ]
            },{
                title: _('themepackagercomponent.chunks')
                ,id: 'tpc-tab-chunks'
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [
                    {
                        html: _('themepackagercomponent.chunks.intro_msg')
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-chunks-selected_ids'
                        ,name: 'tp_chunk_ids'
                        ,value: ''
                    }
                    ,{
                        xtype: 'tp-tree-chunks'
                        ,id: 'tp-tree-chunks-panel'
                        ,listeners: {
                            checkchange: function(el,checked) {
                                if (el.childNodes.length > 0) {
                                    for (var child in el.childNodes) {
                                        if (!isNaN(child)) {
                                            el.childNodes[child].ui.checkbox.checked = checked
                                            el.childNodes[child].attributes.checked = checked
                                        }
                                    }
                                }
                                this.updateSelected();
                            }
                        }
                    }
                ]
            },{
                title: _('themepackagercomponent.subpackages')
                ,id: 'tpc-tab-subpackages'
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [{
                    html: _('themepackagercomponent.subpackages.intro_msg')
                },{
                    xtype: 'tp-grid-packages'
                    ,id: 'tp-grid-packages'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.resources')
                ,id: 'tpc-tab-resources'
                ,layout: 'form'
                ,labelWidth: 200
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [{
                    html: _('themepackagercomponent.resources.intro_msg')
                    ,style: {
                        marginBottom: '20px'
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('themepackagercomponent.resources_field')
                    ,description: _('themepackagercomponent.resources_field_desc')
                    ,name: 'resources'
                    ,id: 'tp-resource-list'
                    ,value: ''
                    ,regex: /^[0-9]+\s*(,\s*[0-9]+\s*)*$/
                    ,regexText: _('themepackagercomponent.resources_field_invalid')
                    ,msgTarget: 'under'
                }]
            },{
                title: _('themepackagercomponent.snippets')
                ,id: 'tpc-tab-snippets'
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [
                    {
                        html: _('themepackagercomponent.snippets.intro_msg')
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-snippets-selected_ids'
                        ,name: 'tp_snippet_ids'
                        ,value: ''
                    }
                    ,{
                        xtype: 'tp-tree-snippets'
                        ,id: 'tp-tree-snippets-panel'
                        ,listeners: {
                            checkchange: function(el,checked) {
                                if (el.childNodes.length > 0) {
                                    for (var child in el.childNodes) {
                                        if (!isNaN(child)) {
                                            el.childNodes[child].ui.checkbox.checked = checked
                                            el.childNodes[child].attributes.checked = checked
                                        }
                                    }
                                }
                                this.updateSelected();
                            }
                        }
                    }
                ]
            },{
                title: _('themepackagercomponent.plugins')
                ,id: 'tpc-tab-plugins'
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [
                    {
                        html: _('themepackagercomponent.plugins.intro_msg')
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-plugins-selected_ids'
                        ,name: 'tp_plugin_ids'
                        ,value: ''
                    }
                    ,{
                        xtype: 'tp-tree-plugins'
                        ,id: 'tp-tree-plugins-panel'
                        ,listeners: {
                            checkchange: function(el,checked) {
                                if (el.childNodes.length > 0) {
                                    for (var child in el.childNodes) {
                                        if (!isNaN(child)) {
                                            el.childNodes[child].ui.checkbox.checked = checked
                                            el.childNodes[child].attributes.checked = checked
                                        }
                                    }
                                }
                                this.updateSelected();
                            }
                        }
                    }
                ]
            },{
                title: _('themepackagercomponent.directories')
                ,id: 'tpc-tab-directories'
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [fs, {
                    html: _('themepackagercomponent.directories.intro_msg')
                },{
                    xtype: 'tp-grid-directories'
                    ,id: 'tp-grid-directories'
                    ,preventRender: true
                }]
            }]
        }]
        ,listeners: {
            'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    TP.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(TP.panel.Home,MODx.FormPanel,{
    beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            templates: Ext.getCmp('tp-tree-templates-selected_ids').getValue()
            ,chunks: Ext.getCmp('tp-tree-chunks-selected_ids').getValue()
            ,plugins: Ext.getCmp('tp-tree-plugins-selected_ids').getValue()
            ,snippets: Ext.getCmp('tp-tree-snippets-selected_ids').getValue()
            ,packages: Ext.getCmp('tp-grid-packages').encode()
            ,directories: Ext.getCmp('tp-grid-directories').encode()
        });
    }
    ,success: function(o) {
        if (o.result.success) {
            var name = o.result.message;

            Ext.getCmp('tp-btn-export').setDisabled(false);
            Ext.getCmp('tp-grid-packages').getStore().commitChanges();
            Ext.getCmp('tp-grid-directories').getStore().commitChanges();
            
            location.href = TP.config.connector_url+'?action=build&download='+name+'&HTTP_MODAUTH='+MODx.siteId;
        }
    }
});
Ext.reg('tp-panel-home',TP.panel.Home);