<?php
/**
 * @package themepackagercomponent
 */
require_once dirname(__FILE__) . '/tpcBuilderInterface.class.php';
class Modx_tpcVaporBuilder implements Modx_Package_Builder {
    /**
     * A reference to the modX instance communicating with this service instance.
     * @var modX
     */
    public $modx= null;
    /**
     * A collection of parameters defining all of the details of the package to be built.
     * @var array
     */
    protected $parameters= array();

    public function __construct (modX &$modx, array $param) {
        $this->modx = $modx;
        $this->parameters = $param;
    }


    function handle() {

        $startTime = microtime(true);
        $modx = &$this->modx;

        $version = $this->parameters['version'];
        $release = $this->parameters['release'];
        $name_lower = $this->parameters['name_lower'];
        $cachePath = $this->parameters['cachePath'];
        $pathLookups = $this->parameters['pathLookups'];

        $everything = $this->parameters['everything'] == 'yes';
        $chunks = $everything ? true : $this->parameters['tp_chunk_ids'];
        $templates = $everything ? true : $this->parameters['tp_template_ids'];
        $packageList = $everything ? true : $modx->fromJSON($this->parameters['packages']);
        $directoryList = $everything? true : $modx->fromJSON($this->parameters['directories']);
        $resources = $everything ? true : array_map('trim', explode(',', $this->parameters['resources']));

        define('VAPOR_DIR', $modx->tp->config['corePath']);
        define('VAPOR_VERSION', '1.2.0-dev');

        // @todo load options from settings
        $vaporOptions = array(
            'excludeExtraTablePrefix' => array(),
            'excludeExtraTables' => array(),
            'excludeFiles' => array()
        );

        if (!ini_get('safe_mode')) {
            set_time_limit(0);
        }

        $options = array(
            'log_level' => xPDO::LOG_LEVEL_INFO,
            'log_target' => array(
                'target' => 'FILE',
                'options' => array(
                    'filename' => 'vapor-' . strftime('%Y%m%dT%H%M%S', $startTime) . '.log'
                )
            ),
            xPDO::OPT_CACHE_DB => false,
            xPDO::OPT_SETUP => true
        );

        // @todo: confirm these settings are working, as we're skipping mgr context initialization
        $originalLogTarget = $modx->setLogTarget($options['log_target']);
        $originalLogLevel = $modx->setLogLevel($options['log_level']);
        $originalOptCacheDb = $modx->config[xPDO::OPT_CACHE_DB];
        $modx->setOption(xPDO::OPT_CACHE_DB, false);
        $originalOptSetup = $modx->config[xPDO::OPT_SETUP];
        $modx->setOption(xPDO::OPT_SETUP, true);
        $originalDebug = $modx->setDebug(-1);

        $returnMessage = '';
        try {
            $modx->getVersionData();
            $modxVersion = $modx->version['full_version'];


            $modxSettingsVersion = $modx->getOption('settings_version', null, '');
            $modxSettingsDistro = $modx->getOption('settings_distro', null, '');

            $modxDatabase = $modx->getOption('dbname', $options, $modx->getOption('database', $options));
            $modxTablePrefix = $modx->getOption('table_prefix', $options, '');

            $core_path = realpath($modx->getOption('core_path', $options, MODX_CORE_PATH)) . '/';
            $assets_path = realpath($modx->getOption('assets_path', $options, MODX_ASSETS_PATH)) . '/';
            $manager_path = realpath($modx->getOption('manager_path', $options, MODX_MANAGER_PATH)) . '/';
            $base_path = realpath($modx->getOption('base_path', $options, MODX_BASE_PATH)) . '/';

            $modx->log(modX::LOG_LEVEL_INFO, "Vapor version: " . VAPOR_VERSION);
            $modx->log(modX::LOG_LEVEL_INFO, "Vapor options: " . print_r($vaporOptions, true));
            $modx->log(modX::LOG_LEVEL_INFO, "PHP version: " . PHP_VERSION);

            $modx->log(modX::LOG_LEVEL_INFO, "MODX core version: " . $modxVersion);
            $modx->log(modX::LOG_LEVEL_INFO, "MODX settings_version: " . $modxSettingsVersion);
            $modx->log(modX::LOG_LEVEL_INFO, "MODX settings_distro: " . $modxSettingsDistro);
            $modx->log(modX::LOG_LEVEL_INFO, "MODX core_path: " . $core_path);
            $modx->log(modX::LOG_LEVEL_INFO, "MODX assets_path: " . $assets_path);
            $modx->log(modX::LOG_LEVEL_INFO, "MODX manager_path: " . $manager_path);
            $modx->log(modX::LOG_LEVEL_INFO, "MODX base_path: " . $base_path);

            $modx->loadClass('transport.modPackageBuilder', '', false, true);
            $builder = new modPackageBuilder($modx);

            /** @var modWorkspace $workspace */
            $workspace = $modx->getObject('modWorkspace', 1);
            if (!$workspace) {
                $modx->log(modX::LOG_LEVEL_FATAL, "no workspace!");
            }

            if (!defined('PKG_NAME')) define('PKG_NAME', str_replace(array('-', '.'), array('_', '_'), $name_lower));
            define('PKG_VERSION', $version);
            define('PKG_RELEASE', $release);

            /** @var modTransportPackage $package */
            $package = $builder->createPackage(PKG_NAME, PKG_VERSION, PKG_RELEASE);

            /* Defines the classes to extract */
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
            );
            if (version_compare($modxVersion, '2.2.0', '>=')) {
                array_push(
                    $classes,
                    'modDashboard',
                    'modDashboardWidget',
                    'modDashboardWidgetPlacement',
                    'sources.modAccessMediaSource',
                    'sources.modMediaSource',
                    'sources.modMediaSourceElement',
                    'sources.modMediaSourceContext'
                );
            }
            if ($everything) {
                $classesToTruncate = $classes;
            } else {
                $classesToTruncate = array(
                    'modChunk',
                    'modCategory',
                    'modCategoryClosure',
                    'modElementPropertySet',
                    'modManagerLog',
                    'modPlugin',
                    'modResource',
                    'modSnippet',
                    'modTemplate',
                    'modTemplateVar',
                    'modTemplateVarTemplate',
                    'modTemplateVarResource',
                    'modTemplateVarResourceGroup',
                );
            }

            // @todo decide if any of Vapor's default web-root directory file additions should be included

            // add user-specified directories
            if (!empty($directoryList) && is_array($directoryList)) {
                foreach ($directoryList as $directoryData) {
                    if (empty($directoryData['source']) || empty($directoryData['target'])) continue;

                    $source = str_replace($pathLookups['sources'],$pathLookups['targets'],$directoryData['source']);
                    if (empty($source)) continue;
                    $l = strlen($source);
                    if (substr($source,$l-1,$l) != '/') $source .= '/';
                    if (!file_exists($source) || !is_dir($source)) continue;

                    $target = str_replace($pathLookups['sources'],array(
                        '".MODX_BASE_PATH."',
                        '".MODX_CORE_PATH."',
                        '".MODX_ASSETS_PATH."',
                    ),$directoryData['target']);
                    if (empty($target)) continue;
                    $l = strlen($target);
                    if (substr($target,$l-1,$l) != '/') $target .= '/';

                    $target = 'return "'.$target.'";';

                    $modx->log(modX::LOG_LEVEL_INFO, "Packaging directory " . $source);
                    $package->put(
                        array(
                            'source' => $source,
                            'target' => $target
                        ),
                        array(
                            'vehicle_class' => 'xPDOFileVehicle'
                        )
                    );
                }
            }
            // @todo handle $directoryList === true (everything)

            if (!ini_get('safe_mode')) {
                set_time_limit(0);
            }

            /* package up the vapor model for use on install */
            $modx->log(modX::LOG_LEVEL_INFO, "Packaging vaporVehicle class");
            $package->put(
                array(
                    'source' => VAPOR_DIR . 'model/vapor',
                    'target' => "return MODX_CORE_PATH . 'components/vapor/model/';"
                ),
                array(
                    'vehicle_class' => 'xPDOFileVehicle',
                    'validate' => array(
                        array(
                            'type' => 'php',
                            'source' => VAPOR_DIR . 'includes/scripts/validate.truncate_tables.php',
                            'classes' => $classesToTruncate
                        ),
                    ),
                    'resolve' => array(
                        array(
                            'type' => 'php',
                            'source' => VAPOR_DIR . 'includes/scripts/resolve.vapor_model.php'
                        )
                    )
                )
            );

            $attributes = array(
                'preserve_keys' => false,
                'update_object' => true
            );

            /* get the extension_packages and resolver */
            $object = $modx->getObject('modSystemSetting', array('key' => 'extension_packages'));
            if ($object) {
                $extPackages = $object->get('value');
                $extPackages = $modx->fromJSON($extPackages);
                foreach ($extPackages as &$extPackage) {
                    if (!is_array($extPackage)) continue;

                    foreach ($extPackage as $pkgName => &$pkg)
                        if (!empty($pkg['path']) && strpos($pkg['path'], '[[++') === false) {
                            if (substr($pkg['path'], 0, 1) !== '/' || (strpos($pkg['path'], $base_path) !== 0 && strpos($pkg['path'], $core_path) !== 0)) {
                                $path = realpath($pkg['path']);
                                if ($path === false) {
                                    $path = $pkg['path'];
                                } else {
                                    $path = rtrim($path, '/') . '/';
                                }
                            } else {
                                $path = $pkg['path'];
                            }
                            if (strpos($path, $core_path) === 0) {
                                $path = str_replace($core_path, '[[++core_path]]', $path);
                            } elseif (strpos($path, $assets_path) === 0) {
                                $path = str_replace($assets_path, '[[++assets_path]]', $path);
                            } elseif (strpos($path, $manager_path) === 0) {
                                $path = str_replace($manager_path, '[[++manager_path]]', $path);
                            } elseif (strpos($path, $base_path) === 0) {
                                $path = str_replace($base_path, '[[++base_path]]', $path);
                            }
                            $pkg['path'] = $path;
                        }
                }
                $modx->log(modX::LOG_LEVEL_INFO, "Setting extension packages to: " . print_r($extPackages, true));

                $object->set('value', $modx->toJSON($extPackages));
                $package->put($object, array_merge($attributes,
                    array(
                        'resolve' => array(
                            array(
                                'type' => 'php',
                                'source' => VAPOR_DIR . 'includes/scripts/resolve.extension_packages.php'
                            ),
                        )
                    )
                ));
            }

            /* loop through the classes and package the objects */
            foreach ($classes as $class) {
                if (!ini_get('safe_mode')) {
                    set_time_limit(0);
                }

                $instances = 0;
                $classCriteria = null;
                $classAttributes = $attributes;
                switch ($class) {
                    case 'modChunk':
                        $classAttributes['unique_key'] = 'name';
                        $classAttributes['related_objects'] = true;
                        $classAttributes['related_object_attributes'] = array(
                            'PropertySets'=> array(
                                'preserve_keys'=> false,
                                'update_object'=> true,
                                'unique_key'=> array("element", "element_class", "property_set"),
                                'related_objects'=> true,
                                'related_object_attributes'=> array(
                                    'PropertySet'=> array(
                                        'preserve_keys'=> false,
                                        'update_object'=> true,
                                        'unique_key'=> 'name'
                                    )
                                )
                            ),
                            'Category'=> array(
                                'preserve_keys'=> false,
                                'update_object'=> true,
                                'unique_key'=> 'category'
                            )
                        );
                        if (!$everything) {
                            $classCriteria = array('modChunk.id:IN'=> explode(',', $chunks));
                        }
                        $Chunks = $modx->getCollectionGraph($class, '{"Category":{}}', $classCriteria);
                        foreach ($Chunks as $object) {
                            // un-nest categories for theme package
                            if (isset($object->Category) && is_object($object->Category)) {
                                $object->Category->set('parent', 0);
                            }
                            if ($package->put($object, $classAttributes)) {
                                $instances++;
                            } else {
                                $modx->log(modX::LOG_LEVEL_WARN, "Could not package {$class} instance with pk: " . print_r($object->getPrimaryKey()));
                            }
                        }
                        continue 2;
                    case 'modTemplate':
                        $classAttributes['unique_key'] = 'templatename';
                        $classAttributes['related_objects'] = true;
                        $classAttributes['related_object_attributes'] = array(
                            'PropertySets'=> array(
                                'preserve_keys'=> false,
                                'update_object'=> true,
                                //'unique_key'=> array("element", "element_class", "property_set"),
                                'related_objects'=> true,
                                'related_object_attributes'=> array(
                                    'PropertySet'=> array(
                                        'preserve_keys'=> false,
                                        'update_object'=> true,
                                        'unique_key'=> 'name'
                                    )
                                )
                            ),
                            'Category'=> array(
                                'preserve_keys'=> false,
                                'update_object'=> true,
                                'unique_key'=> 'category'
                            ),
                            'TemplateVarTemplates'=> array(
                                'preserve_keys'=> false,
                                'update_object'=> true,
                                'related_objects'=> true,
                                'related_object_attributes'=> array(
                                    'TemplateVar'=> array(
                                        'preserve_keys'=> false,
                                        'update_object'=> true,
                                        'unique_key'=> 'name'
                                    )
                                )
                            )
                        );
                        if (!$everything) {
                            $classCriteria = array(
                                'modTemplate.id:IN'=> explode(',', $templates)
                            );
                        }
                        $Templates = $modx->getCollectionGraph($class, '{"Category":{},"TemplateVarTemplates":{"TemplateVar":{}},"PropertySets":{"PropertySet":{}}}', $classCriteria);
                        $instances = 0;
                        foreach ($Templates as $object) {
                            // un-nest categories for theme package
                            if (isset($object->Category) && is_object($object->Category)) {
                                $object->Category->set('parent', 0);
                            }
                            if ($package->put($object, $classAttributes)) {
                                $instances++;
                            } else {
                                $modx->log(modX::LOG_LEVEL_WARN, "Could not package {$class} instance with pk: " . print_r($object->getPrimaryKey()));
                            }
                            $modx->log(modX::LOG_LEVEL_WARN, "Packaged {$instances} of {$class}");
                        }
                        continue 2;

                    case 'modResource':

                        if ($everything) {
                            continue 1;
                        } elseif (is_array($resources) && count($resources) > 0) {
                            $classAttributes['unique_key'] = 'pagetitle';
                            $classAttributes['related_objects'] = true;
                            $classAttributes['related_object_attributes'] = array(
                                'TemplateVarResources'=> array(
                                    'preserve_keys'=> false,
                                    'update_object'=> true,
                                    'unique_key'=> array('tmplvarid', 'contentid'),
                                    'related_objects'=> true,
                                    'related_object_attributes'=> array(
                                        'TemplateVar'=> array(
                                            'preserve_keys'=> false,
                                            'update_object'=> false,
                                            'unique_key'=> 'name'
                                        )
                                    )
                                ),
                                'Templates'=> array(
                                    'preserve_keys'=> false,
                                    'update_object'=> false,
                                    'unique_key'=> 'templatename'
                                )
                            );
                            $classCriteria = array(
                                'modResource.id:IN'=> $resources
                            );
                            $instances = 0;
                            $Resources = $modx->getIterator('modResource', $classCriteria);
                            foreach ($Resources as $object) {
                                $object->getGraph('{"TemplateVarResources":{"TemplateVar":{}}, "Templates":{}}');
                                if ($package->put($object, $classAttributes)) {
                                    $instances++;
                                } else {
                                    $modx->log(modX::LOG_LEVEL_WARN, "Could not package {$class} instance " . print_r($object->getPrimaryKey()));
                                }
                            }
                            $modx->log(modX::LOG_LEVEL_WARN, "Packaged {$instances} of {$class}");
                        }
                        continue 2;

                    case 'modSystemSetting':
                        if ($everything) {
                            $classCriteria = array('key:!=' => 'extension_packages');
                        } else {
                            continue 2;
                        }
                        break;
                    case 'modWorkspace':
                        if ($everything) {
                            /** @var modWorkspace $object */
                            foreach ($modx->getIterator('modWorkspace', $classCriteria) as $object) {
                                if (strpos($object->path, $core_path) === 0) {
                                    $object->set('path', str_replace($core_path, '{core_path}', $object->path));
                                } elseif (strpos($object->path, $assets_path) === 0) {
                                    $object->set('path', str_replace($assets_path, '{assets_path}', $object->path));
                                } elseif (strpos($object->path, $manager_path) === 0) {
                                    $object->set('path', str_replace($manager_path, '{manager_path}', $object->path));
                                } elseif (strpos($object->path, $base_path) === 0) {
                                    $object->set('path', str_replace($base_path, '{base_path}', $object->path));
                                }
                                if ($package->put($object, $classAttributes)) {
                                    $instances++;
                                } else {
                                    $modx->log(modX::LOG_LEVEL_WARN, "Could not package {$class} instance with pk: " . print_r($object->getPrimaryKey()));
                                }
                            }
                            $modx->log(modX::LOG_LEVEL_INFO, "Packaged {$instances} of {$class}");
                        }
                        continue 2;
                    case 'modMenu':
                        if ($everything) {
                            $classCriteria = array('text:!='=> 'themepackagercomponent');
                        } else {
                            continue 2;
                        }
                        break;
                    case 'transport.modTransportPackage':
                        $modx->loadClass($class);
                        if (!empty($packageList)) {
                            $modx->addPackage('modx.transport',$modx->getOption('core_path').'model/');

                            $packageDir = $modx->getOption('core_path',null,MODX_CORE_PATH).'packages/';
                            $spAttr = array('vehicle_class' => 'xPDOTransportVehicle');
                            $spReplaces = array(
                                '{version}',
                                '{version_major}',
                                '{name}',
                            );
                            $resolverReplaces = array(
                                '{signature}',
                                '{provider}',
                                '{attributes}',
                                '{metadata}',
                            );

                            foreach ($packageList as $packageData) {
                                $file = $packageDir.$packageData['signature'].'.transport.zip';
                                if (!file_exists($file)) continue;

                                $subpackage = $modx->getObject('transport.modTransportPackage',array('signature' => $packageData['signature']));
                                if (!$subpackage) {
                                    $modx->log(modX::LOG_LEVEL_ERROR,'[ThemePackagerComponent] Package could not be found with signature: '.$packageData['signature']);
                                    continue;
                                }

                                /* create package as subpackage */
                                $subpackagevehicle = $builder->createVehicle(array(
                                    'source' => $file,
                                    'target' => "return MODX_CORE_PATH . 'packages/';",
                                ),$spAttr);

                                /* get signature values */
                                $sig = explode('-',$packageData['signature']);
                                $vsig = explode('.',$sig[1]);

                                /* create custom package validator to resolve if the package on the client server is newer than this version */
                                $cacheKey = 'themepackagercomponent/validators/'.$packageData['signature'].'.php';
                                $validator = file_get_contents($modx->tp->config['includesPath'].'validate.subpackage.php');
                                $validator = str_replace($spReplaces,array(
                                    $sig[1].(!empty($sig[2]) ? '-'.$sig[2] : ''),
                                    $vsig[0],
                                    $sig[0],
                                ),$validator);
                                $modx->cacheManager->writeFile($cachePath.$cacheKey,$validator);

                                /* add validator to vehicle */
                                $subpackagevehicle->validate('php',array(
                                    'source' => $cachePath.$cacheKey,
                                ));

                                /* add resolver to subpackage to add to packages grid */
                                $cacheKey = 'themepackagercomponent/resolvers/'.$packageData['signature'].'.php';
                                $resolver = file_get_contents($modx->tp->config['includesPath'].'resolve.subpackage.php');
                                $resolver = str_replace($resolverReplaces,array(
                                    $packageData['signature'],
                                    $subpackage->get('provider'),
                                    str_replace("'","\'",$modx->toJSON($subpackage->get('attributes'))),
                                    str_replace("'","\'",$modx->toJSON($subpackage->get('metadata'))),
                                ),$resolver);
                                $modx->cacheManager->writeFile($cachePath.$cacheKey,$resolver);

                                /* add resolver to vehicle */
                                $subpackagevehicle->resolve('php',array(
                                    'source' => $cachePath.$cacheKey,
                                ));

                                /* add subpackage to build */
                                $builder->putVehicle($subpackagevehicle);
                            }
                        }
                        $modx->log(modX::LOG_LEVEL_INFO, "Packaged {$instances} of {$class}");
                        continue 2;
                    case 'sources.modMediaSource':
                        if (!$everything) {
                            continue 2;
                        }
                        foreach ($modx->getIterator('sources.modMediaSource') as $object) {
                            $classAttributes = $attributes;
                            /** @var modMediaSource $object */
                            if ($object->get('is_stream') && $object->initialize()) {
                                $sourceBases = $object->getBases('');
                                $source = $object->getBasePath();
                                if (!$sourceBases['pathIsRelative'] && strpos($source, '://') === false) {
                                    $sourceBasePath = $source;
                                    if (strpos($source, $base_path) === 0) {
                                        $sourceBasePath = str_replace($base_path, '', $sourceBasePath);
                                        $classAttributes['resolve'][] = array(
                                            'type' => 'php',
                                            'source' => VAPOR_DIR . 'includes/scripts/resolve.media_source.php',
                                            'target' => $sourceBasePath,
                                            'targetRelative' => true
                                        );
                                    } else {
                                        /* when coming from Windows sources, remove "{volume}:" */
                                        if (strpos($source, ':\\') !== false || strpos($source, ':/') !== false) {
                                            $sourceBasePath = str_replace('\\', '/', substr($source, strpos($source, ':') + 1));
                                        }
                                        $target = 'dirname(MODX_BASE_PATH) . "/sources/' . ltrim(dirname($sourceBasePath), '/') . '/"';
                                        $classAttributes['resolve'][] = array(
                                            'type' => 'file',
                                            'source' => $source,
                                            'target' => "return {$target};"
                                        );
                                        $classAttributes['resolve'][] = array(
                                            'type' => 'php',
                                            'source' => VAPOR_DIR . 'includes/scripts/resolve.media_source.php',
                                            'target' => $sourceBasePath,
                                            'targetRelative' => false,
                                            'targetPrepend' => "return dirname(MODX_BASE_PATH) . '/sources/';"
                                        );
                                    }
                                }
                            }
                            if ($package->put($object, $classAttributes)) {
                                $instances++;
                            } else {
                                $modx->log(modX::LOG_LEVEL_WARN, "Could not package {$class} instance with pk: " . print_r($object->getPrimaryKey()));
                            }
                        }
                        $modx->log(modX::LOG_LEVEL_INFO, "Packaged {$instances} of {$class}");
                        continue 2;
                    default:
                        if (!$everything) {
                            // skip everything else
                            continue 2;
                        }
                }
                /** @var xPDOObject $object */
                foreach ($modx->getIterator($class, $classCriteria) as $object) {
                    if ($package->put($object, $classAttributes)) {
                        $instances++;
                    } else {
                        $modx->log(modX::LOG_LEVEL_WARN, "Could not package {$class} instance with pk: " . print_r($object->getPrimaryKey()));
                    }
                }
                $modx->log(modX::LOG_LEVEL_INFO, "Packaged {$instances} of {$class}");
            }

            /* collect table names from classes and grab any additional tables/data not listed */
            $coreTables = array();
            foreach ($classes as $class) {
                $coreTables[$class] = $modx->quote($modx->literal($modx->getTableName($class)));
            }

            $stmt = $modx->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$modxDatabase}' AND TABLE_NAME NOT IN (" . implode(',', $coreTables) . ")");
            $extraTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (is_array($extraTables) && !empty($extraTables)) {
                $modx->loadClass('vapor.vaporVehicle', VAPOR_DIR . 'model/', true, true);
                $excludeExtraTablePrefix = isset($vaporOptions['excludeExtraTablePrefix']) && is_array($vaporOptions['excludeExtraTablePrefix']) ? $vaporOptions['excludeExtraTablePrefix'] : array();
                $excludeExtraTables = isset($vaporOptions['excludeExtraTables']) && is_array($vaporOptions['excludeExtraTables']) ? $vaporOptions['excludeExtraTables'] : array();
                foreach ($extraTables as $extraTable) {
                    if (in_array($extraTable, $excludeExtraTables)) continue;
                    if (!ini_get('safe_mode')) {
                        set_time_limit(0);
                    }

                    $instances = 0;
                    $object = array();
                    $attributes = array(
                        'vehicle_package' => 'vapor',
                        'vehicle_class' => 'vaporVehicle'
                    );

                    /* remove modx table_prefix if table starts with it */
                    $extraTableName = $extraTable;
                    if (!empty($modxTablePrefix) && strpos($extraTableName, $modxTablePrefix) === 0) {
                        $extraTableName = substr($extraTableName, strlen($modxTablePrefix));
                        $addTablePrefix = true;
                    } elseif (!empty($modxTablePrefix) || in_array($extraTableName, $excludeExtraTablePrefix)) {
                        $addTablePrefix = false;
                    } else {
                        $addTablePrefix = true;
                    }
                    $object['tableName'] = $extraTableName;
                    $modx->log(modX::LOG_LEVEL_INFO, "Extracting non-core table {$extraTableName}");

                    /* generate the CREATE TABLE statement */
                    $stmt = $modx->query("SHOW CREATE TABLE {$modx->escape($extraTable)}");
                    $resultSet = $stmt->fetch(PDO::FETCH_NUM);
                    $stmt->closeCursor();
                    if (isset($resultSet[1])) {
                        if ($addTablePrefix) {
                            $object['drop'] = "DROP TABLE IF EXISTS {$modx->escape('[[++table_prefix]]' . $extraTableName)}";
                            $object['table'] = str_replace("CREATE TABLE {$modx->escape($extraTable)}", "CREATE TABLE {$modx->escape('[[++table_prefix]]' . $extraTableName)}", $resultSet[1]);
                        } else {
                            $object['drop'] = "DROP TABLE IF EXISTS {$modx->escape($extraTableName)}";
                            $object['table'] = $resultSet[1];
                        }

                        /* collect the rows and generate INSERT statements */
                        $object['data'] = array();
                        $stmt = $modx->query("SELECT * FROM {$modx->escape($extraTable)}");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            if ($instances === 0) {
                                $fields = implode(', ', array_map(array($modx, 'escape'), array_keys($row)));
                            }
                            $values = array();
                            while (list($key, $value) = each($row)) {
                                switch (gettype($value)) {
                                    case 'string':
                                        $values[] = $modx->quote($value);
                                        break;
                                    case 'NULL':
                                    case 'array':
                                    case 'object':
                                    case 'resource':
                                    case 'unknown type':
                                        $values[] = 'NULL';
                                        break;
                                    default:
                                        $values[] = (string) $value;
                                        break;
                                }
                            }
                            $values = implode(', ', $values);
                            if ($addTablePrefix) {
                                $object['data'][] = "INSERT INTO {$modx->escape('[[++table_prefix]]' . $extraTableName)} ({$fields}) VALUES ({$values})";
                            } else {
                                $object['data'][] = "INSERT INTO {$modx->escape($extraTable)} ({$fields}) VALUES ({$values})";
                            }
                            $instances++;
                        }
                    }

                    if (!$package->put($object, $attributes)) {
                        $modx->log(modX::LOG_LEVEL_WARN, "Could not package rows for table {$extraTable}: " . print_r($object, true));
                    } else {
                        $modx->log(modX::LOG_LEVEL_INFO, "Packaged {$instances} rows for table {$extraTable}");
                    }
                }
            }

            // include license, readme, changelog and setup options and some other options in package attributes
            $packageAttributes = array();
            if (isset($_FILES['license']) && !empty($_FILES['license']) && $_FILES['license']['error'] == UPLOAD_ERR_OK) {
                $packageAttributes['license'] = file_get_contents($_FILES['license']['tmp_name']);
            }
            if (isset($_FILES['readme']) && !empty($_FILES['readme']) && $_FILES['readme']['error'] == UPLOAD_ERR_OK) {
                $packageAttributes['readme'] = file_get_contents($_FILES['readme']['tmp_name']);
            }
            if (isset($_FILES['changelog']) && !empty($_FILES['changelog']) && $_FILES['changelog']['error'] == UPLOAD_ERR_OK) {
                $packageAttributes['changelog'] = file_get_contents($_FILES['changelog']['tmp_name']);
            }
            if ($this->parameters['enduser_option_merge'] == 'yes' || $this->parameters['enduser_option_samplecontent'] == 'yes') {
                $packageAttributes['setup-options'] = array('source' => VAPOR_DIR . 'includes/scripts/setup.options.php');
            }
            $packageAttributes['enduser_option_merge'] = $this->parameters['enduser_option_merge'];
            $packageAttributes['enduser_install_action_default'] = $this->parameters['enduser_install_action_default'];
            $packageAttributes['enduser_option_samplecontent'] = $this->parameters['enduser_option_samplecontent'];

            $builder->setPackageAttributes($packageAttributes);
            $modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

            if (!ini_get('safe_mode')) {
                set_time_limit(0);
            }

            if (!$package->pack()) {
                $message = "Error extracting package, could not pack transport: {$package->signature}";
                $modx->log(modX::LOG_LEVEL_ERROR, $message);
                echo "{$message}\n";
            } else {
                $message = "Completed extracting package: {$package->signature}";
                $modx->log(modX::LOG_LEVEL_INFO, $message);
                $returnMessage .= "{$message}\n";
            }
            $endTime = microtime(true);
            $modx->log(modX::LOG_LEVEL_INFO, sprintf("Vapor execution completed without exception in %2.4fs", $endTime - $startTime));

            $returnMessage = sprintf("Vapor execution completed without exception in %2.4fs\n", $endTime - $startTime);

            $signature = $package->signature;
            $return = $modx->error->success($signature);

        } catch (Exception $e) {
            if (empty($endTime)) $endTime = microtime(true);
            if (!empty($modx)) {
                $modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
                $modx->log(modX::LOG_LEVEL_INFO, sprintf("Vapor execution completed with exception in %2.4fs", $endTime - $startTime));
            } else {
                $returnMessage .= $e->getMessage() . "\n";
            }
            $returnMessage .= sprintf("Vapor execution completed with exception in %2.4fs\n", $endTime - $startTime);
            $return = $modx->error->failure($returnMessage);
        }


        // cleanup
        $modx->setLogTarget($originalLogTarget);
        $modx->setLogLevel($originalLogLevel);
        $modx->setOption(xPDO::OPT_CACHE_DB, $originalOptCacheDb);
        $modx->setOption(xPDO::OPT_SETUP, $originalOptSetup);
        $modx->setDebug($originalDebug);

        // @todo refactor client to accept return success or fail response with message and/or path to download
        return $return;

    }
}
