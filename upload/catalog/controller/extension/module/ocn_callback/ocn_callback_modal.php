<?php
class ControllerExtensionModuleOCNCallBackOcnCallbackModal extends Controller {
	public function index() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_modal');
		$data['url_store'] = $this->url->link('extension/module/ocn_callback/store', $this->user_token, true);
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_modal', $data);
	}
}
