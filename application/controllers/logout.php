<?php

class Logout extends CI_Controller {

	public function __construct() {
		parent::__construct();		
		$this->load->model('global_m');
	}
	
	public function index() {
            $this->common->clear_session();
//		$this->global_m->del_nopeg_maintain($this->session->userdata('s_nopeg'));
//		//unset session
//		$this->session->unset_userdata('profile');
//		$this->session->unset_userdata('userid');
//		$this->session->unset_userdata('username');
//		$this->session->unset_userdata('s_nopeg');
//		$this->session->unset_userdata('s_man');
//		$this->session->unset_userdata('fullname');
//		$this->session->sess_destroy();
		redirect ('login', 'refresh');
	}

}