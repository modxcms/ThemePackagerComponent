<?php
include dirname(dirname(dirname(__FILE__))) . '/config.core.php';
include MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();

$classes= array (
    'modAccessAction',
    'modAccessActionDom',
    'modAccessCategory',
    'modAccessContext',
    'modAccessElement',
    'modAccessMenu',
    'modAccessPermission',
    'modAccessPolicy',
    'modAccessPolicyTemplate',
    'modAccessPolicyTemplateGroup',
    'modAccessResource',
    'modAccessResourceGroup',
    'modAccessTemplateVar',
    'modAction',
    'modActionDom',
    'modActionField',
    'modActiveUser',
    'modCategory',
    'modCategoryClosure',
    'modChunk',
    'modClassMap',
    'modContentType',
    'modContext',
    'modContextResource',
    'modContextSetting',
    'modDashboard',
    'modDashboardWidget',
    'modDashboardWidgetPlacement',
    'modElementPropertySet',
    'modEvent',
    'modFormCustomizationProfile',
    'modFormCustomizationProfileUserGroup',
    'modFormCustomizationSet',
    'modLexiconEntry',
    'modManagerLog',
    'modMenu',
    'modNamespace',
    'modPlugin',
    'modPluginEvent',
    'modPropertySet',
    'modResource',
    'modResourceGroup',
    'modResourceGroupResource',
    'modSession',
    'modSnippet',
    'modSystemSetting',
    'modTemplate',
    'modTemplateVar',
    'modTemplateVarResource',
    'modTemplateVarResourceGroup',
    'modTemplateVarTemplate',
    'modUser',
    'modUserProfile',
    'modUserGroup',
    'modUserGroupMember',
    'modUserGroupRole',
    'modUserMessage',
    'modUserSetting',
    'modWorkspace',
    'registry.db.modDbRegisterMessage',
    'registry.db.modDbRegisterTopic',
    'registry.db.modDbRegisterQueue',
    'transport.modTransportProvider',
    'transport.modTransportPackage',
    'sources.modAccessMediaSource',
    'sources.modMediaSource',
    'sources.modMediaSourceElement',
    'sources.modMediaSourceContext',
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
$tpl['name'] = 'complete';
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
    'vehicle_class' => 'xPDOFileVehicle',
    'object' => array(
        'script' => 'extract/basepathassets.php'
    ),
    'attributes' => $dirAttributes
);
$tpl['vehicles'][] = array(
    'vehicle_class' => 'xPDOScriptVehicle',
    'object' => array(
        'source' => 'tpl/scripts/truncate.tables.php',
        'classes' => $classes
    ),
    'attributes' => array(
        'vehicle_class' => 'xPDOScriptVehicle',
    )
);
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
    $vehiclePackage = '';
    $vehicleClass = '\\Siphon\\Transport\\SiphonXPDOCollectionVehicle';
    $classAttributes = $xPDOObjectAttributes;
    $classCriteria = $xPDOObjectCriteria;
    $classGraph = array();
    $classGraphCriteria = null;
    $classScript = null;
    $classOptions = array();
    $classPackage = 'modx';
    switch ($class) {
        case 'modActiveUser':
        case 'modManagerLog':
        case 'modSession':
        case 'registry.db.modDbRegisterMessage':
        case 'registry.db.modDbRegisterQueue':
        case 'registry.db.modDbRegisterTopic':
            continue 2;
            break;
        case 'modSystemSetting':
            $classCriteria = array('key:NOT IN' => array('extension_packages'));
            break;
        case 'transport.modTransportPackage':
            $classScript = 'extract/modtransportpackage.php';
            $vehicleClass = 'xPDOObjectVehicle';
            break;
        default:
            break;
    }
    $vehicle = array(
        'vehicle_package' => $vehiclePackage,
        'vehicle_class' => $vehicleClass,
        'object' => array(
            'class' => $class,
            'criteria' => $classCriteria,
        ),
        'attributes' => array_merge(
            $classAttributes,
            array(
                'vehicle_package' => $vehiclePackage,
                'vehicle_class' => $vehicleClass,
            )
        )
    );
    if ($vehicleClass === '\\Siphon\\Transport\\SiphonXPDOCollectionVehicle') $vehicle['object']['limit'] = 500;
    if (!empty($classGraph)) $vehicle['object']['graph'] = $classGraph;
    if (!empty($classGraphCriteria)) $vehicle['object']['graphCriteria'] = $classGraphCriteria;
    if (!empty($classScript)) $vehicle['object']['script'] = $classScript;
    if (!empty($classPackage)) $vehicle['object']['package'] = $classPackage;
    if (!empty($classOptions)) $vehicle['object'] = array_merge($vehicle['object'], $classOptions);
    $tpl['vehicles'][] = $vehicle;
}
$tpl['vehicles'][] = array(
    'vehicle_package' => '',
    'vehicle_class' => '\\Siphon\\Transport\\SiphonMySQLVehicle',
    'object' => array(
        'classes' => $classes,
        'excludeExtraTables' => array(),
        'excludeExtraTablePrefix' => array(),
    ),
    'attributes' => array(
        'vehicle_package' => '',
        'vehicle_class' => '\\Siphon\\Transport\\SiphonMySQLVehicle'
    )
);
file_put_contents(dirname(dirname(__FILE__)) . '/' . $tpl['name'] . '.tpl.json', json_encode($tpl));
