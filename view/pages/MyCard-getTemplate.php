<?php
	$template = dirname(__FILE__).'/MyCard-getTemplate-'.strtolower($_GET['t']).'.php';
	if(file_exists($template))
		include_once $template;
	else
		echo 'Template not found';
?>