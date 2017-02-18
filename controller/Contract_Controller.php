<?php
class Contract_Controller extends Base_Controller
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

		$controller_data = (isset($_GET['contract_id']) || isset($_GET['id'])) ? $controller_data[0] : false;
		$this->controller_data = $controller_data;

        if (in_array($this->action, ['new', 'edit'])) {
			$contract_type = parse_fancyform_enum($this->model->API->get_index('db_contract', 'contract_type'));

			$this->fancyform = [
	        	[//hợp đòng lao động
					'type'	=> 'tab',
					'label'	=> 'Hợp đồng lao động',
					'items'	=> [
						[
							'type'	=> 'line',
							'items'	=> [
								[
									'type'	=> 'string',
									'name'	=> 'contract_id',
									'label'	=> 'Mã số Hợp đồng',
						        	'value'	=> $controller_data ? $controller_data['contract_id'] : '',

								], [
									'type'	=> 'combo',
									'name'	=> 'contract_type',
									'label'	=> 'Loại hợp đồng',
									'data'	=> $contract_type,
						        	'value'	=> $controller_data ? $controller_data['contract_type'] : '',
								], [
									'type'	=> 'date',
									'name'	=> 'contract_begin',
									'label'	=> 'Ngày bắt đầu',
						        	'value'	=> $controller_data ? $controller_data['contract_begin'] : '',
								], [
									'type'	=> 'date',
									'name'	=> 'contract_end',
									'label'	=> 'Ngày kết thúc',
						        	'value'	=> $controller_data ? $controller_data['contract_end'] : '',
								], [
									'type'	=> 'number',
									'name'	=> 'contract_basic_salary',
									'label'	=> 'Mức lương cơ bản',
						        	'value'	=> $controller_data ? $controller_data['contract_basic_salary'] : '',
								], [
									'type'	=> 'number',
									'name'	=> 'contract_responsibitity_salary',
									'label'	=> 'Phụ cấp trách nhiệm',
						        	'value'	=> $controller_data ? $controller_data['contract_responsibitity_salary'] : '',
								], [
									'type'	=> 'number',
									'name'	=> 'contract_insurance_salary',
									'label'	=> 'Lương bảo hiểm',
						        	'value'	=> $controller_data ? $controller_data['contract_insurance_salary'] : '',
								], [
									'type'	=> 'textarea',
									'name'	=> 'contract_description',
									'label'	=> 'Diễn giải',
						        	'value'	=> $controller_data ? $controller_data['contract_description'] : '',
								],[
						        	'type'	=> 'hidden',
						        	'name'	=> 'contract_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['contract_trash_flag'] : 0,
				        		],
							]
						]
					]
				]
	        ];
	    } else if (in_array($this->action, ['management', 'trash'])) {
			$contract_type = parse_fancygrid_enum($this->model->API->get_index('db_contract', 'contract_type'));

			$this->fancygrid = [
	        	'columns' => [
		        	[
		        		'type'	=> 'select',
		        		'width'	=> 35,
		        	], [
		        		'type'	=> 'string',
		        		'index'	=> 'contract_id',
						'title'	=> 'Mã hợp đồng',
		        	], [
		        		'type'	=> 'combo',
		        		'index'	=> 'contract_type',
						'title'	=> 'Loại hợp đồng',
						'data'	=> $contract_type,
		        	], [
		        		'type'	=> 'number',
		        		'index'	=> 'contract_basic_salary',
						'title'	=> 'Mức lương cơ bản',
		        	], [
		        		'type'	=> 'number',
		        		'index'	=> 'contract_responsibitity_salary',
						'title'	=> 'Phụ cấp trách nhiệm',
		        	], [
		        		'type'	=> 'number',
		        		'index'	=> 'contract_insurance_salary',
						'title'	=> 'Lương bảo hiểm',
		        	], [
		        		'type'	=> 'date',
		        		'index'	=> 'contract_begin',
						'title'	=> 'Ngày bắt đầu',
		        	], [
		        		'type'	=> 'date',
		        		'index'	=> 'contract_end',
						'title'	=> 'Ngày kết thúc',
		        	], [
		        		'type'	=> 'string',
		        		'index'	=> 'contract_description',
						'title'	=> 'Diễn giải',
		        	],
		    	],
		    ];
	    }
        $this->sql_database = [
        	'db_contract' => [
	            'contract_id'   => 'Contract No',
	            'contract_type' => 'Contract Type',
	            'contract_basic_salary' => 'Basic Salary',
	            'contract_responsibitity_salary'    => 'Responsibitity Allowance',
	            'contract_insurance_salary'	=> 'Insurance Salary',
	            'contract_begin'    => 'Begin Contract',
	            'contract_end'  => 'End Contract',
	            'contract_description'  => '',
	            'contract_trash_flag'   => ''
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

        if ($this->method == 'PUT' && isset($_GET['contract_id'])) {

        	$id = $_GET['contract_id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Contract Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_contract', ['contract_trash_flag' => true], 'contract_id="'.$id.'"');
        	}
        } else if ($this->method == 'PUT' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_contract', ['contract_trash_flag' => true], 'id="'.$id.'"');
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

        if ($this->method == 'PUT' && isset($_GET['contract_id'])) {

        	$id = $_GET['contract_id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Contract Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_contract', ['contract_trash_flag' => false], 'contract_id="'.$id.'"');
        	}
        } else if ($this->method == 'PUT' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_contract', ['contract_trash_flag' => false], 'id="'.$id.'"');
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

        if (isset($_GET['contract_id'])) {

        	$id = $_GET['contract_id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Contract Id không đúng';
        	} else {
        		$query_flag = $this->model->API->delete_row('db_contract', 'contract_id="'.$id.'"');
        	}
        } else if ($this->method == 'DELETE' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$query_flag = $this->model->API->delete_row('db_contract', 'id="'.$id.'"');
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

    public function managementAction() { //done
    	$data = [
	        'page_title'=> 'Danh sách hợp đồng',
	        'page_id'   => $this->page_id,

	        'page'	=> 'pages/Controller-management.php',
	        'controller'	=> $this->controller,

	        'fancygrid'	=> $this->fancygrid,
	    ];

	    $this->view->load('main', $data);
    }

    public function trashAction() { //done

		$contract_type = parse_fancygrid_enum($this->model->API->get_index('db_contract', 'contract_type'));

    	$data = [
	        'page_title'=> 'Danh sách hợp đồng đã xóa',
	        'page_id'   => $this->page_id,

	        'page'	=> 'pages/Controller-trash.php',
	        'controller'	=> $this->controller,

	        'fancygrid'	=> [
	        	'columns' => [
		        	[
		        		'type'	=> 'select',
		        		'width'	=> 35,
		        	], [
		        		'type'	=> 'string',
		        		'index'	=> 'contract_id',
						'title'	=> 'Mã hợp đồng',
		        	], [
		        		'type'	=> 'combo',
		        		'index'	=> 'contract_type',
						'title'	=> 'Loại hợp đồng',
						'data'	=> $contract_type,
		        	], [
		        		'type'	=> 'number',
		        		'index'	=> 'contract_basic_salary',
						'title'	=> 'Mức lương cơ bản',
		        	], [
		        		'type'	=> 'number',
		        		'index'	=> 'contract_responsibitity_salary',
						'title'	=> 'Phụ cấp trách nhiệm',
		        	], [
		        		'type'	=> 'number',
		        		'index'	=> 'contract_insurance_salary',
						'title'	=> 'Lương bảo hiểm',
		        	], [
		        		'type'	=> 'date',
		        		'index'	=> 'contract_begin',
						'title'	=> 'Ngày bắt đầu',
		        	], [
		        		'type'	=> 'date',
		        		'index'	=> 'contract_end',
						'title'	=> 'Ngày kết thúc',
		        	], [
		        		'type'	=> 'string',
		        		'index'	=> 'contract_description',
						'title'	=> 'Diễn giải',
		        	],
		    	],
		    ],
	    ];

	    $this->view->load('main', $data);
    }

    public function newAction() { //done
		if ($this->method == 'POST') {
			$data = [
            	'response'  => [
	                'success'   => false,
	                'message'   => 'Tạo mới bị lỗi',
	                'data'      => [],
	            ],
	        ];

            // checking sender data
            $data_ok = true;
            $sender_data = [];
            foreach ($this->sql_database as $table_name => $table_fields) {
                foreach ($table_fields as $table_field_key => $khong_can_quan_tam) {
                    if (!isset($_POST[$table_field_key]))
                        $data_ok = false;
                    else
                        $sender_data[$table_name][$table_field_key] = $_POST[$table_field_key];
                }
            }
            if ($data_ok) {
                foreach ($sender_data as $table_name => $table_fields) {
                	$prefix = str_replace('db_', '', $table_name);

            		/*$tmp = [];
            		foreach ($table_fields as $key => $value) {
                        // sua loi dinh dang
            			if (strpos($key, '_date') !== false ||
                            strpos($key, '_begin') !== false ||
                            strpos($key, '_end') !== false) {// fix date format
            				$date = explode('/', $value);
                            if (sizeof($date) >= 3) {
                                // fix vietnamese input (dd/mm/yyyy) to SQL format (Y-m-d)
                                $date_format = $date[2].'/'.$date[1].'/'.$date[0];
                                $tmp[$key] = $date_format;
                            } else {
                                $tmp[$key] = null;
                            }
            			}
                        else
                        	$tmp[$key] = $value;
            		}*/

            		$tmp = $table_fields; //đã format sẵn định dạng ngày tháng

            		if (isset($tmp[$prefix.'_id']) && ($tmp[$prefix.'_id'] != '' || $tmp[$prefix.'_id'] != null)) {
						$insert_flag = $this->model->API->new_row($table_name, $tmp);
                        if ($insert_flag) {
                        	$data['response']['success'] = true;
			                $data['response']['message'] = 'Đã tạo thành công';
			                $data['response']['data']['id'] = $insert_flag;
                        } else {
			                $data['response']['message'] = 'ID này đã tồn tại';
                        }
            		}
                }

            } else {
                $data['response']['message'] = 'Dữ liệu không đúng, vui lòng kiểm tra lại';
            }

            $this->view->load('json', $data);
		} else {
			if (isset($_GET['contract_id']))
				header('location:?c=Contract&a=edit&contract_id='.$_GET['contract_id']);
			else if (isset($_GET['id']))
				header('location:?c=Contract&a=edit&id='.$_GET['id']);

			$data = [
		        'page_title'=> 'Thêm mới hợp đồng',
		        'page_id'   => $this->page_id,

		        'page'	=> 'pages/Controller-new.php',
		        'controller'	=> $this->controller,

		        'controller_data'	=> $this->controller_data,
		        'fancyform'	=> $this->fancyform,
		    ];
			$this->view->load('main', $data);
		}
	}

	public function editAction() { //done
		if ($this->method == 'PUT') {
			$data = [
            	'response'  => [
	                'success'   => false,
	                'message'   => 'Chỉnh sửa chưa được lưu',
	                'data'      => [],
	            ],
	        ];

	        $changed = false;

	        // checking sender data
            $data_ok = true;
            $sender_data = [];
            foreach ($this->sql_database as $table_name => $table_fields) {
                foreach ($table_fields as $table_field_key => $khong_can_quan_tam) {
                    if (!isset($_GET[$table_field_key]))
                        $data_ok = false;
                    else
                        $sender_data[$table_name][$table_field_key] = $_GET[$table_field_key];
                }
            }
            if ($data_ok) {
                foreach ($sender_data as $table_name => $table_fields) {
                	$prefix = str_replace('db_', '', $table_name);

                	/*$tmp = [];
            		foreach ($table_fields as $key => $value) {
                        // sua loi dinh dang
            			if (strpos($key, '_date') !== false ||
                            strpos($key, '_begin') !== false ||
                            strpos($key, '_end') !== false) {// fix date format
            				$date = explode('/', $value);
                            if (sizeof($date) >= 3) {
                                // fix vietnamese input (dd/mm/yyyy) to SQL format (Y-m-d)
                                $date_format = $date[2].'/'.$date[1].'/'.$date[0];
                                $tmp[$key] = $date_format;
                            } else {
                                $tmp[$key] = null;
                            }
            			}
                        else
                        	$tmp[$key] = $value;
            		}*/

            		$tmp = $table_fields; //đã format sẵn định dạng ngày tháng

            		if (isset($tmp[$prefix.'_id']) && ($tmp[$prefix.'_id'] != '' || $tmp[$prefix.'_id'] != null)) {
            			$edit_flag = $this->model->API->edit_row($table_name, $tmp, $prefix.'_id = \''.$tmp[$prefix.'_id'].'\'');

                        if ($changed || $edit_flag) {
                        	$changed = true;
                        	$data['response']['success'] = true;
			                $data['response']['message'] = 'Đã lưu thông tin thành công';
			                $data['response']['data']['id'] = $edit_flag;
                        } else {
			                $data['response']['message'] = 'Không tìm thấy dữ liệu';
                        }
            		}
                }

            } else {
                $data['response']['message'] = 'Dữ liệu không đúng, vui lòng kiểm tra lại';
            }

            $this->view->load('json', $data);
		} else {
			if (!isset($_GET['contract_id']) && !isset($_GET['id']))
				header('location:?c=Contract&a=new');

			$data = [
		        'page_title'=> 'Chỉnh sửa hồ sơ',
		        'page_id'   => $this->page_id,

		        'page'	=> 'pages/Controller-edit.php',
		        'controller'	=> $this->controller,

		        'controller_data'	=> $this->controller_data,
		        'fancyform'	=> $this->fancyform,
		    ];

			$this->view->load('main', $data);
		}
	}

	public function getDataAction() { //done
		$this->view->load('json', $this->getData());
	}

	private function getData() { //done
		$join_employee = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_employee', ['id']),
			'from'	=> 'prefix_db_employee',
		]; $employee_as = str_replace(DB_PREFIX, '', $join_employee['from']);

		$params = [
			'type'	=> 'select',
			'fields'=> [],
			'from'	=> 'prefix_db_contract',
			'join'	=> [
				[
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_employee),
					'as'	=> $employee_as,
					'on'	=> [
						'operator' => '=',
						[
							$employee_as.'_contract_id'	=> 'contract_id',
						]
					],
				],
			],
			'where'	=> [
				'relations'	=> 'AND',
				'operator' => '=',
				[
					//'working_status'	=> 'Active',
					'contract_trash_flag'	=> isset($_GET['trash']),
				]
			],
		];

		if (isset($_GET['contract_id'])) {
			$params['where'][0]['contract_id'] = $_GET['contract_id'];
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