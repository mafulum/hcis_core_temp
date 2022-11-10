<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of HcInternalApi
 *
 * @author maful
 */
class hc_internal_api {
    //put your code here
    public static function getObject(){
        return new hc_internal_api();
    }
    
    private function request($PATH,$METHOD,$PARAM){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://172.10.30.23:8081/'.$PATH,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $METHOD,
          CURLOPT_POSTFIELDS => $PARAM,
        ));
        curl_exec($curl);
        curl_close($curl);
    }
    
    public function sendMail($obj_reff,$subject,$message,$mail_to,$mail_cc=""){
        $path = "machine/outbox_mail_message";
        $param = array('mail_to' => $mail_to,'obj_ref' => $obj_reff,'message' => $message,'subject' => $subject);
        if(!empty($mail_cc)){
            $param['mail_cc'] = $mail_cc;
        }
        $this->request($path, "POST", $param);
    }
}
