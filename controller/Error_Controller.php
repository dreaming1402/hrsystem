<?php
class Error_Controller extends Base_Controller
{
	public function e404Action() {
		$data = [
			'page_id' 	=> 'e404',
			'page_title'=> '404 page not found',
			'page'		=> 'pages/Error-e404.php',
		];
		$_GET['c'] = '404 Error Page';
		$_GET['a'] = '';

		$this->view->load('main', $data);
	}

	public function e500Action() {
		$data = [
			'page_id' 	=> 'e500',
			'page_title'=> '500 error',
			'page'		=> 'pages/Error-e500.php',
		];
		$_GET['c'] = '500 Error Page';
		$_GET['a'] = '';

		$this->view->load('main', $data);
	}
}