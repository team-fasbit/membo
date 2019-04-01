<?php
/**
 * Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
class PrivateNetwork {
		/**
		 * OSSN core minimal pages
		 *
		 * @param null
		 * 
		 * @return array
		 */
		private function alp() {
				return array(
						'css',
						'js',
						'action',
						'administrator',
						'uservalidate',
						'resetlogin',
						'index',
						'login',
						'site',
						'avatar',
						'userverified',
				);
		}
		/**
		 * Deny for visiting page
		 *
		 * @param null
		 * 
		 * @return void
		 */
		private function deney() {
				ossn_trigger_message(ossn_print("private:network:deney"), 'error');
				redirect();
		}
		/**
		 * Start the PrivateNetwork Process
		 *
		 * @param null
		 * 
		 * @return array
		 */
		public function start() {
				if(!empty($this->handler)) {
						if(!in_array($this->handler, $this->alp())) {
								$this->deney();
						}
				}
		}
}