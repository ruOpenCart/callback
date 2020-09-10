<?php
class ControllerExtensionModuleOCNCallBackOcnCallbackModal extends Controller {
	public function index() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_modal');
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_modal');
	}
}
