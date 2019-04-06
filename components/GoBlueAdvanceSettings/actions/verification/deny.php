<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

$guid = input('guid');
if (isset($guid)) {
    $entity = ossn_get_entities([
        'type' => 'object',
        'subtype' => 'status',
        'owner_guid' => $guid
    ]);
    if ($entity && is_array($entity) && count($entity) > 0) {
        $entity[0]->value = 'DENIED';
        if ($entity[0]->updateEntity()) {
            ossn_trigger_message('Successfully Denied.');
        } else {
            ossn_trigger_message('Unable to Denied.', 'error');
        }
    } else {
        ossn_trigger_message('Unable to Find Proof.', 'error');
    }
} else {
    ossn_trigger_message('Invalid Request.', 'error');
}
redirect(REF);