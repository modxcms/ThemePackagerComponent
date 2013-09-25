<?php
include dirname(dirname(dirname(__FILE__))) . '/config.core.php';
include MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();

$classes= array (
    'modSystemSetting',
    'modUserGroup',
    'modUserGroupRole',
    'modUser',
//    'modUserProfile',
//    'modUserGroupMember',
//    'modUserMessage',
//    'modUserSetting',
);
$xPDOObjectAttributes = array(
    'preserve_keys' => true,
    'update_object' => true
);
$xPDOObjectCriteria = array("1 = 1");

//$directories = array (
//    '{+properties.modx.core_path}components' => "return MODX_CORE_PATH;",
//    '{+properties.modx.context_web_path}assets' => "return MODX_BASE_PATH;"
//);
//$dirAttributes = array (
//    'vehicle_class' => 'xPDOFileVehicle',
//);

$tpl = array();
$tpl['name'] = 'users';
$tpl['vehicles'] = array();
//foreach ($directories as $source => $target) {
//    $tpl['vehicles'][] = array(
//        'vehicle_class' => 'xPDOFileVehicle',
//        'object' => array(
//            'source' => $source,
//            'target' => $target
//        ),
//        'attributes' => $dirAttributes
//    );
//}
foreach ($classes as $class) {
    $classAttributes = $xPDOObjectAttributes;
    $classCriteria = $xPDOObjectCriteria;
    $classGraph = array();
    $classGraphCriteria = null;
    $classScript = null;
    $classPackage = 'modx';
    switch ($class) {
        case 'modSystemSetting':
            $classAttributes = array_merge($classAttributes, array(
                'resolve' => array(
                    array(
                        'type' => 'php',
                        'source' => 'tpl/scripts/resolve/extension_packages.php',
                    )
                )
            ));
            $classCriteria = array('key' => 'extension_packages');
            $classScript = 'extract/userextensionpackages.php';
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
    $tpl['vehicles'][] = $vehicle;
}
file_put_contents(dirname(dirname(__FILE__)) . '/' . $tpl['name'] . '.tpl.json', json_encode($tpl));
