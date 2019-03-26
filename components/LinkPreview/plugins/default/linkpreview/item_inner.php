<div class="row link-preview-item link-preview-item-<?php echo $params['guid'];?>">
	<div class="col-md-12">
    	<a href="<?php echo $params['item']->link_full;?>" target="_blank">
        <div class="link-preview-item-inner">
        		<?php if(!empty($params['item']->linkPreviewImage)){ ?>
        		<div class="link-preview-item-mage-preview">
                			<img src="<?php echo $params['item']->linkPreviewImage;?>"  class="img-responsive" />
                </div>
                <?php } ?>
        		<div class="link-preview-item-title">
                		<?php echo $params['item']->title;?>
                </div>
                <div class="link-preview-item-contents-desc">
                	<?php echo $params['item']->description;?>
                 </div>
                <div class="link-preview-link">
                	<?php 
					$host = parse_url($params['item']->link_full);
					echo str_replace("wwww.", '', $host['host']);
					?>	
                </div> 
        </div>
        </a>
    
    </div>
</div>