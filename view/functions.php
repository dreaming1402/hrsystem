<?php
include_once 'inc/lang.php';
include_once 'inc/helper.php';

if (!function_exists('setup_site')) {
	$GLOBALS['app_configs']['template'] = [
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

		'rasterizehtml'	=> [
			'js' => [TEMPLATE_DIR . '/plugins/RasterizeHTML/rasterizeHTML.allinone.js']
		],
		'jszip'	=> [
			'js' => [TEMPLATE_DIR . '/plugins/JsZip/jszip.min.js']
		],
		'filesaver'	=> [
			'js' => [TEMPLATE_DIR . '/plugins/FileSaver/FileSaver.js']
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
}