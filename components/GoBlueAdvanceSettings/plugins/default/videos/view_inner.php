<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

 $video = $params['video'];
 $entity = ossn_get_entities(array(
		'type' => 'object',
		'owner_guid' => $video->guid,
		'subtype' => 'file:video',
  ));
  $ossn_entity = ossn_get_entities([
	  'wheres' => [
		 "e.subtype='item_guid'",
		 'emd.value=' . $video->guid
	  ]
  ]);
  if (count($ossn_entity) > 0) {
	 $wall = new OssnWall;
	 $get = $wall->GetPost($ossn_entity[0]->owner_guid);
	 $get = ossn_wallpost_to_item($get);
	 $params = array_merge($params, $get);
  }
 ?>
<div class="ossn-wall-item">
<video class="ossn-video-player" poster="<?php echo $video->getCoverURL();?>" controls="controls" preload="none">
	<source type="video/mp4" src="<?php echo $video->getFileURL();?>" />
	<object width="640" height="360" type="application/x-shockwave-flash" data="<?php echo ossn_site_url();?>components/Videos/vendors/player/flashmediaelement.swf"> 		
		<param name="movie" value="<?php echo ossn_site_url();?>components/Videos/vendors/player/flashmediaelement.swf" /> 
		<param name="flashvars" value="controls=true&poster=<?php echo $video->getCoverURL();?>&file=<?php echo $video->getFileURL();?>" /> 		
		<img src="<?php echo $video->getCoverURL();?>" width="640" height="360" />
	</object> 	
</video> 
<div class="ossn-video-description">
	<p><?php echo $video->description;?> <span class="time-created"><?php echo ossn_user_friendly_time($video->time_created); ?></span> <span class="time-created"><?php echo $params['location']; ?></span>
	<?php
		echo ossn_plugin_view('privacy/icon/view', array(
				'privacy' => $params['post']->access,
				'text' => '-',
				'class' => 'time-created',
		));
		if(isset($params['post']->sentiment) && !empty($params['post']->sentiment)){
			echo ossn_plugin_view('sentiment/icon', array(
					'sentiment' => $params['post']->sentiment,
					'text' => '-',
					'class' => 'time-created',
			));
		}
	?>
	</p>
	<?php
	if(!empty($params['friends'])){
		echo '<div class="friends"><p>';
		foreach ($params['friends'] as $friend) {
			if(!empty($friend)){
				$user = ossn_user_by_guid($friend);
				$url = $user->profileURL();
				$friends[] = "<a href='{$url}'>{$user->fullname}</a>";
			}
		}
		if(!empty($friends)){
			echo implode(', ', $friends);
		}
		echo '</p></div>';
	}
	?>
</div>
<?php
	$vars['entity'] = $entity[0];
	echo ossn_plugin_view('entity/comment/like/share/view', $vars);
?>
<?php 
$user = ossn_loggedin_user();
if(ossn_isLoggedin()){
	if($user->guid == $video->owner_guid || $user->canModerate()){ ?>
<div class="ossn-video-controls"> 
	<a href="<?php echo $video->getDeleteURL();?>" class="btn btn-danger"><?php echo ossn_print('video:com:delete');?></a>
	<a href="<?php echo $video->getEditURL();?>" class="btn btn-success"><?php echo ossn_print('video:com:edit');?></a>
</div>
<?php
	}
}
?>
</div>