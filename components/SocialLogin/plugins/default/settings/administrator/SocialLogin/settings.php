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
 
echo ossn_view_form('sociallogin/settings', array(
    'action' => ossn_site_url() . 'action/social_login/settings',
	'class' => 'sociallogin-form-admin',
));