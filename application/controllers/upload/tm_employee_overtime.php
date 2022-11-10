<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author Garuda
 */
class tm_employee_overtime extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('pa_payroll/emp_overtime_m');
    }

    function index() {
        $data = $this->emp_overtime_m->reff_upload_page();
        $this->load->view('upload/main', $data);
    }

    function upload() {
        $sError = "<b>ERROR</b> : <br/><br/>" . "Empty File";
        if (!empty($_FILES['userfile']) && isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])) {
            $sError = "";
            $filename = "";
            $dir = "mass_upload/";
            $len = strlen($_FILES['userfile']['name']);
            $ext = substr($_FILES['userfile']['name'], $len - 3, 3);
            //pengecekan extension
            if ($ext != "xls") {
                $sError = "<b>ERROR</b> : <br/><br/>" . "Extension file must be xls";
            }
            if (empty($sError)) {
                $filename = "m_emp_overtime_" . date("Ymd_Hi") . '.xls';
                //copy file
                $this->load->library('upload', array('upload_path' => $dir, 'overwrite' => true, 'allowed_types' => 'xls', 'remove_spaces' => true, 'file_name' => $filename));
                $resUpload = $this->upload->do_upload('userfile');
                if ($resUpload == false) {
                    $sError = $this->upload->display_errors();
                }
            }
            if (empty($sError)) {
                $sError = $this->load($dir . $filename);
            }
            $data = $this->emp_overtime_m->reff_upload_page();
            $data['sError'] = $sError;
            $this->load->view('upload/main', $data);
        }
    }

    private function isValidDateTime($date, $format = 'Y-m-d H:i') {
        $dateObj = DateTime::createFromFormat($format, $date);
        return ($dateObj && $dateObj->format($format) == $date);
    }

    function load($filename) {
        if (!empty($this->reader)) {
            return "<b>ERROR</b> : <br/><br/>" . "Library Error";
        }
        if (!file_exists($filename)) {
            return "<b>ERROR</b> : <br/><br/>" . "file does not exist";
        }
        $this->load->library('reader');
        $this->reader->setOutputEncoding('CP1251');
        $this->reader->read($filename);
        $this->load->model('employee_m');
        //HEADER
        $header = 1;
        $col = 1;
        $aStringHeader = array("PERNR", "CNAME", "IHDAY", "PRDPY", "NOTE", "BEGTI", "ENDTI", "OTPNT");
        $MAX_ROW = $this->reader->sheets[0]['numRows'];
        $aFlag = array("1", "0");
        foreach ($aStringHeader as $headerName) {
            $text = $this->reader->sheets[0]['cells'][$header][$col];
            if (!empty($text) && $text != $headerName) {
                return "<b>ERROR</b> : <br/><br/>" . "Header Mismatch " . $headerName . "|" . $text;
            }
            $col++;
        }
        if ($MAX_ROW == $col) {
            return "<b>ERROR</b> : <br/><br/>" . "Does not have data";
        }

        $sError = "";
        $aInput = array();
        $aPERNR = array();
        for ($i = 2; $i <= $MAX_ROW; $i++) {
            $aData['PERNR'] = $this->reader->sheets[0]['cells'][$i][1];
            $aData['CNAME'] = $this->reader->sheets[0]['cells'][$i][2];
            $aData['IHDAY'] = $this->reader->sheets[0]['cells'][$i][3];
            $aData['PRDPY'] = $this->reader->sheets[0]['cells'][$i][4];
            $aData['NOTE'] = null;
            if (isset($this->reader->sheets[0]['cells'][$i][5])) {
                $aData['NOTE'] = $this->reader->sheets[0]['cells'][$i][5];
            }
            $aData['BEGTI'] = $this->reader->sheets[0]['cells'][$i][6];
            $aData['ENDTI'] = $this->reader->sheets[0]['cells'][$i][7];
            $aData['OTPNT'] = null;
            if (isset($this->reader->sheets[0]['cells'][$i][8])) {
                $aData['OTPNT'] = $this->reader->sheets[0]['cells'][$i][8];
            }

            //pengecekan error 
            $aMandatory = array("PERNR", "PRDPY", "BEGTI", "ENDTI");
            foreach ($aMandatory as $mandatory) {
                if (empty($aData[$mandatory])) {
                    $sError .= $mandatory . " empty @ row $i<br/>";
                }
            }
            //PERNR
            if (empty($aPERNR[$aData['PERNR']])) {
                $aPERNR[$aData['PERNR']] = $this->employee_m->get_master_emp_single($aData['PERNR']);
            }
            if (empty($aPERNR[$aData['PERNR']])) {
                $sError .= $aData['PERNR'] . " NOT FOUND @ row $i<br/>";
            } else if (strtoupper(trim($aPERNR[$aData['PERNR']]['CNAME'])) != strtoupper(trim($aData['CNAME']))) {
                $sError .= $aData['PERNR'] . " Unmatched CNAME on DATABASE " . $aData['CNAME'] . "|" . $aPERNR[$aData['PERNR']]['CNAME'] . ". @ row $i<br/>";
            }
            if ($this->isValidDateTime($aData['BEGTI']) == false) {
                $sError .= $aData['BEGTI'] . " INVALID FORMAT @ row $i<br/>";
            } else {
                $aData['row_begti'] = DateTime::createFromFormat("Y-m-d H:i:s.u", $aData['BEGTI'].":00.0");
                $aData['row_begti_ts'] = $aData['row_begti']->getTimestamp();
            }
            if ($this->isValidDateTime($aData['ENDTI']) == false) {
                $sError .= $aData['ENDTI'] . " INVALID FORMAT @ row $i<br/>";
            } else {
                $aData['row_endti'] = DateTime::createFromFormat("Y-m-d H:i:s.u", $aData['ENDTI'].":00.0");
                $aData['row_endti_ts'] = $aData['row_endti']->getTimestamp();
                $aData['row_num'] = $i;
            }
            $aInput[] = $aData;
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        $aMap = array();
        //check internal files
        foreach ($aInput as $row) {
            if (empty($aMap[$row['PERNR']])) {
                $aMap[$row['PERNR']]['BEGTI'] = $row;
                $aMap[$row['PERNR']]['ENDTI'] = $row;
                $aMap[$row['PERNR']]['rows'][] = $row;
            } else {
                $sAddError = "";
                foreach ($aMap[$row['PERNR']]['rows'] as $detrow) {
                    if ((($detrow['row_begti_ts'] >= $row['row_endti_ts']) || ($detrow['row_endti_ts'] <= $row['row_begti_ts']))==false) {
//                        var_dump($detrow);
//                        echo "<br/>";
//                        echo "<br/>";
//                        var_dump($row);
//                        echo "<br/>";
//                        echo "X|".($detrow['row_begti_ts'] > $row['row_endti_ts'])."<br/>";
//                        echo "Y|".($detrow['row_endti_ts'] < $row['row_begti_ts'])."<br/>";
//                        exit;
                        $sAddError .= "Periode BEGTI AND ENDTI Conflict between row " . $row['row_num'] . " AND " . $detrow['row_num'] . "<br/>";
                    }
                }
                if ($sAddError == "") {
                    if ($aMap[$row['PERNR']]['BEGTI']['row_begti_ts'] > $row['row_begti_ts']) {
                        $aMap[$row['PERNR']]['BEGTI'] = $row;
                    }
                    if ($aMap[$row['PERNR']]['ENDTI']['row_endti_ts'] < $row['row_endti_ts']) {
                        $aMap[$row['PERNR']]['ENDTI'] = $row;
                    }
                    $aMap[$row['PERNR']]['rows'][] = $row;
                } else {
                    $sError .= $sAddError;
                }
            }
        }
        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;
        //check data with databases
        foreach ($aMap as $pernr => $infos) {
            $rows_db = $this->emp_overtime_m->get_a_overtime_begti_endti($pernr, $infos['BEGTI']['BEGTI'], $infos['ENDTI']['ENDTI']);
            if (!empty($rows_db)) {
                for ($i = 0; $i < count($rows_db); $i++) {
                    $row_db = $rows_db[$i];
                    if (empty($rows_db[$i]['row_begti_ts'])) {
                        $rows_db[$i]['row_begti_ts'] = DateTime::createFromFormat("Y-m-d H:i:s", $rows_db[$i]['BEGTI'])->getTimestamp();
                    }
                    if (empty($rows_db[$i]['row_endti_ts'])) {
                        $rows_db[$i]['row_endti_ts'] = DateTime::createFromFormat("Y-m-d H:i:s", $rows_db[$i]['ENDTI'])->getTimestamp();
                    }
                }
                foreach ($infos['rows'] as $row_file) {
                    if (empty($row_file['row_begti_ts'])) {
                        $row_file['row_begti_ts'] = DateTime::createFromFormat("Y-m-d H:i:s", $row_file['BEGTI'])->getTimestamp();
                    }
                    if (empty($row_file['row_endti_ts'])) {
                        $row_file['row_endti_ts'] = DateTime::createFromFormat("Y-m-d H:i:s", $row_file['ENDTI'])->getTimestamp();
                    }
                    foreach ($rows_db as $row_db) {
                        if ((($row_db['row_begti_ts'] > $row_file['row_endti_ts']) || ($row_db['row_endti_ts'] < $row_file['row_begti_ts'])) == false) {
                            $sAddError .= "Periode BEGTI AND ENDTI (DB) Conflict between row " . $row_file['row_num'] . " AND ID TABLE" . $row_db['id'] . "<br/>";
                        }
                    }
                }
            }
        }

        if (!empty($sError))
            return "<b>ERROR</b> : <br/><br/>" . $sError;

        $sReturn = "<table border='1' cellpadding='0' cellspacing='0'u><tr>";
        foreach ($aStringHeader as $header) {
            $sReturn .= "<td>" . $header . "</td>";
        }
        $sReturn .= "</tr>";
//        echo "STOP";exit;
        for ($i = 0; $i < count($aInput); $i++) {
            unset($aInput[$i]['row_endti']);
            unset($aInput[$i]['row_begti']);
            unset($aInput[$i]['row_endti_ts']);
            unset($aInput[$i]['row_begti_ts']);
            unset($aInput[$i]['row_num']);
            $this->emp_overtime_m->emp_overtime_new($aInput[$i]);
            $sReturn .= "<tr>";
            foreach ($aStringHeader as $header) {
                $sReturn .= "<td>" . $aInput[$i][$header] . "</td>";
            }
            $sReturn .= "</tr>";
        }
        $sReturn .= "</table>";
        return "Success.<br/>" . $sReturn;
    }

}

?>