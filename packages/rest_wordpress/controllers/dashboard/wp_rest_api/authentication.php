<?php defined('C5_EXECUTE') or die(_("Access Denied."));


class DashboardWpRestApiAuthenticationController extends DashboardBaseController {
	
	public function view() {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('rest_wordpress'));
		$oauth_token = $co->get('WP_REST_API_OAUTH_TOKEN');
		$oauth_token_secret = $co->get('WP_REST_API_OAUTH_TOKEN_SECRET');
		
		if (!empty($oauth_token) && !empty($oauth_token_secret)) {
			$this->set('isAuthenticated',true);
		}
	}

	public function authenticated() {
		$this->set('isAuthenticated',true);
		$this->set('message', t("Authenticated."));	
		$this->view();
	}

	public function invalid_request_token() {
		$this->set('error', t("Invalid request token."));	
		$this->view();
	}
	
	/**
	 * Action method to get request token
	 */
	public function request_token() {
		if ($this->token->validate("request_token")) {
			$co = new Config();
			$co->setPackageObject(Package::getByHandle('rest_wordpress'));
			$wp_rest_api_url = $co->get('WP_REST_API_URL');
			$oauth_key = $co->get('WP_REST_API_OAUTH_KEY');
			$oauth_secret = $co->get('WP_REST_API_OAUTH_SECRET');
			
			// Get Zend_Oauth_Consumer object
			$consumer = $this->getOauthConsumer($wp_rest_api_url, $oauth_key, $oauth_secret);
			
			if (is_object($consumer)) {
				try {
					// Get Zend_Oauth_Token_Request object
					$token = $consumer->getRequestToken();
					$this->set('token', $token);
					$_SESSION['REQUEST_TOKEN'] = serialize($token);
					$consumer->redirect(array(
						'oauth_callback' => $this->getCollbackUrl()
					));
				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
			} else {
				$this->redirect('/dashboard/wp_rest_api/settings','required');
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}
	
	/**
	 * Action method to exchange details for access token
	 */
	public function callback() {
		if (isset($_SESSION['REQUEST_TOKEN'])) {
			$co = new Config();
			$co->setPackageObject(Package::getByHandle('rest_wordpress'));
			$wp_rest_api_url = $co->get('WP_REST_API_URL');
			$oauth_key = $co->get('WP_REST_API_OAUTH_KEY');
			$oauth_secret = $co->get('WP_REST_API_OAUTH_SECRET');
			
			$consumer = $this->getOauthConsumer($wp_rest_api_url, $oauth_key, $oauth_secret);
			
			if (is_object($consumer)) {
				// Get Zend_Oauth_Token_Access object
				$token = $consumer->getAccessToken($_GET, unserialize($_SESSION['REQUEST_TOKEN']));
				$oauth_token = $token->getToken();
				$oauth_token_secret = $token->getTokenSecret();
				
				$co->save('WP_REST_API_OAUTH_TOKEN', $oauth_token);
				$co->save('WP_REST_API_OAUTH_TOKEN_SECRET', $oauth_token_secret);
				
				$_SESSION['REQUEST_TOKEN'] = null;
				
				$this->redirect('/dashboard/wp_rest_api/authentication','authenticated');
			} else {
				$this->redirect('/dashboard/wp_rest_api/settings','required');
			}
		} else {
			$this->redirect('/dashboard/wp_rest_api/authentication','invalid_request_token');
		}
	}
	
	/**
	 * Action method to test api call
	 */
	public function api_test() {
		if ($this->token->validate("api_test")) {
		
			$co = new Config();
			$co->setPackageObject(Package::getByHandle('rest_wordpress'));
			$wp_rest_api_url = $co->get('WP_REST_API_URL');
			$oauth_key = $co->get('WP_REST_API_OAUTH_KEY');
			$oauth_secret = $co->get('WP_REST_API_OAUTH_SECRET');
			$oauth_token = $co->get('WP_REST_API_OAUTH_TOKEN');
			$oauth_token_secret = $co->get('WP_REST_API_OAUTH_TOKEN_SECRET');

			$token = new Zend_Oauth_Token_Access;
			$token->setParams(array(
				Zend_Oauth_Token_Access::TOKEN_PARAM_KEY => $oauth_token,
				Zend_Oauth_Token_Access::TOKEN_SECRET_PARAM_KEY => $oauth_token_secret
			));

			$client = $token->getHttpClient(array(
				'consumerKey' => $oauth_key,
				'consumerSecret' => $oauth_secret
			));
			// RETRIEVE USERS - http://wp-api.org/#users_retrieve-users
			$client->setUri($wp_rest_api_url.'/users');
			$client->setMethod(Zend_Http_Client::GET);
			
			$response = $client->request();
			
			$data = @Loader::helper('json')->decode($response->getBody());
			$this->set('data',$data);
			$this->view();
			
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}
	
	private function getOauthConsumer($wp_rest_api_url, $oauth_key, $oauth_secret) {
		if (!isset($wp_rest_api_url) || !isset($oauth_key) || !isset($oauth_secret)) {
			return false;
		}
		
		$fh = Loader::helper('file');
		$res = $fh->getContents($wp_rest_api_url);
		try {
			$r = @Loader::helper('json')->decode($res);
			if(isset($r) && is_object($r) && isset($r->authentication->oauth1)) {
				$oauth1_args = $r->authentication->oauth1;
			}
			$config = array(
				'requestScheme' => Zend_Oauth::REQUEST_SCHEME_HEADER,
				'callbackUrl' => $this->getCollbackUrl(),
				'requestTokenUrl' => $oauth1_args->request,
				'userAuthorizationUrl' => $oauth1_args->authorize,
				'accessTokenUrl' => $oauth1_args->access,
				'consumerKey' => $oauth_key,
				'consumerSecret' => $oauth_secret
			);
			$consumer = new Zend_Oauth_Consumer($config);
			
			return $consumer;
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function getCollbackUrl() {
		$callback_url = BASE_URL . View::url('/dashboard/wp_rest_api/authentication','callback');
		return $callback_url;
	}
		
}