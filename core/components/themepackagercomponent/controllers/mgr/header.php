<?php
/**
 * ThemePackagerComponent
 *
 * Copyright 2013 by Mike Schell <mike@modx.com> for MODX, LLC
 *
 * This file is part of ThemePackagerComponent.
 *
 * ThemePackagerComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThemePackagerComponent is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ThemePackagerComponent; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package themepackagercomponent
 */
/**
 * @package themepackagercomponent
 * @subpackage controllers
 */
$modx = $this->modx;
$modx->regClientStartupScript($this->config['jsUrl'].'themepackagercomponent.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    TP.config = '.$modx->toJSON($this->config).';
    TP.config.connector_url = "'.$this->config['connectorUrl'].'";
    TP.request = '.$modx->toJSON($_GET).';
});
</script>');

return '';