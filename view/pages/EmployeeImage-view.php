<?php 
$default_file = TEMPLATE_DIR . '/assets/no-image-found.png';
$upload_file = UPLOAD_DIR . '/employeeImage/' . $employee_id . '.png';
$compress_file = UPLOAD_DIR . '/employeeImage/compress/' . $employee_id . '.png';

header('Content-Type: image/png');
if ($compress == 'true') {
	if (file_exists($compress_file)) {
		echo file_get_contents($compress_file);
	}
	elseif (file_exists($upload_file)) {	
		echo file_get_contents(image_compress($upload_file, $compress_file, 80));
	}
	else {
		echo file_get_contents($default_file);
	}
} else {
	if (file_exists($upload_file)) {
		echo file_get_contents($upload_file);
	}
	else {
		echo file_get_contents($default_file);
	}
}

exit;