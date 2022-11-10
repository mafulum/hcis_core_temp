<?php

//$_HCPATH = "../../";
//require($_HCPATH."module/session/session.php");

class Dashboard extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_m');
    }

    public function index() {
        if ($this->session->userdata('pernr')) {
            $data = $this->dashboard_m->admin_dshb();
            $this->load->view('main', $data);
        } else
            redirect('login', 'refresh');
    }

    public function manager() {
        if ($this->session->userdata('pernr')) {
            $data = $this->dashboard_m->admin_mgr();
            $this->load->view('main', $data);
        } else
            redirect('login', 'refresh');
    }


}