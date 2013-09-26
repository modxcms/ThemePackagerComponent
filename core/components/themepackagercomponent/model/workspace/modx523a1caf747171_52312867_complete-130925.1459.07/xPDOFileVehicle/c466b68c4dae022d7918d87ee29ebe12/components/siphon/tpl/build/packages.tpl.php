<?php
include dirname(dirname(dirname(__FILE__))) . '/config.core.php';
include MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();

$classes= array (
    'transport.modTransportProvider',
    'transport.modTransportPackage',
);
$xPDOObjectAttributes = array(
    'preserve_keys' => true,
    'update_object' => true
);
$xPDOObjectCriteria = array("1 = 1");

$tpl = array();
$tpl['name'] = basename(__FILE__, '.tpl.php');
$tpl['vehicles'] = array();
foreach ($classes as $class) {
    $classAttributes = $xPDOObjectAttributes;
    $classCriteria = $xPDOObjectCriteria;
    $classGraph = array();
    $classGraphCriteria = null;
    $classScript = null;
    $classPackage = 'modx';
    $classOptions = array();
    switch ($class) {
        case 'transport.modTransportPackage':
            $classScript = 'extract/modtransportpackage.php';
            $classOptions['install'] = true;
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
    if (!empty($classOptions)) $vehicle['object'] = array_merge($vehicle['object'], $classOptions);
    $tpl['vehicles'][] = $vehicle;
}
file_put_contents(dirname(dirname(__FILE__)) . '/' . $tpl['name'] . '.tpl.json', json_encode($tpl));
