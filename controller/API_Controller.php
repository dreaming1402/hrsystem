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

    public function getAction()	{
		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $this->model->API->get_table($_GET['t']),
			],
		];

		$this->view->load('json', $data);
	}

	public function newAction() {
        $tmp['id'] = 'Temp'.rand(1,100);

        $result = $this->model->API->new_row($_GET['t'], $tmp);

    	if ($result) {
    		$data = [
	    		'response' => [
					'success' => true,
	    			'message' => 'Create was successful',
	    			'data' => [
	    				'id' => $result,
	    			]
	    		]
	    	];
    	} else {
    		$data = [
	    		'response' => [
					'success' => false,
	    			'message' => 'The "'.$tmp['id'].'" not available',
	    			'data' => [
	    				'id' => $tmp['id'],
	    			],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}

	public function editAction() {
        $result = $this->model->API->edit_row($_GET['t'], [$_GET['key']=>$_GET['value']], 'id='. $_GET['id']);

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

	public function removeAction() {
		$result = $this->model->API->edit_row($_GET['t'], [str_replace('enum_', '', str_replace('db_', '', $_GET['t'])).'_trash_flag'=>true], 'id='. $_GET['id']);

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
	    			'message' => 'Không thể xóa id="'.$_GET['id'].', vui lòng thử lại',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}

	public function deleteAction() {
		$result = $this->model->API->delete_row($_GET['t'], 'id='. $_GET['id']);

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
	    			'message' => 'Không thể xóa id="'.$_GET['id'].', vui lòng thử lại',
	    			'data' => [],
	    		]
	    	];
    	}

    	$this->view->load('json', $data);
	}
	
}