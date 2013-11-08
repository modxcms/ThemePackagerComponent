TP.panel.Home = function(config) {
    config = config || {};

    // create site root file checkboxen
    var fs = {};
    if(TP.config.rootFiles) {
        var items = [];
        items.push({
            border: false
            ,html: '<p>Pick any files from your site root that you wish to be included in your Theme.</p>'
            ,style: {
                padding: '8px'
            }
        });
        Ext.each(TP.config.rootFiles, function(f){
            items.push({
                xtype: 'checkbox'
                ,id: 'rootfile-' + f.replace(' ', '_')
                ,name: 'rootFiles[]'
                ,boxLabel: f
                ,inputValue: f
                ,hideLabel: true
                ,style: {marginLeft: '17px'}
            });
        });
        fs = {
            title: 'Files'
            ,items: items
        }
    }

    // create package checkboxen
    var ps = {};
    if(TP.config.packages) {
        var items = [];
        items.push({
            border: false
            ,html: '<p>Pick any transport packages that you would like included in your Theme. They will be added as "subpackages" in your theme package.</p>'
            ,style: {
                padding: '8px'
            }
        });
        Ext.each(TP.config.packages, function(p){
            items.push({
                xtype: 'checkbox'
                ,id: 'subpackage-' + p.replace(' ', '_')
                ,name: 'subpackages[]'
                ,boxLabel: p
                ,inputValue: p
                ,hideLabel: true
                ,style: {marginLeft: '17px'}
            });
        });
        ps = {
            title: 'Packages'
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
        ,cls: 'container'
        ,fileUpload: true
        ,listeners: {
            'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
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
            ,defaults: { border: false ,autoHeight: true, bodyStyle: 'padding: 17px;',labelWidth:200 }
            ,items: [{
                title: _('themepackagercomponent.package')
                ,draggable: false
                ,layout: 'form'
                ,defaults: {border:false,autoHeight:true,bodyStyle:'padding:8px;'}
                ,items: [{
                    html: _('themepackagercomponent.intro_msg')
                    ,border: false
                    ,style: {
                        paddingBottom: '9px'
                    }
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
                    xtype: 'checkboxgroup'
                    ,fieldLabel: 'Package All'
                    ,defaults: {
                        xtype: 'checkbox'
                        ,inputValue: 'yes'
                        ,checked: false
                    }
                    ,items: [
                        {
                            id: 'tpc-package-everything-checkbox'
                            ,name: 'everything'
                            ,boxLabel: _('themepackagercomponent.all_elements')
                            ,description: _('themepackagercomponent.all_elements_desc')
                            ,inputValue: 'yes'
                            ,checked: false
                            ,listeners: {
                                check: {
                                    fn: function (el, checked) {
                                        if (checked) {
                                            Ext.getCmp('tpc-tab-elements').disable();
                                            Ext.getCmp('tpc-tab-subpackages').disable();
                                        } else {
                                            Ext.getCmp('tpc-tab-elements').enable();
                                            Ext.getCmp('tpc-tab-subpackages').enable();
                                        }
                                    }
                                    ,scope: this
                                }
                            }
                        },{
                            id: 'tpc-package-allfiles-checkbox'
                            ,name: 'allfiles'
                            ,boxLabel: _('themepackagercomponent.all_files')
                            ,description: _('themepackagercomponent.all_files_desc')
                            ,inputValue: 'yes'
                            ,checked: false
                            ,listeners: {
                                check: {
                                    fn: function (el, checked) {
                                        if (checked) {
                                            Ext.getCmp('tpc-tab-directories').disable();
                                        } else {
                                            Ext.getCmp('tpc-tab-directories').enable();
                                        }
                                    }
                                    ,scope: this
                                }
                            }
                        }
                    ]
                },{
                    title: _('themepackagercomponent.install_options_title')
                    ,html: _('themepackagercomponent.enduser_option_desc')
                    ,style: {
                        paddingTop: '17px'
                    }
                },{
                    xtype:'fieldset'
                    ,defaults:{border:false,autoHeight:true}
                    ,items: [
                        {
                            xtype: 'compositefield'
                            ,defaults: {border:false,autoHeight:true}
                            ,items: [
                                {
                                    xtype: 'checkbox'
                                    ,id: 'tpc-enduser_option_merge-checkbox'
                                    ,name: 'enduser_option_merge'
                                    ,fieldLabel: _('themepackagercomponent.enduser_option_merge_label')
                                    ,checked: true
                                    ,inputValue: 'yes'
                                },{
                                    xtype:'fieldset'
                                    ,border: true
                                    ,defaults: {border:false, autoHeight:true}
                                    ,items: [
                                        {
                                            xtype: 'radiogroup'
                                            ,id: 'tpc-default-merge-option-picker'
                                            ,hidden: false
                                            ,fieldLabel: 'Default'
                                            ,description: 'Specify whether your Theme package will Merge with the user\'s site on install, or completely replace it.'
                                            ,defaults: {labelSeparator: ''}
                                            ,width: 200
                                            ,items: [
                                                {
                                                    xtype: 'radio'
                                                    ,id: 'tpc-default-merge-option-picker-merge'
                                                    ,name: 'enduser_install_action_default'
                                                    ,boxLabel: 'Add'
                                                    ,inputValue: 'merge'
                                                    ,checked: true
                                                }, {
                                                    xtype: 'radio'
                                                    ,id: 'tpc-default-merge-option-picker-replace'
                                                    ,name: 'enduser_install_action_default'
                                                    ,boxLabel: 'Overwrite'
                                                    ,inputValue: 'replace'
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },{
                            html: '<em>' + _('themepackagercomponent.enduser_option_merge_desc') + '</em>'
                        }
                    ]
                }
                ,{
                    xtype:'fieldset'
                    ,defaults:{border:false,autoHeight:true}
                    ,items: [
                        {
                            xtype: 'compositefield'
                            ,defaults:{border:false,autoHeight:true}
                            ,items:[
                                {
                                    xtype: 'checkbox'
                                    ,id: 'tpc-enduser-option-samplecontent'
                                    ,name: 'enduser_option_samplecontent'
                                    ,fieldLabel: _('themepackagercomponent.enduser_option_samplecontent_label')
                                    ,checked: true
                                    ,inputValue: 'yes'
                                },{
                                    xtype:'fieldset'
                                    ,border: true
                                    ,defaults: {border:false, autoHeight:true}
                                    ,items: [
                                        {
                                            xtype: 'radiogroup'
                                            ,id: 'tpc-default-samplecontent-picker'
                                            ,hidden: false
                                            ,fieldLabel: 'Default'
                                            ,description: 'Specify whether the Theme install will default to adding your sample content or not.'
                                            ,width: 200
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
                                }
                            ]
                        },{
                            html: '<em>' + _('themepackagercomponent.enduser_option_samplecontent_desc') + '</em>'
                        }
                    ]
                }]
            },{
                title: _('themepackagercomponent.elements')
                ,id: 'tpc-tab-elements'
                ,disabled: false
                ,deferredRender: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [
                    // Templates
                    {
                        title: _('themepackagercomponent.templates')
                    },{
                        html: _('themepackagercomponent.templates.intro_msg')
                        ,style: {margin: '10px'}
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-templates-selected_ids'
                        ,name: 'tp_template_ids'
                        ,value: ''
                    },{
                        xtype: 'tp-tree-templates'
                        ,id: 'tp-tree-templates-panel'
                        ,style: {marginLeft: '10px'}
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
                    },{html: '&nbsp;'}

                    // Chunks
                    ,{
                        title: _('themepackagercomponent.chunks')
                    },{
                        html: _('themepackagercomponent.chunks.intro_msg')
                        ,style: {margin: '10px'}
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-chunks-selected_ids'
                        ,name: 'tp_chunk_ids'
                        ,value: ''
                    }
                    ,{
                        xtype: 'tp-tree-chunks'
                        ,id: 'tp-tree-chunks-panel'
                        ,style: {marginLeft: '10px'}
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
                    },{html: '&nbsp;'}

                    // Snippets
                    ,{
                        title: _('themepackagercomponent.snippets')
                    },{
                        html: _('themepackagercomponent.snippets.intro_msg')
                        ,style: {margin: '10px'}
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-snippets-selected_ids'
                        ,name: 'tp_snippet_ids'
                        ,value: ''
                    }
                    ,{
                        xtype: 'tp-tree-snippets'
                        ,id: 'tp-tree-snippets-panel'
                        ,style: {marginLeft: '10px'}
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
                    },{html: '&nbsp;'}

                    // Plugins
                    ,{
                        title: _('themepackagercomponent.plugins')
                    },{
                        html: _('themepackagercomponent.plugins.intro_msg')
                        ,style: {margin: '10px'}
                    },{
                        xtype: 'hidden'
                        ,id: 'tp-tree-plugins-selected_ids'
                        ,name: 'tp_plugin_ids'
                        ,value: ''
                    }
                    ,{
                        xtype: 'tp-tree-plugins'
                        ,id: 'tp-tree-plugins-panel'
                        ,style: {marginLeft: '10px'}
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
                    },{html: '&nbsp;'}
                    //,ps

                    // end of Elements tab
                ]
            }
            ,{
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
            }
            ,{
                title: _('themepackagercomponent.directories')
                ,id: 'tpc-tab-directories'
                ,disabled: false
                ,defaults: { border: false ,autoHeight: true }
                ,items: [fs, {
                    title: 'Directories'
                    ,style: {
                        paddingTop: '17px'
                    }
                },{
                    html: _('themepackagercomponent.directories.intro_msg')
                    ,style: {
                        padding: '8px'
                    }
                },{
                    xtype: 'tp-grid-directories'
                    ,id: 'tp-grid-directories'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.advanced_title')
                ,id: 'tpc-tab-advanced'
                ,disabled: false
                ,layout: 'form'
                ,deferredRender: false
                ,defaults:{border:false,autoHeight:true}
                ,items: [
                    {
                        xtype: 'textfield'
                        ,id: 'tpc-advanced-settings'
                        ,name: 'settings'
                        ,grow: true
                        ,growMin: 250
                        ,growMax: 1000
                        ,fieldLabel: _('themepackagercomponent.advanced.settings')
                        ,regex: /^[a-zA-Z0-9_\s\.\-]+\s*(,\s*[a-zA-Z0-9_\s\.\-]+\s*)*$/
                        ,regexText: _('themepackagercomponent.advanced.settings_invalid')
                        ,msgTarget: 'under'
                    },{
                        html: '<em>' + _('themepackagercomponent.advanced.settings_desc') + '</em>'
                        ,style: {
                            marginBottom: '35px'
                            ,marginLeft: '15px'
                        }
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('themepackagercomponent.resources_field')
                        ,name: 'resources'
                        ,id: 'tp-resource-list'
                        ,value: ''
                        ,regex: /^[0-9]+\s*(,\s*[0-9]+\s*)*$/
                        ,regexText: _('themepackagercomponent.resources_field_invalid')
                        ,msgTarget: 'under'
                    },{
                        html: '<em>' + _('themepackagercomponent.resources_field_desc') + '</em>'
                        ,style: {
                            marginBottom: '35px'
                            ,marginLeft: '17px'
                        }
                    }
                ]
            }]
        }]
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