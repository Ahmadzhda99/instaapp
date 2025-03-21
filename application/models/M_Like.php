<?php
class M_Like extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function toggle_like($user_id, $post_id) {
		if ($this->user_liked($user_id, $post_id)) {
			$this->db->where('user_id', $user_id)
			->where('post_id', $post_id)
			->delete('likes');
		} else {
			$data = [
				'user_id' => $user_id,
				'post_id' => $post_id
			];
			$this->db->insert('likes', $data);
		}
	}

	public function remove_like($user_id, $post_id) {
		$this->db->delete('likes', ['user_id' => $user_id, 'post_id' => $post_id]);
	}

	public function get_likes_count($post_id) {
		return $this->db->where('post_id', $post_id)->count_all_results('likes');
	}

	public function user_liked($user_id, $post_id) {
		return $this->db->get_where('likes', ['user_id' => $user_id, 'post_id' => $post_id])->num_rows() > 0;
	}
}
?>