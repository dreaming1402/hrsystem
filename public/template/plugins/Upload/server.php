<?php
function upload_file($_FILE, $rename = null) {
    $result = false;
    $upload_dir = 'uploads';

    $tmp_file = $_FILE['tmp_name'];
    if ($rename == '' || $rename == null) $rename = $_FILE['name'];
    $destination_file = $upload_dir.'/'.$rename;

    $upload_folder = dirname($destination_file);
    if (!is_dir($upload_folder))
        mkdir($upload_folder, 0777, true);

    $upload_flag = move_uploaded_file($tmp_file, $destination_file);

    if ($upload_flag) {
        $result = $destination_file;
    }

    return $result;
}

$data = [
    'response'  => [
        'success'   => false,
        'message'   => 'Upload could not completed',
        'data'      => [],
    ],
];

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
	if (isset($_FILES['file'])) {
        $rename = '/'.$_FILES['file']['name'];
        $upload_file = upload_file($_FILES['file'], $rename);

        if ($upload_file) {
            $data = [
                'response'  => [
                    'success'   => true,
                    'message'   => 'Upload successful',
                    'data'      => [
                        'file'   => $upload_file,
                    ],
                ],
            ];
        }
    }
} else if ($method == 'DELETE') {
	if (isset($_GET['id'])) {
        $file = $_GET['id'];

        if (file_exists($file)) {
            $delete_file = unlink($file);

            if ($delete_file) {
                $data = [
                    'response'  => [
                        'success'   => true,
                        'message'   => 'Delete successful',
                        'data'      => [
                            'file'   => $file,
                        ],
                    ],
                ];
            }
        } else {
            $data = [
                'response'  => [
                    'success'   => false,
                    'message'   => 'File not found',
                    'data'      => [
                        'file'   => $file,
                    ],
                ],
            ];
        }
        
    }
} else {
	$data['response']['message'] = 'Method Not Allowed';
}

$response = $data['response'];
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
exit();