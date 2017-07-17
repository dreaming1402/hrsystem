<?php
//header("Access-Control-Allow-Origin: *");
if (!isset($page))
	$page = 'pages/'.$controller.'-'.$action.'.php';
include_once $page; ?>