<?php
class File_Controller extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_GET['t']))
            $this->error(500);
    }

    public function uploadAction() {
        $data = [
            'response'  => [
                'success'   => false,
                'message'   => 'Tải lên không thành công',
                'data'      => [],
            ],
        ];

        if (isset($_FILES['file'])) {
            $this->helper->load('Upload');

            $rename = '/'.$_GET['t'].'/'.$_FILES['file']['name'];
            $upload_file = upload_file($_FILES['file'], $rename);

            if ($upload_file) {
                $data = [
                    'response'  => [
                        'success'   => true,
                        'message'   => 'Đã tải lên thành công',
                        'data'      => [
                            'file'   => $upload_file,
                        ],
                    ],
                ];
            }
        }

        //mod for project
        if ($data['response']['success']) {
            $this->model->load('API');
            $this->library->load('PHPExcel');
            $this->model->load('ExcelDatabase');

            switch (strtolower($_GET['t'])) {
                case 'employeedatabase':
                    $sql_data_fields = [
                        'db_maternity' => [
                            'maternity_id'  => 'Emp ID',
                            'maternity_type'=> 'Maternity',
                            'maternity_begin'=> 'Start Date',
                            'maternity_end' => 'End Date',
                            'maternity_description'   => '',
                            'maternity_trash_flag'   => '',
                        ],
                        'db_health' => [
                            'health_id'     => 'Health No',
                            'health_place'  => 'Health Place',
                            'health_date'   => 'Health Date',
                            'health_flag'   => 'Health Flag',
                            'health_description'    => '',
                            'health_trash_flag'   => '',
                        ],
                        'db_social' => [
                            'social_id'     => 'Social No',
                            'social_place'  => 'Social Place',
                            'social_date'   => 'Social Date',
                            'social_flag'   => 'Social Flag',
                            'social_description'=> '',
                            'social_trash_flag' => '',
                        ],
                        'db_contract' => [
                            'contract_id'   => 'Contract No',
                            'contract_type' => 'Contract Type',
                            'contract_basic_salary' => 'Basic Salary',
                            'contract_responsibitity_salary'    => 'Responsibitity Allowance',
                            'contract_insurance_salary' => 'Insurance Salary',
                            'contract_begin'    => 'Begin Contract',
                            'contract_end'  => 'End Contract',
                            'contract_description'  => '',
                            'contract_trash_flag'   => ''
                        ],
                        'db_employee' => [
                            'department'    => 'Department',
                            'employee_id'   => 'Emp ID',
                            'employee_old_id' => 'Old ID',
                            'full_name'     => 'Full Name',
                            'birth_date'    => 'Birth Date',
                            'birth_place'   => 'Birth Place',
                            'join_date'     => 'Join Date',
                            'job'           => 'Job',
                            'position'      => 'Position',
                            'phone'         => 'Phone',
                            'permanent_address' => 'Permanent Address',
                            'present_address'   => 'Present Address',
                            'gender'        => 'Sex',
                            'person_id'     => 'Person ID',
                            'person_issued_date'   => 'Issued Date',
                            'person_place'  => 'Person Place',
                            'ethenic'       => 'Ethenic',
                            'education'     => 'Education',
                            'employee_type' => 'Employee Type',
                            'working_status'=> 'Status',
                            'left_date'     => 'Left Date',
                            'account'       => 'Account',
                            'pit_id'        => 'PIT No',
                            'email'         => 'Email',
                            'office_phone'  => 'Office Phone',
                            'contract_id'   => 'Contract No',
                            'social_id'     => 'Social No',
                            'health_id'     => 'Health No',
                            'employee_trash_flag'   => '',
                        ],
                    ];

                    $this->model->ExcelDatabase->connect($data['response']['data']['file']);
                    $excelDatabase = $this->model->ExcelDatabase->select();

                    // checking excel data
                    $data_ok = true;
                    $excel_data_fields = [];
                    foreach ($sql_data_fields as $table_name => $table_fields) {
                        foreach ($table_fields as $sql_field => $excel_field) {
                            $field_keys = array_keys($excelDatabase['data_header'], $excel_field);
                            if (!isset($field_keys[0]))
                                $data_ok = false;
                            else
                                $excel_data_fields[$table_name][$sql_field] = $field_keys[0];
                        }
                    }

                    $logs = [];
                    if ($data_ok) {
                        foreach ($excel_data_fields as $table_name => $table_fields) {
                            $prefix = str_replace('db_', '', $table_name);
                            $logs[$table_name] = [
                                'update'    => [],
                                'insert'    => [],
                                'error'     => [],
                            ];

                            foreach ($excelDatabase['data'] as $row => $row_data) {
                                $tmp = [];
                                foreach ($table_fields as $key => $value) {
                                    // sua loi dinh dang
                                    if (strpos($key, '_date') !== false ||
                                        strpos($key, '_begin') !== false ||
                                        strpos($key, '_end') !== false) {// fix date format

                                        //option2
                                        $date = explode('/', $row_data[$value]);
                                        if (sizeof($date) >= 3) {
                                            // fix vietnamese input (dd/mm/yyyy) to SQL format (Y-m-d)
                                            $date_format = $date[2].'/'.$date[1].'/'.$date[0];
                                            $tmp[$key] = $date_format;
                                        } else {
                                            $tmp[$key] = null;
                                        }
                                    } else
                                        $tmp[$key] = $row_data[$value];
                                }

                                if (isset($tmp[$prefix.'_id']) && ($tmp[$prefix.'_id'] != '' || $tmp[$prefix.'_id'] != null)) {
                                    
                                    //option1
                                    $insert_flag = $this->model->API->new_row($table_name, $tmp);
                                    if ($insert_flag) {
                                        array_push($logs[$table_name]['insert'], $tmp);
                                    } else {
                                        $update_flag = $this->model->API->edit_row($table_name, $tmp, $prefix.'_id = \''.$tmp[$prefix.'_id'].'\'');
                                        if ($update_flag > 0) {
                                            array_push($logs[$table_name]['update'], $tmp);
                                        } else {
                                            array_push($logs[$table_name]['error'], $tmp);                                            
                                        }
                                    }

                                    //option2
                                   /* $insert_flag = json_decode($this->send('POST', 'http://localhost/pidn/hrsystem.php?c=employee&a=new', $tmp), true) ;

                                    if ($insert_flag['success']) {
                                        array_push($logs[$table_name]['insert'], $tmp);
                                    } else {
                                        $update_flag = json_decode($this->send('PUT', 'http://localhost/pidn/hrsystem.php?c=employee&a=edit', $tmp), true);
                                        if ($update_flag['success']) {
                                            array_push($logs[$table_name]['update'], $tmp);
                                        } else {
                                            array_push($logs[$table_name]['error'], $tmp);                                            
                                        }
                                    }*/
                                }
                            }
                        }
                        $data['response']['success'] = true;
                        $data['response']['message'] = 'Đã hoàn tất update';
                    } else {
                        $data['response']['success'] = false;
                        $data['response']['message'] = 'Dữ liệu không đúng';
                    }

                    $data['response']['data']['logs'] = $logs;
                break;

                case 'printdatabase':
                    $sql_data_fields = [
                        'db_print_card' => [
                            'print_card_id' => 'Print ID',
                            'print_date'    => 'Print Date',
                            'print_description' => 'Description',
                            'print_card_trash_flag' => '',

                            'employee_department'    => 'Department',
                            'employee_id'   => 'Emp ID',
                            'employee_full_name'     => 'Full Name',
                            'employee_position'      => 'Position',
                            'employee_type' => 'Employee Type',
                            'employee_contract_id'  => 'Contract No',

                            'maternity_type'=> 'Maternity',
                            'maternity_begin'=> 'Start Date',
                            'maternity_end' => 'End Date',
                        ],
                    ];

                    $this->model->ExcelDatabase->connect($data['response']['data']['file']);
                    $excelDatabase = $this->model->ExcelDatabase->select();

                    // checking excel data
                    $data_ok = true;
                    $excel_data_fields = [];
                    foreach ($sql_data_fields as $table_name => $table_fields) {
                        foreach ($table_fields as $sql_field => $excel_field) {
                            $field_keys = array_keys($excelDatabase['data_header'], $excel_field);
                            if (!isset($field_keys[0]))
                                $data_ok = false;
                            else
                                $excel_data_fields[$table_name][$sql_field] = $field_keys[0];
                        }
                    }

                    $logs = [];
                    if ($data_ok) {
                        foreach ($excel_data_fields as $table_name => $table_fields) {
                            $prefix = str_replace('db_', '', $table_name);
                            $logs[$table_name] = [
                                'update'    => [],
                                'insert'    => [],
                                'error'     => [],
                            ];

                            foreach ($excelDatabase['data'] as $row => $row_data) {
                                $tmp = [];
                                foreach ($table_fields as $key => $value) {
                                    // sua loi dinh dang
                                    if (strpos($key, '_date') !== false ||
                                        strpos($key, '_begin') !== false ||
                                        strpos($key, '_end') !== false) {// fix date format

                                        $date = explode('/', $row_data[$value]);

                                        if (sizeof($date) >= 3) { //option 2
                                            // fix vietnamese input (dd/mm/yyyy) to SQL format (Y-m-d)
                                            $date_format = $date[2].'/'.$date[1].'/'.$date[0];
                                            $tmp[$key] = $date_format;
                                        } else {
                                            $tmp[$key] = null;
                                        }
                                    } else
                                        $tmp[$key] = $row_data[$value];
                                }

                                if (isset($tmp[$prefix.'_id']) && ($tmp[$prefix.'_id'] != '' || $tmp[$prefix.'_id'] != null)) {
                                    
                                    //option1
                                    $insert_flag = $this->model->API->new_row($table_name, $tmp);
                                    if ($insert_flag) {
                                        array_push($logs[$table_name]['insert'], $tmp);
                                    } else {
                                        $update_flag = $this->model->API->edit_row($table_name, $tmp, $prefix.'_id = \''.$tmp[$prefix.'_id'].'\'');
                                        if ($update_flag > 0) {
                                            array_push($logs[$table_name]['update'], $tmp);
                                        } else {
                                            array_push($logs[$table_name]['error'], $tmp);                                            
                                        }
                                    }

                                    //option2
                                   /* $insert_flag = json_decode($this->send('POST', 'http://localhost/pidn/hrsystem.php?c=employee&a=new', $tmp), true) ;

                                    if ($insert_flag['success']) {
                                        array_push($logs[$table_name]['insert'], $tmp);
                                    } else {
                                        $update_flag = json_decode($this->send('PUT', 'http://localhost/pidn/hrsystem.php?c=employee&a=edit', $tmp), true);
                                        if ($update_flag['success']) {
                                            array_push($logs[$table_name]['update'], $tmp);
                                        } else {
                                            array_push($logs[$table_name]['error'], $tmp);                                            
                                        }
                                    }*/
                                }
                            }
                        }
                        $data['response']['success'] = true;
                        $data['response']['message'] = 'Đã hoàn tất update';
                    } else {
                        $data['response']['success'] = false;
                        $data['response']['message'] = 'Dữ liệu không đúng';
                    }

                    $data['response']['data']['logs'] = $logs;
                break;

                case 'employeeimage':
                $compress_image = UPLOAD_DIR.'/'.$_GET['t'].'/compress/'.$_FILES['file']['name'];
                if (file_exists($compress_image)) {
                    unlink($compress_image);
                }
                break;
            }
        }

        $this->view->load('json', $data);
    }

    public function deleteAction() {
        $data = [
            'response'  => [
                'success'   => false,
                'message'   => 'Xóa file không thành công',
                'data'      => [],
            ],
        ];

        if (isset($_GET['id'])) {
            $file = $_GET['id'];//UPLOAD_DIR.'/'.$_GET['t'].'/'.$_GET['id'].'.png';

            //mod for project
            switch (strtolower($_GET['t'])) {
                case 'employeedatabase':
                    $file = UPLOAD_DIR.'/'.$_GET['t'].'/'.$_GET['id'].'.xlsx';
                    break;
                
                case 'employeeimage':
                    $file = UPLOAD_DIR.'/'.$_GET['t'].'/'.$_GET['id'].'.png';
                    break;
            }

            if (file_exists($file)) {
                $delete_file = unlink($file);
                if ($_GET['t'] == 'employeeImage') {
                    $compress_image = UPLOAD_DIR.'/'.$_GET['t'].'/compress/'.$_GET['id'].'.png';
                    if (file_exists($compress_image)) {
                        unlink($compress_image);
                    }
                }

                if ($delete_file) {
                    $data = [
                        'response'  => [
                            'success'   => true,
                            'message'   => 'Đã xóa',
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
                        'message'   => 'Không tìm thấy file cần xóa',
                        'data'      => [
                            'file'   => $file,
                        ],
                    ],
                ];
            }
            
        }

        $this->view->load('json', $data);
    }

    public function send($method, $server, $data) {
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
}