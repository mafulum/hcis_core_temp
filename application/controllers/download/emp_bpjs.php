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
class emp_bpjs extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('download_m');
        $data = $this->download_m->emp_bpjs();
        $this->load->view('main', $data);
    }
    
    private function getLastUnitPositionActive($pernr){
        $sQuery = "SELECT PERSG,PERSK,WERKS,ABKRS,BTRTL,PLANS,ORGEH,pos.STEXT_POS,org.STEXT_ORG "
                . "FROM (select BEGDA,ENDDA,WERKS,PERSG,PERSK,ABKRS,PLANS,ORGEH,BTRTL from tm_emp_org WHERE PERNR='$pernr' AND PERSG NOT IN('X','Z') ) eorg "
                . "LEFT JOIN (SELECT BEGDA,ENDDA,OBJID,STEXT as STEXT_POS FROM tm_master_org WHERE OTYPE='S') pos ON eorg.PLANS=pos.OBJID AND eorg.ENDDA BETWEEN pos.BEGDA AND pos.ENDDA "
                . "LEFT JOIN (SELECT BEGDA,ENDDA,OBJID,SHORT as SHORT_ORG,STEXT as STEXT_ORG FROM tm_master_org WHERE OTYPE='O') org ON eorg.ORGEH=org.OBJID AND eorg.ENDDA BETWEEN org.BEGDA AND org.ENDDA "
                . "ORDER BY eorg.ENDDA DESC LIMIT 1";
//        echo $sQuery;
//        echo "<br/>";
        $oRes = $this->db->query($sQuery);
        if(empty($oRes)){
            return null;
        }
        return $oRes->row_array();
    }

    function download() {
        ini_set("memory_limit", "512M");
        $date = date("Y-m-d");
        if ($this->input->post('tmtdate')) {
            $date =  $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tmtdate'));
        }
        $sQuery = "select ec.PERNR,ec.SUBTY, USRID FROM tm_emp_comm ec JOIN (SELECT PERNR,SUBTY,MAX(BEGDA) AS MAX_BEGDA from tm_emp_comm where BEGDA<='$date' AND ENDDA>='$date' AND SUBTY IN('1','2','4') GROUP BY PERNR,SUBTY) ecg ON ec.PERNR =ecg.PERNR AND ec.BEGDA=ecg.MAX_BEGDA AND ec.SUBTY=ecg.SUBTY";
//        echo $sQuery;exit;
        $ores = $this->db->query($sQuery);
        $empComm = $ores->result_array();
        $aEmpCom = [];
        foreach($empComm as $row){
            $aEmpCom[$row['PERNR']][$row['SUBTY']] = $row['USRID'];
        }
//            LEFT JOIN (select ec.PERNR,USRID as office_email FROM tm_emp_comm ec JOIN (SELECT PERNR,MAX(BEGDA) AS MAX_BEGDA from tm_emp_comm where BEGDA<='$date' AND ENDDA>='$date' AND SUBTY IN('1')GROUP BY PERNR ) mc ON ec.PERNR =mc.PERNR AND ec.BEGDA=mc.MAX_BEGDA) oemail ON eorg.PERNR=oemail.PERNR
//            LEFT JOIN (select ec.PERNR,USRID as personal_email FROM tm_emp_comm ec JOIN (SELECT PERNR,MAX(BEGDA) AS MAX_BEGDA from tm_emp_comm where BEGDA<='$date' AND ENDDA>='$date' AND SUBTY IN('2') GROUP BY PERNR ) mm ON ec.PERNR =mm.PERNR AND ec.BEGDA=mm.MAX_BEGDA) pemail ON eorg.PERNR=pemail.PERNR
//            LEFT JOIN (select ec.PERNR,USRID as mobile_phone FROM tm_emp_comm ec JOIN (SELECT PERNR,MAX(BEGDA) AS MAX_BEGDA from tm_emp_comm where BEGDA<='$date' AND ENDDA>='$date' AND SUBTY IN('4') GROUP BY PERNR ) mp ON ec.PERNR =mp.PERNR AND ec.BEGDA=mp.MAX_BEGDA ) mphone ON eorg.PERNR=mphone.PERNR
//office_email,personal_email,mobile_phone,
        $sQuery = "SELECT eorg.PERNR,BEGDA,ENDDA,STEXT_POS,STEXT_ORG,STEXT_WERKS,STEXT_PAYROLL,PERSG,STEXT_PERSG,STEXT_PERSK,STEXT_PA,CNAME,GBDAT,GBLND,
            KTP,BPJSID,FCLTY,INSID,PRCTE,PRCTC,MAXRE,MAXRC,IBU_KANDUNG,TanggalMasuk,AKHIR_KONTRAK,KK,FCLTY,
            PRCTE,BANK_ACCOUNT,BANK_PAYEE,BANK_NAME,ADDR FROM
            (select BEGDA,ENDDA,PERNR,WERKS,PERSG,PERSK,ABKRS,PLANS,ORGEH,BTRTL from tm_emp_org WHERE BEGDA<='$date' AND ENDDA>='$date' ) eorg
            JOIN (select PERNR,CNAME,GBDAT,GBLND from tm_master_emp WHERE BEGDA<='$date' AND ENDDA>='$date' ) me ON eorg.PERNR=me.PERNR
            LEFT JOIN (select PERNR,ICNUM AS KTP from tm_emp_personalid WHERE BEGDA<='$date' AND ENDDA>='$date' AND SUBTY='1') ktp ON eorg.PERNR=ktp.PERNR
            LEFT JOIN (SELECT PERNR,BPJSID,FCLTY FROM tm_emp_bpjs_tk WHERE BEGDA<='$date' AND ENDDA>='$date' ) jtk ON eorg.PERNR=jtk.PERNR
            LEFT JOIN (select PERNR,INSID from tm_emp_inshealth where BEGDA<='$date' AND ENDDA>='$date' AND INSTY='1' AND FAMSA='9999' ) jkn ON eorg.PERNR=jkn.PERNR
            LEFT JOIN (select PERNR,BANK_MID,BANK_ACCOUNT,BANK_PAYEE from tm_emp_bank where BEGDA<='$date' AND ENDDA>='$date' AND BANK_ORDER=1) ebank ON eorg.PERNR=ebank.PERNR
            LEFT JOIN (select PERNR,PRCTE,PRCTC,MAXRE,MAXRC from tm_emp_insurance WHERE BEGDA<='$date' AND ENDDA>='$date' AND INSTY ='1' ) jkn_cost ON eorg.PERNR=jkn_cost.PERNR
            LEFT JOIN (select PERNR,CNAME AS IBU_KANDUNG from tm_emp_fam where BEGDA<='$date' AND ENDDA>='$date' AND SUBTY='3110' AND GESCH='2' ) ibu ON eorg.PERNR=ibu.PERNR
            LEFT JOIN (select PERNR,TanggalMasuk from tm_emp_date WHERE BEGDA<='$date' AND ENDDA>='$date') tmasuk ON eorg.PERNR=tmasuk.PERNR
            LEFT JOIN (select PERNR,ENDDA as AKHIR_KONTRAK from tm_emp_motask where BEGDA<='$date' AND ENDDA>='$date' AND REMINDER_TYPE ='0001') motask ON eorg.PERNR=motask.PERNR
            LEFT JOIN (select PERNR,ICNUM AS KK from tm_emp_personalid WHERE BEGDA<='$date' AND ENDDA>='$date' AND SUBTY='9') kk ON eorg.PERNR=kk.PERNR
            LEFT JOIN (select PERNR,ADDR from tm_emp_address where BEGDA<='$date' AND ENDDA>='$date' AND ADDRESS_TYPE='HOME') eaddress ON eorg.PERNR=eaddress.PERNR
            LEFT JOIN (SELECT OBJID,STEXT as STEXT_POS FROM tm_master_org WHERE BEGDA<='$date' AND ENDDA>='$date' AND OTYPE='S') pos ON eorg.PLANS=pos.OBJID 
            LEFT JOIN (SELECT OBJID,STEXT as STEXT_ORG FROM tm_master_org WHERE BEGDA<='$date' AND ENDDA>='$date' AND OTYPE='O') org ON eorg.ORGEH=org.OBJID 
            LEFT JOIN (SELECT SHORT,STEXT as STEXT_WERKS FROM tm_master_abbrev WHERE SUBTY=5) mwerks ON eorg.WERKS=mwerks.SHORT 
            LEFT JOIN (SELECT SHORT,STEXT as STEXT_PAYROLL FROM tm_master_abbrev WHERE SUBTY=5) mabkrs ON eorg.ABKRS=mabkrs.SHORT 
            LEFT JOIN (SELECT SHORT,STEXT as STEXT_PERSG FROM tm_master_abbrev WHERE SUBTY=3) mpersg ON eorg.PERSG=mpersg.SHORT 
            LEFT JOIN (SELECT SHORT,STEXT as STEXT_PERSK FROM tm_master_abbrev WHERE SUBTY=4) mpersk ON eorg.PERSK=mpersk.SHORT  
            LEFT JOIN (SELECT bank_mid,BANK_NAME FROM tm_master_bank WHERE BEGDA<='$date' AND ENDDA>='$date' ) mbank ON ebank.BANK_MID=mbank.bank_mid
            LEFT JOIN (SELECT SHORT,STEXT as STEXT_PA FROM tm_master_abbrev WHERE SUBTY=26) mpa ON eorg.BTRTL=mpa.SHORT ORDER BY TanggalMasuk ASC,AKHIR_KONTRAK ASC";
        //echo $sQuery;exit;
        $ores = $this->db->query($sQuery);
        $data = $ores->result_array();
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AG1')->applyFromArray(
                array('fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('argb' => 'FFCCFFCC')
                    ),
                    'borders' => array(
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                        'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
                    )
                )
        );
        $objPHPExcel->getActiveSheet()->setCellValue("A1","NOPEG")
                ->setCellValue("B1","NAMA")
                ->setCellValue("C1","BEGDA")
                ->setCellValue("D1","ENDDA")
                ->setCellValue("E1","NO_KTP")
                ->setCellValue("F1","NO_KPJ")
                ->setCellValue("G1","Program")
                ->setCellValue("H1","NO_JKN")
                ->setCellValue("I1","PERSENTASE")
                ->setCellValue("J1","TANGGAL_LAHIR")
                ->setCellValue("K1","TEMPAT_LAHIR")
                ->setCellValue("L1","NAMA_IBU")
                ->setCellValue("M1","AWAL_KONTRAK")
                ->setCellValue("N1","TANGGAL_NON_AKTIF")
                ->setCellValue("O1","EMP_GROUP")
                ->setCellValue("P1","EMP_SUB_GROUP")
                ->setCellValue("Q1","NO_KK")
                ->setCellValue("R1","JABATAN")
                ->setCellValue("S1","OFFICE_EMAIL")
                ->setCellValue("T1","PERSONAL_EMAIL")
                ->setCellValue("U1","NO_HP")
                ->setCellValue("V1","UNIT")
                ->setCellValue("W1","CABANG")
                ->setCellValue("X1","PENEMPATAN")
                ->setCellValue("Y1","PAYROLL_AREA")
                ->setCellValue("Z1","NO_REKENING")
                ->setCellValue("AA1","BANK")
                ->setCellValue("AB1","ATAS NAMA")
                ->setCellValue("AC1","ALAMAT")
                ->setCellValue("AD1","POSITION_BEF_PHK")
                ->setCellValue("AE1","ORG_STEXT_BEF_PHK")
                ->setCellValue("AF1","WERKS_BEF_PHK")
                ->setCellValue("AG1","BTRTL_BEF_PHK");
