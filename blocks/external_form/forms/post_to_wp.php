<?php
defined('C5_EXECUTE') or die("Access Denied.");

$form = Loader::helper('form');

// Show errors
Loader::element('system_errors', array('error' => $error));

if (isset($response)) {
    echo $response;
}
?>
<form method="post" action="<?php echo $this->action('post_to_wp')?>">
<fieldset>
<h3><?php echo t('Post title')?></h3>
<?php echo $form->text('post_title')?> 
<h3><?php echo t('Post body')?></h3>
<?php echo $form->textarea('post_body')?>
</fieldset>
<?php echo $form->submit('submit',t('Submit')); ?>
</form>