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
class emp_org extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->model('download_m');
        $data = $this->download_m->emp_org();
        $this->load->view('main', $data);
    }

    function download() {
        ini_set("memory_limit", "512M");
        $date = date("Y-m-d");
        if ($this->input->post('tmtdate')) {
            $date =  $this->global_m->convert_ddmmyyyy_yyyymmdd($this->input->post('tmtdate'));
        }
        $sQuery = "SELECT me.PERNR,me.CNAME,eo.BEGDA,eo.ENDDA,eo.PLANS,pos.STEXT as POSITION_TEXT, "
                . "eo.ORGEH,org.STEXT as ORG_TEXT,eo.PERSG,mpersg.STEXT as PERSG_TEXT,eo.PERSK, "
                . "mpersk.STEXT as PERSK_TEXT,eo.WERKS, mwerks.STEXT as WERKS_TEXT, "
                . "eo.ABKRS,mabkrs.STEXT as ABKRS_TEXT FROM "
                . "(SELECT PERNR,CNAME FROM tm_master_emp WHERE BEGDA<='$date' AND ENDDA>='$date') me "
                . "JOIN (SELECT PERNR,BEGDA,ENDDA,PERSG,PERSK,ORGEH,PLANS,WERKS,ABKRS FROM tm_emp_org WHERE BEGDA<='$date' AND ENDDA>='$date') eo ON me.PERNR=eo.PERNR "
                . "LEFT JOIN (SELECT OBJID,STEXT FROM tm_master_org WHERE OTYPE='S' AND BEGDA<='$date' AND ENDDA>='$date') pos ON eo.PLANS=pos.OBJID "
                . "LEFT JOIN (SELECT OBJID,STEXT FROM tm_master_org WHERE OTYPE='O' AND BEGDA<='$date' AND ENDDA>='$date') org ON eo.ORGEH=org.OBJID "
                . "LEFT JOIN (SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY=5) mwerks ON eo.WERKS=mwerks.SHORT "
                . "LEFT JOIN (SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY=5) mabkrs ON eo.ABKRS=mabkrs.SHORT "
                . "LEFT JOIN (SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY=3) mpersg ON eo.PERSG=mpersg.SHORT "
                . "LEFT JOIN (SELECT SHORT,STEXT FROM tm_master_abbrev WHERE SUBTY=4) mpersk ON eo.PERSK=mpersk.SHORT ";
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
        $objPHPExcel->getActiveSheet()->setCellValue("A1","PERNR")
                ->setCellValue("B1","NAME")
                ->setCellValue("C1","BEGDA")
                ->setCellValue("D1","ENDDA")
                ->setCellValue("E1","PLANS")
                ->setCellValue("F1","POSITION_TEXT")
                ->setCellValue("G1","ORGEH")
                ->setCellValue("H1","ORG_TEXT")
                ->setCellValue("I1","PERSG")
                ->setCellValue("J1","PERSG_TEXT")
                ->setCellValue("K1","PERSK")
                ->setCellValue("L1","PERSK_TEXT")
                ->setCellValue("M1","WERKS")
                ->setCellValue("N1","WERKS_TEXT")
                ->setCellValue("O1","ABKRS")
                ->setCellValue("P1","ABKRS_TEXT");
        for($i=0;$i<count($data);$i++){
            $objPHPExcel->getActiveSheet()->setCellValue("A".(2+$i),$data[$i]['PERNR'])
                ->setCellValue("B".(2+$i),$data[$i]['CNAME'])
                ->setCellValue("C".(2+$i),$data[$i]['BEGDA'])
                ->setCellValue("D".(2+$i),$data[$i]['ENDDA'])
                ->setCellValue("E".(2+$i),$data[$i]['PLANS'])
                ->setCellValue("F".(2+$i),$data[$i]['POSITION_TEXT'])
                ->setCellValue("G".(2+$i),$data[$i]['ORGEH'])
                ->setCellValue("H".(2+$i),$data[$i]['ORG_TEXT'])
                ->setCellValue("I".(2+$i),$data[$i]['PERSG'])
                ->setCellValue("J".(2+$i),$data[$i]['PERSG_TEXT'])
                ->setCellValue("K".(2+$i),$data[$i]['PERSK'])
                ->setCellValue("L".(2+$i),$data[$i]['PERSK_TEXT'])
                ->setCellValue("M".(2+$i),$data[$i]['WERKS'])
                ->setCellValue("N".(2+$i),$data[$i]['WERKS_TEXT'])
                ->setCellValue("O".(2+$i),$data[$i]['ABKRS'])
                ->setCellValue("P".(2+$i),$data[$i]['ABKRS_TEXT']);
        }
                

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="OrgEmp_' . $date . '_on_' . date("Ymd") . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}

?>
