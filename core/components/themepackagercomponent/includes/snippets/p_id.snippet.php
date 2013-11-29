<?php
/**
 * portable identifier
 *
 * simply returns the passed identifier or list of identifiers
 *
 * But the real purpose of this snippet is to act as a placeholder for a package resolver,
 * so that the ids specified in the snippet call can be replaced by updated ids on package install.
 */
$id = $scriptProperties['id'];
$return = $id;
if(preg_match('/([a-zA-Z]+)([0-9,\s]+)/', $id, $ma)) {
    $return = str_replace(' ', '', $ma[2]);
}
return $return;
