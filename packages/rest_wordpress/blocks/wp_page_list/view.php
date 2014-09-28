<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$dh = Loader::helper('date');

if (is_array($posts) && count($posts) > 0) {
	foreach ($posts as $post) {
		if (is_object($post)) {
			$title = $th->entities($post->title);
			$url = View::url('/archives', intval($post->ID));
			$date = $dh->date(DATE_APP_GENERIC_MDY_FULL, strtotime($post->date));
			?>
			<h3 class="ccm-page-list-title">
				<?php echo $date?> - <a href="<?php echo $url ?>"><?php echo $title ?></a>
			</h3>
			<?php
		}
	}
} elseif ($error) {
	?><p><?php echo $error ?></p><?php
}
?>

<?php if ($showPagination): ?>
	<div id="pagination">
		<div class="ccm-spacer"></div>
		<div class="ccm-pagination">
			<span class="ccm-page-left"><?php echo $paginator->getPrevious('&laquo; ' . t('Previous')) ?></span>
			<?php echo $paginator->getPages() ?>
			<span class="ccm-page-right"><?php echo $paginator->getNext(t('Next') . ' &raquo;') ?></span>
		</div>
	</div>
<?php endif;
