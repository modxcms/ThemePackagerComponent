<?php
/**
 * Build the setup options form.
 *
 * @package themepackagercomponent
 * @subpackage build
 */
/* set some default values */
$output = '';
$values = array(
);

//$modx->log(xPDO::LOG_LEVEL_INFO, "[setup-options] Install options: " . print_r($options, true));
//$modx->log(xPDO::LOG_LEVEL_INFO, "[setup-options] Install attributes: " . print_r($attributes, true));

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        //$setting = $modx->getObject('modSystemSetting',array('key' => 'tpc.install_replace'));
        //if ($setting != null) { $values['install_replace'] = $setting->get('value'); }
        //unset($setting);
        break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

if (array_key_exists('enduser_option_merge', $options['attributes']) && $options['attributes']['enduser_option_merge'] == 'yes') {
    $checked = array_key_exists('enduser_install_action_default', $options['attributes']) && $options['attributes']['enduser_install_action_default'] == 'yes' ? ' checked="checked"' : '';
    $output .= <<<HTML
<label for="themepackagercomponent-install_replace">Overwrite site with Theme?</label>
<input type="checkbox" name="install_replace" id="themepackagercomponent-install_replace" value="true" {$checked}/>
<br /><br />

HTML;
}

if (array_key_exists('enduser_option_samplecontent', $options['attributes']) && $options['attributes']['enduser_option_samplecontent'] == 'yes') {
    $output .= <<<HTML
<label for="themepackagercomponent-sample_content">Install Sample Content?</label>
<input type="checkbox" name="sample_content" id="themepackagercomponent-sample_content" value="true" />
<br /><br />

HTML;
}


return $output;