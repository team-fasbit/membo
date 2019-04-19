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