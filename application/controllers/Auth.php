<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		$this->load->model('m_user');
	}

	public function register()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('auth/register');
		} else {
			$data = [
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
			];
			$this->m_user->register($data);
			redirect('auth/login');
		}
	}

	public function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('auth/login');
		} else {
			$user = $this->m_user->login($this->input->post('username'), $this->input->post('password'));
			if ($user) {
				$this->session->set_userdata(['user_id' => $user['id'], 'username' => $user['username']]);
				redirect('post');
			} else {
				redirect('auth/login');
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect();
	}
}