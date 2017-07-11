<?php
class API_Controller extends Base_Controller
{
	public function __construct()
    {
        parent::__construct();
		if (!isset($_GET['t']))
			$this->error(500);

		$this->model->load('API');
    }

    public function getAction()	{ // done
    	$sql = [
    		'select'=> [],
    		'from'	=> DB_PREFIX.$_GET['t'],	
    	];

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $this->model->API->get_table($sql),
			],
		];

		$this->view->load('json', $data);
	}

	public function newAction() { // done
        $sql['id'] = 'temp'.rand(1,100);

        $result = $this->model->API->new_row($_GET['t'], $sql);

    	if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Đã tạo thành công id="'.$result.'"',
	    			'data' => [
	    				'id' => $result,
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Tạo mới không thành công',
	    			'data' => [
	    				'id' => $sql['id'],
	    			],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}

	public function editAction() { // done
		$sql['data'] = [
			$_GET['key'] => $_GET['value'],
		];

		$sql['where'] = [
			'id' => $_GET['id'],
		];

        $result = $this->model->API->edit_row($_GET['t'], $sql['data'], $sql['where']);

        if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Saved',
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
	    			'message' => 'Not save',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}

	public function removeAction() { // done
		$sql['data'] = [
			str_replace('enum_', '', str_replace('db_', '', $_GET['t'])).'_trash_flag' => true,
		];

		$sql['where'] = [
			'id' => $_GET['id'],
		];

        $result = $this->model->API->edit_row($_GET['t'], $sql['data'], $sql['where']);

		if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Đã xóa thành công id="'.$_GET['id'].'"',
	    			'data' => [
	    				'id' 	=> $_GET['id'],
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Không tìm thấy id="'.$_GET['id'].'"',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}

	public function deleteAction() { // done
		$sql['id'] = $_GET['id'];

		$result = $this->model->API->delete_row($_GET['t'], $sql);

    	if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Đã xóa thành công id="'.$_GET['id'].'"',
	    			'data' => [
	    				'id' 	=> $_GET['id']
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'Không tìm thấy id="'.$_GET['id'].'"',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}
}