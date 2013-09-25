<?php
include dirname(dirname(dirname(__FILE__))) . '/config.core.php';
include MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();

$classes= array (
    'modCategory',
//    'modCategoryClosure',

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
$tpl['name'] = 'elements';
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
    switch ($class) {
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
