<?php
class EmployeeImage_Controller extends Base_Controller
{
	public function viewAction() {
		$this->helper->Load('image');

		$data = [
			'compress' => isset($_GET['compress']) ? $_GET['compress'] : 'true',
			'employee_id' => isset($_GET['id']) ? $_GET['id'] : '',
		];
		$this->view->Load('raw', $data);
	}
}