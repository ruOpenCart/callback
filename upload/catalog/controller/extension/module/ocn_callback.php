<?php
class ControllerExtensionModuleOCNCallback extends Controller {
	private $user_token;
	private $errors = [];
	private $warning;
	
	public function __construct($registry)
	{
		parent::__construct($registry);
		
		$this->user_token = 'user_token=' . $this->session->data['user_token'];
	}
	
	public function index() {
		$this->document->addStyle('catalog/view/style/ocn_callback/ocn_callback.css');
		$this->document->addScript('catalog/view/javascript/ocn_callback/ocn_callback.js');
		
		$this->load->language('extension/module/ocn_callback/ocn_callback');
		
		
		$data['view_modal'] = $this->load->controller('extension/module/ocn_callback/ocn_callback_modal');

		return $this->load->view('extension/module/ocn_callback/ocn_callback', $data);
	}

	public function store() {
//		$this->response->setOutput($this->load->view('extension/module/ocn_callback/ocn_callback_success'));
		$this->load->language('extension/module/ocn_callback/ocn_callback');
//		$this->load->model('setting/modification');
		
		if ($this->validateForm()) {
//			$this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['warning'] = $this->warning;
			$json['errors'] = $this->errors;
		}
		
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}
	
	private function validateForm() {
		if (!isset($this->request->post['phone']) || empty($this->request->post['phone'])) {
			$this->errors['phone'] = $this->language->get('error_phone');
		}
		
		if (count($this->errors) > 0) {
			$this->warning = $this->language->get('error_warning');
		}
		
		return !$this->errors;
	}
}
