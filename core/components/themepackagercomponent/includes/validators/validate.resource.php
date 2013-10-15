<?php
$return = false;
if ($transport && $transport->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $transport->xpdo;
            $samplecontent_default = $modx->getOption('enduser_install_samplecontent_default', $options, 'no');
            $samplecontent_userchoice = $modx->getOption('sample_content', $options, $samplecontent_default);
            $return = $samplecontent_userchoice == 'yes';
            $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "[resource] install sample content? $samplecontent_userchoice");
    }
}

return $return;
