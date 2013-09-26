<?php
include dirname(dirname(dirname(__FILE__))) . '/config.core.php';
include MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();

$classes= array (
    'modNamespace', /* get the namespaces - TODO: use namespace.path */

//    'modWorkspace', /* TODO: handle workspaces when implemented */

//    'modClassMap', /* deprecated - no longer used in 2.2 */

    'modSystemSetting',

    'modEvent',

    'modLexiconEntry',

    'modAction',
    'modMenu',
//    'modActionField',

    'modUserGroup',
    'modUserGroupRole',
    'modUser',
//    'modUserProfile',
//    'modUserGroupMember',
//    'modUserMessage',
//    'modUserSetting',

    'sources.modMediaSource',

    'modDashboard',
//    'modDashboardWidget',
//    'modDashboardWidgetPlacement',

    'modCategory',
//    'modCategoryClosure',

    'modResourceGroup',

    'modPropertySet',
//    'modElementPropertySet',

    'modChunk',
    'modPlugin',
//    'modPluginEvent',
    'modSnippet',
    'modTemplateVar',
    'modTemplate',
//    'modTemplateVarTemplate',
//    'modTemplateVarResourceGroup',


    'modContentType',

    'modContext',
    'modContextSetting',

    'sources.modMediaSourceElement',
    'sources.modMediaSourceContext',
    'sources.modAccessMediaSource',

    'modResource',
//    'modContextResource',
//    'modTemplateVarResource',
//    'modResourceGroupResource',

    'modFormCustomizationProfile',
//    'modFormCustomizationProfileUserGroup',
//    'modFormCustomizationSet',
//    'modActionDom',

    'modAccessPolicyTemplateGroup',
//    'modAccessPolicyTemplate',
//    'modAccessPolicy',
//    'modAccessPermission',

//    'modAccessAction',
//    'modAccessActionDom',
    'modAccessCategory',
    'modAccessContext',
    'modAccessResourceGroup',
//    'modAccessElement',
//    'modAccessMenu',
//    'modAccessResource',
//    'modAccessTemplateVar',

//    'modActiveUser',

//    'modManagerLog',

//    'modSession',

//    'registry.db.modDbRegisterMessage',
//    'registry.db.modDbRegisterTopic',
//    'registry.db.modDbRegisterQueue',

//    'transport.modTransportPackage',
//    'transport.modTransportProvider',
);
$xPDOObjectAttributes = array(
    'preserve_keys' => true,
    'update_object' => true
);
$xPDOObjectCriteria = array("1 = 1");

$directories = array (
    '{+properties.modx.core_path}components' => "return MODX_CORE_PATH;",
    '{+properties.modx.context_web_path}assets' => "return MODX_BASE_PATH;"
);
$dirAttributes = array (
    'vehicle_class' => 'xPDOFileVehicle',
);

