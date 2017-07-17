<?php // File tiêu chuẩn
class API_Model extends MVC_Model
{
	public function ExecuteQuery($_args) { // done
		$this->execute($this->ToSql($_args));
		
		return $this->GetResult();
	}

	public function InsertRow($_table_name, $_data) { // done
		$_table_name =  DB_PREFIX.strtolower($_table_name);
		$sql = [
            'insert'    => $_data,
            'into'      => $_table_name,
        ];

		return $this->insert($this->ToSql($sql));
	}

	public function DeleteRow($_table_name, $_where) { // done
		$_table_name =  DB_PREFIX.strtolower($_table_name);
		$sql = [
            'delete'    => $_table_name,
            'where'      => [$_where],
        ];

		return $this->delete($this->ToSql($sql));
	}

	public function UpdateRow($_table_name, $_data, $_where) { // done
		$_table_name =  DB_PREFIX.strtolower($_table_name);		
        $sql = [
            'update'    => $_data,
            'table'     => $_table_name,
            'where'     => [$_where],
        ];

		return $this->update($this->ToSql($sql));
	}
}