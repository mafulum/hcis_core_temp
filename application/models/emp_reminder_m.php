<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_mma
 *
 * @author Garuda
 */
class emp_reminder_m extends CI_Model {

    const MONITORING_OF_TASK_DAILY = "MON_TASK_DAILY";
    //put your code here
    public function getListEmail($type){
        $sql = "SELECT email FROM tm_emp_reminder WHERE reminder_type='".$type."' AND CURDATE() BETWEEN begda and endda";
        $oRes = $this->db->query($sql);
        return $oRes->result_array();
    }
}

?>
