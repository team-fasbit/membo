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

                    if ($verify_status === false && $action[0] !== '') {
                        redirect('verification');
                    } else if ($verify_status === 'WAITING' && $action[0] !== 'waiting') {
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

        // recreate post actions
        ossn_unregister_action('wall/post/a');
        ossn_register_action('wall/post/a', __gbas__ . 'actions/wall/post/home.php');
    }

    // admin side setting page
    ossn_register_com_panel('GoBlueAdvanceSettings', 'settings');
    ossn_register_admin_sidemenu('userverification', 'userverification', ossn_site_url('administrator/settings/userverification'), ossn_print('admin:sidemenu:usermanager'));
    ossn_register_site_settings_page('userverification', 'settings/administrator/GoBlueAdvanceSettings/verification');
    if (ossn_isAdminLoggedin()) {
        ossn_register_action('goblueadvancesettings/settings', __gbas__ . 'actions/settings.php');
        ossn_register_action('verification/approve', __gbas__ . 'actions/verification/approve.php');
        ossn_register_action('verification/deny', __gbas__ . 'actions/verification/deny.php');
        ossn_register_action('verification/download', __gbas__ . 'actions/verification/download.php');
    }

    // solution of video issues
    ossn_extend_view('css/ossn.default', 'videos/css/videos');

    // solution of click on image post
    //templates
    ossn_add_hook('single:template', 'user', 'ossn_single_templates');
    ossn_add_hook('single:template', 'group', 'ossn_single_templates');
    ossn_add_hook('single:template', 'cover:photo', 'ossn_single_profile_cover_photo');
    ossn_add_hook('single:template', 'profile:photo', 'ossn_single_profile_photo');

    // video option on post box
    $container_control = [
        'name' => 'video',
        'class' => 'ossn-wall-video',
        'text' => '<i class="fa fa-video-camera"></i>',
    ];
    ossn_register_menu_item('wall/container/controls/home', $container_control);
    ossn_register_menu_item('wall/container/controls/user', $container_control);
    // ossn_register_menu_item('wall/container/controls/group', $container_control);

    // solution of invisible notification
    ossn_add_hook('notification:view', 'like:entity:file:video', 'ossn_notification_like_video');
    ossn_add_hook('notification:view', 'comments:entity:file:video', 'ossn_notification_like_video');
}
ossn_register_callback('ossn', 'init', 'gbas_com_init');

// solution of click on image post
function ossn_single_view_template(array $params = array())
{
    if (!is_array($params)) {
        return false;
    }
    $type = $params['post']->type;
    if (isset($params['post']->item_type)) {
        $type = $params['post']->item_type;
    }
    if (ossn_is_hook('single:template', $type)) {
        return ossn_call_hook('single:template', $type, $params);
    }
    return false;
}

function ossn_single_templates($hook, $type, $return, $params)
{
    ossn_trigger_callback('wall', 'load:item', $params);
    $params = ossn_call_hook('wall', 'templates:item', $params, $params);
    return ossn_plugin_view("wall/templates/single/{$type}/item", $params);
}

function ossn_single_profile_photo($hook, $type, $return, $params)
{
    return ossn_plugin_view("profile/single/profile/photo", $params);
}

function ossn_single_profile_cover_photo($hook, $type, $return, $params)
{
    return ossn_plugin_view("profile/single/cover/photo", $params);
}

function ossn_notification_like_video($hook, $type, $return, $params)
{
    $notif          = $params;
    $baseurl        = ossn_site_url();
    $user           = ossn_user_by_guid($notif->poster_guid);
    $user->fullname = "<strong>{$user->fullname}</strong>";
    $iconURL        = $user->iconURL()->small;

    $img = "<div class='notification-image'><img src='{$iconURL}' /></div>";
    if (preg_match('/like/i', $notif->type)) {
        $type = 'like';
    }
    if (preg_match('/comments/i', $notif->type)) {
        $type = 'comment';
    }
    $type = "<div class='ossn-notification-icon-{$type}'></div>";
    if ($notif->viewed !== NULL) {
        $viewed = '';
    } elseif ($notif->viewed == NULL) {
        $viewed = 'class="ossn-notification-unviewed"';
    }
    $video_guid = ossn_get_entity($notif->subject_guid)->owner_guid;
    $url               = ossn_site_url("video/view/{$video_guid}");
    $notification_read = "{$baseurl}notification/read/{$notif->guid}?notification=" . urlencode($url);
    return "<a href='{$notification_read}'>
       <li {$viewed}> {$img} 
       <div class='notfi-meta'> {$type}
       <div class='data'>" . ossn_print("ossn:notifications:{$notif->type}", array(
        $user->fullname
    )) . '</div>
       </div></li></a>';
}
