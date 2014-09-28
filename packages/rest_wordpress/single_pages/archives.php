<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$dh = Loader::helper('date');

$a = new Area('Main'); 
$a->display($c);

if (is_object($post)) {
	$title = $th->entities($post->title);
	$date = $dh->date(DATE_APP_GENERIC_MDY_FULL, strtotime($post->date));
	$content = $post->content;
	?>
	<h1><?php echo $title ?></h1>
	<p><?php echo t('Date published : %s', $date)?></p>
	<?php
	echo $content;
}

$a = new Area('Blog Footer'); 
$a->display($c);
