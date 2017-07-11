<?php
class API_Model extends MVC_Model
{
	public function get_table($args) { // done
		$this->execute($this->to_sql($args));
		
		return $this->get_result();
	}

	public function new_row($table_name, $data) { // done
		$table_name =  DB_PREFIX.strtolower($table_name);
		$sql = [
            'insert'    => $data,
            'into'      => $table_name,
        ];

		return $this->insert($this->to_sql($sql));
	}

	public function delete_row($table_name, $where) { // done
		$table_name =  DB_PREFIX.strtolower($table_name);
		$sql = [
            'delete'    => $table_name,
            'where'      => [$where],
        ];

		return $this->delete($this->to_sql($sql));
	}

	public function edit_row($table_name, $data, $where) { // done
		$table_name =  DB_PREFIX.strtolower($table_name);		
        $sql = [
            'update'    => $data,
            'table'     => $table_name,
            'where'     => [$where],
        ];

		return $this->update($this->to_sql($sql));
	}
}