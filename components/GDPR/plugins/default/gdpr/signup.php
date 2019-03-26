<?php
	$terms = ossn_plugin_view('output/url', array(
				'href' => ossn_site_url('site/terms'),
				'text' => ossn_print('site:terms'),
	));
	$privacy = ossn_plugin_view('output/url', array(
				'href' => ossn_site_url('site/terms'),
				'text' => ossn_print('gdpr:privacypolicy'),
	));
?>
<div class="gdpr">
    <p>
        <input type="checkbox" name="gdpr_agree"> <?php echo ossn_print('gdpr:confirm:signup', array($terms, $privacy));?> 
    </p>
</div>