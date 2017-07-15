<?php
class Employee_Controller extends Base_Controller
{
    public function printCardAction() { // done
    	if ($this->method == 'POST') {
			$this->model->load('API');

    		$query = $this->model->API->new_row('db_print_card', $_POST);
			$data = [
				'response' => [
					'success' => $query != false,
					'data'	=> $query,
				],
			];

			$this->view->load('json', $data);
			return;
    	}

        $this->library->load('Fancygrid');
    	$data = [
    		'page_title'=> 'In thẻ nhân viên',
	        'page_id'   => $this->page_id,

	        'fancyform'	=> [
	        	'items'	=> [
	        		[
	        			'type'	=> 'set',
		        		'label'	=> 'Xem trước',
		        		'checkbox'	=> true,
		        		'cls'	=> 'print-viewer',
		        		'items'	=> [
		        			[ // print_id
		        				'type'	=> 'hidden',
		        				'name'	=> 'print_card_id',
		        			],
		        			[ // print_by
		        				'type'	=> 'hidden',
		        				'name'	=> 'print_by',
		        			],
		        			[ // employee_id
		        				'type'	=> 'hidden',
		        				'name'	=> 'employee_id',
		        			],
		        			[ // employee_name
		        				'type'	=> 'hidden',
		        				'name'	=> 'employee_name',
		        			],
		        			[ // employee_department
		        				'type'	=> 'hidden',
		        				'name'	=> 'employee_department',
		        			],
		        			[ // employee_position
		        				'type'	=> 'hidden',
		        				'name'	=> 'employee_position',
		        			],
		        			[ // employee_type
		        				'type'	=> 'hidden',
		        				'name'	=> 'employee_type',
		        			],
		        			[ // contract_type
		        				'type'	=> 'hidden',
		        				'name'	=> 'contract_type',
		        			],
		        			[ // maternity_type
		        				'type'	=> 'hidden',
		        				'name'	=> 'maternity_type',
		        			],
		        			[ // maternity_begin
		        				'type'	=> 'hidden',
		        				'name'	=> 'maternity_begin',
		        			],
		        			[ // maternity_end
		        				'type'	=> 'hidden',
		        				'name'	=> 'maternity_end',
		        			],
		        		]
	        		],
	        		[
	        			'type'	=> 'set',
		        		'label'	=> 'Nội dung in',
		        		'items'	=> [
		        			[ // print_card_type
		        				'type'	=> 'combo',
		        				'label'	=> 'Loại thẻ',
		        				'name'	=> 'print_card_type',
		        				'data'	=> [
		        					['valueKey'	=> 'Thẻ giấy', 'displayKey' => 'Thẻ giấy'],
		        					['valueKey'	=> 'Thẻ nhựa', 'displayKey' => 'Thẻ nhựa'],
		        					['valueKey'	=> 'Thẻ bầu', 'displayKey' => 'Thẻ bầu'],
		        					['valueKey'	=> 'Thẻ con nhỏ', 'displayKey' => 'Thẻ con nhỏ'],
		        				],
		        				'editable'	=> 'false',
		        			],
		        			[ // print_description
		        				'type'	=> 'textarea',
		        				'label'	=> 'Ghi chú',
		        				'vtype'	=> 'notempty',
		        				'name'	=> 'print_description',
		        			]
		        		]
	        		]
	        	]
	        ],

	        'childgrid'	=> [
	        	'defaults'	=> [
					'type'		=> 'string',
	        		'filter'	=> [
	        			'header'	=> true,
	        			'emptyText'	=> 'Tìm kiếm',
	        		],
					//'filter' 	=> true,
					'menu'		=> true,
	        		'sortable'	=> true,
    				'resizable'	=> true,
    				'editable'	=> false,
    				'vtype'		=> 'notempty',
					'ellipsis'	=> true,
	        	],
	        	'columns'	=> [
	        		[
						'type'	=> 'date',
		        		'index'	=> 'print_date',
						'title'	=> 'Ngày in',
						'format'=> [
							'read' => 'Y-m-d',
							'write'=> 'd-m-Y',
							'edit' => 'Y-m-d',
						]
		        	],
		        	[
						'type'	=> 'string',
		        		'index'	=> 'print_by',
						'title'	=> 'Người in',
		        	],
		        	[
						'type'	=> 'string',
		        		'index'	=> 'print_card_type',
						'title'	=> 'Loại thẻ',
		        	],
		        	[
						'type'	=> 'string',
		        		'index'	=> 'print_description',
						'title'	=> 'Giải thích',
						'flex'	=> 1,
		        	],
	        	],
	        ],

	        'fancygrid'	=> [
	        	'defaults'	=> [
					'type'		=> 'string',
	        		'filter'	=> [
	        			'header'	=> true,
	        			'emptyText'	=> 'Tìm kiếm',
	        		],
					//'filter' 	=> true,
					'menu'		=> true,
	        		'sortable'	=> true,
    				'resizable'	=> true,
    				'editable'	=> false,
    				'vtype'		=> 'notempty',
					'ellipsis'	=> true,
					'width'		=> 120,
	        	],
	        	'data'	=> [
	        		'proxy'	=> [
	        			'api'	=> [
	        				'read'	=> '?c=Employee&a=getPrintList',
	        			]
	        		],
	        	],
	        	'columns'	=> [
		        	
	        		/*[ // select
		        		'type'	=> 'select',
		        		'width'	=> 35,
		        		'locked'=> true,
		        	],*/
		        	[ // action
		        		'type'	=> 'action',
				        'title'	=> 'Tải',
		        		'width'	=> 40,
		        		'locked'=> true,
		        		'filter'=> false,
		        		'value'	=> '',
		        		'items'	=> [
		        			'text'	=> '<i class="fa fa-download"></i>',
		        			'cls'	=> 'action-print_card',
		        			'action'=> 'cellclick',
		        		]
		        	],
		        	/*[ // action
		        		'type'	=> 'action',
				        'title'	=> 'Xem',
		        		'width'	=> 40,
		        		'locked'=> true,
		        		'filter'=> false,
		        		'value'	=> '',
		        		'items'	=> [
		        			'text'	=> '<i class="fa fa-eye"></i>',
		        			'cls'	=> 'action-print_card',
		        			'action'=> 'cellclick',
		        		],
						'locked' => true,
		        	],*/
		        	[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
		        		'locked'=> true,
		        		'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=employee_department',
							],
						],
		        	],
		        	[ // employee_id
						'type'	=> 'number',
		        		'index'	=> 'employee_id',
						'title'	=> 'Mã NV',
						'width'	=> 70,
		        		'locked'=> true,
		        	],
		        	[ // employee_name
						'type'	=> 'string',
		        		'index'	=> 'employee_name',
		        		'title'	=> 'Họ và tên',
		        		'locked'=> true,
						'width'	=> 160,
		        	],
		        	[ // employee_old_id
						'type'	=> 'number',
		        		'index'	=> 'employee_old_id',
						'title'	=> 'Mã NV cũ',
						'width'	=> 70,
		        	],
		        	[ // employee_gender
						'type'	=> 'combo',
						'index'	=> 'employee_gender',
						'title'	=> 'Giới tính',
						'width'	=> 70,
						'displayKey' => 'employee_gender',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=employee_gender',
							],
						],
					],
					[ // employee_birth_date
						'type'	=> 'date',
		        		'index'	=> 'employee_birth_date',
						'title'	=> 'Ngày sinh',
						'width'	=> 100,
		        	],
		        	[ // employee_join_date
						'type'	=> 'date',
		        		'index'	=> 'employee_join_date',
						'title'	=> 'Ngày vào làm',
						'width'	=> 100,
		        	],
		        	[ // employee_left_date
						'type'	=> 'date',
		        		'index'	=> 'employee_left_date',
						'title'	=> 'Ngày nghỉ việc',
						'width'	=> 100,
		        	],
	        		[ // employee_status
						'type'	=> 'combo',
						'index'	=> 'employee_status',
						'title'	=> 'Trạng thái làm việc',
						'displayKey' => 'employee_status',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=employee_status',
							],
						],
					],
	        		[ // employee_position
						'type'	=> 'combo',
						'index'	=> 'employee_position',
						'title'	=> 'Vị trí làm việc',
						'displayKey' => 'employee_position',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=employee_position',
							],
						],
					],
	        		[ // employee_type
						'type'	=> 'combo',
						'index'	=> 'employee_type',
						'title'	=> 'Loại nhân viên',
						'displayKey' => 'employee_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=employee_type',
							],
						],
					],							
	        		[ // contract_id
						'type'	=> 'string',
						'index'	=> 'contract_id',
						'title'	=> 'Mã hợp đồng',
					],
	        		[ // contract_type
						'type'	=> 'combo',
						'index'	=> 'contract_type',
						'title'	=> 'Loại hợp đồng',
						'displayKey' => 'contract_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=contract_type',
							],
						],
					],
					[ // contract_begin
						'type'	=> 'date',
						'index'	=> 'contract_begin',
						'title'	=> 'Ngày bắt đầu HĐLĐ',
						//'data'	=> $employee_type,
					],
					[ // contract_end
						'type'	=> 'date',
						'index'	=> 'contract_end',
						'title'	=> 'Ngày kết thúc HĐLĐ',
						//'data'	=> $employee_type,
					],
					[ // maternity_type
						'type'	=> 'combo',
						'index'	=> 'maternity_type',
						'title'	=> 'Loại thai sản',
						'displayKey' => 'maternity_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=maternity_type',
							],
						],
					],
	        		[ // maternity_begin
						'type'	=> 'date',
		        		'index'	=> 'maternity_begin',
						'title'	=> 'Ngày hưởng chế độ thai sản',
						'width'	=> 100,
		        	],
		        	[ // maternity_end
						'type'	=> 'date',
		        		'index'	=> 'maternity_end',
						'title'	=> 'Ngày kết thúc độ thai sản',
						'width'	=> 100,
		        	],
		        	[ // employee_has_image
						'type'	=> 'combo',
		        		'index'	=> 'employee_has_image',
		        		'title'	=> 'Có ảnh',
						'width'	=> 50,
						'rightLocked' => true,
						'displayKey' => 'employee_has_image',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=employee_has_image',
							],
						],
		        	],
		        	[ // print_count
						'type'	=> 'number',
		        		'index'	=> 'print_count',
		        		'title'	=> 'Đã in',
						'width'	=> 50,
						'rightLocked' => true,
		        	],
	        		[ // print_date
						'type'	=> 'date',
		        		'index'	=> 'print_date',
		        		'title'	=> 'Lần in cuối',
						'width'	=> 90,
						'rightLocked' => true,						
						'format' => [
							'read' => 'Y-m-d',
							'write'=> 'd-m-Y',
							'edit' => 'Y-m-d',
						]
		        	],
		        	[ // print_by
						'type'	=> 'combo',
		        		'index'	=> 'print_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'rightLocked' => true,
						'displayKey' => 'print_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=print_by',
							],
						],
		        	],
		        	[ // print_card_type
						'type'	=> 'combo',
		        		'index'	=> 'print_card_type',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'rightLocked' => true,
						'displayKey' => 'print_card_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=Employee&a=getPrintList&i=print_card_type',
							],
						],
		        	],
		        	[ // print_description
						'type'	=> 'string',
		        		'index'	=> 'print_description',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'rightLocked' => true,
		        	],
	        		// print_card_trash_flag	<-hidden
	        		// employee_id				<-hidden <- history
	        		// employee_name			<-hidden <- history
	        		// employee_position		<-hidden <- history
	        		// employee_department		<-hidden <- history
	        		// employee_type			<-hidden <- history
	        		// contract_type			<-hidden <-	history
	        		// maternity_type			<-hidden <- history
	        		// maternity_begin			<-hidden <- history
	        		// maternity_end			<-hidden <- history
	        		[ // select
						'type'	=> 'select',
						'rightLocked' => true,
						'width'	=> 50,
		        	],
	        	],
	        ],
    	];

	    // Rebuild fancygrid
    	$data['fancyform'] = $this->library->Fancygrid->FancyformParse($data['fancyform']);
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);
    	$data['childgrid'] = $this->library->Fancygrid->FancygridParse($data['childgrid']);

		$this->view->load('main', $data);
    }

    public function getPrintListAction() { //done
    	// Define
		$data = $this->getData($_GET);

		// lấy index theo field i
		$index_data = [];
		if (isset($_GET['i'])) {
			array_push($index_data, [$_GET['i'] => '']);
			foreach ($data['response']['data'] as $key => $value) {
				if (isset($value[$_GET['i']])) {
					$index_value = [$_GET['i'] => $value[$_GET['i']]];
					if (!in_array($index_value, $index_data))
						array_push($index_data, $index_value);
				}
			}

			$data = [
				'response' => [
					'success' => true,
					'data'	=> $index_data,
				]
			];
		};

		$this->view->load('json', $data);
	}

	private function getData($_where, $_trash = false) { // done
		$this->model->load('API');

		$data_file = 'http://localhost:81/data.json';
		// báo lỗi không tìm thấy dữ liệu
		if (!UrlExist($data_file))
			die('Không tìm thấy CSDL');

		// Define
		$data = [
			'response' => [
				'success'	=> false,
				'data'		=> [],
			],
		];

		// Lấy danh sách in thẻ
		$sql_print_card = [
			'select'	=> [],
			'count'	=> 'print_count',
			'from'	=> DB_PREFIX.'db_print_card',
			'groupby'	=> 'print_card_id',
			'order'	=> 'DESC',
			'where'	=> [
				[
					'print_card_trash_flag' => $_trash,
				],
			],
		];

		if (isset($_where['employee_id'])) {
			$sql_print_card['where'][0]['employee_id'] = $_where['employee_id'];
		} else if (isset($_where['id'])) {
			$sql_print_card['where'][0]['id'] = $_where['id'];
		};

		$print_card_data = $this->model->API->get_table($sql_print_card);
		$print_data = [];
		foreach ($print_card_data as $index => $row) {
			$print_data[$row['employee_id']] = $row;
		}

		// Copy new data
		$json_data = json_decode(file_get_contents($data_file), true);
		$new_data = $json_data;		

		if (isset($new_data['data'])) {
			$tmp_data = [];
			foreach ($json_data['data'] as $index => $row) {
				$employee_id = $row['employee_id'];
				$tmp_data[$employee_id] = $row;

				// Tìm ảnh trong uploads/employeeImage
				$image_file = UPLOAD_DIR.'/employeeImage/'.$employee_id.'.png';
				$new_data['data'][$index]['employeeImage'] = 'src="?c=EmployeeImage&a=view&id='.$employee_id.'"';
				if (file_exists($image_file))
					$new_data['data'][$index]['employee_has_image'] = 'Yes';
				else
					$new_data['data'][$index]['employee_has_image'] = 'No';

				// Tự động add thông tin theo danh sách
				$print_meta = [
					'print_count',
					'print_date',
					'print_by',
					'print_description',
					'print_card_type',
				];

				// Thêm thông tin in thẻ
				if (isset($print_data[$employee_id])) {
					foreach ($print_meta as $meta_key) {
						if (isset($print_data[$employee_id][$meta_key]))
							$new_data['data'][$index][$meta_key] = $print_data[$employee_id][$meta_key];
					}
				} else {
					$new_data['data'][$index]['print_count'] = 0;
				}

				// Đánh dấu đã in
				$new_data['data'][$index]['print_flag'] = $new_data['data'][$index]['print_count'] > 0 ? 'Yes': 'No'; 
			}			
		} else die('Không tìm thấy \'data\'');

		$data = [
			'response' => $new_data,
		];
				
		return $data;
	}

	public function uploadImageAction() { // done
		$this->library->load('Uploader');

    	$data = [
			'page_title'	=> 'Tải lên ảnh thẻ nhân viên',
			'page_id'		=> $this->page_id,
		];

		$this->view->load('main', $data);
    }

    public function importDataAction() { // done
    	$data = [
			'page_title'	=> 'Tải lên danh sách nhân viên',
			'page_id'		=> $this->page_id,
		];

		$this->view->load('main', $data);
    }
}