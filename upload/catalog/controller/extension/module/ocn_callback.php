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
		
		$data['url_store'] = $this->url->link('extension/module/ocn_callback/store', $this->user_token, true);
		$data['view_modal'] = $this->load->controller('extension/module/ocn_callback/ocn_callback_modal');

		return $this->load->view('extension/module/ocn_callback/ocn_callback', $data);
	}

	public function store() {}
}
