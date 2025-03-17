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

	public function upload() {
		header('Content-Type: application/json');
		$user_id = $this->session->userdata('user_id');

		$config['upload_path']   = './uploads/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size']      = 2048;
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('image')) {
			echo json_encode(['error' => $this->upload->display_errors()]);
		} else {
			$uploadData = $this->upload->data();
			$data = [
				'user_id' => $user_id,
				'caption' => $this->input->post('caption'),
				'image'   => $uploadData['file_name']
			];
			$this->m_post->insert_post($data);
			echo json_encode($data);
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
		header('Content-Type: application/json');
		if (!$this->session->userdata('user_id')) {
			echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
			return;
		}

		$comment_text = $this->input->post('comment', true);
		if (empty($comment_text)) {
			echo json_encode(['status' => 'error', 'message' => 'Komentar tidak boleh kosong.']);
			return;
		}

		$data = [
			'user_id' => $this->session->userdata('user_id'),
			'post_id' => $post_id,
			'comment' => $comment_text
		];

		$comment_id = $this->m_comment->add_comment($data);

		if ($comment_id) {
			$username = $this->session->userdata('username');
			echo json_encode([
				'status' => 'success',
				'username' => $username,
				'comment' => $comment_text
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan komentar.']);
		}
	}

}
