<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

$friends = input('friends-input');
$group_guid = input('group');

$users = explode(',', $friends);
$sender = ossn_loggedin_user();
if(!empty($friends)){
	foreach($users as $item){
			$friend = ossn_user_by_guid($item);
			//check again if user friend with user or not
			if($friend && $sender->isFriend($sender->guid, $friend->guid)){
						$notification = new OssnNotifications;
						$notification->add('groupinvite', $sender->guid, $group_guid, $group_guid, $friend->guid);
			}
	}	
    ossn_trigger_message(ossn_print('groupinvite:sent'));
} else {
    ossn_trigger_message('Tag Friends to Send Invite.', 'error');
}
redirect(REF);