<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of orgchart
 *
 * @author Garuda
 */
class cierror extends CI_Controller {

    //put your code herbvge
    public function __construct() {
        parent::__construct();
        //$this->load->model('orgchart_m');
    }

	function index(){
		$data['base_url'] = $this->config->item('base_url');
        $this->load->view('404', $data);
	}
    
}

?>
