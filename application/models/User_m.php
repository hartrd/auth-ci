<?php
defined("BASEPATH") or exit('not allowed direct access');
/**
* USER Class
*/
class User_m extends CI_Model
{	
	public function check_auth($username, $password)
	{
		$this->db->select('id, username, fullname');
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$this->db->limit(1);

		$query = $this->db->get();
		if ($query->num_rows() === 1) {
			return $query->result();
		}
		else {
			return false;
		}

	}
}