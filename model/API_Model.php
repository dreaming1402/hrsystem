<?php
class API_Model extends MVC_Model
{
	public function get_table($params) {
		$this->execute($this->to_sql($params));
		return $this->get_result();
	}

	public function new_row($table_name, $data) {
		$table = strtolower($table_name);
		return $this->insert(DB_PREFIX.$table, $data);
	}

	public function delete_row($table_name, $where) {
		$table = strtolower($table_name);
		return $this->delete(DB_PREFIX.$table, $where);
	}

	public function edit_row($table_name, $data, $where) {
		$table = strtolower($table_name);
		return $this->update(DB_PREFIX.$table, $data, $where);
	}
}