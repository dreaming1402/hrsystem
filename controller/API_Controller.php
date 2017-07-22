<?php // File tiêu chuẩn
/*
Các field cơ bản vd: tên của bảng = DB_PREFIX.db_print_card
id							= autonumber
db_print_card_id			= khóa chính khi liên kết bảng
db_print_card_name			= khóa phụ
db_print_card_desc			= mô tả
db_print_card_create_date	= timelapse thời gian tạo
db_print_card_create_by		= Người tạo ra row
db_print_card_mod_date		= timelapse thời gian sửa cuối cùng
db_print_card_mod_by		= Người sửa cuối cùng
db_print_card_trash_flag	= đánh dấu đã chuyển vào thùng rác
db_print_card_trash_date	= ngày chuyển vào thùng rác
db_print_card_trash_by		= người chuyển vào thùng rác
db_print_card_restore_date	= ngày chuyển vào thùng rác
db_print_card_restore_by	= người chuyển vào thùng rác
+ ... các field nội dung khác

Các action trừ get ra thì đều lưu lại bảng action history
Các field của db_action
id -> autonumber
db_action_date		= timelapse thời gian thực hiện action
db_action_by		= action đc thực hiện bởi ai
db_action_type		= tên action
db_action_success	= kết quả action true: thành công, false: thất bại
db_action_content	= nội dung của action table.id=id
*/
class API_Controller extends MVC_Controller
{
	private $tableName = NULL;

	public function __construct() {
        parent::__construct();

		if (!isset($_GET['t']))
			Error(500);

		$this->tableName = $_GET['t'];

		$this->model->Load('API');
    }
// Data
    public function getAction()	{ // done
    	$sql = [
    		'select'=> [],
    		'from'	=> DB_PREFIX.$this->tableName,
    	];

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $this->model->API->GetData($sql, GetLoginUserId()),
			],
		];

		$this->view->Load('json', $data);
	}

	public function newAction() { // done
		$sql = [
			'insert' => [
				'id' => 'temp'.rand(1,100),
				$this->tableName.'_create_date' => date(DB_DATE_FORMAT), // auto create date
				$this->tableName.'_create_by'	  => GetLoginUserId(),
				$this->tableName.'_trash_flag'  => false,
			],
			'into' => $this->tableName,
		];

        $result = $this->model->API->InsertRow($sql['into'], $sql['insert']);

    	if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'New record id="'.$result.'" completed',
	    			'data' => [
	    				'id' => $result,
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'New record is not available',
	    			'data' => [
	    				'id' => $sql['insert']['id'],
	    			],
	    		]
	    	];
    	}

    	$this->view->Load('json', $data);
	}

	public function editAction() { // done
		if (!isset($_GET['id']))
			Error(503);

		$sql = [
			'update' => [
				$_GET['key'] => $_GET['value'],
				$this->tableName.'_mod_date' => date(DB_DATE_FORMAT),
				$this->tableName.'_mod_by'   => GetLoginUserId(),
			],
			'table' => $this->tableName,
			'where' => [
				[
					'id' => $_GET['id'],
					$this->tableName.'_trash_flag' => false,
				],
			],
		];

        $result = $this->model->API->UpdateRow($sql['table'], $sql['update'], $sql['where'][0]);

        if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Data saved',
	    			'data' => [
	    				'id' 	=> $_GET['id'],
	    				'key'	=> $_GET['key'],
	    				'value' => $_GET['value'],
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Data not save',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->Load('json', $data);
	}

	public function removeAction() { // done
		if (!isset($_GET['id']))
			Error(503);

		$sql = [
			'update' => [
				$this->tableName.'_trash_flag' => true,
				$this->tableName.'_trash_date' => date(DB_DATE_FORMAT),
				$this->tableName.'_trash_by'   => GetLoginUserId(),
			],
			'table' => $this->tableName,
			'where' => [
				[
					'id' => $_GET['id'],
					$this->tableName.'_trash_flag' => false,
				],
			],
		];

        $result = $this->model->API->RemoveRow($sql['table'], $sql['update'], $sql['where'][0]);

		if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Remove record id="'.$_GET['id'].'" completed',
	    			'data' => [
	    				'id' 	=> $_GET['id'],
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Record id="'.$_GET['id'].'" not found',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->Load('json', $data);
	}

	public function restoreAction() { // done
		if (!isset($_GET['id']))
			Error(503);

		$sql = [
			'update' => [
				$this->tableName.'_trash_flag' => false,
				$this->tableName.'_restore_date' => date(DB_DATE_FORMAT),
				$this->tableName.'_restore_by'   => GetLoginUserId(),
			],
			'table' => $this->tableName,
			'where' => [
				[
					'id' => $_GET['id'],
					$this->tableName.'_trash_flag' => true,
				],
			],
		];

        $result = $this->model->API->RestoreRow($sql['table'], $sql['update'], $sql['where'][0]);

		if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Restore record id="'.$_GET['id'].'" completed',
	    			'data' => [
	    				'id' 	=> $_GET['id'],
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Record id="'.$_GET['id'].'" not found',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->Load('json', $data);
	}

	public function deleteAction() { // done
		if (!isset($_GET['id']))
			Error(503);

		$sql = [
			'delete'=> $this->tableName,
			'where'	=> [
				[
					'id' => $_GET['id'],
				],
			],
		];

		$result = $this->model->API->DeleteRow($sql['delete'], $sql['where'][0], GetLoginUserId());

    	if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Delete record id="'.$_GET['id'].'" completed',
	    			'data' => [
	    				'id' 	=> $_GET['id']
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Record id="'.$_GET['id'].'" not found',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->Load('json', $data);
	}

// Table
	public function newTableAction() {
		// Define
		$data = [
			'response' => [
				'success'	=> false,
				'message'	=> 'Tạo bảng mới thất bại',
				'data'	=> [],
			],
		];

		$result = $this->model->API->NewTable($this->tableName, GetLoginUserId());

		echo $result;

		if ($result) {
			$data['response']['success'] = true;
			$data['response']['message'] = 'Tạo thành công "'.$this->tableName.'"';
			$data['response']['data']    = $this->tableName;
		} else {
			$data['response']['message'] = 'Đã tồn tại "'.$this->tableName.'"';
		}

		$this->view->load('json', $data);
	}
}