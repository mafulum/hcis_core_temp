<?php

//$_HCPATH = "../../";
//require($_HCPATH."module/session/session.php");

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_m');
        $this->load->model('home_m');
        $this->load->model('global_m');
    }

    public function index() {
        die("HERE");
        if ($this->session->userdata('id')) {
            redirect('dashboard', 'refresh');
        } else {
            if ($this->input->post()) {
                $this->form_validation->set_rules('username', 'username', 'trim|required|callback_login_check');
                if ($this->form_validation->run() == FALSE && !$this->session->userdata('id')) {
                    $data = $this->login_m->index();
                    $this->load->view('login', $data);
                } else //goto main
                    redirect('dashboard', 'refresh');
            } else {
                $data = $this->login_m->index();
                $this->load->view('login', $data);
            }
        }
    }

    public function login_check() {
        $sUsername = $this->db->escape_str(trim($this->input->post('username')));
        if ($this->input->post()) {
            $qUserCheck = $this->db->query("SELECT * FROM tm_user WHERE username = '" . $sUsername . "'");
            if ($qUserCheck->num_rows() > 0) {
                $row = $qUserCheck->row_array();
                $password = md5($this->db->escape_str($this->input->post('password')));
                if ($row['isActive'] == 0) {
                    $this->form_validation->set_message('login_check', 'User Locked');
                    return FALSE;
                } else if ($password != $row['password']) {
                    $this->form_validation->set_message('login_check', 'Password Salah');
                    return FALSE;
                } else {
                    $this->common->save_session($row);
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message('login_check', 'Akses Ditolak');
                return FALSE;
            }
        }
    }

    function change_password() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('oPass', 'oPass', 'trim|required');
            $this->form_validation->set_rules('nPass', 'nPass', 'trim|required');
            $this->form_validation->set_rules('rPass', 'rPass', 'trim|required');
            if ($this->form_validation->run() && $this->session->userdata('id')) {
                $qUserCheck = $this->db->query("SELECT * FROM tm_user WHERE id = '" . $this->session->userdata('id') . "'");
                if ($qUserCheck->num_rows() > 0) {
                    $row = $qUserCheck->row_array();
                    $password = md5($this->db->escape_str($this->input->post('oPass')));
                    $nPass = $this->db->escape_str($this->input->post('nPass'));
                    $rPass = $this->db->escape_str($this->input->post('rPass'));
                    if ($password != $row['password']) {
                        echo "Wrong Password";
                    } else
                    if ($nPass != $rPass && strlen($nPass) < 6) {
                        echo "Incorrect New Password";
                    } else {
                        $this->db->query("UPDATE tm_user SET password='" . md5($nPass) . "',updated_by = '".$this->session->userdata('username')."' WHERE id = '" . $this->session->userdata('id') . "'");
                        echo "Success";
                    }
                } else {
                    echo "Session Lost, please re login first";
                }
            } else //goto main
                echo "Incorrect current password";
        }
    }

}