TP.panel.Home = function(config) {
    config = config || {};
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
                    ,inputType: 'file'
                    ,name: 'readme'
                    ,fieldLabel: _('themepackagercomponent.readme')
                    ,description: _('themepackagercomponent.readme_desc')
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,inputType: 'file'
                    ,name: 'license'
                    ,fieldLabel: _('themepackagercomponent.license')
                    ,description: _('themepackagercomponent.license_desc')
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,inputType: 'file'
                    ,name: 'changelog'
                    ,fieldLabel: _('themepackagercomponent.changelog')
                    ,description: _('themepackagercomponent.changelog_desc')
                    ,width: 300
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
                    ,name: 'everything'
                    ,fieldLabel: _('themepackagercomponent.everything')
                    ,description: _('themepackagercomponent.everything_desc')
                    ,inputValue: 'yes'
                    ,checked: true
                    ,listeners: {
                        check: {
                            fn: function (el, checked) {
                                if (checked) {
                                    Ext.getCmp('tpc-tab-templates').disable();
                                    Ext.getCmp('tpc-tab-chunks').disable();
                                    Ext.getCmp('tpc-tab-snippets').disable();
                                    Ext.getCmp('tpc-tab-plugins').disable();
                                    Ext.getCmp('tpc-tab-subpackages').disable();
                                    Ext.getCmp('tpc-tab-directories').disable();
                                    Ext.getCmp('tpc-tab-resources').disable();
                                    Ext.getCmp('tpc-enduser-option-samplecontent').setValue(true).disable();
                                } else {
                                    Ext.getCmp('tpc-tab-templates').enable();
                                    Ext.getCmp('tpc-tab-chunks').enable();
                                    Ext.getCmp('tpc-tab-snippets').enable();
                                    Ext.getCmp('tpc-tab-plugins').enable();
                                    Ext.getCmp('tpc-tab-subpackages').enable();
                                    Ext.getCmp('tpc-tab-directories').enable();
                                    Ext.getCmp('tpc-tab-resources').enable();
                                    Ext.getCmp('tpc-enduser-option-samplecontent').enable();
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
                            ,name: 'enduser_option_merge'
                            ,fieldLabel: _('themepackagercomponent.enduser_option_merge_label')
                            ,description: _('themepackagercomponent.enduser_option_merge_desc')
                            ,checked: true
                            ,value: 'yes'
                            ,listeners: {
                                check: {
                                    fn: function (el, checked) {
                                        if (checked) {
                                            Ext.getCmp('tpc-default-merge-option-picker').hide();
                                            Ext.getCmp('tpc-default-merge-option-picker-merge').hide();
                                            Ext.getCmp('tpc-default-merge-option-picker-replace').hide();
                                        } else {
                                            Ext.getCmp('tpc-default-merge-option-picker').show();
                                            Ext.getCmp('tpc-default-merge-option-picker-merge').show();
                                            Ext.getCmp('tpc-default-merge-option-picker-replace').show();
                                        }
                                    }
                                    ,scope: this
                                }
                            }
                        },{
                            xtype: 'radiogroup'
                            ,id: 'tpc-default-merge-option-picker'
                            ,fieldLabel: 'Default install action'
                            ,hidden: true
                            ,description: 'Specify whether your Theme package will Merge with the user\'s site on install, or completely replace it.'
                            ,width: 300
                            ,defaults: {labelSeparator: ''}
                            ,items: [
                                {
                                    xtype: 'radio'
                                    ,id: 'tpc-default-merge-option-picker-merge'
                                    ,name: 'enduser_install_action_default'
                                    ,boxLabel: 'Merge'
                                    ,value: 'merge'
                                    ,checked: true
                                }, {
                                    xtype: 'radio'
                                    ,id: 'tpc-default-merge-option-picker-replace'
                                    ,name: 'enduser_install_action_default'
                                    ,boxLabel: 'Replace'
                                    ,value: 'replace'
                                }
                            ]
                        },{
                            xtype: 'checkbox'
                            ,id: 'tpc-enduser-option-samplecontent'
                            ,name: 'enduser_option_samplecontent'
                            ,fieldLabel: _('themepackagercomponent.enduser_option_samplecontent_label')
                            ,description: _('themepackagercomponent.enduser_option_samplecontent_desc')
                            ,checked: true
                            ,disabled: true
                            ,value: 'yes'
                        }
                    ]
                }]
            },{
                title: _('themepackagercomponent.templates')
                ,id: 'tpc-tab-templates'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.templates.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-templates'
                    ,id: 'tp-grid-templates'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.chunks')
                ,id: 'tpc-tab-chunks'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.chunks.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-chunks'
                    ,id: 'tp-grid-chunks'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.snippets_custom')
                ,id: 'tpc-tab-snippets'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.snippets.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-snippets'
                    ,id: 'tp-grid-snippets'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.plugins')
                ,id: 'tpc-tab-plugins'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.plugins.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-plugins'
                    ,id: 'tp-grid-plugins'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.subpackages')
                ,id: 'tpc-tab-subpackages'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.subpackages.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-packages'
                    ,id: 'tp-grid-packages'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.directories')
                ,id: 'tpc-tab-directories'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.directories.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-directories'
                    ,id: 'tp-grid-directories'
                    ,preventRender: true
                }]
            },{
                title: _('themepackagercomponent.resources')
                ,id: 'tpc-tab-resources'
                ,disabled: true
                ,items: [{
                    html: _('themepackagercomponent.resources.intro_msg')
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
            templates: Ext.getCmp('tp-grid-templates').encode()
            ,chunks: Ext.getCmp('tp-grid-chunks').encode()
            ,snippets: Ext.getCmp('tp-grid-snippets').encode()
            ,plugins: Ext.getCmp('tp-grid-plugins').encode()
            ,packages: Ext.getCmp('tp-grid-packages').encode()
            ,directories: Ext.getCmp('tp-grid-directories').encode()
        });
    }
    ,success: function(o) {
        if (o.result.success) {
            var name = o.result.message;

            Ext.getCmp('tp-btn-export').setDisabled(false);
            Ext.getCmp('tp-grid-templates').getStore().commitChanges();
            Ext.getCmp('tp-grid-chunks').getStore().commitChanges();
            Ext.getCmp('tp-grid-snippets').getStore().commitChanges();
            Ext.getCmp('tp-grid-plugins').getStore().commitChanges();
            Ext.getCmp('tp-grid-packages').getStore().commitChanges();
            Ext.getCmp('tp-grid-directories').getStore().commitChanges();
            
            location.href = TP.config.connector_url+'?action=build&download='+name+'&HTTP_MODAUTH='+MODx.siteId;
        }
    }
});
Ext.reg('tp-panel-home',TP.panel.Home);