<?php
namespace Siphon\Actions;
/**
 * Pushes a file to the MODX Cloud Repository
 */
class Push extends Action {
    /**
     * @var array Defines the fields required for the Push action.
     */
    public $required = array('source','target','profile');

    public function process() {
        parent::process();
        try {
            $pushed = false;

            if (empty($this->profile) or !file_exists($this->profile)) {
                throw new \Exception("Profile does not exist at: ".$this->profile);
            }

            $this->profile = $this->loadProfile($this->profile);
            define('MODX_CORE_PATH', $this->profile->properties->modx->core_path);
            define('MODX_CONFIG_KEY', !empty($this->profile->properties->modx->config_key) ? $this->profile->properties->modx->config_key : 'config');

            $this->initMODX();
            $this->modx->getService('error', 'error.modError');
            $this->modx->error->message = '';
            $this->modx->setOption(\xPDO::OPT_SETUP, true);

            if ($this->modx->getCacheManager()) {
                $pushed = $this->modx->cacheManager->copyFile(
                    $this->source,
                    $this->target,
                    array('copy_preserve_permissions' => true)
                );
            }
            $this->request->log($this->target, false);

            $this->request->log($pushed ? '1' : '0', false);
        } catch (\Exception $e) {
            throw new \Siphon\SiphonException("Error pushing to repository: {$e->getMessage()}");
        }
    }
}