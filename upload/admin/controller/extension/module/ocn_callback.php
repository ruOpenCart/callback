<?php
class ControllerExtensionModuleOCNCallBack extends Controller {
	private $errors = [];

	public function install() {}

	public function uninstall() {}

	public function index() {
		$this->load->language('extension/module/ocn_callback');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('module_ocn_callback', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			// If button apply
			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->url->link('extension/module/ocn_callback', 'user_token=' . $this->session->data['user_token'], true));
			}
			
			// Go to list modules
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		$this->getForm();
	}
	
	protected function getForm() {
		$data['user_token'] = $this->session->data['user_token'];
		
		// Success
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		// Errors
		$data['errors'] = $this->errors;
		$data['warning'] = $this->warning;
		
		// Breadcrumbs
		$data['breadcrumbs'] = [
			[
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			],
			[
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
			],
			[
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ocn_callback', 'user_token=' . $this->session->data['user_token'], true)
			]
		];
		
		// Buttons
		$data['url_action'] = $this->url->link('extension/module/ocn_callback', 'user_token=' . $this->session->data['user_token'], true);
		$data['url_cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		// Data form
		$data['module_ocn_callback_status'] = isset($this->request->post['module_ocn_callback_status'])
			? $this->request->post['module_ocn_callback_status']
			: $this->config->get('module_ocn_callback_status');
		
		// Templates
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/ocn_callback', $data));
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/ocn_callback')) {
			$this->errors['permission'] = $this->language->get('error_permission');
		}
		
		if (count($this->errors) > 0) {
			$this->warning = $this->language->get('error_warning');
		}
		
		return !$this->errors;
	}
}
