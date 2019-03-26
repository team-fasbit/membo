<?php
/**
 * Open Source Social Network
 *
 * @packageOpen Source Social Network
 * @author    Open Social Website Core Team <info@informatikon.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
echo '<div class="ossn-likes-view">';
$dislikes = new Dislike;
$guid = input('guid');
$type = input('type');
if (empty($type)) {
    $type = 'post';
}
$dislikes = $dislikes->GetDislikes($guid, $type);
if ($dislikes) {
    foreach ($dislikes as $us) {
        if ($us->guid !== ossn_loggedin_user()->guid) {
            $users[] = ossn_user_by_guid($us->guid);
        }
    }
}
$users['users'] = $users;
$users['icon_size'] = 'small';
echo ossn_plugin_view("output/users_list", $users);
echo '</div>';
