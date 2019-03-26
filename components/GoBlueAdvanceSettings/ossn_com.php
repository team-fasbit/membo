<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

define('__gbas__', ossn_route()->com . 'GoBlueAdvanceSettings/');

function gbas_com_init()
{
    // full page background configuration for startup pages
    ossn_extend_view('pages/contents/user/login', 'full_background/extended');
    ossn_extend_view('pages/index', 'full_background/extended');
    ossn_extend_view('pages/contents/user/registered', 'full_background/extended');
    ossn_extend_view('pages/contents/user/resetlogin', 'full_background/extended');
    ossn_extend_view('pages/contents/user/resetcode', 'full_background/extended');
    
    // hooks for signup fields
    // ossn_add_hook('user', 'default:fields', 'gbas_default_fields', 10000);
    // ossn_add_hook('user', 'signup:fields', 'gbas_default_fields', 10000);
    // function gbas_default_fields($hook, $type, $args, $params) {
    //     // echo 'hook';
    //     // print_r($hook);
    //     // echo 'type';
    //     // print_r($type);
    //     // echo 'args';
    //     // print_r($args);
    //     // echo 'params';
    //     // print_r($params);
    //     // die();
    //     $args = [];
    //     return $args;
    // }

    // admin side setting page
    ossn_register_com_panel('GoBlueAdvanceSettings', 'settings');
    if (ossn_isAdminLoggedin()) {
        ossn_register_action('goblueadvancesettings/settings', __gbas__ . 'actions/settings.php');
    }
}
ossn_register_callback('ossn', 'init', 'gbas_com_init');
