<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('WordPress REST API'),'', 'span10 offset1', false); ?>
<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>" class="form-horizontal">

<?php echo $this->controller->token->output('save_settings'); ?>

<div class="ccm-pane-body">
	<fieldset>
		<legend><?php echo t('Server')?></legend>
		<div class="control-group">
			<?php echo $form->label('wp_rest_api_url', t('API URL'))?>
			<div class="controls">
				<?php echo $form->text('wp_rest_api_url', $wp_rest_api_url, array('class' => 'input-xxlarge', 'placeholder' => 'http://example.com/wp-json'))?>
			</div>
		</div>
		<legend><?php echo t('Oauth')?></legend>
		<div class="control-group">
			<?php echo $form->label('oauth_key', t('Oauth key'))?>
			<div class="controls">
				<?php echo $form->text('oauth_key', $oauth_key, array('class' => 'input-xxlarge'))?>
			</div>
		</div>
		<div class="control-group">
			<?php echo $form->label('oauth_secret', t('Oauth secret'))?>
			<div class="controls">
				<div class="input-append">
					<?php echo $form->password('oauth_secret', $oauth_secret, array('class' => 'input-xlarge'))?>
					<?php echo $form->submit('show_secret', t('Show secret key'), array('class' => 'btn'))?>
				</div>
			</div>
		</div>
	</fieldset>
</div>
<div class="ccm-pane-footer">
	<?php print $interface->submit(t('Save'), 'site-form', 'right', 'primary'); ?>
</div>

</form>

<script type="text/javascript">
$(function(){
	$('#show_secret').click(function(e){
		e.preventDefault();
		$('#oauth_secret').prop('type','text');
	});
});
</script>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>