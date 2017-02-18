<?php
//header("Access-Control-Allow-Origin: *");
if (!isset($page))
	$page = 'pages/'.$_GET['c'].'-'.$_GET['a'].'.php';
include_once $page; ?>