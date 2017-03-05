<?php

defined('BASEPATH') or exit('Not Allow direct');

/**
* HOME
*/
class Home extends CI_Controller
{
	
	public function index()
	{
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$data['id'] = $session_data['id'];
			$data['username'] = $session_data['username'];
			$data['fullname'] = $session_data['fullname'];

			$this->load->view('user/home', $data);
		}
		else{
			redirect('user', 'refresh');
		}
	}
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();

		redirect(site_url('user'), 'refresh');
	}
}