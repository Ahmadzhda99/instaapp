<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('m_post');
		$this->load->model('m_like');
		$this->load->model('m_comment');
	}

	public function index() {
		$data['posts'] = $this->m_post->get_posts();
		$this->load->view('post/index', $data);
	}
}
