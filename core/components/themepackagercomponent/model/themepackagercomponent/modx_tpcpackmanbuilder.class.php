<?php
/**
 * @package themepackagercomponent
 */
require_once dirname(__FILE__) . '/tpcBuilderInterface.class.php';
class Modx_tpcPackManBuilder implements Modx_Package_Builder {
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
        $modx =& $this->modx;
        $version = $this->parameters['version'];
        $release = $this->parameters['release'];
        $name_lower = $this->parameters['name_lower'];
        $directories = $this->parameters['directories'];
        $cachePath = $this->parameters['cachePath'];
        $pathLookups = $this->parameters['pathLookups'];

        $modx->loadClass('transport.modPackageBuilder','',false, true);
        $builder = new modPackageBuilder($modx);
        $builder->createPackage($name_lower,$version,$release);
        $builder->registerNamespace($name_lower,false,true,'{core_path}components/'.$name_lower.'/');

        /* create category */
        $category= $modx->newObject('modCategory');
        $category->set('id',1);
        $category->set('category',$_POST['category']);

        /* add Chunks */
        $chunkList = $modx->fromJSON($_POST['chunks']);
        if (!empty($chunkList)) {
            $chunks = array();
            foreach ($chunkList as $chunkData) {
                if (empty($chunkData['id'])) continue;
                $chunk = $modx->getObject('modChunk',$chunkData['id']);
                if (empty($chunk)) continue;

                $chunks[] = $chunk;
            }
            if (empty($chunks)) {
                return $modx->error->failure('Error packaging chunks!');
            }
            $category->addMany($chunks,'Chunks');
            $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($chunks).' chunks...');
        }

        /* add snippets */
        $snippetList = $modx->fromJSON($_POST['snippets']);
        if (!empty($snippetList)) {
            $snippets = array();
            foreach ($snippetList as $snippetData) {
                if (empty($snippetData['id'])) continue;
                $snippet = $modx->getObject('modSnippet',$snippetData['id']);
                if (empty($snippet)) continue;

                $snippets[] = $snippet;

                /* package in assets_path if it exists */
                if (!empty($snippetData['assets_path'])) {
                    $files = str_replace($pathLookups['sources'],$pathLookups['targets'],$snippetData['assets_path']);
                    $l = strlen($files);
                    if (substr($files,$l-1,$l) != '/') $files .= '/';
                    /* verify files exist */
                    if (file_exists($files) && is_dir($files)) {
                        $directories[] = array(
                            'source' => $files,
                            'target' => "return MODX_ASSETS_PATH . 'components/';",
                        );
                    }
                }
                /* package in core_path if it exists */
                if (!empty($snippetData['core_path'])) {
                    $files = str_replace($pathLookups['sources'],$pathLookups['targets'],$snippetData['core_path']);
                    $l = strlen($files);
                    if (substr($files,$l-1,$l) != '/') $files .= '/';
                    /* verify files exist */
                    if (file_exists($files) && is_dir($files)) {
                        $directories[] = array(
                            'source' => $files,
                            'target' => "return MODX_CORE_PATH . 'components/';",
                        );
                    }
                }
            }
            if (empty($snippets)) {
                return $modx->error->failure('Error packaging Snippets!');
            }
            $category->addMany($snippets,'Snippets');
            $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($snippets).' Snippets...');
        }

        /* add Plugins */
        $pluginList = $modx->fromJSON($_POST['plugins']);
        if (!empty($pluginList)) {
            $plugins = array();
            foreach ($pluginList as $pluginData) {
                if (empty($pluginData['id'])) continue;
                $plugin = $modx->getObject('modPlugin',$pluginData['id']);
                if (empty($plugin)) continue;

                $pluginEvents = $plugin->getMany('PluginEvents');
                $plugin->addMany($pluginEvents);

                $attr = array (
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                    xPDOTransport::RELATED_OBJECTS => true,
                    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
                        'PluginEvents' => array(
                            xPDOTransport::PRESERVE_KEYS => true,
                            xPDOTransport::UPDATE_OBJECT => false,
                            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
                        ),
                    ),
                );
                $vehicle = $builder->createVehicle($plugin,$attr);
                $builder->putVehicle($vehicle);

                $plugins[] = $plugin;
            }
            if (empty($plugins)) {
                return $modx->error->failure('Error packaging plugins!');
            }
            //$category->addMany($plugins,'Plugins');
            $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($plugins).' plugins...');
        }

        /* add Templates */
        $tvs = array();
        $tvMap = array();
        $templateList = $modx->fromJSON($_POST['templates']);
        if (!empty($templateList)) {
            $templates = array();
            foreach ($templateList as $templateData) {
                if (empty($templateData['id'])) continue;
                $template = $modx->getObject('modTemplate',$templateData['id']);
                if (empty($template)) continue;

                $templates[] = $template;
                /* add in directory for Template */
                if (!empty($templateData['directory'])) {
                    $files = str_replace($pathLookups['sources'],$pathLookups['targets'],$templateData['directory']);
                    $l = strlen($files);
                    if (substr($files,$l-1,$l) != '/') $files .= '/';
                    /* verify files exist */
                    if (file_exists($files) && is_dir($files)) {
                        $directories[] = array(
                            'source' => $files,
                            'target' => "return MODX_ASSETS_PATH . 'templates/';",
                        );
                    }
                }

                /* collect TVs assigned to Template */
                $c = $modx->newQuery('modTemplateVar');
                $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
                $c->where(array(
                    'TemplateVarTemplates.templateid' => $template->get('id'),
                ));
                $tvList = $modx->getCollection('modTemplateVar',$c);
                foreach ($tvList as $tv) {
                    if (!isset($tvMap[$tv->get('name')])) {
                        $tvs[] = $tv; /* only add TV once */
                        $tvMap[$tv->get('name')] = array();
                    }
                    array_push($tvMap[$tv->get('name')],$template->get('templatename'));
                    $tvMap[$tv->get('name')] = array_unique($tvMap[$tv->get('name')]);
                }
            }
            if (empty($templates)) {
                return $modx->error->failure('Error packaging Templates!');
            }
            $category->addMany($templates,'Templates');
            $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($templates).' Templates...');
        }

        /* add in TVs */
        $category->addMany($tvs);

        /* package in category vehicle */
        $attr = array(
            xPDOTransport::UNIQUE_KEY => 'category',
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RELATED_OBJECTS => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
                'Chunks' => array (
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ),
                'TemplateVars' => array (
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ),
                'Templates' => array (
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'templatename',
                ),
                'Snippets' => array (
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ),
            ),
        );
        $vehicle = $builder->createVehicle($category,$attr);


        /* add user-specified directories */
        $directoryList = $modx->fromJSON($_POST['directories']);
        if (!empty($directoryList)) {
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

                $directories[] = array(
                    'source' => $source,
                    'target' => $target,
                );
            }
        }

        /* add directories to category vehicle */
        if (!empty($directories)) {
            foreach ($directories as $directory) {
                $vehicle->resolve('file',$directory);
            }
            $modx->log(modX::LOG_LEVEL_INFO,'Added '.count($directories).' directories to category...');
        }



        /* create dynamic TemplateVarTemplate resolver */
        if (!empty($tvMap)) {
            $tvp = var_export($tvMap,true);
            $resolverCachePath = $cachePath.'themepackagercomponent/resolve.tvt.php';
            $resolver = file_get_contents($modx->tp->config['includesPath'].'resolve.tvt.php');
            $resolver = str_replace(array('{tvs}'),array($tvp),$resolver);

            $modx->cacheManager->writeFile($resolverCachePath,$resolver);
            $vehicle->resolve('php',array(
                'source' => $resolverCachePath,
            ));
        }

        /* add category vehicle to build */
        $builder->putVehicle($vehicle);



        /* add in packages */
        $packageList = $modx->fromJSON($_POST['packages']);
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

                $package = $modx->getObject('transport.modTransportPackage',array('signature' => $packageData['signature']));
                if (!$package) {
                    $modx->log(modX::LOG_LEVEL_ERROR,'[ThemePackagerComponent] Package could not be found with signature: '.$packageData['signature']);
                    continue;
                }

                /* create package as subpackage */
                $vehicle = $builder->createVehicle(array(
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
                $vehicle->validate('php',array(
                    'source' => $cachePath.$cacheKey,
                ));

                /* add resolver to subpackage to add to packages grid */
                $cacheKey = 'themepackagercomponent/resolvers/'.$packageData['signature'].'.php';
                $resolver = file_get_contents($modx->tp->config['includesPath'].'resolve.subpackage.php');
                $resolver = str_replace($resolverReplaces,array(
                    $packageData['signature'],
                    $package->get('provider'),
                    str_replace("'","\'",$modx->toJSON($package->get('attributes'))),
                    str_replace("'","\'",$modx->toJSON($package->get('metadata'))),
                ),$resolver);
                $modx->cacheManager->writeFile($cachePath.$cacheKey,$resolver);

                /* add resolver to vehicle */
                $vehicle->resolve('php',array(
                    'source' => $cachePath.$cacheKey,
                ));

                /* add subpackage to build */
                $builder->putVehicle($vehicle);
            }
        }

        /* now pack in the license file, readme and setup options */
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
        if (!empty($packageAttributes)) $builder->setPackageAttributes($packageAttributes);


        /* zip up the package */
        $builder->pack();

        /* remove any cached files */
        $modx->cacheManager->deleteTree($cachePath.'themepackagercomponent/',array(
            'deleteTop' => true,
            'skipDirs' => false,
            'extensions' => array('.php'),
        ));

        /* output name to browser */
        $signature = $name_lower.'-'.$version.'-'.$release;
        return $modx->error->success($signature);
    }
}