//        setCellValueExplicit('A1', $val,PHPExcel_Cell_DataType::TYPE_STRING)
        $arrPERSGPHK = ["X","Z"];
        for($i=0;$i<count($data);$i++){
            $gbdat = PHPExcel_Shared_Date::PHPToExcel( 
                DateTime::createFromFormat('Y-m-d', $data[$i]['GBDAT']) 
            );
            $tglMasuk = PHPExcel_Shared_Date::PHPToExcel( 
                DateTime::createFromFormat('Y-m-d', $data[$i]['TanggalMasuk']) 
            );
            $akhirKontrak = PHPExcel_Shared_Date::PHPToExcel( 
                DateTime::createFromFormat('Y-m-d', $data[$i]['AKHIR_KONTRAK']) 
            );
            $begda = PHPExcel_Shared_Date::PHPToExcel( 
                DateTime::createFromFormat('Y-m-d', $data[$i]['BEGDA']) 
            );
            $endda = PHPExcel_Shared_Date::PHPToExcel( 
                DateTime::createFromFormat('Y-m-d', $data[$i]['ENDDA']) 
            );
            $office_mail = "";
            $personal_email = "";
            $mobile_phone = "";
            if(!empty($aEmpCom[$data[$i]['PERNR']]['1'])){
                $office_mail = $aEmpCom[$data[$i]['PERNR']]['1'];
            }
            if(!empty($aEmpCom[$data[$i]['PERNR']]['2'])){
                $personal_email = $aEmpCom[$data[$i]['PERNR']]['2'];
            }
            if(!empty($aEmpCom[$data[$i]['PERNR']]['4'])){
                $mobile_phone = $aEmpCom[$data[$i]['PERNR']]['4'];
            }
            $objPHPExcel->getActiveSheet()->setCellValue("A".(2+$i),$data[$i]['PERNR'])
                ->setCellValue("B".(2+$i),$data[$i]['CNAME'])
                ->setCellValue("C".(2+$i),$begda)
                ->setCellValue("D".(2+$i),$endda)
                ->setCellValueExplicit("E".(2+$i),$data[$i]['KTP'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("F".(2+$i),$data[$i]['BPJSID'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("G".(2+$i),$data[$i]['FCLTY'])
                ->setCellValueExplicit("H".(2+$i),$data[$i]['INSID'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("I".(2+$i),$data[$i]['PRCTE'])
                ->setCellValue("J".(2+$i),$gbdat)
                ->setCellValue("K".(2+$i),$data[$i]['GBLND'])
                ->setCellValue("L".(2+$i),$data[$i]['IBU_KANDUNG'])
                ->setCellValue("M".(2+$i),$tglMasuk)
                ->setCellValue("N".(2+$i),$akhirKontrak)
                ->setCellValue("O".(2+$i),$data[$i]['STEXT_PERSG'])
                ->setCellValue("P".(2+$i),$data[$i]['STEXT_PERSK'])
                ->setCellValueExplicit("Q".(2+$i),$data[$i]['KK'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("R".(2+$i),$data[$i]['STEXT_POS'])
                ->setCellValue("S".(2+$i),$office_mail)
                ->setCellValue("T".(2+$i),$personal_email)
                ->setCellValueExplicit("U".(2+$i),$mobile_phone,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("V".(2+$i),$data[$i]['STEXT_ORG'])
                ->setCellValue("W".(2+$i),$data[$i]['STEXT_PA'])
                ->setCellValue("X".(2+$i),$data[$i]['STEXT_WERKS'])
                ->setCellValue("Y".(2+$i),$data[$i]['STEXT_PAYROLL'])
                ->setCellValueExplicit("Z".(2+$i),$data[$i]['BANK_ACCOUNT'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("AA".(2+$i),$data[$i]['BANK_NAME'])
                ->setCellValue("AB".(2+$i),$data[$i]['BANK_PAYEE'])
                ->setCellValue("AC".(2+$i),$data[$i]['ADDR']);
            
            $arrTempPHK = null;
            if(in_array($data[$i]['PERSG'], $arrPERSGPHK)){
//                echo "PHK : ".$data[$i]['PERNR'];
//                echo "<br/>";
                $arrTempPHK = $this->getLastUnitPositionActive($data[$i]['PERNR']);
//                var_dump($arrTempPHK);exit;
                if(!empty($arrTempPHK)){
                    $objPHPExcel->getActiveSheet()->setCellValue("AD".(2+$i),$arrTempPHK['STEXT_POS'])
                    ->setCellValue("AE".(2+$i),$arrTempPHK['STEXT_ORG'])
                    ->setCellValue("AF".(2+$i),$arrTempPHK['WERKS'])
                    ->setCellValue("AG".(2+$i),$arrTempPHK['BTRTL']);
                }
            }
            $objPHPExcel->getActiveSheet()
                ->getStyle('C'.(2+$i))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objPHPExcel->getActiveSheet()
                ->getStyle('D'.(2+$i))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objPHPExcel->getActiveSheet()
                ->getStyle('H'.(2+$i))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objPHPExcel->getActiveSheet()
                ->getStyle('K'.(2+$i))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $objPHPExcel->getActiveSheet()
                ->getStyle('L'.(2+$i))
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
        }
                

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="BPJSEmp_' . $date . '_on_' . date("Ymd") . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}

?>
