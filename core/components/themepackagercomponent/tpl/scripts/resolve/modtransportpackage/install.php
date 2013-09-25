<?php
/**
 * @var \Siphon\Transport\SiphonTransport $transport
 * @var \modTransportPackage $object
 * @var array $options
 */
$result = true;
if ($object instanceof modTransportPackage) {
    $result = $object->install();
}
return $result;