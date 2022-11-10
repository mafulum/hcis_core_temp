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
class emp_payroll extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('download_m');
        $data = $this->download_m->emp_payroll();
        $this->load->view('main', $data);
    }

    function download() {
        ini_set("memory_limit", "512M");
        $date = date("Y-m-d");
        if ($this->input->post('tmtdate')) {
            $date =  $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tmtdate'));
        }
        $sQuery = "SELECT eorg.PERNR,BEGDA,ENDDA,STEXT_POS,STEXT_ORG,STEXT_WERKS,STEXT_PAYROLL,STEXT_PERSG,STEXT_PERSK,STEXT_PA,CNAME,
            BANK_ACCOUNT,BANK_PAYEE ,TanggalMasuk,TanggalPensiun, AKHIR_KONTRAK,AKHIR_PERBANTUAN,KTP,BANK_NAME FROM
(select PERNR,BEGDA,ENDDA,WERKS,PERSG,PERSK,ABKRS,PLANS,ORGEH,BTRTL from tm_emp_org WHERE BEGDA<='$date' AND ENDDA>='$date' AND ABKRS IS NOT NULL) eorg
JOIN (select PERNR,CNAME,GBDAT,GBLND from tm_master_emp WHERE BEGDA<='$date' AND ENDDA>='$date' ) me ON eorg.PERNR=me.PERNR
LEFT JOIN (select PERNR,ICNUM AS KTP from tm_emp_personalid WHERE BEGDA<='$date' AND ENDDA>='$date' AND SUBTY='1') ktp ON eorg.PERNR=ktp.PERNR
LEFT JOIN (select PERNR,BANK_MID,BANK_ACCOUNT,BANK_PAYEE from tm_emp_bank WHERE BEGDA<='$date' AND ENDDA>='$date' ) bank ON eorg.PERNR=bank.PERNR
LEFT JOIN (SELECT bank_mid,BANK_NAME FROM tm_master_bank WHERE BEGDA<='$date' AND ENDDA>='$date' ) mbank ON bank.BANK_MID=mbank.bank_mid
LEFT JOIN (select PERNR,TanggalMasuk,TanggalPensiun from tm_emp_date WHERE BEGDA<='$date' AND ENDDA>='$date') tmasuk ON eorg.PERNR=tmasuk.PERNR
LEFT JOIN (select PERNR,ENDDA as AKHIR_KONTRAK from tm_emp_motask where BEGDA<='$date' AND ENDDA>='$date' AND REMINDER_TYPE ='0001') motask ON eorg.PERNR=motask.PERNR
LEFT JOIN (select PERNR,ENDDA as AKHIR_PERBANTUAN from tm_emp_motask where BEGDA<='$date' AND ENDDA>='$date' AND REMINDER_TYPE ='0003') motask_perbantuan ON eorg.PERNR=motask_perbantuan.PERNR
LEFT JOIN (SELECT OBJID,STEXT as STEXT_POS FROM tm_master_org WHERE BEGDA<='$date' AND ENDDA>='$date' AND OTYPE='S') pos ON eorg.PLANS=pos.OBJID 
LEFT JOIN (SELECT OBJID,STEXT as STEXT_ORG FROM tm_master_org WHERE BEGDA<='$date' AND ENDDA>='$date' AND OTYPE='O') org ON eorg.ORGEH=org.OBJID 
LEFT JOIN (SELECT SHORT,STEXT as STEXT_WERKS FROM tm_master_abbrev WHERE SUBTY=5) mwerks ON eorg.WERKS=mwerks.SHORT 
LEFT JOIN (SELECT SHORT,STEXT as STEXT_PAYROLL FROM tm_master_abbrev WHERE SUBTY=5) mabkrs ON eorg.ABKRS=mabkrs.SHORT 
LEFT JOIN (SELECT SHORT,STEXT as STEXT_PERSG FROM tm_master_abbrev WHERE SUBTY=3) mpersg ON eorg.PERSG=mpersg.SHORT 
LEFT JOIN (SELECT SHORT,STEXT as STEXT_PERSK FROM tm_master_abbrev WHERE SUBTY=4) mpersk ON eorg.PERSK=mpersk.SHORT  
LEFT JOIN (SELECT SHORT,STEXT as STEXT_PA FROM tm_master_abbrev WHERE SUBTY=26) mpa ON eorg.BTRTL=mpa.SHORT ";
//        echo $sQuery;exit;
        $ores = $this->db->query($sQuery);
        $data = $ores->result_array();
        $this->load->library("excel");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray(
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
                ->setCellValue("E1","JABATAN")
                ->setCellValue("F1","BANK")
                ->setCellValue("G1","ATAS_NAMA")
                ->setCellValue("H1","REKENING")
                ->setCellValue("I1","EMP GROUP")
                ->setCellValue("J1","STATUS")
                ->setCellValue("K1","AWAL_KONTRAK")
                ->setCellValue("L1","AKHIR_KONTRAK")
                ->setCellValue("M1","COMPANY")
                ->setCellValue("N1","KTP_NO")
                ->setCellValue("O1","PROJECT/UNIT")
                ->setCellValue("P1","PAYROLL_AREA");
        for($i=0;$i<count($data);$i++){
            $akhir_kontrak = ""; 
            if(!empty($data[$i]['AKHIR_KONTRAK'])){
                $akhir_kontrak = $data[$i]['AKHIR_KONTRAK'];
            }else if (!empty($data[$i]['TanggalPensiun'])){
                $akhir_kontrak = $data[$i]['TanggalPensiun'];
            }else if(!empty($data[$i]['AKHIR_PERBANTUAN'])){
                $akhir_kontrak = $data[$i]['AKHIR_PERBANTUAN'];
            }
            $objPHPExcel->getActiveSheet()->setCellValue("A".(2+$i),$data[$i]['PERNR'])
                ->setCellValue("B".(2+$i),$data[$i]['CNAME'])
                ->setCellValue("C".(2+$i),$data[$i]['BEGDA'])
                ->setCellValue("D".(2+$i),$data[$i]['ENDDA'])
                ->setCellValue("E".(2+$i),$data[$i]['STEXT_POS'])
                ->setCellValue("F".(2+$i),$data[$i]['BANK_NAME'])
                ->setCellValue("G".(2+$i),$data[$i]['BANK_PAYEE'])
                ->setCellValueExplicit("H".(2+$i),$data[$i]['BANK_ACCOUNT'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("I".(2+$i),$data[$i]['STEXT_PERSG'])
                ->setCellValue("J".(2+$i),$data[$i]['STEXT_PERSK'])
                ->setCellValue("K".(2+$i),$data[$i]['TanggalMasuk'])
                ->setCellValue("L".(2+$i),$akhir_kontrak)
                ->setCellValue("M".(2+$i),$data[$i]['STEXT_WERKS'])
                ->setCellValueExplicit("N".(2+$i),$data[$i]['KTP'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("O".(2+$i),$data[$i]['STEXT_ORG'])
                ->setCellValue("P".(2+$i),$data[$i]['STEXT_PAYROLL']);
        }
                

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PayrollEmp_' . $date . '_on_' . date("Ymd") . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}

?>
