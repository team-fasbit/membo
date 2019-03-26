<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright 2014-2017 SOFTLAB24 LIMITED
 * @license   SOFTLAB24 LIMITED, COMMERCIAL LICENSE  https://www.softlab24.com/license/commercial-license-v1
 * @link      https://www.softlab24.com/
 */
namespace Ossn\Component;
class LinkPreview extends \OssnObject {
		/**
		 * LinkePreview Initilize
		 * 
		 * @param string $text A wall post text
		 * @param integer $guid A wallpost/comment guid
		 * @param string $type A linkepreview type
		 * 
		 * @return void 
		 */
		public function __construct($text = '', $guid = '', $type = 'wallpost') {
				$this->linkPreviewText = $text;
				$this->linkPreviewURL  = false;
				$this->linkPreviewGUID = false;
				$this->linkPreviewType = false;
				
				if(!empty($guid)) {
						$this->linkPreviewGUID = $guid;
				}
				if(!empty($type)) {
						$this->linkPreviewType = $type;
				}
				preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $this->linkPreviewText, $urls);
				if(count($urls) > 0 && !empty($urls)) {
						$urlR = array_reverse($urls[0]);
						if(filter_var($urlR[0], FILTER_VALIDATE_URL)) {
								$this->linkPreviewURL = trim($urlR[0]);
						}
				}
		}
		/**
		 * Add linke preview to database
		 *
		 * @return boolean 
		 */
		public function addLinkPreview() {
				if(empty($this->linkPreviewGUID) || empty($this->linkPreviewURL)) {
						return false;
				}
				if($this->excludeDomains()) {
						return false;
				}
				$vars = $this->getMeta();
				
				$this->description = $vars['description'];
				$this->title       = $vars['title'];
				$this->owner_guid  = $this->linkPreviewGUID;
				$this->type        = 'object';
				$this->subtype     = 'LinkPreview';
				
				$this->data                  = new \stdClass;
				$this->data->link_full       = $vars['link'];
				$this->data->linkPreviewType = $this->linkPreviewType;
				
				$this->data->linkPreviewImage = false;
				if(!empty($vars['pictures'])) {
						$photo = $vars['pictures'][0];
						if(!empty($vars['pictures'][0])) {
								$this->data->linkPreviewImage = $photo;
						}
				}
				if(!empty($vars['image'])) {
						if(filter_var($vars['image'], FILTER_VALIDATE_URL)) {
								$this->data->linkPreviewImage = $vars['image'];
						}
				}
				return $this->addObject();
		}
		/**
		 * Get JSON data to pre-view for link before posting
		 *
		 * @return boolean 
		 */
		public function getJson() {
				if($this->excludeDomains()) {
						return false;
				}
				$data = $this->getMeta();
				
				if(!empty($data['pictures'][0])) {
						$this->linkPreviewImage = $data['pictures'][0];
				}
				
				if(!empty($data['image'])) {
						if(filter_var($data['image'], FILTER_VALIDATE_URL)) {
								$this->linkPreviewImage = $data['image'];
						}
				}
				$linkobj                   = new \stdClass;
				$linkobj->linkPreviewImage = $this->linkPreviewImage;
				$linkobj->description      = $data['description'];
				$linkobj->title            = $data['title'];
				$linkobj->link_full        = $this->linkPreviewURL;
				
				$jsondata = array(
						'contents' => ossn_plugin_view('linkpreview/item_inner', array(
								'item' => $linkobj,
								'guid' => 0
						))
				);
				header('Content-Type: application/json');
				return json_encode($jsondata);
		}
		/**
		 * Don't linkpreview for the videos channels as they are handled by OssnEmbed
		 *
		 * @return boolean 
		 */
		public function excludeDomains() {
				$patterns = array(
						'#(((https?://)?)|(^./))(((www.)?)|(^./))youtube\.com/watch[?]v=([^\[\]()<.,\s\n\t\r]+)#i',
						'#(((https?://)?)|(^./))(((www.)?)|(^./))youtu\.be/([^\[\]()<.,\s\n\t\r]+)#i',
						'/(https?:\/\/)(www\.)?(vimeo\.com\/groups)(.*)(\/videos\/)([0-9]*)/',
						'/(https?:\/\/)(www\.)?(vimeo.com\/)([0-9]*)/',
						'/(https?:\/\/)(player\.)?(vimeo.com\/video\/)([0-9]*)/',
						'/(https?:\/\/)(www\.)?(metacafe\.com\/watch\/)([0-9a-zA-Z_-]*)(\/[0-9a-zA-Z_-]*)(\/)/',
						'/(https?:\/\/www\.dailymotion\.com\/.*\/)([0-9a-z]*)/'
				);
				$regex    = "/<a[\s]+[^>]*?href[\s]?=[\s\"\']+" . "(.*?)[\"\']+.*?>" . "([^<]+|.*?)?<\/a>/";
				if(preg_match_all($regex, linkify_lP($this->linkPreviewText), $matches, PREG_SET_ORDER)) {
						foreach($matches as $match) {
								foreach($patterns as $pattern) {
										if(preg_match($pattern, $match[2]) > 0) {
												return true;
										}
								}
						}
				}
				return false;
		}
		/**
		 * Get the link OpenGraph data,  images, title, description
		 *
		 * @return array 
		 */
		public function getMeta() {
				if($this->excludeDomains()) {
						return false;
				}
				require_once(LinkPreview . 'vendors/vendor/autoload.php');
				$linkPreview = new \LinkPreview\LinkPreview($this->linkPreviewURL);
				$parsed      = $linkPreview->getParsed();
				$metadata                = array();
				$metadata['link']        = $parsed['general']->getUrl();
				$metadata['title']       = lp_ossn_input_str($parsed['general']->getTitle());
				$metadata['description'] = lp_ossn_input_str($parsed['general']->getDescription());
				$metadata['image']       = $parsed['general']->getImage();
				
				$pictures = $parsed['general']->getPictures();
				if(!empty($pictures)) {
						foreach($pictures as $picture) {
								if(!empty($picture)) {
										if(filter_var($picture, FILTER_VALIDATE_URL)) {
												$metadata['pictures'][] = $picture;
										}
								}
						}
				}
				return $metadata;
		}
		/**
		 * Delete the wallpost linkpreview
		 *
		 * @return boolean
		 */
		public function deletePreview() {
				if(!empty($this->linkPreviewGUID)) {
						$preview = $this->searchObject(array(
								'type' => 'object',
								'subtype' => 'LinkPreview',
								'owner_guid' => $this->linkPreviewGUID
						));
						if($preview) {
								if($preview[0]->deleteObject()) {
										return true;
								}
						}
				}
				return false;
		}
		
}