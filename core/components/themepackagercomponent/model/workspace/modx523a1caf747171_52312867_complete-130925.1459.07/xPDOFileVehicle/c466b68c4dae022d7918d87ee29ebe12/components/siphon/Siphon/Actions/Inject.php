<?php
namespace Siphon\Actions;

/**
 * Inject a Snapshot into a MODX Instance.
 */
class Inject extends Action {
    /**
     * @var array Defines the fields required for the Inject action.
     */
    public $required = array('profile', 'source');
    /**
     * @var \modX An instance of modX to Inject a Snapshot into.
     */
    public $modx;
    /**
     * @var \Siphon\Transport\SiphonTransport The Snapshot transport package to be Injected into the \modX instance.
     */
    public $package;

    /**
     * Process the Inject action.
     *
     * @throws \Siphon\RequestException If an error is encountered during processing.
     */
    public function process() {
        parent::process();
        try {
            $this->profile = $this->loadProfile($this->profile);

            define('MODX_CORE_PATH', $this->profile->properties->modx->core_path);
            define('MODX_CONFIG_KEY', !empty($this->profile->properties->modx->config_key) ? $this->profile->properties->modx->config_key : 'config');

            $this->initMODX();

            $this->modx->setOption(\xPDO::OPT_SETUP, true);

            $this->modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
            $this->modx->loadClass('transport.xPDOVehicle', XPDO_CORE_PATH, true, true);
            $this->modx->loadClass('transport.xPDOObjectVehicle', XPDO_CORE_PATH, true, true);

            $transportName = basename($this->source);
            if (SIPHON_BASE_PATH . 'workspace/' . $transportName !== realpath($this->source)) {
                if (!$this->pull($this->source, SIPHON_BASE_PATH . 'workspace/' . $transportName)) {
                    throw new \Siphon\RequestException("Error pulling {$this->source}", $this->request->results);
                }
            } else {
                $this->preserveWorkspace = true;
            }

            $this->package = \Siphon\Transport\SiphonTransport::retrieve($this->modx, SIPHON_BASE_PATH . 'workspace/' . $transportName, SIPHON_BASE_PATH . 'workspace/');
            if (!$this->package instanceof \Siphon\Transport\SiphonTransport) {
                throw new \Siphon\RequestException("Error extracting {$transportName} in workspace/", $this->request->results);
            }

            $this->package->preInstall();

            if (!$this->package->install(array(\xPDOTransport::PREEXISTING_MODE => \xPDOTransport::REMOVE_PREEXISTING))) {
                throw new \Siphon\RequestException("Error installing {$transportName}", $this->request->results);
            }

            $this->package->postInstall();

            if ($this->modx->getCacheManager()) {
                $this->modx->cacheManager->refresh();
                $this->modx->cacheManager->deleteTree($this->modx->cacheManager->getCachePath());
            }

            if (!$this->preserveWorkspace && $this->modx->getCacheManager()) {
                $this->modx->cacheManager->deleteTree($this->package->path . $transportName);
                @unlink($this->package->path . $transportName . '.transport.zip');
            }

            $this->request->log("Successfully injected {$transportName} into instance {$this->profile->code}");
        } catch (\Exception $e) {
            throw new \Siphon\RequestException('Error injecting snapshot: ' . $e->getMessage(), $this->request->results, $e);
        }
    }

    /**
     * Pull a snapshot from a source to a target.
     *
     * @param string $source A valid stream URI or file path to the snapshot source.
     * @param string $target A valid stream URI or file path to copy the snapshot to.
     * @return bool True if the pull was completed successfully.
     */
    public function pull($source, $target) {
        $pulled = false;
        if ($this->modx->getCacheManager()) {
            $pulled = $this->modx->cacheManager->copyFile($source, $target, array('copy_preserve_permissions' => true));
        }
        return $pulled;
    }
}