<?php
class Index_Controller extends Base_Controller
{
	public function testAction() {
		Error(404);
	}

	public function indexAction() {
		$this->library->load('PHPExcel');
        $this->model->load('ExcelDatabase');
        $database = $this->model->ExcelDatabase->select([], [1]);

        $active_count = 0;
        $resign_count = 0;
        $none_image_count = 0;
        $none_card_count = 0;
        $pregnant_count = 0;
        $has_baby_count = 0;
        foreach ($database['data'] as $row_index => $row_value) {
        	if (strtolower($row_value[37]) == 'active')
        		$active_count++;

        	if (strtolower($row_value[37]) == 'resign')
        		$resign_count++;

        	if (strtolower($row_value[37]) == 'active') {
        		if (!file_exists(UPLOAD_DIR . '/employeeImage/' . $row_value[2] . '.png'))
	        		$none_image_count++;

	        	if (sizeof(glob(UPLOAD_DIR .'/employeeCard/*/*/' . $row_value[2] . '.png', GLOB_BRACE)) <= 0)
	        		$none_card_count++;

	        	if (strtolower($row_value[44]) == 'pregnancy')
        			$pregnant_count++;
	        	elseif (strtolower($row_value[44]) == 'has baby')
	        		$has_baby_count++;
        	}
        	
        }

        $plastic_card_count = sizeof(glob(UPLOAD_DIR .'/employeeCard/??months/*/????????.png', GLOB_BRACE));
        $paper_card_count = sizeof(glob(UPLOAD_DIR .'/employeeCard/probation/*/????????.png', GLOB_BRACE));

		$data = [
			'page'		=> 'pages/index.php',
			'page_title'=> 'Welcome to HRsystem application',

			'info_box' => [
				0 => [
					'bg_color'	=> 'bg-aqua',
					'icon'	=> 'fa-database',
					'text' 	=> 'Cơ sở dữ liệu',
					'number'=> $database['data_info'],
				],
				1 => [
					'bg_color'	=> 'bg-yellow',
					'icon'	=> 'fa-users',
					'text' 	=> 'Tổng records',
					'number'=> sizeof($database['data']),
				],
				2 => [
					'bg_color'	=> 'bg-green',
					'icon'	=> 'fa-check-square-o',
					'text' 	=> 'Đang làm việc',
					'number'=> $active_count,
				],
				3 => [
					'bg_color'	=> 'bg-red',
					'icon'	=> 'fa-user-times',
					'text' 	=> 'Đã nghỉ việc',
					'number'=> $resign_count,
				]
			],

			'box_widget' => [
				0 => [
					'img'	=> TEMPLATE_DIR . '/assets/warning.png',
					'bg_color' => 'bg-aqua',
					'username' => 'Các mục còn thiếu',
					'desc'		=> '2 mục',
					'stacked'	=> [
						0 => [
							'text' => 'Ảnh thẻ còn thiếu',
							'num'  => $none_image_count,
						],
						1 => [
							'text' => 'Chưa in',
							'num'  => $none_card_count,
						],

					]

				],
				1 => [
					'img'	=> TEMPLATE_DIR . '/assets/plastic-card.png',
					'bg_color' => 'bg-green',
					'username' => 'Số lượng thẻ đã in',
					'desc'		=> $plastic_card_count + $paper_card_count . ' cái',
					'stacked'	=> [
						0 => [
							'text' => 'Thẻ nhựa',
							'num'  => $plastic_card_count,
						],
						1 => [
							'text' => 'Thẻ giấy',
							'num'  => $paper_card_count,
						],

					]

				],
				2 => [
					'img'	=> TEMPLATE_DIR . '/assets/paper-card.png',
					'bg_color' => 'bg-red',
					'username' => 'Mang thai + con nhỏ',
					'desc'		=> $pregnant_count + $has_baby_count . ' người',
					'stacked'	=> [
						0 => [
							'text' => 'Mang thai',
							'num'  => $pregnant_count,
						],
						1 => [
							'text' => 'Có con nhỏ',
							'num'  => $has_baby_count,
						],

					]

				],
				
			]
		];
		$_GET['c'] = 'Dashboard';
		$_GET['a'] = 'HRsystem';

		$this->view->load('main', $data);
	}
}