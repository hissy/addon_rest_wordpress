<?php defined('C5_EXECUTE') or die(_("Access Denied."));


class DashboardWpRestApiSettingsController extends DashboardBaseController {
	
	public function view() {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('rest_wordpress'));
		$wp_rest_api_url = $co->get('WP_REST_API_URL');
		$oauth_key = $co->get('WP_REST_API_OAUTH_KEY');
		$oauth_secret = $co->get('WP_REST_API_OAUTH_SECRET');
		$this->set('wp_rest_api_url', $wp_rest_api_url);
		$this->set('oauth_key', $oauth_key);
		$this->set('oauth_secret', $oauth_secret);
	}

	public function required() {
		$this->set('message', t("Please fill required values."));	
		$this->view();
	}

	public function updated() {
		$this->set('message', t("Settings saved."));	
		$this->view();
	}
	
	public function save_settings() {
		if ($this->token->validate("save_settings")) {
			if ($this->isPost()) {
				$wp_rest_api_url = $this->post('wp_rest_api_url');
				$oauth_key = $this->post('oauth_key');
				$oauth_secret = $this->post('oauth_secret');
				
				$co = new Config();
				$co->setPackageObject(Package::getByHandle('rest_wordpress'));
				
				$co->save('WP_REST_API_URL', $wp_rest_api_url);
				$co->save('WP_REST_API_OAUTH_KEY', $oauth_key);
				$co->save('WP_REST_API_OAUTH_SECRET', $oauth_secret);
				
				$this->redirect('/dashboard/wp_rest_api/settings','updated');
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}
		
}