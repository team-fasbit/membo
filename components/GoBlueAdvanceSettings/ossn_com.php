<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

define('__gbas__', ossn_route()->com . 'GoBlueAdvanceSettings/');
require_once(__gbas__ . 'classes/Verification.php');

function gbas_com_init()
{
    // full page background configuration for startup pages
    ossn_extend_view('pages/contents/user/login', 'full_background/extended');
    ossn_extend_view('pages/index', 'full_background/extended');
    ossn_extend_view('pages/contents/user/registered', 'full_background/extended');
    ossn_extend_view('pages/contents/user/resetlogin', 'full_background/extended');
    ossn_extend_view('pages/contents/user/resetcode', 'full_background/extended');

    if (!ossn_isLoggedin()) {
        // hooks for signup fields
        ossn_add_hook('user', 'default:fields', 'gbas_default_fields', 1000);
        ossn_add_hook('user', 'signup:fields', 'gbas_default_fields', 1000);
        function gbas_default_fields($hook, $type, $args, $params)
        {
            $args = [];
            return $args;
        }
    } else {
        if (!ossn_isAdminLoggedin()) {
            $user = ossn_loggedin_user();
            $verify = new Verification;
            $verify_status = $verify->isVerified($user->guid);

            if ($verify_status !== 'VERIFIED') {
                ossn_add_hook('page', 'load', 'gbas_page_load');
                function gbas_page_load($hook, $type, $args, $params)
                {
                    if (isset($params['handler']) && !in_array($params['handler'], ['verification', 'avatar', 'css', 'js'])) {
                        redirect('verification');
                    }
                }

                // verification page setup
                ossn_register_page('verification', 'gbas_verification_page_handler');
                function gbas_verification_page_handler($action)
                {
                    $user = ossn_loggedin_user();
                    $verify = new Verification;
                    $verify_status = $verify->isVerified($user->guid);
                    $all_proof_uploaded = $verify->allProofUploaded($user->guid);

                    if ($verify_status === false && $action[0] !== '' && !$all_proof_uploaded) {
                        redirect('verification');
                    } else if ($verify_status === false && $action[0] !== 'waiting' && $all_proof_uploaded) {
                        redirect('verification/waiting');
                    } else if ($verify_status === 'DENIED' && $action[0] !== 'deny') {
                        redirect('verification/deny');
                    } else if ($verify_status === 'VERIFIED') {
                        redirect('home');
                    }

                    switch ($action[0]) {
                        case '':
                            $title = 'Verification';

                            //view profile photos in module layout
                            $contents = array(
                                'title' => 'Verification',
                                'content' => ossn_plugin_view('verification/pages/verification'),
                                'module_width' => '850px'
                            );
                            $module['content'] = ossn_set_page_layout('module', $contents);
                            //set page layout
                            $content = ossn_set_page_layout('contents', $module);
                            echo ossn_view_page($title, $content);
                            break;

                        case 'waiting':
                            $title = 'Verification - Waiting';

                            //view profile photos in module layout
                            $contents = array(
                                'title' => 'Verification - Waiting',
                                'content' => '<p>WAITING FOR VERIFICATION</p>',
                                'module_width' => '850px'
                            );
                            $module['content'] = ossn_set_page_layout('module', $contents);
                            //set page layout
                            $content = ossn_set_page_layout('contents', $module);
                            echo ossn_view_page($title, $content);
                            break;

                        case 'deny':
                            $title = 'Verification - Denied';

                            //view profile photos in module layout
                            $contents = array(
                                'title' => 'Verification - Denied',
                                'content' => '<p>VERIFICATION DENIED</p>',
                                'module_width' => '850px'
                            );
                            $module['content'] = ossn_set_page_layout('module', $contents);
                            //set page layout
                            $content = ossn_set_page_layout('contents', $module);
                            echo ossn_view_page($title, $content);
                            break;

                        default:
                            redirect('verification');
                            break;
                    }
                }
                ossn_register_action('goblueadvancesettings/verification', __gbas__ . 'actions/user/verification.php');
            }
        }

        // user details
        // $user = ossn_loggedin_user();
    }

    // admin side setting page
    ossn_register_com_panel('GoBlueAdvanceSettings', 'settings');
    if (ossn_isAdminLoggedin()) {
        ossn_register_action('goblueadvancesettings/settings', __gbas__ . 'actions/settings.php');
    }
}
ossn_register_callback('ossn', 'init', 'gbas_com_init');
