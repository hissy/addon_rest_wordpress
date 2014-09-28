<?php
defined('C5_EXECUTE') or die("Access Denied.");

class WpApiHelper {
    
    /**
     * Get authenticated Zend HTTP Client
     */
    public function getClient()
    {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('rest_wordpress'));
		
		$oauth_key = $co->get('WP_REST_API_OAUTH_KEY');
		$oauth_secret = $co->get('WP_REST_API_OAUTH_SECRET');
		$oauth_token = $co->get('WP_REST_API_OAUTH_TOKEN');
		$oauth_token_secret = $co->get('WP_REST_API_OAUTH_TOKEN_SECRET');
		
		if (!empty($oauth_key) && !empty($oauth_secret) && !empty($oauth_token) && !empty($oauth_token_secret)) {
			$token = new Zend_Oauth_Token_Access;
			$token->setParams(array(
				Zend_Oauth_Token_Access::TOKEN_PARAM_KEY => $oauth_token,
				Zend_Oauth_Token_Access::TOKEN_SECRET_PARAM_KEY => $oauth_token_secret
			));

			$client = $token->getHttpClient(array(
				'consumerKey' => $oauth_key,
				'consumerSecret' => $oauth_secret
			));
			
			return $client;
		}
    }
    
}