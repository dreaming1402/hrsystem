<?php
include_once 'inc/lang.php';
include_once 'inc/helper.php';

if (!function_exists('setup_site')) {
	$GLOBALS['template'] = [
		'font-awesome' => [
			'css' => [TEMPLATE_DIR . '/plugins/font-awesome/4.7.0/css/font-awesome.min.css']
		],
		'font-roboto' => [
			'css' => [
				'roboto-black'		=> TEMPLATE_DIR . '/fonts/roboto/roboto_black/stylesheet.css',
				'roboto-condensed'	=> TEMPLATE_DIR . '/fonts/roboto/roboto_condensed/stylesheet.css',
				'roboto-regular'	=> TEMPLATE_DIR . '/fonts/roboto/roboto_regular/stylesheet.css',
				'roboto-light'		=> TEMPLATE_DIR . '/fonts/roboto/roboto_light/stylesheet.css',
			]
		],

		'adminlte' => [
			'css' => [
				'datepicker'	=> TEMPLATE_DIR . '/plugins/AdminLTE/plugins/datepicker/datepicker3.css',
				'select2'		=> TEMPLATE_DIR . '/plugins/AdminLTE/plugins/select2/select2.min.css',
				'bootstrap-fileinput'	=> TEMPLATE_DIR . '/plugins/bootstrap-fileinput/v4.3.6/css/fileinput.min.css',

				'bootstrap' 	=> TEMPLATE_DIR . '/plugins/AdminLTE/bootstrap/css/bootstrap.min.css',
				'adminlte'		=> TEMPLATE_DIR . '/plugins/AdminLTE/dist/css/AdminLTE.min.css',
				'adminlte-skin'	=> TEMPLATE_DIR . '/plugins/AdminLTE/dist/css/skins/_all-skins.min.css',
			],

			'js' => [
				'jquery'	=> TEMPLATE_DIR . '/plugins/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js',
				'bootstrap'	=> TEMPLATE_DIR . '/plugins/AdminLTE/bootstrap/js/bootstrap.min.js',
				'adminlte' 	=> TEMPLATE_DIR . '/plugins/AdminLTE/dist/js/app.min.js',

				'datepicker'=> TEMPLATE_DIR . '/plugins/AdminLTE/plugins/datepicker/bootstrap-datepicker.js',
				'select2'	=> TEMPLATE_DIR . '/plugins/AdminLTE/plugins/select2/select2.full.min.js',
				'bootstrap-fileinput'	=> TEMPLATE_DIR . '/plugins/bootstrap-fileinput/v4.3.6/js/fileinput.min.js',
			]
		],

		'datatables' => [
			'css' 	=> [TEMPLATE_DIR . '/plugins/DataTables/datatables.min.css'],
			'js'	=> [TEMPLATE_DIR . '/plugins/DataTables/datatables.min.js']
		],

		'fancygrid'	=> [
			'css'	=> [TEMPLATE_DIR . '/plugins/fancygrid/1.6.0/fancy.min.css'],
			'js'	=> [TEMPLATE_DIR . '/plugins/fancygrid/1.6.0/fancy.full.min.js'],
		],

		'fine-uploader' => [
			'css' => [
				TEMPLATE_DIR . '/plugins/fine-uploader/fine-uploader-new.min.css',
				TEMPLATE_DIR . '/plugins/fine-uploader/fine-uploader-gallery.min.css',
			],
			'js' => [
				'app' => TEMPLATE_DIR . '/plugins/fine-uploader/jquery.fine-uploader.min.js',
			],
			'html' => [				
				'default'	=> TEMPLATE_DIR . '/plugins/fine-uploader/templates/default.html',
				'gallery'	=> TEMPLATE_DIR . '/plugins/fine-uploader/templates/gallery.html',
				'simple'	=> TEMPLATE_DIR . '/plugins/fine-uploader/templates/simple-thumbnails.html',
			]
		],

		'rasterizehtml'	=> [
			'js' => [TEMPLATE_DIR . '/plugins/RasterizeHTML/rasterizeHTML.allinone.js']
		],
		'jszip'	=> [
			'js' => [TEMPLATE_DIR . '/plugins/JsZip/jszip.min.js']
		],
		'filesaver'	=> [
			'js' => [TEMPLATE_DIR . '/plugins/FileSaver/FileSaver.js']
		],
		'upload'	=> [
			'css' => [TEMPLATE_DIR . '/plugins/Upload/upload.css'],
			'js' => [TEMPLATE_DIR . '/plugins/Upload/upload.js'],
		],
		'mycard'	=> [
			'css'	=> [
				'mycard'				=> TEMPLATE_DIR . '/plugins/MyCard/mycard.css',
				'mycard-employee'		=> TEMPLATE_DIR . '/plugins/MyCard/mycard-employee.css',
				'mycard-visitor'		=> TEMPLATE_DIR . '/plugins/MyCard/mycard-visitor.css',
				'mycard-constructor'	=> TEMPLATE_DIR . '/plugins/MyCard/mycard-constructor.css',
			],
			'js'	=> [
				'mycard' => TEMPLATE_DIR . '/plugins/MyCard/mycard.js',
			],
			'html'	=> [
				'mycard'				=> TEMPLATE_DIR . '/plugins/MyCard/mycard.css',
				'mycard-employee'		=> TEMPLATE_DIR . '/plugins/MyCard/mycard-employee.css',
				'mycard-visitor'		=> TEMPLATE_DIR . '/plugins/MyCard/mycard-visitor.css',
				'mycard-constructor'	=> TEMPLATE_DIR . '/plugins/MyCard/mycard-constructor.css',
			],
		],

		'app' => [
			'css'	=> ['style'	=> TEMPLATE_DIR . '/css/style.css'],
			'js'	=> ['javascript' => TEMPLATE_DIR . '/js/javascript.js']
		],
	];

	$GLOBALS['app_default']['sex'] = [
		'icon'	=> 'fa-transgender-alt',
		'name'	=> [
			'vi'	=> 'Giới tính',
			'en'	=> 'Sex',
		],
		'data'	=> [
			'male'	=> [
				'icon'	=> 'fa-male',
				'name'	=> [
					'vi'	=> 'Nam',
					'en'	=> 'Male',
				],
			],
			'female'	=> [
				'icon'	=> 'fa-female',
				'name'	=> [
					'vi'	=> 'Nữ',
					'en'	=> 'Female',
				],
			],
			'other'	=> [
				'icon'	=> 'fa-genderless',
				'name'	=> [
					'vi'	=> 'Khác',
					'en'	=> 'Other',
				],
			],
		],
	];

	$GLOBALS['app_default']['working_status'] = [
		'icon'	=> 'fa-sign-out',
		'name'	=> [
			'vi'	=> 'Trạng thái làm việc',
			'en'	=> 'Working status',
		],
		'data'	=> [
			'active'	=> [
				'icon'	=> '',
				'name'	=> [
					'vi'	=> 'Đang làm việc',
					'en'	=> 'Active',
				],
			],
			'resign'	=> [
				'icon'	=> '',
				'name'	=> [
					'vi'	=> 'Đã nghỉ việc',
					'en'	=> 'Resign',
				],
			]
		]
	];

	$GLOBALS['app_default']['employee_type'] = [
		'icon'	=> 'fa-briefcase',
		'name'	=> [
			'vi'	=> 'Loại nhân viên',
			'en'	=> 'Employee type',
		],
		'data' => [
			'staff'	=> [
				'icon'	=> 'fa-user',
				'name'	=> [
					'vi'	=> 'Nhân viên văn phòng',
					'en'	=> 'Staff',
				],
			],
			'worker'	=> [
				'icon'	=> 'fa-user',
				'name'	=> [
					'vi'	=> 'Công nhân sản xuất',
					'en'	=> 'Worker',
				],
			],
		],
	];

	$GLOBALS['app_default']['social_flag'] = [
		'icon'	=> 'fa-wheelchair',
		'name'	=> [
			'vi'	=> 'Trạng thái sổ',
			'en'	=> 'Social book status',
		],
		'data'	=> [
			'yes'	=> [
				'icon'	=> 'fa-check-square-o',
				'name'	=> [
					'vi'	=> 'Đã nộp',
					'en'	=> 'Yes'
				],
			],
			'no'	=> [
				'icon'	=> 'fa-square-o',
				'name'	=> [
					'vi'	=> 'Chưa nộp',
					'en'	=> 'No'
				],
			],
		]
	];

	$GLOBALS['app_default']['health_flag'] = [
		'icon'	=> 'fa-heartbeat',
		'name'	=> [
			'vi'	=> 'Trạng thái sổ',
			'en'	=> 'Health book status',
		],
		'data'	=> [
			'yes'	=> [
				'icon'	=> 'fa-check-square-o',
				'name'	=> [
					'vi'	=> 'Đã nộp',
					'en'	=> 'Yes'
				],
			],
			'no'	=> [
				'icon'	=> 'fa-square-o',
				'name'	=> [
					'vi'	=> 'Chưa nộp',
					'en'	=> 'No'
				],
			],
		]
	];

	$GLOBALS['app_default']['maternity_type'] = [
		'icon'	=> 'fa-child',
		'name'	=> [
			'vi'	=> 'Loại thai sản',
			'en'	=> 'Maternity type',
		],
		'data'	=> [
			'none'	=> [
				'icon'	=> '',
				'name'	=> [
					'vi'	=> 'Không',
					'en'	=> 'None'
				],
			],
			'pregnancy'	=> [
				'icon'	=> '',
				'name'	=> [
					'vi'	=> 'Đang mang thai',
					'en'	=> 'Pregnancy'
				],
			],
			'hasbaby'	=> [
				'icon'	=> '',
				'name'	=> [
					'vi'	=> 'Có con nhỏ dưới 12 tháng',
					'en'	=> 'Has baby',
				],
			],
		]
	];

	$GLOBALS['app_default']['department'] = [
		'icon'	=> 'fa-users',
		'name'	=> [
			'vi'	=> 'Bộ phận',
			'en'	=> 'Department',
		],
		'data'	=> [
			0	=> [
				'child_of'	=> 0,
				'icon'		=> 'building-o',
				'name'		=> [
					'vi' => 'CÔNG TY TNHH PI VINA DANANG',
					'en' => 'PI VINA DANANG CO., LTD'
				],
			],
			'it'	=> [
				'child_of'	=> 0,
				'icon'		=> 'fa-laptop',
				'name'	=> [
					'vi'	=> 'IT',
					'en'	=> 'Information Technology',
				],			
			],
			'hr'	=> [
				'child_of' => 0,
				'icon'		=> 'fa-users',
				'name'	=> [
					'vi'	=> 'Nhân sự',
					'en'	=> 'Human Resource',
				],
			],
		],
	];

	$GLOBALS['app_default']['job'] = [
		'icon'	=> 'fa-briefcase',
		'name'	=> [
			'vi'	=> 'Công việc',
			'en'	=> 'Job',
		],
		'data'	=> [
			'it'	=> [
				'icon'		=> 'fa-laptop',
				'name'	=> [
					'vi'	=> 'Chuyên viên máy tính',
					'en'	=> 'IT staff',
				],			
			],
		],
	];

	$GLOBALS['app_default']['position'] = [
		'icon'	=> 'fa-map-marker',
		'name'	=> [
			'vi'	=> 'Vị trí làm việc',
			'en'	=> 'Position',
		],
		'data'	=> [
			'mainoffice'	=> [
				'icon'	=> 'fa-circle',
				'name'	=> [
					'vi'	=> 'Văn phòng chính',
					'en'	=> 'Main office',
				],
			],
			'productoffice'	=> [
				'icon'	=> 'fa-circle',
				'name'	=> [
					'vi'	=> 'Văn phòng sản xuất',
					'en'	=> 'Product office',
				],
			],
			'finishingoffice'	=> [
				'icon'	=> 'fa-circle',
				'name'	=> [
					'vi'	=> 'Văn phòng hoàn thành',
					'en'	=> 'Finishing office',
				],
			],
		],
	];
}