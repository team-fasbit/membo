<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */

//init ossnwall
$OssnWall = new OssnWall;

//poster guid and owner guid is same as user is posting on home page on its own wall
$OssnWall->owner_guid  = ossn_loggedin_user()->guid;
$OssnWall->poster_guid = ossn_loggedin_user()->guid;

//check if owner guid is zero then exit
if ($OssnWall->owner_guid == 0 || $OssnWall->poster_guid == 0) {
    ossn_trigger_message(ossn_print('post:create:error'), 'error');
    redirect(REF);
}

//getting some inputs that are required for wall post
$post     = input('post');
$friends  = input('friends');
$location = input('location');
$privacy  = input('privacy');

//validate wall privacy 
$privacy = ossn_access_id_str($privacy);
$access  = '';
if (!empty($privacy)) {
    $access = input('privacy');
}

if (isset($_FILES['ossn_video'])) {
    set_time_limit(0);
    session_write_close();

    $title = input('video_title');
    $description = $post;

    if (empty($title) || empty($description)) {
        ossn_trigger_message(ossn_print('video:com:fields:requred'), 'error');
        redirect(REF);
    }

    $allowed = array('video/mp4', 'video/mpeg', 'video/3gpp', 'video/x-ms-wmv', 'video/x-msvideo', 'video/quicktime');
    if (isset($_FILES['ossn_video']) && !in_array($_FILES['ossn_video']['type'], $allowed)) {
        ossn_trigger_message(ossn_print('video:com:mp4:files'), 'error');
        redirect(REF);
    }

    $file = new OssnFile;
    $extension = $file->getFileExtension($_FILES['ossn_video']['name']);
    $tmp_path = $_FILES['ossn_video']['tmp_name'];

    $video = new Videos;
    $extensions = array('3gp', 'mov', 'avi', 'wmv', 'flv');
    if (in_array($extension, $extensions)) {

        $hash = md5($_FILES['ossn_video']['tmp_name'] . time() . rand());
        $newfile_name = $hash . '.mp4';
        $dir = ossn_get_userdata("tmp/videos/");
        $newfile = $dir . $newfile_name;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // $vtk = input('vtk');
        $vtk = md5(time().rand());
        $progress_file = $dir . $vtk . '.progress.txt';
        if ($video->convert($_FILES['ossn_video']['tmp_name'], $newfile, $progress_file)) {
            $_FILES['video'] = $_FILES['ossn_video'];
            $_FILES['video']['tmp_name'] = $newfile;
            $_FILES['video']['type'] = 'video/mp4';
            $_FILES['video']['name'] = $newfile_name;
            $_FILES['video']['size'] = filesize($newfile);
        } else {
            ossn_trigger_message(ossn_print('video:com:upload:conversion:failed'), 'error');
            redirect(REF);
        }
    } else {
        $_FILES['video'] = $_FILES['ossn_video'];
    }

    if ($video->addVideo($title, $description, $friends, $location, $access)) {
        $guid = $video->getObjectId();
        $title = OssnTranslit::urlize($title);
        $url = "video/view/{$guid}/{$title}";

        if (ossn_is_xhr()) {
            $guid = $video->wall->getObjectId();
            $get  = $OssnWall->GetPost($guid);
            if ($get) {
                $get = ossn_wallpost_to_item($get);
                ossn_set_ajax_data(array(
                    'post' => ossn_wall_view_template($get),
                    'url' => $url
                ));
            }
        }

        if (isset($newfile)) {
            unlink($newfile);
            unlink($progress_file);
        }

        ossn_trigger_message(ossn_print('video:com:uploaded'));
        redirect($url);
    } else {

        if (isset($newfile)) {
            unlink($newfile);
            unlink($progress_file);
        }


        ossn_trigger_message(ossn_print('video:com:upload:failed'), 'error');
        redirect(REF);
    }
} else {
    if ($OssnWall->Post($post, $friends, $location, $access)) {
        if (ossn_is_xhr()) {
            $guid = $OssnWall->getObjectId();
            $get  = $OssnWall->GetPost($guid);
            if ($get) {
                $get = ossn_wallpost_to_item($get);
                ossn_set_ajax_data(array(
                    'post' => ossn_wall_view_template($get)
                ));
            }
        }
        //no need to show message on success.
        //3.x why not? $arsalanshah
        ossn_trigger_message(ossn_print('post:created'));
        redirect(REF);
    } else {
        ossn_trigger_message(ossn_print('post:create:error'), 'error');
        redirect(REF);
    }
}
