<?php // File tiêu chuẩn
class API_Model extends MVC_Model
{
	public function ExecuteQuery($_args, $_by) { // done
		// Thực hiện truy vấn dữ liệu
		$sql_query = $this->ToSql($_args);
		$sql_result = $this->execute($sql_query);
		
		// Ghi lại action
		$this->WriteLog(true,
						$_by,
						'ExecuteQuery',
						!$sql_result?false:true,
						$sql_query);

		return $this->GetResult();
	}

	public function InsertRow($_table_name, $_data) { // done
		// Tên table chỉ chấp nhận chữ thường
		$_table_name =  strtolower($_table_name);
		// Thêm data prefix
		$table_name = DB_PREFIX.$_table_name;

		// Khai báo sql
		$sql = [
            'insert'    => $_data,
            'into'      => $table_name,
        ];

		// Thực hiện truy vấn dữ liệu
        $sql_query = $this->ToSql($sql);
        $sql_result = $this->insert($sql_query);

        // Ghi lại action
		$this->WriteLog($sql['insert'][$_table_name.'_create_date'],
				$sql['insert'][$_table_name.'_create_by'],
				'InsertRow',
				!$sql_result?false:true,
				$sql_query);

		return $sql_result;
	}

	public function DeleteRow($_table_name, $_where, $_by) { // done
		// Tên table chỉ chấp nhận chữ thường
		$_table_name =  strtolower($_table_name);
		// Thêm data prefix
		$table_name = DB_PREFIX.$_table_name;

		// Khai báo sql
		$sql = [
            'delete'    => $table_name,
            'where'      => [$_where],
        ];

		// Thực hiện truy vấn dữ liệu
        $sql_query = $this->ToSql($sql);
        $sql_result = $this->delete($sql_query);

        // Ghi lại action
		$this->WriteLog(true,
						$_by,
						'DeleteRow',
						!$sql_result?false:true,
						$sql_query);

		return $sql_result;
	}

	public function UpdateRow($_table_name, $_data, $_where) { // done
		// Tên table chỉ chấp nhận chữ thường
		$_table_name =  strtolower($_table_name);
		// Thêm data prefix
		$table_name = DB_PREFIX.$_table_name;

		// Khai báo sql	
        $sql = [
            'update'    => $_data,
            'table'     => $table_name,
            'where'     => [$_where],
        ];

		// Thực hiện truy vấn dữ liệu
        $sql_query = $this->ToSql($sql);
        $sql_result = $this->update($sql_query);

        // Ghi lại action
		$this->WriteLog($sql['update'][$_table_name.'_mod_date'],
						$sql['update'][$_table_name.'_mod_by'],
						'UpdateRow',
						!$sql_result?false:true,
						$sql_query);

		return $sql_result;
	}
	public function RemoveRow($_table_name, $_data, $_where) { // done
		// Tên table chỉ chấp nhận chữ thường
		$_table_name =  strtolower($_table_name);
		// Thêm data prefix
		$table_name = DB_PREFIX.$_table_name;

		// Khai báo sql	
        $sql = [
            'update'    => $_data,
            'table'     => $table_name,
            'where'     => [$_where],
        ];

		// Thực hiện truy vấn dữ liệu
        $sql_query = $this->ToSql($sql);
        $sql_result = $this->update($sql_query);

        // Ghi lại action
		$this->WriteLog($sql['update'][$_table_name.'_trash_date'],
						$sql['update'][$_table_name.'_trash_by'],
						'RemoveRow',
						!$sql_result?false:true,
						$sql_query);

		return $sql_result;
	}
	public function RestoreRow($_table_name, $_data, $_where) { // done
		// Tên table chỉ chấp nhận chữ thường
		$_table_name =  strtolower($_table_name);
		// Thêm data prefix
		$table_name = DB_PREFIX.$_table_name;

		// Khai báo sql	
        $sql = [
            'update'    => $_data,
            'table'     => $table_name,
            'where'     => [$_where],
        ];

		// Thực hiện truy vấn dữ liệu
        $sql_query = $this->ToSql($sql);
        $sql_result = $this->update($sql_query);

        // Ghi lại action
		$this->WriteLog($sql['update'][$_table_name.'_restore_date'],
						$sql['update'][$_table_name.'_restore_by'],
						'RestoreRow',
						!$sql_result?false:true,
						$sql_query);

		return $sql_result;
	}
}