<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}
		$this->load->model('m_post');
        $this->load->model('m_like');
        $this->load->model('m_comment');
	}

	public function index() {
		$data['posts'] = $this->m_post->get_posts();
		$this->load->view('post/index', $data);
	}

	public function create() {
		if (!$this->session->userdata('user_id')) redirect('auth/login');

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'jpg|png';
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			$data = [
				'user_id' => $this->session->userdata('user_id'),
				'image' => $this->upload->data('file_name'),
				'caption' => $this->input->post('caption')
			];
			$this->m_post->create_post($data);
			redirect('post');
		} else {
			echo "Upload gagal";
		}
	}

	public function like($post_id) {
		if ($this->m_like->user_liked($this->session->userdata('user_id'), $post_id)) {
			$this->m_like->remove_like($this->session->userdata('user_id'), $post_id);
		} else {
			$this->m_like->add_like($this->session->userdata('user_id'), $post_id);
		}
		redirect('post');
	}

	public function comment($post_id) {
		$data = [
			'user_id' => $this->session->userdata('user_id'),
			'post_id' => $post_id,
			'comment' => $this->input->post('comment')
		];
		$this->m_comment->add_comment($data);
		redirect('post');
	}
}
