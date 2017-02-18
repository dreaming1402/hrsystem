<?php
class MyCard_Controller extends Base_Controller
{
    public function getTemplateAction() { $this->view->load('raw'); }
    public function getBackTemplateAction() { $this->view->load('raw'); }
}