Ext.onReady(function() {
    MODx.load({ xtype: 'tp-page-home'});
});

TP.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'tp-panel-home'
        ,id: 'tp-home'
        ,buttons: [{
            text: _('themepackagercomponent.profile_options')
            ,handler: Ext.emptyFn
            ,id: 'tp-menu-profile-options'
            ,hidden: true
            ,menu: {
                xtype: 'menu'
                ,plain: true
                ,autoWidth: true
                ,defaults: {
                    width: '100%'
                    ,style: 'padding: 5px 25px 5px 10px;'
                }
                ,items: [{
                    text: _('themepackagercomponent.profile_save')
                    ,handler: this.saveProfile
                    ,scope: this
                    ,id: 'tp-btn-profile-save'
                    ,cls: 'x-btn-text'
                },{
                    text: _('themepackagercomponent.profile_remove')
                    ,handler: this.removeProfile
                    ,scope: this
                    ,id: 'tp-btn-profile-remove'
                },{
                    text: _('themepackagercomponent.unload_profile')
                    ,handler: function() { this.resetProfile(true); }
                    ,scope: this
                    ,id: 'tp-btn-profile-unload'
                }]
            }
        },{
            xtype: 'tp-combo-profile'
            ,id: 'tp-combo-profile'
        },{
            text: _('themepackagercomponent.export')
            ,id: 'tp-btn-export'
            ,process: 'build'
            ,method: 'remote'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        }]
        ,components: [{
            xtype: 'tp-panel-home'
            ,renderTo: 'tp-panel-home-div'
        }]
    }); 
    TP.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(TP.page.Home,MODx.Component,{
    windows: {}
    ,saveProfile: function(btn,e) {
        var data = this.prepareProfile();
        
        MODx.Ajax.request({
            url: TP.config.connector_url
            ,params: {
                action: 'profile/update'
                ,id: TP.profileLoaded
                ,data: data
            }
            ,listeners: {
                'success':{fn:function(r) {
                    MODx.msg.alert(_('success'),_('themepackagercomponent.profile_saved'));
                },scope:this}
            }
        });
    }
    ,removeProfile: function(btn,e) {
        MODx.msg.confirm({
            url: TP.config.connector_url
            ,title: _('themepackagercomponent.profile_remove')
            ,text: _('themepackagercomponent.profile_remove_confirm')
            ,params: {
                action: 'profile/remove'
                ,id: TP.profileLoaded
            }
            ,listeners: {
                'success':{fn:function(r) {
                    var cb = Ext.getCmp('tp-combo-profile');
                    cb.store.load();
                    cb.reset();
                    this.resetProfile();
                },scope:this}
            }
        });
    }

    ,resetProfile: function(rc) {
        rc = rc || false;
        Ext.getCmp('tp-panel-home').getForm().reset();
        Ext.getCmp('tpc-package-everything-checkbox').setValue(false);
        Ext.getCmp('tpc-package-allfiles-checkbox').setValue(false);
        Ext.getCmp('tpc-enduser_option_merge-checkbox').setValue(false);
        Ext.getCmp('tpc-enduser-option-samplecontent').setValue(false);
        Ext.getCmp('tp-home-header').update('<h2>'+_('themepackagercomponent')+'</h2>');
        Ext.getCmp('tp-tree-templates-panel').getRootNode().cascade(function(n){n.getUI().toggleCheck(false)});
        Ext.getCmp('tp-tree-chunks-panel').getRootNode().cascade(function(n){n.getUI().toggleCheck(false)});
        Ext.getCmp('tp-tree-plugins-panel').getRootNode().cascade(function(n){n.getUI().toggleCheck(false)});
        Ext.getCmp('tp-tree-snippets-panel').getRootNode().cascade(function(n){n.getUI().toggleCheck(false)});
        Ext.getCmp('tp-grid-packages').store.removeAll();
        Ext.getCmp('tp-grid-directories').store.removeAll();
        TP.profileLoaded = 0;

        var b = Ext.getCmp('tp-menu-profile-options');
        if (b) { b.hide(); }

        if (rc) {
            Ext.getCmp('tp-combo-profile').reset();
        }
    }


    ,prepareProfile: function() {
        var vs = {};
        vs.info = Ext.getCmp('tp-panel-home').getForm().getValues();
        vs.templates = Ext.getCmp('tp-tree-templates-panel').getData();
        vs.chunks = Ext.getCmp('tp-tree-chunks-panel').getData();
        vs.plugins = Ext.getCmp('tp-tree-plugins-panel').getData();
        vs.snippets = Ext.getCmp('tp-tree-snippets-panel').getData();
        vs.packages = Ext.getCmp('tp-grid-packages').getData();
        vs.directories = Ext.getCmp('tp-grid-directories').getData();
        return Ext.util.JSON.encode(vs);
    }
    ,createProfile: function(cb) {
        var r = {};
        r.data = this.prepareProfile();

        if (!this.windows.createProfile) {
            this.windows.createProfile = MODx.load({
                xtype: 'tp-window-profile-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(vs) {
                        cb.store.load({
                            callback: function() {
                                var rs = vs.a.result.object;
                                cb.setValue(rs.id);
                                this.switchProfile(rs.id,rs.name);
                            }
                            ,scope: this
                        });
                    },scope:this}
                }
            });
        }
        this.windows.createProfile.setValues(r);
        this.windows.createProfile.show(cb.el.dom);
    }

    ,switchProfile: function(id,name) {
        Ext.getCmp('tp-home-header').update('<h2>'+_('themepackagercomponent')+' - '+_('profile')+': '+name+'</h2>');
        TP.profileLoaded = id;
        var b = Ext.getCmp('tp-menu-profile-options');
        if (b) { b.show(); }
    }

    ,loadProfile: function(v) {
        this.resetProfile();
        MODx.Ajax.request({
            url: TP.config.connector_url
            ,params: {
                action: 'profile/get'
                ,id: v
            }
            ,listeners: {
                'success':{fn:function(r) {
                    var p = Ext.getCmp('tp-panel-home');
                    p.getForm().setValues(r.object.data.info);

                    var d = [];
                    this.switchProfile(v,r.object.name);


                    if (r.object.data.templates) {
                        Ext.getCmp('tp-tree-templates-selected_ids').setValue(r.object.data.templates.join());
                        Ext.each(r.object.data.templates, function(n){
                            Ext.getCmp('tp-tree-templates-panel').getNodeById(n).ui.checkbox.checked = true;
                            Ext.getCmp('tp-tree-templates-panel').getNodeById(n).attributes.checked = true;
                        });
                        Ext.getCmp('tp-tree-templates-panel').updateSelected();
                    }
                    if (r.object.data.chunks) {
                        Ext.getCmp('tp-tree-chunks-selected_ids').setValue(r.object.data.chunks.join());
                        Ext.each(r.object.data.chunks, function(n){
                            Ext.getCmp('tp-tree-chunks-panel').getNodeById(n).ui.checkbox.checked = true;
                            Ext.getCmp('tp-tree-chunks-panel').getNodeById(n).attributes.checked = true;
                        });
                        Ext.getCmp('tp-tree-chunks-panel').updateSelected();
                    }
                    if (r.object.data.plugins) {
                        Ext.getCmp('tp-tree-plugins-selected_ids').setValue(r.object.data.plugins.join());
                        Ext.each(r.object.data.plugins, function(n){
                            Ext.getCmp('tp-tree-plugins-panel').getNodeById(n).ui.checkbox.checked = true;
                            Ext.getCmp('tp-tree-plugins-panel').getNodeById(n).attributes.checked = true;
                        });
                        Ext.getCmp('tp-tree-plugins-panel').updateSelected();
                    }
                    if (r.object.data.snippets) {
                        Ext.getCmp('tp-tree-snippets-selected_ids').setValue(r.object.data.snippets.join());
                        Ext.each(r.object.data.snippets, function(n){
                            Ext.getCmp('tp-tree-snippets-panel').getNodeById(n).ui.checkbox.checked = true;
                            Ext.getCmp('tp-tree-snippets-panel').getNodeById(n).attributes.checked = true;
                        });
                        Ext.getCmp('tp-tree-snippets-panel').updateSelected();
                    }


                    if (r.object.packages) {
                        d = Ext.decode(r.object.packages);
                        Ext.getCmp('tp-grid-packages').getStore().loadData(d);
                    }

                    if (r.object.directories) {
                        d = Ext.decode(r.object.directories);
                        Ext.getCmp('tp-grid-directories').getStore().loadData(d);
                    }

                    if (r.object.data.info.rootFiles) {
                        Ext.each(r.object.data.info.rootFiles, function(f) {
                            cb = Ext.getCmp('rootfile-' + f.replace(' ', '_'));
                            if (cb) {
                                cb.setValue(true);
                            }
                        });
                    };

                },scope:this}
            }
        });
    }
});
Ext.reg('tp-page-home',TP.page.Home);


