<?php
/**
 * portable identifier
 *
 * simply returns the passed identifier or list of identifiers
 *
 * but the real purpose of this snippet is to act as a placeholder for a packaging script, whose purpose is to replace
 * this snippet with another placeholder which will be resolved to the correct identifier on a target instance
 */
$id = $scriptProperties['id'];
$return = $id;
if(preg_match('/([a-zA-Z]+)([0-9,\s]+)/', $id, $ma)) {
    $return = str_replace(' ', '', $ma[2]);
}
return $return;
