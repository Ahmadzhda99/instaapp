<?php
class M_Post extends CI_Model
{
	public function insert_post($data) {
		return $this->db->insert('posts', $data);
	}

	public function get_posts()
	{
		$this->db->join('users', 'users.id = posts.user_id');
		return $this->db->get('posts')->result_array();
	}
}

?>