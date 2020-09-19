<?php
class ModelExtensionModuleOcnCallBack extends Model {
	public function list() {
		$query = '';
		$query .= "SELECT cb.*, cbs.name as status_name";
		$query .= " FROM " . DB_PREFIX . "ocn_callback cb";
		$query .= " LEFT JOIN " . DB_PREFIX . "ocn_callback_status cbs ON (cb.callback_status_id = cbs.callback_status_id  AND cbs.language_id = '" . (int)$this->config->get('config_language_id') . "')";
		
		return $this->db->query($query)->rows;
	}
}
