<?php
class ExcelDatabase_Model {

	// Biến lưu trữ kết nối
    private $__conn;
    
    // Biến lưu trữ kết quả truy vấn
    protected $_result = NULL;

    public function __construct() {
    	/*$this->connect();
    	$this->get_result();*/
    }
    
    // Hàm Kết Nối
    public function connect($excel_path = '')
    {
    	if (file_exists($excel_path)){
	    	$this->__conn = $excel_path;
	    	$this->get_result();
    	}
	    else die ('Database Connection Failed!');

        /*// Nếu chưa kết nối thì thực hiện kết nối
        if (!$this->__conn){

            $files = glob(UPLOAD_DIR .'/employeeDatabase/????-??-??.xlsx', GLOB_BRACE);
		    $this->__conn = end($files) or die ('Database Connection Failed!');
        }*/
    }

    public function get_result()
    {
    	$this->_result = $this->load_excel($this->__conn, 0, [], [], 6, 5, 6);
        return $this->_result;
    }

    private function load_excel($excel_path, $sheet_index = 0, $select_col = [], $index_col = [], $offset_index = 0, $header_index = 0, $footer_index = 0) {
	    if (file_exists($excel_path))
	    {
	        $inputFileType = PHPExcel_IOFactory::identify($excel_path);
	        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	         
	        $objReader->setReadDataOnly(true);
	         
	        /**  Load $inputexcel_path to a PHPExcel Object  **/
	        $objPHPExcel = $objReader->load($excel_path);
	         
	        $total_sheets=$objPHPExcel->getSheetCount();
	         
	        $allSheetName=$objPHPExcel->getSheetNames();
	        $objWorksheet  = $objPHPExcel->setActiveSheetIndex($sheet_index);
	        $highestRow    = $objWorksheet->getHighestRow();
	        $highestColumn = $objWorksheet->getHighestColumn();
	        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

	        $data_raw = [];
	        $data_index = [];
	        $data_offset = $offset_index;
	        $data_header = [];
	        $data_footer = [];
	        $data = [];

	        for ($row = 1; $row <= $highestRow;++$row)
	        {
	            for ($col = 0; $col <$highestColumnIndex;++$col)
	            {
	                if (!$select_col || in_array($col, $select_col)) {
	                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

	                    // set data_raw
	                    $data_raw[$row-1][$col] = $value;

	                    // set data_header
	                    if ($row == $header_index) $data_header[$col] = $value;

	                    // set data_footer
	                    if ($row == $footer_index) $data_footer[$col] = $value;

	                    // set data
	                    if ($row > $offset_index) $data[$row-2][$col] = $value;

	                    // set data_index
	                    if (in_array($col, $index_col) && $row >= $offset_index) {
	                        if (!array_key_exists($col, $data_index)) $data_index[$col] = [];
	                        if (!in_array($value, $data_index[$col]))
	                            array_push($data_index[$col], $value);
	                    }
	                }
	            }
	        }        

	        $database['data_raw'] = $data_raw;
	        $database['data_index'] = $data_index;
	        $database['data_offset'] = $data_offset;
	        $database['data_footer'] = $data_footer;
	        $database['data_header'] = $data_header;
	        $database['data'] = $data;
	        $database['data_info'] = str_replace('.xls', '', str_replace('.xlsx', '', basename($excel_path)));

	        return $database;
	    } else {
	        die ('File not found: "' . $csv_path . '"');
	    }
	}


	public function select($select_col = [], $index_col = []) {
	    $tmp = $this->_result;
	    if ($tmp == null)
	        return $tmp;
	    else {
	        foreach ($tmp['data'] as $row => $row_data) {
	            foreach ($row_data as $col => $col_data) {
	                // set data
	                if ($select_col != [] && !in_array($col, $select_col)){
	                    unset($tmp['data_header'][$col]);
	                    unset($tmp['data_footer'][$col]);
	                    unset($tmp['data'][$row][$col]);
	                }

	                // check and add index
	                if ($index_col != [] && in_array($col, $index_col)){
	                    if (!isset($tmp['data_index'][$col]))
	                        $tmp['data_index'][$col][0] = $tmp['data_footer'][$col];

	                    if (!in_array($col_data, $tmp['data_index'][$col]))
	                        array_push($tmp['data_index'][$col], $col_data);
	                } else {
	                    unset($tmp['data_index'][$col]);
	                }
	            }
	        }
	        return $tmp;
	    }
	}

	public function insert_col($new_col, $data_header, $data_footer, $row_select, $row_data = '', $index = false, $database = []) {
	    // check and add header
	    if (!isset($database['data_header'][$new_col]))
	        $database['data_header'][$new_col] = $data_header;

	    // check and add footer
	    if (!isset($database['data_footer'][$new_col]))
	        $database['data_footer'][$new_col] = $data_footer;

	    // add row data
	    $database['data'][$row_select][$new_col] = $row_data;

	    // check and add index data
	    if ($index) {
	        if (!isset($database['data_index'][$new_col]))
	            $database['data_index'][$new_col] = [];

	        if (!in_array($row_data, $database['data_index'][$new_col]))
	            array_push($database['data_index'][$new_col], $row_data);
	    }

	    return $database;
	}

}