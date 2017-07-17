<?php
class PrintCard_Controller extends Base_Controller
{	
	public function moveToTrashAction() { // done
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
        				'print_card_trash_flag'	=> true,
        			],
        			'table'	=> 'db_print_card',
        			'where'	=> [
        				[
        					'id'	=> $id,
        				],
        			],
        		];

        		$query_flag = $this->model->API->UpdateRow($sql['table'], $sql['update'], $sql['where'][0]);

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

    public function restoreFromTrashAction() { // done
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
        				'print_card_trash_flag' => false,
        			],
        			'table'	=> 'db_print_card',
        			'where'	=> [
	        			[
	        				'id' => $id,
        				],
        			],
        		];

        		$query_flag = $this->model->API->UpdateRow($sql['table'], $sql['update'], $sql['where'][0]);

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

    public function permanentlyDeleteAction() { // done
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
        			'delete' => 'db_print_card',
        			'where' => [
        				[
        					'id' => $id,
        				],
        			],
        		];

        		$query_flag = $this->model->API->DeleteRow($sql['delete'], $sql['where'][0]);

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
	        				'read'	=> '?c=PrintCard&a=getData',
	        				'update'=> '?c=API&a=edit&t=db_print_card',
	        			]
	        		],
	        	],
	        	'columns'	=> [
	        		[ // select
						'type'	=> 'select',
						'rightLocked' => true,
						'width'	=> 50,
		        	],
	        		[ // print_date
						'type'	=> 'date',
		        		'index'	=> 'print_date',
		        		'title'	=> 'Ngày in',
						'width'	=> 90,
						'locked' => true,
		        	],
	        		[ // print_by
						'type'	=> 'combo',
		        		'index'	=> 'print_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'locked' => true,
						'displayKey' => 'print_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=print_by',
							],
						],
		        	],
	        		[ // print_card_type
						'type'	=> 'combo',
		        		'index'	=> 'print_card_type',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'locked' => true,
						'displayKey' => 'print_card_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=print_card_type',
							],
						],
		        	],
	        		[ // print_description
						'type'	=> 'string',
		        		'index'	=> 'print_description',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'locked' => true,
		        	],
    				[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
						'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=employee_department',
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
								'url'	=> '?c=PrintCard&a=getData&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getData&i=employee_type',
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
								'url'	=> '?c=PrintCard&a=getData&i=contract_type',
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
								'url'	=> '?c=PrintCard&a=getData&i=maternity_type',
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
	        				'read'	=> '?c=PrintCard&a=getData',
	        			]
	        		],
	        	],
	        	'columns'	=> [
	        		[ // select
						'type'	=> 'select',
						'rightLocked' => true,
						'width'	=> 50,
		        	],
	        		[ // print_date
						'type'	=> 'date',
		        		'index'	=> 'print_date',
		        		'title'	=> 'Ngày in',
						'width'	=> 90,
						'locked' => true,
		        	],
	        		[ // print_by
						'type'	=> 'combo',
		        		'index'	=> 'print_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'locked' => true,
						'displayKey' => 'print_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=print_by',
							],
						],
		        	],
	        		[ // print_card_type
						'type'	=> 'combo',
		        		'index'	=> 'print_card_type',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'locked' => true,
						'displayKey' => 'print_card_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=print_card_type',
							],
						],
		        	],
	        		[ // print_description
						'type'	=> 'string',
		        		'index'	=> 'print_description',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'locked' => true,
		        	],
    				[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
						'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=employee_department',
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
								'url'	=> '?c=PrintCard&a=getData&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getData&i=employee_type',
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
								'url'	=> '?c=PrintCard&a=getData&i=contract_type',
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
								'url'	=> '?c=PrintCard&a=getData&i=maternity_type',
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
	        	],
	        ],
	    ];

	    // Rebuild fancygrid
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);

		$this->view->Load('main', $data);
    }

    public function importPrintDataAction() { // done
		$this->library->Load('Uploader');
		$this->library->Load('Fancygrid');

    	$data = [
			'page_title'	=> 'Tải lên danh sách đã in thẻ',
			'table_name'	=> 'db_print_card',
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
	        		[ // select
						'type'	=> 'select',
						'rightLocked' => true,
						'width'	=> 50,
		        	],
		        	[ // print_date
						'type'	=> 'combo',
		        		'title'	=> 'Insert',
		        		'index'	=> 'insert_flag',
						'width'	=> 50,
						'locked' => true,
						'data'	=> ['', 'true', 'false']
		        	],
	        		[ // print_date
						'type'	=> 'date',
		        		'index'	=> 'print_date',
		        		'title'	=> 'Ngày in',
						'width'	=> 90,
						'locked' => true,
		        	],
	        		[ // print_by
						'type'	=> 'combo',
		        		'index'	=> 'print_by',
		        		'title'	=> 'Người in',
						'width'	=> 90,
						'locked' => true,
						'displayKey' => 'print_by',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=print_by',
							],
						],
		        	],
	        		[ // print_card_type
						'type'	=> 'combo',
		        		'index'	=> 'print_card_type',
		        		'title'	=> 'Loại thẻ',
						'width'	=> 70,
						'locked' => true,
						'displayKey' => 'print_card_type',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=print_card_type',
							],
						],
		        	],
	        		[ // print_description
						'type'	=> 'string',
		        		'index'	=> 'print_description',
		        		'title'	=> 'Diễn giải',
						'width'	=> 90,
						'locked' => true,
		        	],
    				[ // employee_department
						'type'	=> 'combo',
		        		'index'	=> 'employee_department',
						'title'	=> 'Bộ phận',
		        		'width'	=> 100,
						'displayKey' => 'employee_department',
						'data'	=> [
							'proxy'	=> [
								'url'	=> '?c=PrintCard&a=getData&i=employee_department',
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
								'url'	=> '?c=PrintCard&a=getData&i=employee_position',
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
								'url'	=> '?c=PrintCard&a=getData&i=employee_type',
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
								'url'	=> '?c=PrintCard&a=getData&i=contract_type',
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
								'url'	=> '?c=PrintCard&a=getData&i=maternity_type',
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
	        	],
	        ],
		];

		// Rebuild fancygrid
    	$data['fancygrid'] = $this->library->Fancygrid->FancygridParse($data['fancygrid']);

		$this->view->Load('main', $data);
    }

    public function getDataAction() { // done
    	// Define
		$data = $this->getData($_GET, isset($_GET['trash']) == true);

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

	private function getData($_where, $_trash = false) { // done
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
			'from'	=> DB_PREFIX.'db_print_card',
			'orderby'	=> [
				[
					'print_date',
				],
				'order'	=> 'DESC',
			],
			'where'	=> [
				[
					'print_card_trash_flag' => $_trash,
				]
			]
		];

		if (isset($_where['employee_id'])) {
			$sql_print_card['where'][0]['employee_id'] = $_where['employee_id'];
		} else if (isset($_where['id'])) {
			$sql_print_card['where'][0]['id'] = $_where['id'];
		};

		$print_card_data = $this->model->API->ExecuteQuery($sql_print_card);

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $print_card_data,
			],
		];

		return $data;
	}
}