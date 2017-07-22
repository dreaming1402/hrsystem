<?php // File tiêu chuẩn
class API_Model extends MVC_Model
{

// Data query
	public function GetData($_args, $_by) { // done
		// Thực hiện truy vấn dữ liệu
		$sql_query = $this->ToSql($_args);
		$sql_result = $this->select($sql_query);
		
		// Ghi lại action
		$this->WriteLog(true,
						$_by,
						'GetData',
						!$sql_result?false:true,
						$sql_query);

		return $sql_result;
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

// Table query
    // Hàm tạo bảng mới
    public function NewTable($_table_name, $_by) {
        // Tạo bảng Action nếu chưa có
        $sql_action = 'CREATE TABLE `'.DB_NAME.'`.`'.DB_PREFIX.'db_action` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `db_action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `db_action_by` varchar(255) NOT NULL,
              `db_action_type` varchar(255) NOT NULL,
              `db_action_success` tinyint(1) NOT NULL DEFAULT \'0\',
              `db_action_content` longtext NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
        $this->execute($sql_action);

        // Table structure
        $sql_query = 'CREATE TABLE `'.DB_NAME.'`.`'.DB_PREFIX.$_table_name.'` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `'.$_table_name.'_id` varchar(50) NOT NULL,
              `'.$_table_name.'_name` varchar(255) NOT NULL,
              `'.$_table_name.'_desc` varchar(255) NOT NULL,
              `'.$_table_name.'_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `'.$_table_name.'_create_by` varchar(50) NOT NULL,
              `'.$_table_name.'_mod_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `'.$_table_name.'_mod_by` varchar(50) NOT NULL,
              `'.$_table_name.'_trash_flag` tinyint(1) NOT NULL DEFAULT \'0\',
              `'.$_table_name.'_trash_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `'.$_table_name.'_trash_by` varchar(50) NOT NULL,
              `'.$_table_name.'_restore_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `'.$_table_name.'_restore_by` varchar(50) NOT NULL,
              PRIMARY KEY (`id`),
              INDEX (`'.$_table_name.'_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';

        $sql_result = $this->execute($sql_query);

        // Ghi lại action
		$this->WriteLog(true,
						$_by,
						'NewTable',
						!$sql_result?false:true,
						$sql_query);

        return $sql_result;
    }

    public function AddField($_table_name, $_field_id, $_comment = false, $_by) {
    	$sql_query = 'ALTER TABLE `'.DB_NAME.'`.`'.DB_PREFIX.$_table_name.'` 
    			ADD `'.$_field_id.'` longtext NOT NULL '.($_comment&&$_comment!=''?'COMMENT \''.$_comment.'\';' : ';');
    	$sql_result = $this->execute($sql);

    	// Ghi lại action
		$this->WriteLog(true,
						$_by,
						'NewTable',
						!$sql_result?false:true,
						$sql_query);

		return $sql_result;
    }
}