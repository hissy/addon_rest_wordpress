<?php
defined('C5_EXECUTE') or die("Access Denied.");
class PostToWpExternalFormBlockController extends BlockController {

	public function action_post_to_wp() {

		// Validation
		$val = Loader::helper('validation/form');
		$val->setData($this->post());
		$val->addRequired('post_title', 'Post title is required.');
		if (!$val->test()) {
			$error = $val->getError()->getList();
			$this->set('error', $error);
		} else {
			// Get client
			$co = new Config();
			$co->setPackageObject(Package::getByHandle('rest_wordpress'));
			$wp_rest_api_url = $co->get('WP_REST_API_URL');
			$client = Loader::helper('wp_api','rest_wordpress')->getClient();
			$client->setUri($wp_rest_api_url.'/posts');
			$client->setMethod(Zend_Http_Client::POST);
			
			// Setup JSON data
			$data = array(
				'title' => $this->post('post_title'),
				'content_raw' => $this->post('post_body'),
				'status' => 'publish'
			);
			$client->setRawData(Loader::helper('json')->encode($data),'application/json');
			
			// Send
			$res = $client->request();
			$status = $res->getStatus();
			
			if ($status == '201') {
				$this->set('response', 'Completed.');
			} else {
				$this->set('response', 'Failed.');
			}
		}

	}

}