TP.combo.Profile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'profile'
        ,hiddenName: 'profile'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: true
        ,listWidth: 300
        ,emptyText: _('themepackagercomponent.profile_select')
        ,url: TP.config.connector_url
        ,baseParams: {
            action: 'profile/getList'
        }
    });
    TP.combo.Profile.superclass.constructor.call(this,config);
    this.on('select',this.onSel);
};
Ext.extend(TP.combo.Profile,MODx.combo.ComboBox,{
    onSel: function(cb,rec,idx) {
        var v = cb.getValue();
        if (v == 'CNEW' || v == '-') {
            cb.reset();
        }
        if (v == 'CNEW') {
            Ext.getCmp('tp-home').createProfile(cb);
        } else if (v != '-') {
            Ext.getCmp('tp-home').loadProfile(v);
        }
    }

});
Ext.reg('tp-combo-profile',TP.combo.Profile);


TP.window.CreateProfile = function(config) {
    config = config || {};
    this.ident = config.ident || 'tpproc'+Ext.id();
    Ext.applyIf(config,{
        title: _('themepackagercomponent.profile_create')
        ,frame: true
        ,url: TP.config.connector_url
        ,baseParams: {
            action: 'profile/create'
        }
        ,id: 'tp-window-profile-create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'data'
            ,id: 'tp-'+this.ident+'-data'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('themepackagercomponent.name')
            ,description: _('themepackagercomponent.profile_name_desc')
            ,name: 'name'
            ,id: 'tp-'+this.ident+'-name'
            ,width: 300
            ,allowBlank: false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('themepackagercomponent.description')
            ,description: _('themepackagercomponent.profile_description_desc')
            ,name: 'description'
            ,id: 'tp-'+this.ident+'-description'
            ,width: 300
        }]
    });
    TP.window.CreateProfile.superclass.constructor.call(this,config);
};
Ext.extend(TP.window.CreateProfile,MODx.Window,{
});
Ext.reg('tp-window-profile-create',TP.window.CreateProfile);
