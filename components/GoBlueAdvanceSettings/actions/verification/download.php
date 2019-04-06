<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

$guid = input('guid');
$type = input('type');

if (isset($guid) && isset($type) && in_array($type, ['address_proof', 'id_proof'])) {
    $entity = ossn_get_entities([
        'type' => 'object',
        'subtype' => ($type === 'address_proof') ? 'addressProof' : 'idProof',
        'owner_guid' => $guid
    ]);

    if ($entity && is_array($entity) && count($entity) > 0 && file_exists($entity[0]->value)) {
        $filepath = $entity[0]->value;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush();
        readfile($filepath);
        exit;
    } else {
        ossn_trigger_message('Unable to Find Proof.', 'error');
    }
} else {
    ossn_trigger_message('Invalid Request.', 'error');
}
redirect(REF);
