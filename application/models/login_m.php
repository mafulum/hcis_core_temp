<?php

class Login_m extends CI_Model {

	public function __construct() {
		parent::__construct();		
	}
	
	public function index() {
		$data['base_url'] = $this->config->item('base_url');
		return $data;
	}

}