<?php
class ControllerExtensionModuleOCNCallBack extends Controller {
	private $errors = [];
	private $user_token;
	private $version = '3.0.0.0';
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->user_token = 'user_token=' . $this->session->data['user_token'];
	}
	
	public function install() {
		$this->createTables();
		$this->fillTables();
	}

	public function uninstall() {
		$this->removeTables();
	}
	
	public function list() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_list');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->getList();
	}
	
	protected function getList() {
		$data['data_version'] = $this->version;
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
		$data['breadcrumbs'] = $this->generateBreadcrumbs('extension/module/ocn_callback/list');
		
		// Buttons
		$data['url_settings'] = $this->url->link('extension/module/ocn_callback', $this->user_token, true);
		$data['url_cancel'] = $this->url->link('marketplace/extension', $this->user_token . '&type=module', true);
		$data['url_update'] = $this->url->link('extension/module/ocn_callback/items', $this->user_token, true);
		$data['url_remove'] = $this->url->link('extension/module/ocn_callback/remove', $this->user_token, true);
		$data['url_test'] = $this->url->link('extension/module/ocn_callback/test', $this->user_token, true);
		
		// Data
		$data['view_modal'] = $this->viewListModal();
		$data['view_items'] = $this->viewListItems();
		
		// Main
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/ocn_callback/ocn_callback_list', $data));
	}
	
	private function viewListModal() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_list_modal');
		
		$data['url_action'] = $this->url->link('extension/module/ocn_callback/update', $this->user_token, true);
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_list_modal', $data);
	}
	private function viewListItems() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_list_items');
		
		$data['url_get'] = $this->url->link('extension/module/ocn_callback/get', $this->user_token, true);
		
		// Prepare paginate
		$this->load->model('extension/module/ocn_callback');
		$total = (int)$this->model_extension_module_ocn_callback->total();
		$path = $this->url->link('extension/module/ocn_callback/items', $this->user_token, true);
		$page = (isset($this->request->get['page']) && $this->request->get['page'] > 1) ? (int)$this->request->get['page'] : 1;
		$paginate = $this->generatePagination($total, $path, $page);
		
		$data['result'] = sprintf($this->language->get('text_pagination'), $paginate['from'] + 1, $paginate['current_page'] == $paginate['last_page'] ? $paginate['total'] : $paginate['to'], $paginate['total']);
		
		$filter_data = array(
			'start' => $paginate['from'],
			'limit' => $paginate['per_page']
		);
		
		$data['list'] = $this->model_extension_module_ocn_callback->list($filter_data);
		$data['view_pagination'] = $this->viewListPagination($paginate);
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_list_items', $data);
	}
	private function viewListPagination($data) {
		$this->load->language('extension/module/ocn_callback/ocn_callback_list_pagination');
		
		return $this->load->view('extension/module/ocn_callback/ocn_callback_list_pagination', $data);
	}
	
	public function items() {
		$this->response->setOutput($this->viewListItems());
	}
	
	public function remove() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_list');
		$this->load->model('extension/module/ocn_callback');
		
		$selected = $this->request->post['selected'];
		$ids = implode(',', $selected);
		
		$status = $this->model_extension_module_ocn_callback->remove($ids);
		
		if ($status) {
			$data['status'] = 'success';
			$data['title'] = $this->language->get('title_success');
			$data['text'] = $this->language->get('success_delete');
		} else {
			$data['status'] = 'danger';
			$data['title'] = $this->language->get('title_error');
			$data['text'] = $this->language->get('error_delete');
		}
		
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($data));
	}
	
	public function get() {
		$this->load->model('extension/module/ocn_callback');
		
		$data['callback'] = $this->model_extension_module_ocn_callback->getById($this->request->get['callback_id']);
		$data['statuses'] = $this->model_extension_module_ocn_callback->statuses();
		
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($data));
	}
	
	public function update() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_list_modal');
		$this->load->model('extension/module/ocn_callback');
		$status = $this->model_extension_module_ocn_callback->update($this->request->post);

		if ($status) {
			$data['status'] = 'success';
			$data['title'] = $this->language->get('title_success');
			$data['text'] = $this->language->get('success_update');
		} else {
			$data['status'] = 'danger';
			$data['title'] = $this->language->get('title_error');
			$data['text'] = $this->language->get('error_update');
		}
		
		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($data));
	}

	public function index() {
		$this->load->language('extension/module/ocn_callback/ocn_callback_settings');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateData()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('module_ocn_callback', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('title_success');
			
			// If button apply
			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->url->link('extension/module/ocn_callback', $this->user_token, true));
			}
			
			// Go to list modules
			$this->response->redirect($this->url->link('marketplace/extension', $this->user_token . '&type=module', true));
		}
		
		$this->getData();
	}
	
	protected function getData() {
		$data['data_version'] = $this->version;
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
		$data['breadcrumbs'] = $this->generateBreadcrumbs('extension/module/ocn_callback');
		
		// Buttons
		$data['url_action'] = $this->url->link('extension/module/ocn_callback', $this->user_token, true);
		$data['url_list'] = $this->url->link('extension/module/ocn_callback/list', $this->user_token, true);
		$data['url_cancel'] = $this->url->link('marketplace/extension', $this->user_token . '&type=module', true);
		
		// Templates
		// Tabs
		$data['data_tab_info'] = $this->load->controller('extension/module/ocn_callback/ocn_callback_tab_info', ['data_version' => $this->version]);
		$data['data_tab_general'] = $this->load->controller('extension/module/ocn_callback/ocn_callback_tab_general');
		// Main
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/ocn_callback/ocn_callback_settings', $data));
	}
	
	protected function validateData() {
		if (!$this->user->hasPermission('modify', 'extension/module/ocn_callback')) {
			$this->errors['permission'] = $this->language->get('error_permission');
		}
		
		if (count($this->errors) > 0) {
			$this->warning = $this->language->get('error_warning');
		}
		
		return !$this->errors;
	}
	
	protected function generateBreadcrumbs($module)	{
		return [
			[
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', $this->user_token, true)
			],
			[
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', $this->user_token . '&type=module', true)
			],
			[
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link($module, $this->user_token, true)
			]
		];
	}
	
	protected function generatePagination($total, $path, $current_page = 1) {
		$pagination = new \OCN\Pagination();
		$pagination->prepare($total, $path, $current_page);
		
		return $pagination->get();
	}
	
	// Install extension
	protected function createTables() {
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ocn_callback_status` (
			  `callback_status_id` int(11) NOT NULL AUTO_INCREMENT,
			  `language_id` int(11) NOT NULL,
			  `name` varchar(32) NOT NULL,
			  PRIMARY KEY (`callback_status_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;"
		);
		
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ocn_callback` (
			    `callback_id` int(11) NOT NULL AUTO_INCREMENT,
			    `store_id` int(11) NOT NULL DEFAULT '0',
			    `language_id` int(11) NOT NULL,
			    `callback_status_id` int(11) NOT NULL DEFAULT '1',
				`url` varchar(255) NULL,
			    `name` varchar(32) NULL,
				`email` varchar(96) NULL,
				`phone` varchar(32) NULL,
				`comment` text NULL,
				`date_added` datetime NOT NULL,
				`date_modified` datetime NOT NULL,
			    PRIMARY KEY (`callback_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;"
		);
	}
	protected function fillTables() {
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			switch ($language['code']) {
				case 'ru-ru':
					$status_wait = 'Ожидание';
					$status_work = 'Обработка';
					$status_complete = 'Выполнен';
					break;
				default:
					$status_wait = 'Waiting';
					$status_work = 'Processing';
					$status_complete = 'Completed';
			}
			
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "ocn_callback_status` (`callback_status_id`, `language_id`, `name`) VALUES
				('1', '" . $language['language_id'] . "', '" . $status_wait . "'),
				('2', '" . $language['language_id'] . "', '" . $status_work . "'),
				('3', '" . $language['language_id'] . "', '" . $status_complete . "');"
			);
		}
	}
	// Uninstall extension
	protected function removeTables() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ocn_callback_status`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ocn_callback`;");
	}
}
