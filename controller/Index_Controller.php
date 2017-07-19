<?php
class Index_Controller extends Base_Controller
{
	public function indexAction() {
    	header('Location: '.BASE_URL.'.php?c=PrintCard&a=index');
	}
}