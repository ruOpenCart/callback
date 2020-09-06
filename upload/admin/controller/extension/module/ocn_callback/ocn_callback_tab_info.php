<?php
class ControllerExtensionModuleOCNCallBackOcnCallbackTabInfo extends Controller {
	public function index() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_tab_info');
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_tab_info');
	}
}
