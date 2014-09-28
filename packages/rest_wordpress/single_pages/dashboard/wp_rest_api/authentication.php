<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('WordPress OAuth1 Authentication'),'', 'span10 offset1'); ?>


	<form method="post" id="site-form" action="<?php echo $this->action('request_token'); ?>" class="form-horizontal">
		<fieldset>
			<?php echo $this->controller->token->output('request_token'); ?>
			<legend><?php echo t('Authenticate to WordPress')?></legend>
			<?php if(isset($isAuthenticated) && $isAuthenticated === true):?>
				<p><?php echo t('Currently authenticated.')?></p>
			<?php endif; ?>
			<?php print $interface->submit(t('Get access token'), 'site-form', 'left'); ?>
		</fieldset>
	</form>
	<form method="post" id="site-form" action="<?php echo $this->action('api_test'); ?>" class="form-horizontal">
		<fieldset>
			<?php echo $this->controller->token->output('api_test'); ?>
			<legend><?php echo t('Test to access to WordPress API')?></legend>
			<?php if(isset($data)): ?>
			<pre><?php var_dump($data)?></pre>
			<?php endif; ?>
			<?php print $interface->submit(t('Get WordPress users'), 'site-form', 'left'); ?>
		</fieldset>
	</form>


<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(); ?>