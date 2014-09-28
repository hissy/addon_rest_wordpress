<?php defined('C5_EXECUTE') or die("Access Denied."); ?> 

<div class="ccm-block-field-group">
	<h2><?php echo t('Number and Category of Posts')?></h2>
	<label>
		<?php echo t('Display')?>
		<?php echo $form->text('num',$num,array('class'=>'input-mini')); ?>
		<?php echo t('posts of category')?>
		<?php
		$caregories = array(t('** Select Category'));
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('rest_wordpress'));
		$wp_rest_api_url = $co->get('WP_REST_API_URL');
		$client = Loader::helper('wp_api','rest_wordpress')->getClient();
		$client->setUri($wp_rest_api_url.'/taxonomies/category/terms');
		$client->setMethod(Zend_Http_Client::GET);
		$res = $client->request();
		try {
			$terms = @Loader::helper('json')->decode($res->getBody());
			if(isset($terms) && is_array($terms)) {
				foreach($terms as $term) {
					$caregories[$term->ID] = $term->name;
				}
				echo $form->select('cat',$caregories,$cat);
			}
		} catch (Exception $e) {
			throw new Exception(t('Unable to parse API response.'));
		}
		
		?>
	</label>
</div>
<div class="ccm-block-field-group">
	<h2><?php echo t('Pagination')?></h2>
	<label class="checkbox">
		<?php echo $form->checkbox('paginate', 1, $paginate); ?>
		<?php echo t('Display pagination interface if more items are available than are displayed.')?>
	</label>
</div>
<div class="ccm-block-field-group">
	<h2><?php echo t('Sort Pages')?></h2>
	<?php echo t('Pages should appear')?>
	<?php
	$orderByArray = array(
		'chrono_desc' => t('with the most recent first'),
		'chrono_asc' => t('with the earliest first'),
		'alpha_asc' => t('in alphabetical order'),
		'alpha_desc' => t('in reverse alphabetical order')
	);
	echo $form->select('orderBy', $orderByArray, $orderBy);
	?>
</div>
<div class="ccm-block-field-group">
    <h2><?php echo t('Truncate Summaries')?></h2>
    <?php echo $form->checkbox('truncateSummaries', 1, $truncateSummaries); ?>
	<?php echo t('Truncate descriptions after')?>
	<input id="ccm-pagelist-truncateChars" <?php echo ($truncateSummaries?"":"disabled=\"disabled\"")?> type="text" name="truncateChars" size="3" value="<?php echo intval($truncateChars)?>" />
	<?php echo t('characters')?>
</div>
