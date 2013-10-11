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
    $replace_checked = '';
    $merge_checked = '';
    if (array_key_exists('enduser_install_action_default', $options['attributes']) && $options['attributes']['enduser_install_action_default'] == 'replace') {
        $replace_checked = ' checked="checked"';
    } else {
        $merge_checked = ' checked="checked"';
    }
    $output .= <<<HTML
<label for="themepackagercomponent-install_replace">Overwrite site with Theme?</label>
Yes <input type="radio" name="install_replace" id="themepackagercomponent-install_replace-replace" value="replace" {$replace_checked}/>
&nbsp;&nbsp;No <input type="radio" name="install_replace" id="themepackagercomponent-install_replace-merge" value="merge" {$merge_checked}/>
<br /><br />

HTML;
}

if (array_key_exists('enduser_option_samplecontent', $options['attributes']) && $options['attributes']['enduser_option_samplecontent'] == 'yes') {
    $yes_checked = '';
    $no_checked = '';
    if (array_key_exists('enduser_install_samplecontent_default', $options['attributes']) && $options['attributes']['enduser_install_samplecontent_default'] == 'yes') {
        $yes_checked = ' checked="checked"';
    } else {
        $no_checked = ' checked="checked"';
    }
    $output .= <<<HTML
<label for="themepackagercomponent-sample_content">Install Sample Content?</label>
Yes <input type="radio" name="sample_content" id="themepackagercomponent-sample_content-yes" value="yes" {$yes_checked}/>
&nbsp;&nbsp;No <input type="radio" name="sample_content" id="themepackagercomponent-sample_content-no" value="no" {$no_checked}/>
<br /><br />

HTML;
}


return $output;