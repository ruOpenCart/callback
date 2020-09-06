<?php
class ControllerExtensionModuleOCNCallBackOcnCallbackTabGeneral extends Controller {
	public function index() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_tab_general');
		
		// Data form
		$data['module_ocn_callback_status'] = isset($this->request->post['module_ocn_callback_status'])
			? $this->request->post['module_ocn_callback_status']
			: $this->config->get('module_ocn_callback_status');
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_tab_general', $data);
	}
}
