<?php
//header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
if (!isset($response)) {
	$response = [
		'success'	=> true,
		'message'	=> 'Bad request',
		'data'		=> []
	];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
exit;