<?php if ( ! defined('PATH_SYSTEM')) die ('Bad requested!');

class Base_Controller extends MVC_Controller
{
    public $error;

    public function __construct() 
    {
        parent::__construct();
        $this->load_before();
    }
    
    // Hàm này cho phép load nội dung trước khi in ra trình duyệt
    public function load_before()
    {
        // gọi đến file functions
        $function_file = PATH_APPLICATION . '/view/functions.php';
        if (file_exists($function_file))
            include_once $function_file;
    }
    
    // Hàm này cho phép load nội dung sau khi in ra trình duyệt
    public function load_after()
    {
    }

    public function error($code, $message = '')
    {
        header('Location:?c=error&a=e'.$code);
    }
    
    // Hàm hủy này có nhiệm vụ show nội dung của view, lúc này các controller
    // không cần gọi đến $this->view->show nữa
    public function __destruct() 
    {

        $this->view->show();
        $this->load_after();
    }
}