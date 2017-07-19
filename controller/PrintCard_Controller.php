<?php
class PrintCard_Controller extends Base_Controller
{	
	private $tableName = 'db_print_card';

	public function indexAction() { // done
    	if ($this->method == 'POST') { // Lưu ảnh đã in
			$this->model->Load('API');
			$_POST[$this->tableName.'_create_date'] = date(DB_DATE_FORMAT);
			$_POST[$this->tableName.'_create_by'] = $this->uid;
    		$query = $this->model->API->InsertRow($this->tableName, $_POST);
			$data = [
				'response' => [
					'success' => $query != false,
					'data'	=> $query,
				],
			];

			$this->view->Load('json', $data);
			return;
    	}
    	
    	// Library
		RegisterScript('rasterizehtml', 'js', TEMPLATE_DIR . '/plugins/RasterizeHTML/rasterizeHTML.allinone.js');
		RegisterScript('jszip', 'js', TEMPLATE_DIR . '/plugins/JsZip/jszip.min.js');
		RegisterScript('filesaver', 'js', TEMPLATE_DIR . '/plugins/FileSaver/FileSaver.js');
		// My Card
		RegisterScript('mycard', 'css', TEMPLATE_DIR . '/plugins/MyCard/mycard.css');
		RegisterScript('mycard', 'js', TEMPLATE_DIR . '/plugins/MyCard/mycard.js');
		
        $this->library->Load('Fancygrid');

    	$data = [
    		'page_title'=> 'In thẻ nhân viên',
    		'uid'		=> $this->uid,

	        'fancyform'	=> [
	        	'items'	=> [
	        		[
	        			'type'	=> 'set',
		        		'label'	=> 'Xem trước',
		        		'checkbox'	=> true,
		        		'cls'	=> 'print-viewer',
		        		'items'	=> [
		        			[ // db_print_card_id
		        				'type'	=> 'hidden',
		        				'name'	=> 'db_print_card_id',
		        			],
		        			[ // db_print_card_create_date
		        				'type'	=> 'hidden',
		        				'name'	=> 'db_print_card_create_date',
		        			],
		        			[ // db_print_card_create_by
		        				'type'	=> 'hidden',
		        				'name'	=> 'db_print_card_create_by',
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
		        				'name'	=> 'contract_id',
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
		        			[ // db_print_card_name
		        				'type'	=> 'combo',
		        				'label'	=> 'Loại thẻ',
		        				'name'	=> 'db_print_card_name',
		        				'data'	=> [
		        					['valueKey'	=> 'Thẻ giấy', 'displayKey' => 'Thẻ giấy'],
		        					['valueKey'	=> 'Thẻ nhựa', 'displayKey' => 'Thẻ nhựa'],
		        					['valueKey'	=> 'Thẻ bầu', 'displayKey' => 'Thẻ bầu'],
		        					['valueKey'	=> 'Thẻ con nhỏ', 'displayKey' => 'Thẻ con nhỏ'],
		        				],
		        				'editable'	=> 'false',
		        			],
		        			[ // db_print_card_desc
		        				'type'	=> 'textarea',
		        				'label'	=> 'Ghi chú',
		        				'vtype'	=> 'notempty',
		        				'name'	=> 'db_print_card_desc',
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
	        		[ // db_print_card_create_date
						'type'	=> 'date',
		        		'index'	=> 'db_print_card_create_date',
						'title'	=> 'Ngày in',
		        	],
		        	[ // db_print_card_create_by
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_create_by',
						'title'	=> 'Người in',
		        	],
		        	[ // db_print_card_name
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_name',
						'title'	=> 'Loại thẻ',
		        	],
		        	[ // db_print_card_desc
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_desc',
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
	        				'read'	=> '?c=PrintCard&a=getPrintList',
	        			]
	        		],
	        	],
	        	'columns'	=> [
	        		[ // action print
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
		        	/*[ // action view
		        		'type'	=> 'action',
				        'title'	=> 'Xem',
				        'index'	=> 'action_view',
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
		        	[ // employee_has_image
						'type'	=> 'combo',
		        		'index'	=> 'employee_has_image',
		        		'title'	=> 'Có ảnh',
						'width'	=> 50,
						'rightLocked' => true,
						'displayKey' => 'employee_has_image',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintList&i=employee_has_image',
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
	        		[ // db_print_card_create_date
						'type'	=> 'date',
		        		'index'	=> 'db_print_card_create_date',
		        		'title'	=> 'Lần in cuối',
						'width'	=> 90,
						'rightLocked' => true,
		        	],
		        	[ // db_print_card_create_by
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_create_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'rightLocked' => true,
						'displayKey' => 'db_print_card_create_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintList&i=db_print_card_create_by',
							],
						],
		        	],
		        	[ // db_print_card_name
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_name',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'rightLocked' => true,
						'displayKey' => 'db_print_card_name',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintList&i=db_print_card_name',
							],
						],
		        	],
		        	[ // db_print_card_desc
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_desc',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'rightLocked' => true,
		        	],
		        	[ // select
		        		'type'	=> 'select',
		        		'width'	=> 50,
		        		'rightLocked'=> true,
		        	],

		        	[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
		        		'locked'=> true,
		        		'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintList&i=employee_department',
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
								'url'	=> '?c=PrintCard&a=getPrintList&i=employee_gender',
							],
						],
					],
					[ // employee_birth_date
						'type'	=> 'date',
		        		'index'	=> 'employee_birth_date',
						'title'	=> 'Ngày sinh',
						'width'	=> 100,	
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
		        	[ // employee_join_date
						'type'	=> 'date',
		        		'index'	=> 'employee_join_date',
						'title'	=> 'Ngày vào làm',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
		        	[ // employee_left_date
						'type'	=> 'date',
		        		'index'	=> 'employee_left_date',
						'title'	=> 'Ngày nghỉ việc',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
	        		[ // employee_status
						'type'	=> 'combo',
						'index'	=> 'employee_status',
						'title'	=> 'Trạng thái làm việc',
						'displayKey' => 'employee_status',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintList&i=employee_status',
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
								'url'	=> '?c=PrintCard&a=getPrintList&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getPrintList&i=employee_type',
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
								'url'	=> '?c=PrintCard&a=getPrintList&i=contract_type',
							],
						],
					],
					[ // contract_begin
						'type'	=> 'date',
						'index'	=> 'contract_begin',
						'title'	=> 'Ngày bắt đầu HĐLĐ',
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
					],
					[ // contract_end
						'type'	=> 'date',
						'index'	=> 'contract_end',
						'title'	=> 'Ngày kết thúc HĐLĐ',
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
					],
					[ // maternity_type
						'type'	=> 'combo',
						'index'	=> 'maternity_type',
						'title'	=> 'Loại thai sản',
						'displayKey' => 'maternity_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintList&i=maternity_type',
							],
						],
					],
	        		[ // maternity_begin
						'type'	=> 'date',
		        		'index'	=> 'maternity_begin',
						'title'	=> 'Ngày hưởng chế độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
		        	[ // maternity_end
						'type'	=> 'date',
		        		'index'	=> 'maternity_end',
						'title'	=> 'Ngày kết thúc độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
	        	],
	        ],
    	];

	    // Rebuild fancygrid
    	$data['fancyform'] = $this->library->Fancygrid->FancyformParse($data['fancyform']);
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);
    	$data['childgrid'] = $this->library->Fancygrid->FancygridParse($data['childgrid']);

		$this->view->Load('main', $data);
    }
    public function getPrintListAction() { // done
    	// Define
		$data = $this->getPrintList($_GET, isset($_GET['trash']) == true);

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

		$this->view->Load('json', $data);
	}
	private function getPrintList($_where, $_trash = false) { // done
		$this->model->Load('API');

		$data_file = 'http://localhost:81/data.json';
		// báo lỗi không tìm thấy dữ liệu
		if (!UrlExists($data_file))
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
			'from'	=> DB_PREFIX.$this->tableName,
			'groupby'	=> [
				[
					$this->tableName.'_id',
				],
				'order'	=> 'DESC',
			],
			'where'	=> [
				[
					$this->tableName.'_trash_flag' => $_trash,
				],
			],
		];

		if (isset($_where['employee_id'])) {
			$sql_print_card['where'][0]['employee_id'] = $_where['employee_id'];
		} else if (isset($_where['id'])) {
			$sql_print_card['where'][0]['id'] = $_where['id'];
		};

		$print_card_data = $this->model->API->ExecuteQuery($sql_print_card, $this->uid);
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
					'db_print_card_create_date',
					'db_print_card_create_by',
					'db_print_card_desc',
					'db_print_card_name',
				];

				// Thêm thông tin in thẻ
				if (isset($print_data[$employee_id])) {
					foreach ($print_meta as $meta_key) {
						if (isset($print_data[$employee_id][$meta_key]))
							$new_data['data'][$index][$meta_key] = $print_data[$employee_id][$meta_key];
						else $new_data['data'][$index][$meta_key] = "";
					}
				} else {
					$new_data['data'][$index]['print_count'] = 0;
					$new_data['data'][$index]['db_print_card_create_date'] = "";
					$new_data['data'][$index]['db_print_card_create_by'] = "";
					$new_data['data'][$index]['db_print_card_desc'] = "";
					$new_data['data'][$index]['db_print_card_name'] = "";
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

	public function historyAction() { // done
		$this->library->Load('Fancygrid');

    	$data = [
	        'page_title'=> 'Lịch sử in thẻ',
	        'page'		=> 'pages/Controller-history.php',

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
	        				'read'	=> '?c=PrintCard&a=getPrintData',
	        				'update'=> '?c=API&a=edit&t='.$this->tableName,
	        			]
	        		],
	        	],
	        	'columns'	=> [
	        		[ // db_print_card_create_date
						'type'	=> 'date',
		        		'index'	=> 'db_print_card_create_date',
		        		'title'	=> 'Lần in cuối',
						'width'	=> 90,
						'locked' => true,
		        	],
		        	[ // db_print_card_create_by
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_create_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'locked' => true,
						'displayKey' => 'db_print_card_create_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=db_print_card_create_by',
							],
						],
		        	],
		        	[ // db_print_card_name
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_name',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'locked' => true,
						'displayKey' => 'db_print_card_name',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=db_print_card_name',
							],
						],
		        	],
		        	[ // db_print_card_desc
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_desc',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'locked' => true,
		        	],
		        	[ // select
		        		'type'	=> 'select',
		        		'width'	=> 50,
		        		'rightLocked'=> true,
		        	],

		        	[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
		        		'locked'=> true,
		        		'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_department',
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
	        		[ // employee_position
						'type'	=> 'combo',
						'index'	=> 'employee_position',
						'title'	=> 'Vị trí làm việc',
						'displayKey' => 'employee_position',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_type',
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
								'url'	=> '?c=PrintCard&a=getPrintData&i=contract_type',
							],
						],
					],
					[ // maternity_type
						'type'	=> 'combo',
						'index'	=> 'maternity_type',
						'title'	=> 'Loại thai sản',
						'displayKey' => 'maternity_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=maternity_type',
							],
						],
					],
	        		[ // maternity_begin
						'type'	=> 'date',
		        		'index'	=> 'maternity_begin',
						'title'	=> 'Ngày hưởng chế độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
		        	[ // maternity_end
						'type'	=> 'date',
		        		'index'	=> 'maternity_end',
						'title'	=> 'Ngày kết thúc độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
	        	],
	        ],
	    ];

	    // Rebuild fancygrid
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);

		$this->view->Load('main', $data);
    }
    public function trashAction() { // done
		$this->library->Load('Fancygrid');

    	$data = [
	        'page_title'=> 'Lịch sử in thẻ đã xóa',
	        'page'	=> 'pages/Controller-trash.php',

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
	        				'read'	=> '?c=PrintCard&a=getPrintData&trash',
	        				'update'=> '?c=API&a=edit&t='.$this->tableName,
	        			]
	        		],
	        	],
	        	'columns'	=> [
	        		[ // db_print_card_create_date
						'type'	=> 'date',
		        		'index'	=> 'db_print_card_create_date',
		        		'title'	=> 'Lần in cuối',
						'width'	=> 90,
						'locked' => true,
		        	],
		        	[ // db_print_card_create_by
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_create_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'locked' => true,
						'displayKey' => 'db_print_card_create_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=db_print_card_create_by',
							],
						],
		        	],
		        	[ // db_print_card_name
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_name',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'locked' => true,
						'displayKey' => 'db_print_card_name',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=db_print_card_name',
							],
						],
		        	],
		        	[ // db_print_card_desc
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_desc',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'locked' => true,
		        	],
		        	[ // select
		        		'type'	=> 'select',
		        		'width'	=> 50,
		        		'rightLocked'=> true,
		        	],

		        	[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
		        		'locked'=> true,
		        		'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_department',
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
	        		[ // employee_position
						'type'	=> 'combo',
						'index'	=> 'employee_position',
						'title'	=> 'Vị trí làm việc',
						'displayKey' => 'employee_position',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_type',
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
								'url'	=> '?c=PrintCard&a=getPrintData&i=contract_type',
							],
						],
					],
					[ // maternity_type
						'type'	=> 'combo',
						'index'	=> 'maternity_type',
						'title'	=> 'Loại thai sản',
						'displayKey' => 'maternity_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=maternity_type',
							],
						],
					],
	        		[ // maternity_begin
						'type'	=> 'date',
		        		'index'	=> 'maternity_begin',
						'title'	=> 'Ngày hưởng chế độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
		        	[ // maternity_end
						'type'	=> 'date',
		        		'index'	=> 'maternity_end',
						'title'	=> 'Ngày kết thúc độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
	        	],
	        ],
	    ];

	    // Rebuild fancygrid
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);

		$this->view->Load('main', $data);
    }
    public function getPrintDataAction() { // done
    	// Define
		$data = $this->getPrintData($_GET, isset($_GET['trash']) == true);

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

		$this->view->Load('json', $data);
	}
	private function getPrintData($_where, $_trash = false) { // done
		$this->model->Load('API');

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
			'from'	=> DB_PREFIX.$this->tableName,
			'orderby'	=> [
				[
					$this->tableName.'_create_date',
				],
				'order'	=> 'DESC',
			],
			'where'	=> [
				[
					$this->tableName.'_trash_flag' => $_trash,
				]
			]
		];

		if (isset($_where['employee_id'])) {
			$sql_print_card['where'][0]['employee_id'] = $_where['employee_id'];
		} else if (isset($_where['id'])) {
			$sql_print_card['where'][0]['id'] = $_where['id'];
		};

		$print_card_data = $this->model->API->ExecuteQuery($sql_print_card, $this->uid);

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $print_card_data,
			],
		];

		return $data;
	}

	public function removeAction() { // done
        // Define
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
        		$this->model->Load('API');

        		$sql = [
        			'update'	=> [
        				$this->tableName.'_trash_flag'	=> true,
        				$this->tableName.'_trash_date'	=> date(DB_DATE_FORMAT),
        				$this->tableName.'_trash_by'	=> $this->uid,
        			],
        			'table'	=> $this->tableName,
        			'where'	=> [
        				[
        					'id'	=> $id,
        					$this->tableName.'_trash_flag'	=> false,
        				],
        			],
        		];

        		$query_flag = $this->model->API->RemoveRow($sql['table'], $sql['update'], $sql['where'][0]);

				if ($query_flag) {
					$data['response']['success'] = true;
		            $data['response']['message'] = 'Đã chuyển vào thùng rác';
		            $data['response']['data']['id'] = $id;
				}
				
		        $this->view->Load('json', $data);
        	}
        } else {
        	Error(405);
        }
    }
    public function restoreAction() { // done
        // Define
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
        		$this->model->Load('API');

        		$sql = [
        			'update' => [
        				$this->tableName.'_trash_flag'	=> false,
        				$this->tableName.'_restore_date'=> date(DB_DATE_FORMAT),
        				$this->tableName.'_restore_by'	=> $this->uid,
        			],
        			'table'	=> $this->tableName,
        			'where'	=> [
	        			[
	        				'id' => $id,
        					$this->tableName.'_trash_flag'	=> true,
        				],
        			],
        		];

        		$query_flag = $this->model->API->RestoreRow($sql['table'], $sql['update'], $sql['where'][0]);

				if ($query_flag) {
					$data['response']['success'] = true;
		            $data['response']['message'] = 'Đã khôi phục thành công';
		            $data['response']['data']['id'] = $id;
				}
				
		        $this->view->Load('json', $data);
        	}
        } else {
        	Error(405);
        }
    }
    public function deleteAction() { // done
		// Define
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
        		$this->model->Load('API');

        		$sql = [
        			'delete' => $this->tableName,
        			'where' => [
        				[
        					'id' => $id,
        				],
        			],
        		];

        		$query_flag = $this->model->API->DeleteRow($sql['delete'], $sql['where'][0], $this->uid);

				if ($query_flag) {
					$data['response']['success'] = true;
		            $data['response']['message'] = 'Đã xóa thành công';
		            $data['response']['data']['id'] = $id;
				}
				
		        $this->view->Load('json', $data);
        	}
        } else {
        	Error(405);
        }
    }

    public function importPrintDataAction() { // done
		$this->library->Load('Uploader');
		$this->library->Load('Fancygrid');

    	$data = [
			'page_title'	=> 'Tải lên danh sách đã in thẻ',
			'table_name'	=> $this->tableName,
			
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
	        	'columns'	=> [
	        		[ // insert_flag
						'type'	=> 'combo',
		        		'index'	=> 'insert_flag',
		        		'title'	=> 'Success',
						'width'	=> 90,
						'locked' => true,
						'data'	=> ['', 'true', 'false'],
		        	],
	        		[ // db_print_card_create_date
						'type'	=> 'date',
		        		'index'	=> 'db_print_card_create_date',
		        		'title'	=> 'Lần in cuối',
						'width'	=> 90,
						'locked' => true,
		        	],
		        	[ // db_print_card_create_by
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_create_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'locked' => true,
						'displayKey' => 'db_print_card_create_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=db_print_card_create_by',
							],
						],
		        	],
		        	[ // db_print_card_name
						'type'	=> 'combo',
		        		'index'	=> 'db_print_card_name',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'locked' => true,
						'displayKey' => 'db_print_card_name',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=db_print_card_name',
							],
						],
		        	],
		        	[ // db_print_card_desc
						'type'	=> 'string',
		        		'index'	=> 'db_print_card_desc',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'locked' => true,
		        	],
		        	[ // select
		        		'type'	=> 'select',
		        		'width'	=> 50,
		        		'rightLocked'=> true,
		        	],

		        	[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
		        		'locked'=> true,
		        		'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_department',
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
	        		[ // employee_position
						'type'	=> 'combo',
						'index'	=> 'employee_position',
						'title'	=> 'Vị trí làm việc',
						'displayKey' => 'employee_position',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getPrintData&i=employee_type',
							],
						],
					],
	        		[ // contract_type
						'type'	=> 'combo',
						'index'	=> 'contract_type',
						'title'	=> 'Loại hợp đồng',
						'displayKey' => 'contract_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=contract_type',
							],
						],
					],
					[ // maternity_type
						'type'	=> 'combo',
						'index'	=> 'maternity_type',
						'title'	=> 'Loại thai sản',
						'displayKey' => 'maternity_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getPrintData&i=maternity_type',
							],
						],
					],
	        		[ // maternity_begin
						'type'	=> 'date',
		        		'index'	=> 'maternity_begin',
						'title'	=> 'Ngày hưởng chế độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
		        	[ // maternity_end
						'type'	=> 'date',
		        		'index'	=> 'maternity_end',
						'title'	=> 'Ngày kết thúc độ thai sản',
						'width'	=> 100,
						'format'=> [
							'read' => 'd/m/Y',
							'write'=> 'd/m/Y',
							'edit' => 'd/m/Y',
						],
		        	],
	        	],
	        ],
		];

		// Rebuild fancygrid
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);

		$this->view->Load('main', $data);
    }

    public function getTemplateAction() {
		RegisterScript('mycard', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard.css');
		RegisterScript('mycard-employee', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard-employee.css');
		RegisterScript('mycard-visitor', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard-visitor.css');
		RegisterScript('mycard-constructor', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard-constructor.css');

    	$this->view->load('raw');
    }
    public function getBackTemplateAction() {    	
		RegisterScript('mycard', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard.css');
		RegisterScript('mycard-employee', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard-employee.css');
		RegisterScript('mycard-visitor', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard-visitor.css');
		RegisterScript('mycard-constructor', 'html', TEMPLATE_DIR . '/plugins/MyCard/mycard-constructor.css');

    	$this->view->load('raw');
    }

}