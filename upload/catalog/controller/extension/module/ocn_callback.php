<?php
class ControllerExtensionModuleOCNCallback extends Controller {
	private $user_token;
	
	public function __construct($registry)
	{
		parent::__construct($registry);
		
		$this->user_token = 'user_token=' . $this->session->data['user_token'];
	}
	
	public function index() {
		$this->document->addStyle('catalog/view/style/ocn_callback/ocn_callback.css');
		$this->document->addScript('catalog/view/javascript/ocn_callback/ocn_callback.js');
		
		$this->load->language('extension/module/ocn_callback/ocn_callback');
		
		$data['view_modal'] = $this->getViewModal();
		$data['view_alert'] = $this->getViewAlert();

		return $this->load->view('extension/module/ocn_callback/ocn_callback', $data);
	}

	public function store() {
		$this->load->language('extension/module/ocn_callback/ocn_callback');
		$this->load->model('extension/module/ocn_callback');
		
		$callbackData = [
			'store_id' => $this->config->get('config_store_id'),
			'language_id' => $this->config->get('config_language_id'),
			'url' => htmlspecialchars($this->request->post['url'], ENT_QUOTES),
			'name' => htmlspecialchars($this->request->post['name'], ENT_QUOTES),
			'email' => htmlspecialchars($this->request->post['email'], ENT_QUOTES),
			'phone' => htmlspecialchars($this->request->post['phone'], ENT_QUOTES),
			'comment' => htmlspecialchars($this->request->post['comment'], ENT_QUOTES)
		];
		
		$json['callback_id'] = $this->model_extension_module_ocn_callback->addCallback($callbackData);
		$json['success'] = 'success';
		
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}
	
	private function getViewModal()
	{
		$this->load->language('extension/module/ocn_callback/ocn_callback_modal');
		$data['url_store'] = $this->url->link('extension/module/ocn_callback/store', $this->user_token, true);
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_modal', $data);
	}
	
	private function getViewAlert()
	{
		$this->load->language('extension/module/ocn_callback/ocn_callback_alert');
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_alert');
	}
}
