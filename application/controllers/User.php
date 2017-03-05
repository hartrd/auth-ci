<?php
defined('BASEPATH') OR exit('No direct script alloewed');

Class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->model('user_m');
	}

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

	public function register()
	{		
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|matches[password]');
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');

		if ($this->form_validation->run() === false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('user/register');
			
		} else {			
			// set variables from the form
			$username = $this->input->post('username');
			$email    = $this->input->post('email');
			$password = md5($this->input->post('password'));
			$fullname = $this->input->post('fullname');
			
			if ($this->user_m->register($username, $email, $password, $fullname)) {
				// user creation ok
				$this->session->set_flashdata('success_msg', 'Success Created');
				redirect(site_url('user/register'));
				
			} else {
				
				// send error to the view
				$this->load->view('user/register');
				
			}
			
		}		
	}
}