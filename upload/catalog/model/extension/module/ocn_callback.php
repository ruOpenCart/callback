<?php
class ModelExtensionModuleOcnCallback extends Model {
	public function addCallback($formData) {
		$query = "INSERT INTO " . DB_PREFIX . "ocn_callback SET";
		$query .= " store_id = '" . $formData['store_id'] . "',";
		$query .= " language_id = '" . $formData['language_id'] . "',";
		$query .= " url = '" . $formData['url'] . "', ";
		$query .= " name = '" . $formData['name'] . "', ";
		$query .= " email = '" . $formData['email'] . "', ";
		$query .= " phone = '" . $formData['phone'] . "', ";
		$query .= " message = '" . $formData['message'] . "', ";
		$query .= " date_added = NOW(),";
		$query .= " date_modified = NOW()";
		
		$this->db->query($query);
		
		return $this->db->getLastId();
	}
}
