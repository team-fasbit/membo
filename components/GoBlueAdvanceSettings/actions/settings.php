<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

define('__gbas_GoBlue_Theme__', ossn_route()->themes . 'goblue/');

if (isset($_FILES)) {
    gbas_process_upload('gbas_logo', 'png', __gbas_GoBlue_Theme__ . 'images/logo.png', 'Logo File');
    gbas_process_upload('gbas_bg', 'jpg', __gbas_GoBlue_Theme__ . 'images/background.jpg', 'Login Background File');
}

// Flush Cache ==> Not Tested
// $action = ossn_add_tokens_to_url('action/admin/cache/flush');
// redirect($action);

redirect(REF);


// supported functions

function gbas_process_upload($source_filename, $extension, $destination_file, $notify_name) {
    if (isset($_FILES[$source_filename]) && isset($_FILES[$source_filename]['name']) && !empty($_FILES[$source_filename]['name'])) {
        $file_name = $_FILES[$source_filename]['name'];
        $file_tmp =$_FILES[$source_filename]['tmp_name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if (strtolower($ext) == strtolower($extension)) {
            if (move_uploaded_file($file_tmp, $destination_file)) {
                ossn_trigger_message($notify_name . ' is Successfully Uploaded.');
            } else {
                ossn_trigger_message($notify_name . ' is Failed to Upload.', 'error');
            }
        } else {
            ossn_trigger_message($notify_name . ' ' . $file_name . ' Uploaded with Invalid Extension.', 'error');
        }
    }
}
