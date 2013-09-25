<?php
namespace Siphon\Actions;

class UserCreate extends Action {
    /**
     * @var array Defines the fields required for the UserCreate action.
     */
    public $required = array('profile', 'username');

    public function process() {
        parent::process();
        try {
            $this->profile = $this->loadProfile($this->profile);
            if (empty($this->passwordnotifymethod)) {
                $this->passwordnotifymethod = 's';
            }
            if (!empty($this->password)) {
                $this->passwordgenmethod = '';
                $this->newpassword = $this->password;
                $this->specifiedpassword = $this->password;
                $this->confirmpassword = $this->password;
            }

            define('MODX_CORE_PATH', $this->profile->properties->modx->core_path);
            define('MODX_CONFIG_KEY', !empty($this->profile->properties->modx->config_key) ? $this->profile->properties->modx->config_key : 'config');

            $this->initMODX();
            $this->modx->getService('error', 'error.modError');
            $this->modx->error->message = '';
            $this->modx->setOption(\xPDO::OPT_SETUP, true);

            /** @var \modProcessorResponse $response */
            $response = $this->modx->runProcessor('security/user/create', $this->request->args());
            if ($response->isError()) {
                throw new \Siphon\SiphonException(implode("\n", $response->getAllErrors()) . "\n0");
            } else {
                $this->request->log("Created user for {$this->profile->name} with username {$this->username}: {$response->getMessage()}");
            }
            $this->request->log('1', false);
        } catch (\Exception $e) {
            throw new \Siphon\SiphonException("Error creating MODX user: {$e->getMessage()}");
        }
    }
}