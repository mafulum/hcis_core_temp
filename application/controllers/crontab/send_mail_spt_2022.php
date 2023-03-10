<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin
 *
 * @author Garuda
 */
class send_mail_spt_2022 extends CI_Controller {

    var $maps =[];

    //put your code here
    function __construct() {
        parent::__construct();
        $this->maps = file_get_contents("./libraries/spt_2022.json");
    }

    function index() {
        set_time_limit(0);
        echo count($this->maps);
        echo "<br/>";
        foreach($this->maps as $map){
            var_dump($maps);
            echo "<br/>";
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //   CURLOPT_URL => 'http://172.17.0.1:8181/api/machine/send_spt_2022',
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_ENCODING => '',
            //   CURLOPT_MAXREDIRS => 10,
            //   CURLOPT_TIMEOUT => 0,
            //   CURLOPT_FOLLOWLOCATION => true,
            //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //   CURLOPT_CUSTOMREQUEST => 'POST',
            //   CURLOPT_POSTFIELDS => array('pernr' => $map['pernr'],'email' => $map['email'],'title' => 'SPT 2022 - PT. Garuda Daya Pratama Sejahtera',
            //   'emp_nama' => $map['emp_nama'],'file' => 'SPT_1','year' => '2022'),
            // ));
            // $response = curl_exec($curl);
            // curl_close($curl);
            // echo $response;
        }
    }

}

?>
