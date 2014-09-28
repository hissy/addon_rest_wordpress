<?php

defined('C5_EXECUTE') or die("Access Denied.");
class ArchivesController extends Controller {
	
	public function view($post_id = 0) {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('rest_wordpress'));
		$wp_rest_api_url = $co->get('WP_REST_API_URL');
		
		$fh = Loader::helper('file');
		$res = $fh->getContents($wp_rest_api_url . '/posts/' . intval($post_id));
		
		try {
			$post = @Loader::helper('json')->decode($res);
			if(is_object($post)) {
				$this->set('post',$post);
			}
		} catch (Exception $e) {
			throw new Exception(t('Unable to parse API response.'));
		}
	}
	
}