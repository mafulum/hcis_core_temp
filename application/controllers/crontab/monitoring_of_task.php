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
class monitoring_of_task extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('pa/emp_motask_m');
    }

    function daily($date=null) {
        if(empty($date)){
            $date = date("Y-m-d");
        }
        $aRow = $this->emp_motask_m->getReminderByDate($date);
        if (!empty($aRow) && count($aRow) > 0) {
            $aPernr=[];
            foreach($aRow as $row){
                if(in_array($row['PERNR'], $aPernr)==false){
                    $aPernr[] = $row['PERNR'];
                }
            }
            $aEmp = $this->employee_m->getEmpByPernrs($aPernr);
            if(empty($aEmp)){
                return null;
            }
            // var_dump($aEmp);exit;
            $this->load->model('emp_reminder_m');
            $aEmail = $this->emp_reminder_m->getListEmail(emp_reminder_m::MONITORING_OF_TASK_DAILY);
            if(empty($aEmail)){
                return null;
            }
            $sEmail = "";
            foreach($aEmail as $val){
                if(!empty($sEmail)){
                    $sEmail.=";";
                }
                $sEmail.= $val['email'];
            }
            $aEmpKV = $this->common->getKVArr($aEmp,'PERNR');
            $abbrev = $this->common->get_abbrev(29);
            $abbrevKV = $this->common->getKVArr($abbrev,'id');
            $aParamViews=['rows'=>$aRow,'emp'=>$aEmpKV,'abbrev'=>$abbrevKV,'date'=>$date];
            $msg = $this->load->view('employee/pa/monitoring_mail_daily', $aParamViews, TRUE);
            echo $msg;exit;
            $obj_reff = "MONTASK_DAILY|".$date;
            $subject = "Monitoring of Task Daily ".$date;
            $this->hc_internal_api->sendMail($obj_reff,$subject,$msg,$sEmail);
            return null;
        }
    }

}

?>
