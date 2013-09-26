<?php
namespace Siphon\Actions;

/**
 * Generate a Siphon Profile from a MODX Instance.
 */
class Profile extends Action {
    /**
     * @var array Defines the fields required for the Extract action.
     */
    public $required = array('name', 'core_path');

    /**
     * Process the Profile action.
     *
     * @throws \Siphon\RequestException If an error is encountered during processing.
     */
    public function process() {
        parent::process();
        try {
            if (!array_key_exists('config_key', $this->request->args())) {
                $this->config_key = 'config';
            }
            if (!array_key_exists('code', $this->request->args())) {
                $this->code = str_replace(array('-', '.'), array('_', '_'), $this->name);
            }

            if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', $this->core_path);
            if (!defined('MODX_CONFIG_KEY')) define('MODX_CONFIG_KEY', $this->config_key);

            $this->initMODX();

            $profile = array(
                'name' => $this->name,
                'code' => $this->code,
                'properties' => array(
                    'modx' => array(
                        'core_path' => $this->core_path,
                        'config_key' => $this->config_key,
                        'context_mgr_path' => $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH),
                        'context_mgr_url' => $this->modx->getOption('manager_url', null, MODX_MANAGER_URL),
                        'context_connectors_path' => $this->modx->getOption('connectors_path', null, MODX_CONNECTORS_PATH),
                        'context_connectors_url' => $this->modx->getOption('connectors_url', null, MODX_CONNECTORS_URL),
                        'context_web_path' => $this->modx->getOption('base_path', null, MODX_BASE_PATH),
                        'context_web_url' => $this->modx->getOption('base_url', null, MODX_BASE_URL),
                    ),
                ),
            );

            $workspacePath = $this->getWorkspaceDir();
            $profileFileName = $this->getProfileFilename();
            $profileFilePath = $workspacePath . $profileFileName;
            $this->modx->cacheManager->writeFile($profileFilePath, $this->modx->toJSON($profile));

            if ($this->target && $this->push) {
                if (!$this->push($profileFilePath, $this->target)) {
                    throw new \Siphon\RequestException("Error pushing profile {$profileFilePath} to {$this->target}", $this->request->results);
                }
                $this->request->log("Successfully pushed profile {$profileFilePath} to {$this->target}");
            }
        } catch (\Exception $e) {
            throw new \Siphon\RequestException("Error generating profile: " . $e->getMessage(), $this->request->results, $e);
        }
    }

    /**
     * Push the profile to a specified target location.
     *
     * @param string $source A valid file or stream location for the profile source.
     * @param string $target A valid file or stream location for the profile target.
     * @return bool True if the snapshot was pushed successfully to the target.
     */
    public function push($source, $target) {
        $pushed = false;
        if ($this->modx->getCacheManager()) {
            $pushed = $this->modx->cacheManager->copyFile(
                $source,
                $target,
                array('copy_preserve_permissions' => true)
            );
        }
        return $pushed;
    }
}