$tpl = array();
$tpl['name'] = 'develop';
$tpl['vehicles'] = array();
foreach ($directories as $source => $target) {
    $tpl['vehicles'][] = array(
        'vehicle_class' => 'xPDOFileVehicle',
        'object' => array(
            'source' => $source,
            'target' => $target
        ),
        'attributes' => $dirAttributes
    );
}
$tpl['vehicles'][] = array(
    'vehicle_class' => 'xPDOObjectVehicle',
    'object' => array(
        'class' => 'modSystemSetting',
        'criteria' => array('key' => 'extension_packages')
    ),
    'attributes' => array_merge(
        $xPDOObjectAttributes,
        array(
            'resolve' => array(
                array(
                    'type' => 'php',
                    'source' => 'tpl/scripts/resolve/modsystemsetting/extension_packages.php'
                )
            )
        )
    )
);
foreach ($classes as $class) {
    $classAttributes = $xPDOObjectAttributes;
    $classCriteria = $xPDOObjectCriteria;
    $classGraph = array();
    $classGraphCriteria = null;
    $classScript = null;
    $classPackage = 'modx';
    $classOptions = array();
    switch ($class) {
        case 'modContextSetting':
        case 'modSystemSetting':
            $classCriteria[] = array(
                "area:NOT IN" => array('session', 'caching', 'gateway', 'mail', 'system', 'file', 'authentication'),
                "key:NOT IN" => array(
                    'host',
                    'http_host',
                    'https_port',
                    'site_url',
                    'base_url',
                    'manager_url',
                    'connectors_url',
                    'url_scheme',
                    'base_path',
                    'manager_path',
                    'connectors_path',
                    'assets_path',
                    'assets_url',
                    'connection_mutable',
                    'connections',
                    'core_path',
                    'date_timezone',
                    'dbname',
                    'debug',
                    'driverOptions',
                    'dsn',
                    'new_file_permissions',
                    'new_folder_permissions',
                    'password',
                    'processors_path',
                    'table_prefix',
                    'ui_debug_mode',
                    'extension_packages',
                )
            );
            break;
        case 'modAction':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'unique_key' => array ('namespace','controller'),
                'update_object' => true,
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Fields' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array('action', 'name', 'type', 'tab', 'form')
                    ),
                )
            ));
            $classScript = 'extract/' . strtolower($class) . '.php';
            break;
        case 'modMenu':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => true,
                'update_object' => true,
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Action' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array ('namespace','controller'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Fields' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array('action', 'name', 'type', 'tab', 'form')
                            ),
                        ),
                    ),
                    'Children' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Action' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array ('namespace','controller'),
                                'related_objects' => true,
                                'related_object_attributes' => array(
                                    'Fields' => array(
                                        'preserve_keys' => false,
                                        'update_object' => true,
                                        'unique_key' => array('action', 'name', 'type', 'tab', 'form')
                                    ),
                                ),
                            ),
                        ),
                    ),
                )
            ));
            $classScript = 'extract/' . strtolower($class) . '.php';
            break;
        case 'modLexiconEntry':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => array('namespace', 'topic', 'name', 'language')
            ));
            break;
        case 'modUserGroup':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name'
            ));
            break;
        case 'modUserGroupRole':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name'
            ));
            break;
        case 'modUser':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'username',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Profile' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'internalKey'
                    ),
                    'UserSettings' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                    ),
                    'UserGroupMembers' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array('user_group', 'member'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'UserGroup' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            ),
                            'UserGroupRole' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                    'PrimaryGroup' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name',
                    )
                )
            ));
            $classGraph = array(
                'Profile' => array(),
                'UserSettings' => array(),
                'UserGroupMembers' => array('UserGroup' => array(), 'UserGroupRole' => array()),
                'PrimaryGroup' => array()
            );
            break;
        case 'modCategory':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'category',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Children' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category',
                    )
                )
            ));
            $classCriteria = array('parent' => 0);
            $classScript = 'extract/' . strtolower($class) . '.php';
            break;
        case 'modPropertySet':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Category' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category',
                    ),
                )
            ));
            $classGraph = array('Category' => array());
            break;
        case 'modChunk':
        case 'modSnippet':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Category' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category',
                    ),
                    'PropertySets' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('element', 'element_class', 'property_set'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'PropertySet' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                )
            ));
            $classGraph = array('Category' => array(), 'PropertySets' => array('PropertySet' => array()));
            break;
        case 'modPlugin':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Category' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category',
                    ),
                    'PropertySets' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('element', 'element_class', 'property_set'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'PropertySet' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                    'PluginEvents' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('pluginid', 'event'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Event' => array(
                                'preserve_keys' => true,
                                'update_object' => false,
                            ),
                            'PropertySet' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    )
                )
            ));
            $classGraph = array(
                'Category' => array(),
                'PropertySets' => array('PropertySet' => array()),
                'PluginEvents' => array('PropertySet' => array())
            );
            break;
        case 'modTemplateVar':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Category' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category',
                    ),
                    'PropertySets' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('element', 'element_class', 'property_set'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'PropertySet' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                    'TemplateVarTemplates' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('tmplvarid', 'templateid'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Template' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'templatename',
                            )
                        )
                    )
                )
            ));
            $classGraph = array(
                'Category' => array(),
                'PropertySets' => array('PropertySet' => array()),
                'TemplateVarTemplates' => array('Template' => array())
            );
            break;
        case 'modTemplate':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'templatename',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Category' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category',
                    ),
                    'PropertySets' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('element', 'element_class', 'property_set'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'PropertySet' => array(
                                'preserve_keys' => false,
                                'update_object' => false,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                )
            ));
            $classGraph = array(
                'Category' => array(),
                'PropertySets' => array('PropertySet' => array()),
            );
            break;
        case 'modContentType':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name'
            ));
            break;
        case 'modAccessPolicyTemplateGroup':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Templates' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'unique_key' => array('template_group', 'name'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Permissions' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array('template', 'name'),
                            ),
                            'Policies' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array('template', 'name'),
                                'related_objects' => true,
                                'related_object_attributes' => array(
                                    'Children' => array(
                                        'preserve_keys' => false,
                                        'update_object' => true,
                                        'unique_key' => array('template', 'name'),
                                    )
                                )
                            )
                        )
                    )
                )
            ));
            $classGraph = array('Templates' => array());
            $classScript = 'extract/' . strtolower($class) . '.php';
            break;
        case 'modResourceGroup':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name'
            ));
            break;
        case 'modResource':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => true,
                'update_object' => true,
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Template' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'templatename',
                    ),
                    'ContentType' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name',
                    ),
                    'TemplateVarResources' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array('tmplvarid', 'contentid'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'TemplateVar' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                    'ResourceGroupResources' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array('document_group', 'document'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'ResourceGroup' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    ),
                    'ContextResources' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Context' => array(
                                'preserve_keys' => true,
                                'update_object' => false
                            )
                        )
                    )
                )
            ));
            $classGraph = array(
                'Template' => array(),
                'ContentType' => array(),
                'TemplateVarResources' => array('TemplateVar' => array()),
                'ResourceGroupResources' => array('ResourceGroup' => array()),
                'ContextResources' => array('Context' => array())
            );
            break;
        case 'modDashboard':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'UserGroups' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name'
                    ),
                    'Placements' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Widget' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array('namespace', 'name')
                            )
                        )
                    )
                )
            ));
            $classGraph = array(
                'UserGroups' => array(),
                'Placements' => array('Widget' => array())
            );
            break;
        case 'sources.modMediaSource':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
            ));
            break;
        case 'modFormCustomizationProfile':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => 'name',
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Sets' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array('profile', 'action', 'template', 'constraint', 'constraint_field', 'constraint_class'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'Action' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array('namespace', 'controller'),
                            ),
                            'Template' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'templatename',
                            ),
                            'Rules' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => array('set', 'action', 'name'),
                                'related_objects' => true,
                                'related_object_attributes' => array(
                                    'Action' => array(
                                        'preserve_keys' => false,
                                        'update_object' => true,
                                        'unique_key' => array('namespace', 'controller'),
                                    )
                                )
                            )
                        )
                    ),
                    'UserGroups' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => array('usergroup', 'profile'),
                        'related_objects' => true,
                        'related_object_attributes' => array(
                            'UserGroup' => array(
                                'preserve_keys' => false,
                                'update_object' => true,
                                'unique_key' => 'name',
                            )
                        )
                    )
                )
            ));
            $classGraph = array(
                'Sets' => array(
                    'Action' => array(),
                    'Template' => array(),
                    'Rules' => array(
                        'Action' => array()
                    )
                ),
                'UserGroups' => array(
                    'UserGroup' => array()
                )
            );
            break;
        case 'modAccessCategory':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => array('context_key', 'target', 'principal_class', 'principal', 'authority', 'policy'),
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Context' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                    ),
                    'Target' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'category'
                    ),
                    'modUserGroup' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name'
                    ),
                    'modUser' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'username'
                    ),
                )
            ));
            $classGraph = array(
                'Context' => array(),
                'Target' => array()
            );
            $classScript = 'extract/modaccess.php';
            break;
        case 'modAccessContext':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => array('target', 'principal_class', 'principal', 'authority', 'policy'),
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Target' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                    ),
                    'modUserGroup' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name'
                    ),
                    'modUser' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'username'
                    ),
                )
            ));
            $classGraph = array(
                'Target' => array()
            );
            $classScript = 'extract/modaccess.php';
            break;
        case 'modAccessResourceGroup':
            $classAttributes = array_merge($classAttributes, array(
                'preserve_keys' => false,
                'update_object' => true,
                'unique_key' => array('context_key', 'target', 'principal_class', 'principal', 'authority', 'policy'),
                'related_objects' => true,
                'related_object_attributes' => array(
                    'Context' => array(
                        'preserve_keys' => true,
                        'update_object' => true,
                    ),
                    'Target' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name'
                    ),
                    'modUserGroup' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'name'
                    ),
                    'modUser' => array(
                        'preserve_keys' => false,
                        'update_object' => true,
                        'unique_key' => 'username'
                    ),
                )
            ));
            $classGraph = array(
                'Context' => array(),
                'Target' => array()
            );
            break;
            $classScript = 'extract/modaccess.php';
        default:
            break;
    }
    $vehicle = array(
        'vehicle_class' => 'xPDOObjectVehicle',
        'object' => array(
            'class' => $class,
            'criteria' => $classCriteria
        ),
        'attributes' => $classAttributes
    );
    if (!empty($classGraph)) $vehicle['object']['graph'] = $classGraph;
    if (!empty($classGraphCriteria)) $vehicle['object']['graphCriteria'] = $classGraphCriteria;
    if (!empty($classScript)) $vehicle['object']['script'] = $classScript;
    if (!empty($classPackage)) $vehicle['object']['package'] = $classPackage;
    if (!empty($classOptions)) $vehicle['object'] = array_merge($vehicle['object'], $classOptions);
    $tpl['vehicles'][] = $vehicle;
}
file_put_contents(dirname(dirname(__FILE__)) . '/' . $tpl['name'] . '.tpl.json', json_encode($tpl));
