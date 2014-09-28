<?php
defined('C5_EXECUTE') or die("Access Denied.");

class WpPageListBlockController extends BlockController {

	protected $btTable = 'btWpPageList';
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "350";
	protected $btCacheBlockRecord = true;
	protected $btWrapperClass = 'ccm-ui';

	public function getBlockTypeDescription() {
		return t("List pages from WordPress");
	}
	
	public function getBlockTypeName() {
		return t("WP Page List");
	}
	
	public function getPageList() {
	    $co = new Config();
		$co->setPackageObject(Package::getByHandle('rest_wordpress'));
		$wp_rest_api_url = $co->get('WP_REST_API_URL');
		
		$num = ($this->num > 0) ? $this->num : 0;
		$cat = ($this->cat > 0) ? $this->cat : 0;
		$current = intval($this->get(PAGING_STRING));
		
	    $client = Loader::helper('wp_api','rest_wordpress')->getClient();
	    $client->setUri($wp_rest_api_url.'/posts');
		$client->setMethod(Zend_Http_Client::GET);
		
		$client->setParameterGet('filter[posts_per_page]',$num);
		$client->setParameterGet('filter[cat]',$cat);
		
		if ($this->orderBy == 'chrono_asc' ) {
		    $client->setParameterGet('filter[order]','ASC');
		} elseif ($this->orderBy == 'alpha_asc' ) {
		    $client->setParameterGet('filter[orderby]','title');
		    $client->setParameterGet('filter[order]','ASC');
		} elseif ($this->orderBy == 'alpha_desc' ) {
		    $client->setParameterGet('filter[orderby]','title');
		    $client->setParameterGet('filter[order]','DESC');
		}
		
		if ($current > 0) {
		    $client->setParameterGet('filter[paged]',$current);
		}
		
		$res = $client->request();
		$this->set('client',$client);
		
		$total = $res->getHeader('X-wp-total');
		
		// Setup paginator
		$pagination = Loader::helper('pagination');
		$pagination->queryStringPagingVariable = PAGING_STRING;
		$pagination->init($current, $total, false, $num);
		$this->set('paginator',$pagination);
		
		try {
			$posts = @Loader::helper('json')->decode($res->getBody());
			if(isset($posts) && is_array($posts)) {
				return $posts;
			}
		} catch (Exception $e) {
			throw new Exception(t('Unable to parse API response.'));
		}
	}
	
	public function view() {
		$posts = $this->getPageList();
		$this->set('posts',$posts);
		$this->set('showPagination',$this->paginate);
	}
		
	function save($args) {
		$args['num'] = ($args['num'] > 0) ? $args['num'] : 0;
		$args['truncateSummaries'] = ($args['truncateSummaries']) ? '1' : '0';
		$args['truncateChars'] = intval($args['truncateChars']); 
		$args['paginate'] = intval($args['paginate']);
		parent::save($args);
	}

}
