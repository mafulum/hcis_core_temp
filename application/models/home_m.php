<?php

class Home_m extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$data['base_url'] = $this->config->item('base_url');
		$data['view'] = 'home';
		
		return $data;
	}
	
	public function changepass($userid) {
		$data = $this->index();
		$data['view'] = 'changepass';
		
		return $data;
	}
	
	public function savenewpass($userid) {		
		//update pass
		$newpass = $this->db->escape_str($this->input->post('newpass'));
		$passnewenc =  md5($newpass);
				
		$strSQL = "UPDATE os_user SET password = '$passnewenc', pass_text = '$newpass' WHERE id_user = '$userid'";
		$this->db->trans_begin();
		$this->db->query($strSQL);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$data = $this->index();
			$data['view'] = 'message';
			$data['message'] = 'Gagal merubah password';
		} else {
			$this->db->trans_commit();
			$data = $this->index();
			$data['view'] = 'message';
			$data['message'] = 'Password baru berhasil disimpan';
		} 
		
		return $data;
	}

}