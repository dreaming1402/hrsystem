<?php // File tiêu chuẩn
class File_Controller extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['t']))
            Error(500);
    }

    public function uploadAction() { // done
        if ($this->method != 'POST')
            Error(503);

        // Define
        $data = [
            'response'  => [
                'success'   => false,
                'message'   => 'Upload file missing',
                'data'      => 0,
            ],
        ];

        if (isset($_FILES['file'])) {
            $this->library->Load('Uploader');

            $file_name = $_FILES['file']['name'];
            if ($_POST['rename'] != 'false')
                $file_name = $_POST['rename'];

            $des_file = UPLOAD_DIR.'/'.$_GET['t'].'/'.$file_name;

            $upload_flag = $this->library->Uploader->UploadFile($_FILES['file'], $des_file, $_POST['overwrite'] == 'true');
            if (strtolower($_GET['t']) == 'employeeimage')
                $this->uploadEmployeeImage($file_name);

            if ($upload_flag == -2) {
                $data = [
                    'response' => [
                        'success'   => false,
                        'message'   => 'File empty',
                        'data'      => $upload_flag,
                    ],
                ];
            } else if ($upload_flag == -1) {
               $data = [
                    'response' => [
                        'success'   => false,
                        'message'   => 'Duplicate file',
                        'data'      => $upload_flag,
                    ],
                ];
            } else {
                $data = [
                    'response' => [
                        'success'   => true,
                        'message'   => 'Upload success',
                        'data'      => $upload_flag,
                    ],
                ];
            }
        }

        $this->view->Load('json', $data);
    }

    public function deleteAction() { // done
        if ($this->method != 'DELETE')
            Error(503);

        $data = [
            'response'  => [
                'success'   => false,
                'message'   => 'File not found',
                'data'      => [],
            ],
        ];

        if (isset($_GET['id'])&&isset($_GET['ext'])) {
            $this->library->Load('Uploader');

            $file_name = $_GET['id'].'.'.$_GET['ext'];
            $des_file = UPLOAD_DIR.'/'.$_GET['t'].'/'.$file_name;
            $delete_flag = $this->library->Uploader->DeleteFile($des_file);

            if ($delete_flag == -2) {
                $data = [
                    'response'  => [
                        'success'   => false,
                        'message'   => 'File empty',
                        'data'      => $delete_flag,
                    ],
                ];
            } else if ($delete_flag == -1) {
                $data = [
                    'response'  => [
                        'success'   => false,
                        'message'   => 'File not found',
                        'data'      => $delete_flag,
                    ],
                ];
            } else {
                $data = [
                    'response'  => [
                        'success'   => true,
                        'message'   => 'Delete success',
                        'data'      => $delete_flag,
                    ],
                ];

                switch (strtolower($_GET['t'])) {
                    case 'employeeimage':
                        $this->uploadEmployeeImage($file_name);
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }            
        }

        $this->view->Load('json', $data);
    }

    public function exportExcelAction() { // done
        if ($this->method == 'POST') {
            // Define
            $data = [
                'response'  => [
                    'success'   => false,
                    'message'   => 'Export failed',
                    'data'      => [],
                ],
            ];

            // Data mẫu
            /*$data = [
                'data'  => [
                    ['16050095', 'Thái Minh Long'],
                    ['16050096', 'Thái Minh Long2']
                ],
                'header' => [
                    ['Date', date('Y-m-d')],
                    ['Emp ID', 'Họ và tên'],
                ],
            ];*/

            $data = $_POST;
            $save_as_file = str_replace(' ', '', $_GET['t']).'.xlsx';

            $this->library->Load('PHPExcel');
            $export_flag = $this->library->PHPExcel->CreateFile($data, $save_as_file, false);

            if ($export_flag == -1) {
                $data['response']['success'] = false;
                $data['response']['message'] = 'Không có dữ liệu để xuất';
            } else {
                $data['response']['success'] = true;
                $data['response']['message'] = 'Đã xuất file thành công';
                $data['response']['data'] = $export_flag;
            }

            $this->view->Load('json', $data);
        } else {
            // Nếu tồn tại tên file thì mới cho tải
            if (isset($_GET['f'])) {
                $file = $_GET['f'];
                if (file_exists($file)) {
                    // Download file
                    /*header('Content-type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.$file.'"');*/

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename='.$file);
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: '.filesize($file));

                    readfile($file);

                    // Xóa file tránh dữ liệu quá nhiều
                    unlink($file);
                } else {
                    Error(404);
                }
            } else {
                Error(404);
            }
        }        
    }

    public function importExcelAction() { // done
        $this->library->Load('PHPExcel');

        // Define
        $data = [
            'response'  => [
                'success'   => false,
                'message'   => 'Read file failed',
                'data'      => [],
            ],
        ];

        $file = $_GET['f'];

        $file_data = $this->library->PHPExcel->ReadFile($file, 2);

        if ($file_data == -2) {
            $data['response']['success'] = false;
            $data['response']['message'] = 'File format incorrect';
        } else if ($file_data == -1) {            
            $data['response']['success'] = false;
            $data['response']['message'] = 'File missing';
        } else {
            $data['response']['success'] = true;
            $data['response']['message'] = 'Dữ liệu đã load xong';
            $data['response']['data'] = $file_data;

            $this->model->Load('API');
            $table_name = strtolower($_GET['t']);
            
            if (!isset($file_data['header']))
                $data['response']['message'] = 'Dữ liệu không đúng';
            else {
                $insert_logs = [];
                foreach ($file_data['data'] as $row) {
                    $insert_data = [];
                    $response_data = [];
                    foreach ($row as $index => $value) {
                        $field_name = $file_data['header'][0][$index];
                        $response_data[$field_name] = $value;
                        // Sửa lỗi định dạng ngày tháng nếu có
                        if (strpos($field_name, '_date') ||
                            strpos($field_name, '_begin') ||
                            strpos($field_name, '_end')) {
                            if (is_numeric($value)) {
                                // Format theo định dạng date của excel
                                $value = date('Y-m-d H:i:s', ($value-25569)*86400);
                            } else {
                                // Format theo người dùng nhập dạng text
                            }
                        }

                        // Bỏ qua nếu field = id (cột tự đếm)
                        if ($field_name == 'id')
                            continue;
                        
                        $insert_data[$field_name] = $value;
                    }

                    // Query và lưu kết quả vào response_data
                    $insert_flag = $this->model->API->InsertRow($table_name, $insert_data);
                    $response_data['insert_flag'] = !$insert_flag?false:true;

                    array_push($insert_logs, $response_data);

                    $data['response']['message'] = 'Import thành công';
                    $data['response']['data'] = $insert_logs;
                };
            };
        };

        $this->view->Load('json', $data);
    }

    public function send($method, $server, $data) { // Hàm dùng để request API bằng php
        $method = strtoupper($method);

        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
            ]
        ];

        $context  = stream_context_create($opts);

        if ($method == 'POST') {
            $result = file_get_contents($server, false, $context);
        } else {
            $result = file_get_contents($server.http_build_query($data), false, $context);
        }
        return $result;
    }

    private function uploadEmployeeImage($_filename) {
        $compress_image = UPLOAD_DIR.'/'.$_GET['t'].'/compress/'.$_filename;
        if (file_exists($compress_image)) {
            unlink($compress_image);
        }
    }
}