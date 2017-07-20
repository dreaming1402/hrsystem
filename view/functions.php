<?php
include_once 'inc/lang.php';
include_once 'inc/helper.php';

if (!function_exists('SetupSite')) {
	function SetupSite() {		
		// Icon package
		RegisterScript('font-awesome', 'css', TEMPLATE_DIR . '/plugins/font-awesome/4.7.0/css/font-awesome.min.css');

		// Main font
		RegisterScript('font-roboto-regular', 'css', TEMPLATE_DIR . '/fonts/roboto/roboto_regular/stylesheet.css');
		RegisterScript('font-roboto-black', 'css', TEMPLATE_DIR . '/fonts/roboto/roboto_black/stylesheet.css');
		RegisterScript('font-roboto-condensed', 'css', TEMPLATE_DIR . '/fonts/roboto/roboto_condensed/stylesheet.css');
		RegisterScript('font-roboto-light', 'css', TEMPLATE_DIR . '/fonts/roboto/roboto_light/stylesheet.css');

		// Enviroment
		RegisterScript('jquery', 'js', TEMPLATE_DIR . '/plugins/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js');
		RegisterScript('bootstrap', 'js', TEMPLATE_DIR . '/plugins/AdminLTE/bootstrap/js/bootstrap.min.js');
		RegisterScript('adminlte', 'js', TEMPLATE_DIR . '/plugins/AdminLTE/dist/js/app.min.js');

		// Template
		RegisterScript('datepicker', 'css', TEMPLATE_DIR . '/plugins/AdminLTE/plugins/datepicker/datepicker3.css');
		RegisterScript('datepicker', 'js', TEMPLATE_DIR . '/plugins/AdminLTE/plugins/datepicker/bootstrap-datepicker.js');
		RegisterScript('select2', 'css', TEMPLATE_DIR . '/plugins/AdminLTE/plugins/select2/select2.min.css');
		RegisterScript('select2', 'js', TEMPLATE_DIR . '/plugins/AdminLTE/plugins/select2/select2.full.min.js');
		RegisterScript('bootstrap-fileinput', 'css', TEMPLATE_DIR . '/plugins/bootstrap-fileinput/v4.3.6/css/fileinput.min.css');
		RegisterScript('bootstrap-fileinput', 'js', TEMPLATE_DIR . '/plugins/bootstrap-fileinput/v4.3.6/js/fileinput.min.js');
		RegisterScript('bootstrap', 'css', TEMPLATE_DIR . '/plugins/AdminLTE/bootstrap/css/bootstrap.min.css');
		RegisterScript('adminlte', 'css', TEMPLATE_DIR . '/plugins/AdminLTE/dist/css/AdminLTE.min.css');
		RegisterScript('adminlte-skin', 'css', TEMPLATE_DIR . '/plugins/AdminLTE/dist/css/skins/_all-skins.min.css');

		RegisterScript(APP_PREFIX, 'css', TEMPLATE_DIR . '/css/style.css');
		RegisterScript(APP_PREFIX, 'js', TEMPLATE_DIR . '/js/javascript.js');
	}; SetupSite();
}