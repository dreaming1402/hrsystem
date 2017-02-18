<?php
class PrintCard_Controller extends Base_Controller
{	
    private $fancyform, $fancygrid, $sql_database, $controller_data;
	public function __construct() {
        parent::__construct();

		$this->model->load('API');
		$this->helper->load('Fancygrid');

		if ($this->action == 'edit')
			$controller_data = $this->getData()['response']['data'];
		else
			$controller_data = false;

		if (sizeof($controller_data) <= 0 && $this->action == 'edit')
			$this->error(404);

		$controller_data = (isset($_GET['print_card_id']) || isset($_GET['id'])) ? $controller_data[0] : false;
		$this->controller_data = $controller_data;

        if (in_array($this->action, ['new', 'edit'])) {
        	$department = parse_fancyform_enum($this->model->API->get_index('db_employee', 'department'));
			$position = parse_fancyform_enum($this->model->API->get_index('db_employee', 'position'));
			$employee_type = parse_fancyform_enum($this->model->API->get_index('db_employee', 'employee_type'));

			$maternity_type = parse_fancyform_enum($this->model->API->get_index('db_maternity', 'maternity_type'));

			$this->fancyform = [
	        	[//lịch sử in ảnh
					'type'	=> 'tab',
					'label'	=> 'Lịch sử in ảnh',
					'items'	=> [
						[
							'type'	=> 'line',
							'items'	=> [
								[
									'type'	=> 'string',
									'name'	=> 'print_card_id',
									'label'	=> 'Mã số in thẻ',
						        	'value'	=> $controller_data ? $controller_data['print_card_id'] : '',

								], [
									'type'	=> 'date',
									'name'	=> 'print_date',
									'label'	=> 'Ngày in',
						        	'value'	=> $controller_data ? $controller_data['print_date'] : '',
								], [
									'type'	=> 'textarea',
									'name'	=> 'print_description',
									'label'	=> 'Diễn giải',
						        	'value'	=> $controller_data ? $controller_data['print_description'] : '',
								], [
						        	'type'	=> 'hidden',
						        	'name'	=> 'print_card_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['print_card_trash_flag'] : 0,
				        		], 

				        		[
									'type'	=> 'combo',
									'name'	=> 'employee_department',
									'label'	=> 'Bộ phận',
						        	'value'	=> $controller_data ? $controller_data['employee_department'] : '',
						        	'data'	=> $department,
								], [
									'type'	=> 'string',
									'name'	=> 'employee_id',
									'label'	=> 'Mã số nhân viên',
						        	'value'	=> $controller_data ? $controller_data['employee_id'] : '',
								], [
									'type'	=> 'string',
									'name'	=> 'employee_full_name',
									'label'	=> 'Họ và tên',
						        	'value'	=> $controller_data ? $controller_data['employee_full_name'] : '',
								], [
									'type'	=> 'combo',
									'name'	=> 'employee_position',
									'label'	=> 'Vị trí làm việc',
						        	'value'	=> $controller_data ? $controller_data['employee_position'] : '',
						        	'data'	=> $position,
								], [
									'type'	=> 'combo',
									'name'	=> 'employee_type',
									'label'	=> 'Loại nhân viên',
						        	'value'	=> $controller_data ? $controller_data['employee_type'] : '',
						        	'data'	=> $employee_type,
								], [
									'type'	=> 'string',
									'name'	=> 'employee_contract_id',
									'label'	=> 'In theo hợp đồng số',
						        	'value'	=> $controller_data ? $controller_data['employee_contract_id'] : '',
								], [
									'type'	=> 'combo',
									'name'	=> 'maternity_type',
									'label'	=> 'Thai sản',
						        	'value'	=> $controller_data ? $controller_data['maternity_type'] : '',
						        	'data'	=> $maternity_type,
								],  [
									'type'	=> 'date',
									'name'	=> 'maternity_begin',
									'label'	=> 'Ngày bắt đầu chế độ',
						        	'value'	=> $controller_data ? $controller_data['maternity_begin'] : '',
								], [
									'type'	=> 'date',
									'name'	=> 'maternity_end',
									'label'	=> 'Ngày kết thúc chế độ',
						        	'value'	=> $controller_data ? $controller_data['maternity_end'] : '',
								],


							]
						]
					]
				]
	        ];
	    } else if (in_array($this->action, ['management', 'trash', 'history'])) {	    	
			$department = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'department'));
			$position = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'position'));
			$employee_type = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'employee_type'));

			$maternity_type = parse_fancygrid_enum($this->model->API->get_index('db_maternity', 'maternity_type'));

	    	$this->fancygrid = [
	        	'columns' => [
		        	[
		        		'type'	=> 'select',
		        		'width'	=> 35,
		        		'locked'=> 1,
		        	], [
						'type'	=> 'string',
		        		'index'	=> 'print_card_id',
		        		'title'	=> 'Mã in thẻ',
		        		'locked'=> 1,
		        	], [
						'type'	=> 'date',
		        		'index'	=> 'print_date',
		        		'title'	=> 'Ngày in thẻ',
		        		'editable'	=> 1,
		        		'locked'=> 1,
		        	], [
						'type'	=> 'string',
		        		'index'	=> 'print_description',
		        		'title'	=> 'Diễn giải',
		        		'editable'	=> 1,
		        		'locked'=> 1,
		        	], [
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
		        		'title'	=> 'Bộ phận',
		        		'data'	=> $department,
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'string',
		        		'index'	=> 'employee_id',
		        		'title'	=> 'Mã nhân viên',
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'string',
		        		'index'	=> 'employee_full_name',
		        		'title'	=> 'Họ và tên',
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'combo',
		        		'index'	=> 'employee_position',
		        		'title'	=> 'Vị trí làm việc',
		        		'data'	=> $position,
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'combo',
		        		'index'	=> 'employee_type',
		        		'title'	=> 'Loại nhân viên',
		        		'editable'	=> 1,
		        		'data'	=> $employee_type,
		        	], [
						'type'	=> 'string',
		        		'index'	=> 'employee_contract_id',
		        		'title'	=> 'Mã hợp đồng',
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'combo',
		        		'index'	=> 'maternity_type',
		        		'title'	=> 'Thai sản',
		        		'data'	=> $maternity_type,
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'date',
		        		'index'	=> 'maternity_begin',
		        		'title'	=> 'Ngày bắt đầu chế độ',
		        		'editable'	=> 1,
		        	], [
						'type'	=> 'date',
		        		'index'	=> 'maternity_end',
		        		'title'	=> 'Ngày kết thúc',
		        		'editable'	=> 1,
		        	]
	        	],
	        ];
	    }
        $this->sql_database = [
        	'db_print_card' => [
	            'print_card_id'   => 'Print No',
	            'print_date'  => 'Print Date',
	            'print_description'  => 'Print Description',
	            'print_card_trash_flag'   => '',

	            'employee_department'	=> 'Department',
	            'employee_id'	=> 'Emp ID',
	            'employee_full_name'	=> 'Full Name',
	            'employee_position'		=> 'Position',
	            'employee_type'			=> 'Employee Type',
	            'emplpyee_contract_id'	=> 'Contract No',

	            'maternity_type'	=> 'Maternity',
	            'maternity_begin'	=> 'Start Date',
	            'maternity_end'		=> 'End Date',
	        ],
        ];
    }

	public function moveToTrashAction() { //done
        $query_flag = false;
		$data = [
        	'response'  => [
                'success'   => false,
                'message'   => 'Không tìm thấy thông tin cần chuyển vào thùng rác',
                'data'      => [],
            ],
        ];

        if ($this->method == 'PUT' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$this->model->load('API');
        		$query_flag = $this->model->API->edit_row('db_print_card', ['print_card_trash_flag' => true], 'id="'.$id.'"');
        	}
        } else {
        	$this->error(405);
        }

		if ($query_flag) {
			$data['response']['success'] = true;
            $data['response']['message'] = 'Đã chuyển vào thùng rác';
            $data['response']['data']['id'] = $id;
		}
		
        $this->view->load('json', $data);
    }

    public function restoreFromTrashAction() { //done
        $query_flag = false;
		$data = [
        	'response'  => [
                'success'   => false,
                'message'   => 'Không tìm thấy thông tin cần khôi phục',
                'data'      => [],
            ],
        ];

        if ($this->method == 'PUT' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$this->model->load('API');
        		$query_flag = $this->model->API->edit_row('db_print_card', ['print_card_trash_flag' => false], 'id="'.$id.'"');
        	}
        } else {
        	$this->error(405);
        }

		if ($query_flag) {
			$data['response']['success'] = true;
            $data['response']['message'] = 'Đã khôi phục thành công';
            $data['response']['data']['id'] = $id;
		}
		
        $this->view->load('json', $data);
    }

    public function permanentlyDeleteAction() { //done
    	$query_flag = false;
		$data = [
        	'response'  => [
                'success'   => false,
                'message'   => 'Không tìm thấy thông tin cần xóa',
                'data'      => [],
            ],
        ];

        if ($this->method == 'DELETE' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$this->model->load('API');
        		$query_flag = $this->model->API->delete_row('db_print_card', 'id="'.$id.'"');
        	}
        } else {
        	$this->error(405);
        }

		if ($query_flag) {
			$data['response']['success'] = true;
            $data['response']['message'] = 'Đã xóa thành công';
            $data['response']['data']['id'] = $id;
		}
		
        $this->view->load('json', $data);
    }

	public function historyAction() { //done
    	if ($this->method == 'POST') { //save history
			$this->model->API->new_row('db_print_card', $_POST);
		} else {
			$data = [
		        'page_title'=> 'Lịch sử in thẻ',
		        'page_id'   => $this->page_id,

		        'page'	=> 'pages/PrintCard-history.php',
		        'controller'	=> $this->controller,

		        'fancygrid'	=> $this->fancygrid,
		    ];

			$this->view->load('main', $data);
		}
    }

	public function trashAction() { //done
    	$data = [
	        'page_title'=> 'Lịch sử in thẻ đã xóa',
	        'page_id'   => $this->page_id,

	        'page'	=> 'pages/Controller-trash.php',
	        'controller'	=> $this->controller,

	        'fancygrid'	=> $this->fancygrid,
	    ];

		$this->view->load('main', $data);
    }

    public function importDataAction() { //done
    	$data = [
			'page_title'	=> 'Tải lên danh sách đã in thẻ',
			'page_id'		=> $this->page_id,
		];

		$this->view->load('main', $data);
    }

    public function getDataAction() { //done
		$this->view->load('json', $this->getData());
	}

	public function getData() { //done
		$this->model->load('API');

		$params = [
			'type'	=> 'select',
			'fields'=> [],
			'from'	=> 'prefix_db_print_card',
			'where'	=> [
				'relations'	=> 'AND',
				'operator' => '=',
				[
					'print_card_trash_flag'	=> isset($_GET['trash']),
				]
			],
		];

		if (isset($_GET['print_card_id'])) {
			$params['where'][0]['print_card_id'] = $_GET['print_card_id'];
		} else if (isset($_GET['id'])) {			
			$params['where'][0]['id'] = $_GET['id'];
		};

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $this->model->API->get_table($params),
			],
		];
		return $data;
	}
}