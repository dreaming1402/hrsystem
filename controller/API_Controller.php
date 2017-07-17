<?php // File tiêu chuẩn
class API_Controller extends MVC_Controller
{
	public function __construct() {
        parent::__construct();
		if (!isset($_GET['t']))
			Error(500);

		$this->model->Load('API');
    }

    public function getAction()	{ // done
    	$sql = [
    		'select'=> [],
    		'from'	=> DB_PREFIX.$_GET['t'],
    	];

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $this->model->API->ExecuteQuery($sql),
			],
		];

		$this->view->Load('json', $data);
	}

	public function newAction() { // done
		$sql = [
			'insert' => [
				'id'	=> 'temp'.rand(1,100),
			],
			'into'	=> $_GET['t']
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
	    				'id' => $insert_data['id'],
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
			],
			'table'	=> $_GET['t'],
			'where'	=> [
				[
					'id'	=> $_GET['id'],
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
				str_replace('enum_', '', str_replace('db_', '', $_GET['t'])).'_trash_flag' => true,
			],
			'table'	=> $_GET['t'],
			'where'	=> [
				[
					'id'	=> $_GET['id'],
				],
			],
		];

        $result = $this->model->API->UpdateRow($sql['table'], $sql['update'], $sql['where'][0]);

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

	public function deleteAction() { // done
		if (!isset($_GET['id']))
			Error(503);

		$sql = [
			'delete'=> $_GET['t'],
			'where'	=> [
				[
					'id'	=> $_GET['id'],
				],
			],
		];

		$result = $this->model->API->DeleteRow($sql['delete'], $sql['where'][0]);

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
}