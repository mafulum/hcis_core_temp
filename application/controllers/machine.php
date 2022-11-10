<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of machine
 *
 * @author Garuda
 */
class machine extends CI_Controller {
    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('gen_machine');
    }
    function gen_emp_plans(){
        $this->gen_machine->gen_from_pos_detail();
        $this->gen_machine->gen_from_empcompt();
        $this->gen_machine->gen_from_job_compt();
    }
    function gen_performance(){
        $this->gen_machine->gen_from_performance();
    }
    function gen_age(){
        $this->gen_machine->gen_from_age();
    }
    
    function gen_educ(){
        $this->gen_machine->gen_from_educ();
    }
    function gen_medic(){
        $this->gen_machine->gen_from_medic();
    }
    function gen_all(){
        $this->gen_emp_plans();
        $this->gen_machine->gen_from_performance();
        $this->gen_machine->gen_from_age();
        $this->gen_machine->gen_from_educ();
        $this->gen_machine->gen_from_medic();
    }
    
    function run_trigger(){
        $this->gen_machine->run_trigger();
	 $this->gen_emp_readiness();

    }
    function gen_emp_readiness(){
        $this->gen_machine->gen_readiness();
    }
    
    function gen_job_family($sOrg,$sJobFam,$n=1){
        if(!empty($sOrg) && !empty($sJobFam)){
           $sQuery="SELECT * FROM tm_master_org where OBJID='$sOrg' AND OTYPE='O' AND CURDATE() BETWEEN BEGDA AND ENDDA";
           $oRes = $this->db->query($sQuery);
           if($oRes->num_rows()==0) die("unknown ORG UNIT");
           $aRes = $oRes->row_array();
           //echo "ORG UNIT : ".$sOrg." | SHORT : ".$aRes['SHORT']." | STEXT : ".$aRes['STEXT']."<br/>";
           
           $oRes->free_result();
           $sQuery="SELECT * FROM tm_master_compt where OBJID='$sJobFam' AND OTYPE='JF' AND CURDATE() BETWEEN BEGDA AND ENDDA";
           $oRes = $this->db->query($sQuery);
           if($oRes->num_rows()==0) die("unknown JOB FAM");
           $aRes = $oRes->row_array();
           //echo "JF_ID : ".$sJobFam." | SHORT : ".$aRes['SHORT']." | STEXT : ".$aRes['STEXT']."<br/>";
           $this->load->model('orgchart_m');
           $sDate = date("Ymd");
           $aPos = $this->orgchart_m->get_sub_pos($sOrg,$sDate);
           for($i=0;$i<count($aPos);$i++){
               //var_dump($aPos[$i]);
               $sQuery="SELECT * from tm_pos_detail where PLANS='".$aPos[$i]['OBJID']."' AND CURDATE() BETWEEN BEGDA AND ENDDA";
               $oRes = $this->db->query($sQuery);
               //echo "--POS : ".$aPos[$i]['OBJID']." | ".$aPos[$i]['SHORT']." | ".$aPos[$i]['STEXT']." | ".$aPos[$i]['FAMILY'].' = UDPATE TO '.$sJobFam."<br/>";
               if($oRes->num_rows()>0){
                   $sQuery="UPDATE tm_pos_detail SET FAMILY='$sJobFam' WHERE PLANS='".$aPos[$i]['OBJID']."',updated_by = '".$this->session->userdata('username')."' AND CURDATE() BETWEEN BEGDA AND ENDDA;";
               }else{
                   $sQuery="INSERT INTO tm_pos_detail(`PLANS`,`BEGDA`,`ENDDA`,`SCORE`,`STELL`,`FAMILY`,'created_by') values ('".$aPos[$i]['OBJID']."','1900-01-01','9999-12-31','0','','".$sJobFam."'.'".$this->session->userdata('username')."');";
               }
               $oRes->free_result();
               //echo $sQuery."<br/>";
               $this->db->query($sQuery);
           }
           $aOrg = $this->orgchart_m->get_sub_org($sOrg,$sDate);
           for($i=0;$i<count($aOrg);$i++){
               //echo "--ORG : ".$aOrg[$i]['OBJID']." | ".$aOrg[$i]['SHORT']." | ".$aOrg[$i]['STEXT']." |  = SEARCH SUB<br/>";
               $this->gen_job_family($aOrg[$i]['OBJID'],$sJobFam,$n+1);
           }
           
           
        }
    }
}

?>
