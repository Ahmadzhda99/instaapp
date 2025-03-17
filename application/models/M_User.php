<?php

class M_User extends CI_Model
{
	public function register($data)
	{
		return $this->db->insert('users', $data);
	}

	public function login($username, $password)
	{
		$this->db->where('username', $username);
		$user = $this->db->get('users')->row_array();
		if ($user && password_verify($password, $user['password'])) {
			return $user;
		}
		return false;
	}
}

?>