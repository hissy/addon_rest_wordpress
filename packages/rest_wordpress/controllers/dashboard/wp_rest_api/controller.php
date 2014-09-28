<?php defined('C5_EXECUTE') or die(_("Access Denied."));


class DashboardWpRestApiController extends DashboardBaseController {

	public function view() {
		$this->redirect('/dashboard/wp_rest_api/settings');
	}
	
}