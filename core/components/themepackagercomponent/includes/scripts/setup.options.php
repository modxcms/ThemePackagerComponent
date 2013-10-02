<?php
/**
 * Build the setup options form.
 *
 * @package themepackagercomponent
 * @subpackage build
 */
/* set some default values */
$values = array(
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        //$setting = $modx->getObject('modSystemSetting',array('key' => 'tpc.install_replace'));
        //if ($setting != null) { $values['install_replace'] = $setting->get('value'); }
        //unset($setting);
        break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

$output = '';

if ($attributes['enduser_option_merge'] == 'yes') {
    $checked = $attributes['enduser_install_action_default'] == 'yes' ? ' checked="checked"' : '';
    $output .= <<<HTML
<label for="themepackagercomponent-install_replace">Overwrite site with Theme?</label>
<input type="checkbox" name="install_replace" id="themepackagercomponent-install_replace" value="true" {$checked}/>
<br /><br />

HTML;
}

if ($attributes['enduser_option_samplecontent'] == 'yes') {
    $output .= <<<HTML
<label for="themepackagercomponent-sample_content">Install Sample Content?</label>
<input type="checkbox" name="sample_content" id="themepackagercomponent-sample_content" value="true" />
<br /><br />

HTML;
}


return $output;