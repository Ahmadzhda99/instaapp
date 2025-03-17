<?php
class M_Comment extends CI_Model {
    public function add_comment($data) {
        return $this->db->insert('comments', $data);
    }

    public function get_comments($post_id) {
        $this->db->join('users', 'users.id = comments.user_id');
        return $this->db->get_where('comments', ['post_id' => $post_id])->result_array();
    }
}
?>