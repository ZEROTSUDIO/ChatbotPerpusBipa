<?php
defined('BASEPATH') or exit('No direct script access allowed');
class test extends CI_Controller
{
	public function __construct()
		{
			parent::__construct();
			$this->load->helper('responses');
			$this->load->model('Chat_model', 'chatModel'); // Memuat model Chat_model
			$this->load->model('m_account');			
		}
		
	public function index(){
		$this->load->view('test3');
	}
}