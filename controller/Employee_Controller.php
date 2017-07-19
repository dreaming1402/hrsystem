<?php
class Employee_Controller extends Base_Controller
{
	private $dataTable = 'db_employee';

	public function uploadImageAction() { // done
		$this->library->Load('Uploader');

    	$data = [
			'page_title'	=> 'Tải lên ảnh thẻ nhân viên',
		];

		$this->view->Load('main', $data);
    }
}