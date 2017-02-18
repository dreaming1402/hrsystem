<?php
class Employee_Controller extends Base_Controller
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

		$controller_data = (isset($_GET['employee_id']) || isset($_GET['id'])) ? $controller_data[0] : false;
		$this->controller_data = $controller_data;

        if (in_array($this->action, ['new', 'edit'])) {
			$department = parse_fancyform_enum($this->model->API->get_index('db_employee', 'department'));
			$position = parse_fancyform_enum($this->model->API->get_index('db_employee', 'position'));
			$job = parse_fancyform_enum($this->model->API->get_index('db_employee', 'job'));
			$gender = parse_fancyform_enum($this->model->API->get_index('db_employee', 'gender'));
			$employee_type = parse_fancyform_enum($this->model->API->get_index('db_employee', 'employee_type'));
			$working_status = parse_fancyform_enum($this->model->API->get_index('db_employee', 'working_status'));

			$contract_type = parse_fancyform_enum($this->model->API->get_index('db_contract', 'contract_type'));
			$social_flag = parse_fancyform_enum($this->model->API->get_index('db_social', 'social_flag'));
			$health_flag = parse_fancyform_enum($this->model->API->get_index('db_health', 'health_flag'));
			$maternity_type = parse_fancyform_enum($this->model->API->get_index('db_maternity', 'maternity_type'));

			$this->fancyform = [
	        	[//thông tin nhân viên
					'type'	=> 'tab',
					'label'	=> 'Thông tin nhân viên',
					'items'	=> [
						[
							'type'	=> 'line',
							'items'	=> [
								[
						        	'type'	=> 'hidden',
						        	'name'	=> 'id',
						        	'value'	=> $controller_data ? $controller_data['id'] : '',
				        		], [
						        	'type'	=> 'combo',
						        	'name'	=> 'department',
					        		'label'	=> 'Bộ phận',
						        	'emptyText'	=> 'Department',
						        	'data'	=> $department,
						        	'value'	=> $controller_data ? $controller_data['department'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'employee_id',
					        		'label'	=> 'Mã số nhân viên',
						        	'emptyText'	=> 'Employee ID',
						        	'value'	=> $controller_data ? $controller_data['employee_id'] : '',
						        ], [
						        	'type'	=> 'string',
						        	'name'	=> 'employee_old_id',
					        		'label'	=> 'Mã số cũ',
						        	'emptyText'	=> 'Employee Old ID',
						        	'value'	=> $controller_data ? $controller_data['employee_old_id'] : '',
						        ], [
						        	'type'	=> 'string',
						        	'name'	=> 'full_name',
					        		'label'	=> 'Họ và tên',
						        	'emptyText'	=> 'Full name',
						        	'value'	=> $controller_data ? $controller_data['full_name'] : '',
						        ], [
						        	'type'	=> 'date',
						        	'name'	=> 'birth_date',
					        		'label'	=> 'Ngày sinh',
						        	'emptyText'	=> 'Birth date',
						        	'value'	=> $controller_data ? $controller_data['birth_date'] : '',
						        ], [
						        	'type'	=> 'string',
						        	'name'	=> 'birth_place',
					        		'label'	=> 'Nơi sinh',
						        	'emptyText'	=> 'Birth place',
						        	'value'	=> $controller_data ? $controller_data['birth_place'] : '',
						        ], [
						        	'type'	=> 'date',
						        	'name'	=> 'join_date',
					        		'label'	=> 'Ngày vào làm',
						        	'emptyText'	=> 'Join date',
						        	'value'	=> $controller_data ? $controller_data['join_date'] : '',
						        ], [
						        	'type'	=> 'combo',
						        	'name'	=> 'position',
					        		'label'	=> 'Vị trí làm việc',
						        	'emptyText'	=> 'Position',
						        	'data'	=> $position,
						        	'value'	=> $controller_data ? $controller_data['position'] : '',
				        		], [
						        	'type'	=> 'combo',
						        	'name'	=> 'job',
					        		'label'	=> 'Công việc',
						        	'emptyText'	=> 'Job',
						        	'data'	=> $job,
						        	'value'	=> $controller_data ? $controller_data['job'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'phone',
					        		'label'	=> 'Điện thoại cá nhân',
						        	'emptyText'	=> 'Phone',
						        	'value'	=> $controller_data ? $controller_data['phone'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'permanent_address',
					        		'label'	=> 'Địa chỉ thường trú',
						        	'emptyText'	=> 'Permanent Address',
						        	'value'	=> $controller_data ? $controller_data['permanent_address'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'present_address',
					        		'label'	=> 'Địa chỉ tạm trú',
						        	'emptyText'	=> 'Present Address',
						        	'value'	=> $controller_data ? $controller_data['present_address'] : '',
				        		], [
						        	'type'	=> 'combo',
						        	'name'	=> 'gender',
					        		'label'	=> 'Giới tính',
						        	'emptyText'	=> 'Gender',
						        	'data'	=> $gender,
						        	'value'	=> $controller_data ? $controller_data['gender'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'person_id',
					        		'label'	=> 'Số CMND',
						        	'emptyText'	=> 'Person ID',
						        	'value'	=> $controller_data ? $controller_data['person_id'] : '',
				        		], [
						        	'type'	=> 'date',
						        	'name'	=> 'person_issued_date',
					        		'label'	=> 'Ngày cấp',
						        	'emptyText'	=> 'Issued date',
						        	'value'	=> $controller_data ? $controller_data['person_issued_date'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'person_place',
					        		'label'	=> 'Nơi cấp',
						        	'emptyText'	=> 'Person place',
						        	'value'	=> $controller_data ? $controller_data['person_place'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'ethenic',
					        		'label'	=> 'Dân tộc',
						        	'emptyText'	=> 'Ethenic',
						        	'value'	=> $controller_data ? $controller_data['ethenic'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'education',
					        		'label'	=> 'Trình độ học vấn',
						        	'emptyText'	=> 'Education',
						        	'value'	=> $controller_data ? $controller_data['education'] : '',
				        		], [
						        	'type'	=> 'combo',
						        	'name'	=> 'employee_type',
					        		'label'	=> 'Loại nhân viên',
						        	'emptyText'	=> 'Employee type',
						        	'data'	=> $employee_type,
						        	'value'	=> $controller_data ? $controller_data['employee_type'] : '',
				        		], [
						        	'type'	=> 'combo',
						        	'name'	=> 'working_status',
					        		'label'	=> 'Trạng thái làm việc',
						        	'emptyText'	=> 'Working status',
						        	'data'	=> $working_status,
						        	'value'	=> $controller_data ? $controller_data['working_status'] : '',
				        		], [
						        	'type'	=> 'date',
						        	'name'	=> 'left_date',
					        		'label'	=> 'Ngày nghỉ việc',
						        	'emptyText'	=> 'Left date',
						        	'value'	=> $controller_data ? $controller_data['left_date'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'account',
					        		'label'	=> 'Số tài khoản',
						        	'emptyText'	=> 'Account',
						        	'value'	=> $controller_data ? $controller_data['account'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'pit_id',
					        		'label'	=> 'Mã số thuế cá nhân',
						        	'emptyText'	=> 'Tax id',
						        	'value'	=> $controller_data ? $controller_data['pit_id'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'email',
					        		'label'	=> 'Thư điện tử',
						        	'emptyText'	=> 'Email',
						        	'vtype'	=> 'email',
						        	'value'	=> $controller_data ? $controller_data['email'] : '',
				        		], [
						        	'type'	=> 'string',
						        	'name'	=> 'office_phone',
					        		'label'	=> 'Điện thoại nội bộ',
						        	'emptyText'	=> 'Office phone',
						        	'value'	=> $controller_data ? $controller_data['office_phone'] : '',
				        		], [
						        	'type'	=> 'hidden',
						        	'name'	=> 'employee_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['employee_trash_flag'] : 0,
				        		],

							]
						]
					]
				], [//hợp đòng lao động
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
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_id'] : '',

								], [
									'type'	=> 'combo',
									'name'	=> 'contract_type',
									'label'	=> 'Loại hợp đồng',
									'data'	=> $contract_type,
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_type'] : '',
								], [
									'type'	=> 'date',
									'name'	=> 'contract_begin',
									'label'	=> 'Ngày bắt đầu',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_begin'] : '',
								], [
									'type'	=> 'date',
									'name'	=> 'contract_end',
									'label'	=> 'Ngày kết thúc',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_end'] : '',
								], [
									'type'	=> 'number',
									'name'	=> 'contract_basic_salary',
									'label'	=> 'Mức lương cơ bản',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_basic_salary'] : '',
								], [
									'type'	=> 'number',
									'name'	=> 'contract_responsibitity_salary',
									'label'	=> 'Phụ cấp trách nhiệm',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_responsibitity_salary'] : '',
								], [
									'type'	=> 'number',
									'name'	=> 'contract_insurance_salary',
									'label'	=> 'Lương bảo hiểm',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_insurance_salary'] : '',
								], [
									'type'	=> 'textarea',
									'name'	=> 'contract_description',
									'label'	=> 'Diễn giải',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_description'] : '',
								],[
						        	'type'	=> 'hidden',
						        	'name'	=> 'contract_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['db_contract_contract_trash_flag'] : 0,
				        		],
							]
						]
					]
				], [//bảo hiểm xã hội
					'type'	=> 'tab',
					'label'	=> 'Bảo hiểm Xã hội',
					'items'	=> [
						[
							'type'	=> 'line',
							'items'	=> [
								[
									'type'	=> 'string',
									'name'	=> 'social_id',
									'label'	=> 'Số sổ Bảo hiểm',
						        	'value'	=> $controller_data ? $controller_data['db_social_social_id'] : '',

								], [
									'type'	=> 'string',
									'name'	=> 'social_place',
									'label'	=> 'Nơi đăng ký sổ',
						        	'value'	=> $controller_data ? $controller_data['db_social_social_place'] : '',

								], [
									'type'	=> 'date',
									'name'	=> 'social_date',
									'label'	=> 'Ngày cấp sổ',
						        	'value'	=> $controller_data ? $controller_data['db_social_social_date'] : '',
								], [
									'type'	=> 'combo',
									'name'	=> 'social_flag',
									'label'	=> 'Tình trạng sổ',
									'data'	=> $social_flag,
						        	'value'	=> $controller_data ? $controller_data['db_social_social_flag'] : '',
								], [
									'type'	=> 'textarea',
									'name'	=> 'social_description',
									'label'	=> 'Diễn giải',
						        	'value'	=> $controller_data ? $controller_data['db_social_social_description'] : '',
								],[
						        	'type'	=> 'hidden',
						        	'name'	=> 'social_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['db_social_social_trash_flag'] : 0,
				        		],
							]
						]
					]
				], [//bảo hiểm y tế
					'type'	=> 'tab',
					'label'	=> 'Bảo hiểm Y tế',
					'items'	=> [
						[
							'type'	=> 'line',
							'items'	=> [
								[
									'type'	=> 'string',
									'name'	=> 'health_id',
									'label'	=> 'Số sổ Y tế',
						        	'value'	=> $controller_data ? $controller_data['db_health_health_id'] : '',

								], [
									'type'	=> 'string',
									'name'	=> 'health_place',
									'label'	=> 'Nơi đăng ký sổ',
						        	'value'	=> $controller_data ? $controller_data['db_health_health_place'] : '',

								], [
									'type'	=> 'date',
									'name'	=> 'health_date',
									'label'	=> 'Ngày cấp sổ',
						        	'value'	=> $controller_data ? $controller_data['db_health_health_date'] : '',
								], [
									'type'	=> 'combo',
									'name'	=> 'health_flag',
									'label'	=> 'Tình trạng sổ',
									'data'	=> $health_flag,
						        	'value'	=> $controller_data ? $controller_data['db_health_health_flag'] : '',
								], [
									'type'	=> 'textarea',
									'name'	=> 'health_description',
									'label'	=> 'Diễn giải',
						        	'value'	=> $controller_data ? $controller_data['db_health_health_description'] : '',
								], [
						        	'type'	=> 'hidden',
						        	'name'	=> 'health_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['db_health_health_trash_flag'] : 0,
				        		],
							]
						]
					]
				], [//chế độ thai sản
					'type'	=> 'tab',
					'label'	=> 'Chế độ thai sản',
					'items'	=> [
						[
							'type'	=> 'line',
							'items'	=> [
								[
									'type'	=> 'hidden',
									'name'	=> 'maternity_id',
						        	'value'	=> $controller_data ? $controller_data['employee_id'] : '',
								], [
						        	'type'	=> 'combo',
						        	'name'	=> 'maternity_type',
					        		'label'	=> 'Chế độ thai sản',
						        	'emptyText'	=> 'Maternity type',
						        	'value'	=> $controller_data ? $controller_data['db_maternity_maternity_type'] : '',
						        	'data'	=> $maternity_type,
				        		], [
						        	'type'	=> 'date',
						        	'name'	=> 'maternity_begin',
					        		'label'	=> 'Bắt đầu chế độ thai sản',
						        	'emptyText'	=> 'Maternity begin',
						        	'value'	=> $controller_data ? $controller_data['db_maternity_maternity_begin'] : '',
				        		], [
						        	'type'	=> 'date',
						        	'name'	=> 'maternity_end',
					        		'label'	=> 'Kết thúc chế độ thai sản',
						        	'emptyText'	=> 'Maternity end',
						        	'value'	=> $controller_data ? $controller_data['db_maternity_maternity_end'] : '',
				        		], [
									'type'	=> 'textarea',
									'name'	=> 'maternity_description',
									'label'	=> 'Diễn giải',
						        	'value'	=> $controller_data ? $controller_data['db_maternity_maternity_description'] : '',
								], [
						        	'type'	=> 'hidden',
						        	'name'	=> 'maternity_trash_flag',
						        	'value'	=> $controller_data ? $controller_data['db_maternity_maternity_trash_flag'] : 0,
				        		],

							]
						]
					]
				]
	        ];
	    } else if (in_array($this->action, ['management', 'trash'])) {
			$department = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'department'));
			$position = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'position'));
			$job = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'job'));
			$gender = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'gender'));
			$employee_type = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'employee_type'));
			$working_status = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'working_status'));

	    	$this->fancygrid = [
	        	'columns'	=> [
			        [
		        		'type'	=> 'select',
		        		'locked'=> 1,
		        		'width'	=> 35,
		        	], [
			        	'type'	=> 'combo',
			        	'name'	=> 'department',
		        		'label'	=> 'Bộ phận',
			        	'emptyText'	=> 'Department',
			        	'data'	=> $department,
		        		'locked'=> 1,
		        		'width'	=> 100,
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'employee_id',
		        		'label'	=> 'Mã số NV',
			        	'emptyText'	=> 'Employee ID',
		        		'locked'=> 1,
						'width'	=> 80,
			        ], [
			        	'type'	=> 'string',
			        	'name'	=> 'full_name',
		        		'label'	=> 'Họ và tên',
			        	'emptyText'	=> 'Full name',
		        		'locked'=> 1,
			        ], [
			        	'type'	=> 'string',
			        	'name'	=> 'employee_old_id',
		        		'label'	=> 'Mã số cũ',
			        	'emptyText'	=> 'Employee Old ID',
						'width'	=> 80,
			        ], [
			        	'type'	=> 'date',
			        	'name'	=> 'birth_date',
		        		'label'	=> 'Ngày sinh',
			        	'emptyText'	=> 'Birth date',
			        ], [
			        	'type'	=> 'string',
			        	'name'	=> 'birth_place',
		        		'label'	=> 'Nơi sinh',
			        	'emptyText'	=> 'Birth place',
			        ], [
			        	'type'	=> 'date',
			        	'name'	=> 'join_date',
		        		'label'	=> 'Ngày vào làm',
			        	'emptyText'	=> 'Join date',
			        ], [
			        	'type'	=> 'combo',
			        	'name'	=> 'position',
		        		'label'	=> 'Vị trí làm việc',
			        	'emptyText'	=> 'Position',
			        	'data'	=> $position,
	        		], [
			        	'type'	=> 'combo',
			        	'name'	=> 'job',
		        		'label'	=> 'Công việc',
			        	'emptyText'	=> 'Job',
			        	'data'	=> $job,
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'phone',
		        		'label'	=> 'Điện thoại cá nhân',
			        	'emptyText'	=> 'Phone',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'permanent_address',
		        		'label'	=> 'Địa chỉ thường trú',
			        	'emptyText'	=> 'Permanent Address',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'present_address',
		        		'label'	=> 'Địa chỉ tạm trú',
			        	'emptyText'	=> 'Present Address',
	        		], [
			        	'type'	=> 'combo',
			        	'name'	=> 'gender',
		        		'label'	=> 'Giới tính',
			        	'emptyText'	=> 'Gender',
			        	'data'	=> $gender,
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'person_id',
		        		'label'	=> 'Số CMND',
			        	'emptyText'	=> 'Person ID',
	        		], [
			        	'type'	=> 'date',
			        	'name'	=> 'person_issued_date',
		        		'label'	=> 'Ngày cấp',
			        	'emptyText'	=> 'Issued date',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'person_place',
		        		'label'	=> 'Nơi cấp',
			        	'emptyText'	=> 'Person place',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'ethenic',
		        		'label'	=> 'Dân tộc',
			        	'emptyText'	=> 'Ethenic',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'education',
		        		'label'	=> 'Trình độ học vấn',
			        	'emptyText'	=> 'Education',
	        		], [
			        	'type'	=> 'combo',
			        	'name'	=> 'employee_type',
		        		'label'	=> 'Loại nhân viên',
			        	'emptyText'	=> 'Employee type',
			        	'data'	=> $employee_type,
	        		], [
			        	'type'	=> 'combo',
			        	'name'	=> 'working_status',
		        		'label'	=> 'Trạng thái làm việc',
			        	'emptyText'	=> 'Working status',
			        	'data'	=> $working_status,
	        		], [
			        	'type'	=> 'date',
			        	'name'	=> 'left_date',
		        		'label'	=> 'Ngày nghỉ việc',
			        	'emptyText'	=> 'Left date',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'account',
		        		'label'	=> 'Số tài khoản',
			        	'emptyText'	=> 'Account',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'pit_id',
		        		'label'	=> 'Mã số thuế cá nhân',
			        	'emptyText'	=> 'Tax id',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'email',
		        		'label'	=> 'Thư điện tử',
			        	'emptyText'	=> 'Email',
			        	'vtype'	=> 'email',
	        		], [
			        	'type'	=> 'string',
			        	'name'	=> 'office_phone',
		        		'label'	=> 'Điện thoại nội bộ',
			        	'emptyText'	=> 'Office phone',
	        		],
				],
			];
	    }
        $this->sql_database = [
        	'db_maternity' => [
                'maternity_id'  => 'Emp ID',
                'maternity_type'=> 'Maternity',
                'maternity_begin'=> 'Start Date',
                'maternity_end' => 'End Date',
                'maternity_description'   => '',
                'maternity_trash_flag'   => '',
            ],
	        'db_health' => [
	            'health_id'     => 'Health No',
	            'health_place'  => 'Health Place',
	            'health_date'   => 'Health Date',
	            'health_flag'   => 'Health Flag',
	            'health_description'    => '',
	            'health_trash_flag'   => '',
	        ],
	        'db_social' => [
	            'social_id'     => 'Social No',
	            'social_place'  => 'Social Place',
	            'social_date'   => 'Social Date',
	            'social_flag'   => 'Social Flag',
	            'social_description'=> '',
	            'social_trash_flag' => '',
	        ],
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
	        'db_employee' => [
	            'department'    => 'Department',
	            'employee_id'   => 'Emp ID',
	            'employee_old_id' => 'Old ID',
	            'full_name'     => 'Full Name',
	            'birth_date'    => 'Birth Date',
	            'birth_place'   => 'Birth Place',
	            'join_date'     => 'Join Date',
	            'job'           => 'Job',
	            'position'      => 'Position',
	            'phone'         => 'Phone',
	            'permanent_address' => 'Permanent Address',
	            'present_address'   => 'Present Address',
	            'gender'        => 'Sex',
	            'person_id'     => 'Person ID',
	            'person_issued_date'   => 'Issued Date',
	            'person_place'  => 'Person Place',
	            'ethenic'       => 'Ethenic',
	            'education'     => 'Education',
	            'employee_type' => 'Employee Type',
	            'working_status'=> 'Status',
	            'left_date'     => 'Left Date',
	            'account'       => 'Account',
	            'pit_id'        => 'PIT No',
	            'email'         => 'Email',
	            'office_phone'  => 'Office Phone',
	            'contract_id'   => 'Contract No',
	            'social_id'     => 'Social No',
	            'health_id'     => 'Health No',
	            'employee_trash_flag'   => '',
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

        if ($this->method == 'PUT' && isset($_GET['employee_id'])) {

        	$id = $_GET['employee_id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Employee Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_employee', ['employee_trash_flag' => true], 'employee_id="'.$id.'"');
        	}
        } else if ($this->method == 'PUT' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_employee', ['employee_trash_flag' => true], 'id="'.$id.'"');
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

        if ($this->method == 'PUT' && isset($_GET['employee_id'])) {

        	$id = $_GET['employee_id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Employee Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_employee', ['employee_trash_flag' => false], 'employee_id="'.$id.'"');
        	}
        } else if ($this->method == 'PUT' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$query_flag = $this->model->API->edit_row('db_employee', ['employee_trash_flag' => false], 'id="'.$id.'"');
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

        if ($this->method == 'DELETE' && isset($_GET['employee_id'])) {

        	$id = $_GET['employee_id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Employee Id không đúng';
        	} else {
        		$query_flag = $this->model->API->delete_row('db_employee', 'employee_id="'.$id.'"');
        	}
        } else if ($this->method == 'DELETE' && isset($_GET['id'])) {

        	$id = $_GET['id'];

        	if ($id == '' || $id == null) {
                $data['response']['message'] = 'Id không đúng';
        	} else {
        		$query_flag = $this->model->API->delete_row('db_employee', 'id="'.$id.'"');
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
			if (isset($_GET['employee_id']))
				header('location:?c=Employee&a=edit&employee_id='.$_GET['employee_id']);
			else if (isset($_GET['id']))
				header('location:?c=Employee&a=edit&id='.$_GET['id']);

			$data = [
		        'page_title'=> 'Thêm mới hồ sơ',
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
			if (!isset($_GET['employee_id']) && !isset($_GET['id']))
				header('location:?c=Employee&a=new');

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

    public function importDataAction() { //done
    	$data = [
			'page_title'	=> 'Tải lên danh sách nhân viên',
			'page_id'		=> $this->page_id,
		];

		$this->view->load('main', $data);
    }

    public function uploadImageAction() { //done
    	$data = [
			'page_title'	=> 'Tải lên ảnh thẻ nhân viên',
			'page_id'		=> $this->page_id,
		];

		$this->view->load('main', $data);
    }


	public function managementAction() { //done
		$data = [
	        'page_title'=> 'Danh sách nhân viên',
	        'page_id'   => $this->page_id,

	        'page'	=> 'pages/Controller-management.php',
	        'controller'	=> $this->controller,

	        'fancygrid'	=> $this->fancygrid,
	    ];

		$this->view->load('main', $data);
	}

	public function trashAction() { //done
		$data = [
	        'page_title'=> 'Danh sách nhân viên đã xóa',
	        'page_id'   => $this->page_id,

	        'page'	=> 'pages/Controller-trash.php',
	        'controller'	=> $this->controller,

	        'fancygrid'	=> $this->fancygrid,
	    ];

		$this->view->load('main', $data);
	}

	public function getDataAction() { //done
		$this->view->load('json', $this->getData());
	}
	private function getData() { //done
		$join_contract = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_contract', ['id']),
			'from'	=> 'prefix_db_contract',
		]; $contract_as = str_replace(DB_PREFIX, '', $join_contract['from']);

		$join_health = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_health', ['id']),
			'from'	=> 'prefix_db_health',
		]; $health_as = str_replace(DB_PREFIX, '', $join_health['from']);

		$join_social = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_social', ['id']),
			'from'	=> 'prefix_db_social',
		]; $social_as = str_replace(DB_PREFIX, '', $join_social['from']);

		$join_maternity = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_maternity', ['id']),
			'from'	=> 'prefix_db_maternity',
		]; $maternity_as = str_replace(DB_PREFIX, '', $join_maternity['from']);

		$join_print_card = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_print_card', ['id']),
			'count'	=> 'print_count',
			'from'	=> 'prefix_db_print_card',
			'where'	=> [
				'relations'	=> 'AND',
				'operator' => '=',
				[
					'print_card_trash_flag'	=> '0',
				]
			],
			'group'	=> [
				'DESC'	=> true,
				[
					'print_card_id',
				]
			],			
		]; $print_card_as = str_replace(DB_PREFIX, '', $join_print_card['from']);

		$params = [
			'type'	=> 'select',
			'fields'=> [],
			'from'	=> 'prefix_db_employee',
			'join'	=> [
				[
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_contract),
					'as'	=> $contract_as,
					'on'	=> [
						'operator' => '=',
						[
							$contract_as.'_contract_id'	=> 'prefix_db_employee.contract_id',
						]
					],
				], [
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_health),
					'as'	=> $health_as,
					'on'	=> [
						'operator' => '=',
						[
							$health_as.'_health_id'	=> 'health_id',
						]
					],
				], [
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_social),
					'as'	=> $social_as,
					'on'	=> [
						'operator' => '=',
						[
							$social_as.'_social_id'	=> 'social_id',
						]
					],
				], [
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_print_card),
					'as'	=> $print_card_as,
					'on'	=> [
						'operator' => '=',
						[
							$print_card_as.'_print_card_id'	=> 'employee_id',
						]
					],
				], [
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_maternity),
					'as'	=> $maternity_as,
					'on'	=> [
						'operator' => '=',
						[
							$maternity_as.'_maternity_id'	=> 'employee_id',
						]
					],
				]
			],
			'where'	=> [
				'relations'	=> 'AND',
				'operator' => '=',
				[
					//'working_status'	=> 'Active',
					'employee_trash_flag'	=> isset($_GET['trash']),
				]
			],
		];

		if (isset($_GET['employee_id'])) {
			$params['where'][0]['employee_id'] = $_GET['employee_id'];
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


	// print card
    public function printCardAction() { //done
    	if ($this->method == 'POST') {
			$this->model->API->new_row('db_print_card', $_POST);
		} else {
			$department = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'department'));
			$position = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'position'));
			$job = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'job'));
			$gender = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'gender'));
			$employee_type = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'employee_type'));
			$working_status = parse_fancygrid_enum($this->model->API->get_index('db_employee', 'working_status'));

			$maternity_type = parse_fancygrid_enum($this->model->API->get_index('db_maternity', 'maternity_type'));

			$data = [
		        'page_title'=> 'In thẻ nhân viên',
		        'page_id'   => $this->page_id,

		        'fancygrid'	=> [
		        	'columns' => [
			        	[
			        		'type'	=> 'select',
			        		'width'	=> 35,
			        		'locked'=> 1,
			        	], [
							'type'	=> 'combo',
			        		'index'	=> 'hasImage',
			        		'title' => 'Có ảnh',
							'data' 	=> ['Yes', 'No'],
			        		'width' => 45,
			        		'locked'=> 1,
			        	], [
			        		'type'	=> 'image',
			        		'index'	=> 'employeeImage',
			        		'title' => 'Image',
			        		'cls'	=> 'employeeImage',
			        		'width' => 70,
			        		'locked'=> 1,
			        		'filter'=> 0,
			        	], [
							'type'	=> 'combo',
			        		'index'	=> 'department',
							'title'	=> 'Bộ phận',
							'data' 	=> $department,
			        		'locked'=> 1,
			        		'width'	=> 100,
			        	], [
							'type'	=> 'number',
			        		'index'	=> 'employee_id',
							'title'	=> 'Mã NV',
							'width'	=> 70,
			        		'locked'=> 1,
			        	], [
							'type'	=> 'string',
			        		'index'	=> 'full_name',
			        		'title'	=> 'Họ và tên',
			        		'locked'=> 1,
			        	], [
							'type'	=> 'number',
			        		'index'	=> 'print_count',
			        		'title'	=> 'Đã in',
							'width'	=> 50,
			        	], [
							'type'	=> 'string',
			        		'index'	=> 'db_print_card_print_date',
			        		'title'	=> 'Lần in cuối',
							'width'	=> 90,
			        	], [
							'type'	=> 'string',
			        		'index'	=> 'db_print_card_print_description',
			        		'title'	=> 'Diễn giải',
							'width'	=> 90,
			        	], [
							'type'	=> 'number',
			        		'index'	=> 'employee_old_id',
							'title'	=> 'Mã NV cũ',
							'width'	=> 70,
			        	], [
							'type'	=> 'date',
			        		'index'	=> 'birth_date',
							'title'	=> 'Ngày sinh',
			        	], [
							'type'	=> 'date',
			        		'index'	=> 'join_date',
							'title'	=> 'Ngày vào làm',
			        	], [
							'type'	=> 'combo',
							'index'	=> 'position',
							'title'	=> 'Vị trí làm việc',
							'data'	=> $position,
						], [
							'type'	=> 'combo',
							'index'	=> 'job_name',
							'title'	=> 'Công việc',
							'data'	=> $job,
						], [
							'type'	=> 'combo',
							'index'	=> 'gender',
							'title'	=> 'Giới tính',
							'data'	=> $gender,
							'width'	=> 70,
						], [
							'type'	=> 'string',
							'index'	=> 'person_id',
							'title'	=> 'Số CMND',
						], [
							'type'	=> 'combo',
							'index'	=> 'employee_type',
							'title'	=> 'Loại nhân viên',
							'data'	=> $employee_type,
						], [
							'type'	=> 'combo',
							'index'	=> 'working_status',
							'title'	=> 'Trạng thái làm việc',
							'data'	=> $working_status,
						], [
							'type'	=> 'combo',
							'index'	=> 'db_maternity_maternity_type',
							'title'	=> 'Chế độ thai sản',
							'data'	=> $maternity_type,
						]
		        	],
		        ],
		    ];

			$this->view->load('main', $data);
		}
    }
	public function getPrintListAction() { //done
		$join_maternity = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_maternity', ['id']),
			'from'	=> 'prefix_db_maternity',
		]; $maternity_as = str_replace(DB_PREFIX, '', $join_maternity['from']);

		$join_print_card = [
			'type'	=> 'select',
			'fields'=> $this->model->API->get_fields('db_print_card', ['id']),
			'count'	=> 'print_count',
			'from'	=> 'prefix_db_print_card',
			'where'	=> [
				'relations'	=> 'AND',
				'operator' => '=',
				[
					'print_card_trash_flag'	=> '0',
				]
			],
			'group'	=> [
				'DESC'	=> true,
				[
					'print_card_id',
				]
			],			
		]; $print_card_as = str_replace(DB_PREFIX, '', $join_print_card['from']);

		$params = [
			'type'	=> 'select',
			'fields'=> [],
			'from'	=> 'prefix_db_employee',
			'join'	=> [
				[
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_print_card),
					'as'	=> $print_card_as,
					'on'	=> [
						'operator' => '=',
						[
							$print_card_as.'_print_card_id'	=> 'employee_id',
						]
					],
				], [
					'type'	=> 'left',
					'table'	=> $this->model->API->to_sql($join_maternity),
					'as'	=> $maternity_as,
					'on'	=> [
						'operator' => '=',
						[
							$maternity_as.'_maternity_id'	=> 'employee_id',
						]
					],
				]
			],
			'where'	=> [
				'relations'	=> 'AND',
				'operator' => '=',
				[
					//'working_status'	=> 'Active',
					'employee_trash_flag'	=> isset($_GET['trash']),
				]
			],
		];

		if (isset($_GET['employee_id'])) {
			$params['where'][0]['employee_id'] = $_GET['employee_id'];
		} else if (isset($_GET['id'])) {			
			$params['where'][0]['id'] = $_GET['id'];
		};

		// add image col
		$tmp_data = $this->model->API->get_table($params);

		foreach ($tmp_data as $row_index => $row_data) {
			$image_file = UPLOAD_DIR.'/employeeImage/'.$tmp_data[$row_index]['employee_id'].'.png';
			$tmp_data[$row_index]['hasImage'] = file_exists($image_file) ? 'Yes' : 'No';
			$tmp_data[$row_index]['employeeImage'] = '?c=EmployeeImage&a=view&id='.$tmp_data[$row_index]['employee_id'];
		}

		$data = [
			'response' => [
				'success'	=> true,
				'data'		=> $tmp_data,
			],
		];

		$this->view->load('json', $data);
	}
}