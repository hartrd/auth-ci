<?php
defined('BASEPATH') OR exit('No direct script alloewed');

Class User extends CI_Controller {
	public function index()
	{
		if ($this->session->userdata('logged_in'))
		{
			redirect(site_url('home'), 'refresh');
		}
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_login');
		if($this->form_validation->run() == false){
			$this->load->view('user/login_view');
		}
		else{
			redirect(site_url('home'), 'refresh');
		}
	}

	function check_login($password)
	{
		$username = $this->input->post('username');
		$result = $this->user->check_auth($username, $password);

		if ($result){
			$sess_arr = array();
			foreach ($result as $row) {
				/*print_r($row);*/
				$sess_arr = $array_name = array('id' => $row->id, 'username' => $row->username, 'fullname' => $row->fullname);
				$this->session->set_userdata('logged_in', $sess_arr);
			}
			return true;
		}
		else{
			$this->form_validation->set_message('check_login', 'Invalid Username or Password');
			return false;
		}
	}
}