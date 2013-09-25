<?php
namespace Siphon\Actions;

/**
 * Abstract class representing a Siphon action.
 */
abstract class Action {
    /** @var array An array of required arguments for the action. */
    public $required = array();
    /** @var \Siphon\Request A reference to the request calling this action. */
    public $request;
    /** @var \modX An instance of modX. */
    public $modx;

    /**
     * Construct a new Action.
     *
     * @param array &$request The request calling this action.
     */
    public function __construct(\Siphon\Request &$request) {
        $this->request =& $request;
    }

    public function __get($name) {
        return $this->request->args($name);
    }

    public function __set($name, $value) {
        if (!empty($name)) {
            $this->request->$name = $value;
        }
    }

    public function __isset($name) {
        return isset($this->request->$name);
    }

    /**
     * Process this action.
     *
     * Call parent::process() explicitly to validate the arguments.
     */
    public function process() {
        $this->validateArgs();
        $this->registerRepositoryStream($this->request->args('s3'));
    }

    /**
     * Validate the required arguments for this action are in the request.
     *
     * @throws \Siphon\RequestException If required arguments are missing.
     */
    public function validateArgs() {
        if (!empty($this->required)) {
            $invalid = array_diff($this->required, array_keys($this->request->args()));
            if (!empty($invalid)) {
                foreach ($invalid as $argKey) {
                    $this->request->results[] = "{$argKey} required for this request.";
                }
                throw new \Siphon\RequestException("Required arguments missing.", $this->request->results);
            }
        }
    }

    /**
     * Registers a stream wrapper for accessing MODX Repositories
     *
     * @param array $config An optional array for overriding default wrapper configuration.
     */
    public function registerRepositoryStream(array $config = array()) {
        require_once(SIPHON_BASE_PATH . 'vendor/s3/lib/wrapper/aS3StreamWrapper.class.php');
        $wrapper = new \aS3StreamWrapper();
        $registered = $wrapper->register(array_merge(array(
            'protocol' => 's3',
            'acl' => \AmazonS3::ACL_PRIVATE,
            'region' => \AmazonS3::REGION_US_W1
        ), $config));
    }

    /**
     * Initialize a MODX instance for use with the Siphon Action.
     *
     * @throws \Siphon\RequestException If the MODX instance could not be initialized or an error occurred during
     * the attempt.
     */
    public function initMODX() {
        if (defined('MODX_CORE_PATH')) {
            try {
                include MODX_CORE_PATH . 'model/modx/modx.class.php';
                $logTarget = $this->request->args('log_target') !== null ? $this->request->args('log_target') : array('target' => 'ARRAY', 'target_options' => array('var' => &$this->request->results));
                $logLevel = $this->request->args('log_level') !== null ? $this->request->args('log_level') : \modX::LOG_LEVEL_INFO;
                $siphonConfig = array(
                    'log_target' => $logTarget,
                    'log_level' => $logLevel,
                    'cache_db' => false,
                );
                $this->modx = new \modX('', $siphonConfig);
                $this->modx->setLogLevel($siphonConfig['log_level']);
                $this->modx->setLogTarget($siphonConfig['log_target']);
                $this->modx->setOption('cache_db', $siphonConfig['cache_db']);
                $this->modx->getVersionData();
                if (version_compare($this->modx->version['full_version'], '2.2.1-pl', '>=')) {
                    $this->modx->initialize('mgr', $siphonConfig);
                } else {
                    $this->modx->initialize('mgr');
                }
                $this->modx->setLogLevel($siphonConfig['log_level']);
                $this->modx->setLogTarget($siphonConfig['log_target']);
                $this->modx->setOption('cache_db', $siphonConfig['cache_db']);
            } catch (\Exception $e) {
                throw new \Siphon\SiphonException("Error initializing MODX: " . $e->getMessage(), $this->request->results);
            }
        } else {
            throw new \Siphon\RequestException("Could not initialize MODX: MODX_CORE_PATH not defined.", $this->request->results);
        }
    }

    /**
     * Load JSON profile data into a PHP stdObject instance.
     *
     * @param $profile A valid stream or file location for the profile.
     * @return object A stdObject representation of the JSON profile data.
     */
    protected function loadProfile($profile) {
        $decoded = json_decode(file_get_contents($profile));
        if (!empty($decoded->code)) {
            $decoded->code = str_replace(array('-', '.'), array('_', '_'), $decoded->code);
        } else {
            throw new \Siphon\SiphonException("Error getting 'code' from profile {$profile}", $this->request->results);
        }
        return $decoded;
    }
}
