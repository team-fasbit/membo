<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

if (isset($_FILES)) {
    $user = ossn_loggedin_user();
    $address_proof = false;
    $id_proof = false;
    $verify = new Verification;

    if (!file_exists(ossn_get_userdata() . 'verification')) {
        mkdir(ossn_get_userdata() . 'verification', 0755, true);
    }

    if (!file_exists(ossn_get_userdata() . 'verification/' . $user->guid)) {
        mkdir(ossn_get_userdata() . 'verification/' . $user->guid, 0755, true);
    }

    $address_proof = gbas_process_upload('gbas_address_proof', ossn_get_userdata() . 'verification/' . $user->guid . '/', 'Address Proof File');
    $id_proof = gbas_process_upload('gbas_id_proof', ossn_get_userdata() . 'verification/' . $user->guid . '/', 'ID Proof File');

    if($address_proof && $id_proof) {
        if ($verify->saveProofs($user->guid, $address_proof, $id_proof)) {
            ossn_trigger_message('Proofs saved Successfully.');
        } else {
            ossn_trigger_message('Unable to save Proofs.', 'error');
        }
    } else {
        ossn_trigger_message('Please select Address Proof and ID Proof both files.', 'error');
    }
}

redirect(REF);


// supported functions

function gbas_process_upload($source_filename, $destination_folder, $notify_name) {
    if (isset($_FILES[$source_filename]) && isset($_FILES[$source_filename]['name']) && !empty($_FILES[$source_filename]['name'])) {
        $file_name = $_FILES[$source_filename]['name'];
        $file_tmp =$_FILES[$source_filename]['tmp_name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), [ 'png', 'bmp', 'jpg', 'jpeg', 'pdf' ])) {
            if (move_uploaded_file($file_tmp, $destination_folder . $file_name)) {
                // ossn_trigger_message($notify_name . ' is Successfully Uploaded.');
                return $destination_folder . $file_name;
            } else {
                ossn_trigger_message($notify_name . ' is Failed to Upload.', 'error');
            }
        } else {
            ossn_trigger_message($notify_name . ' ' . $file_name . ' Uploaded with Invalid Extension.', 'error');
        }
    }
    return false;
}
