<?php

class Dashboard_m extends CI_model {

    public function __construct() {
        parent::__construct();
        $this->load->model('home_m');
        $this->load->model('global_m');
    }

    public function admin_dshb() {
        $data['base_url'] = $this->config->item('base_url');
        $data["userid"] = $this->session->userdata('username');
        $data["userid"] = $this->session->userdata('username');
        $data['view'] = 'dashboard_home';
        $this->load->model('employee_m');
        $data['aPerusahaan']= $this->employee_m->get_anak_perusahaan();
        $selected="";
        $aSelected=array();
        $selRegPos="";
        $aSel=array();
        if($_POST){
            $aSel = $this->input->post('unit');
            for($i=0;$i<count($aSel);$i++){
                if(!empty($selected)){
                    $selected.=",";
                    $selRegPos.=" OR ";
                }
                $selRegPos.="mr.SOBID LIKE '".substr($aSel[$i], 0,3)."%'";
                $selected.=$aSel[$i];
                $aSelected[]=$aSel[$i];
            }
        }else {
            for($i=0;$i<count($data['aPerusahaan']);$i++){
                if(!empty($selected)){
                    $selected.=",";
                    $selRegPos.=" OR ";
                }
                $selected.=$data['aPerusahaan'][$i]['OBJID'];
                $selRegPos.="mr.SOBID LIKE '".substr($data['aPerusahaan'][$i]['OBJID'], 0,3)."%'";
                $aSel[]=$data['aPerusahaan'][$i]['OBJID'];
            }
        }
        $data['selected']=$selected;
        $data['aSel']=$aSel;
        $data['selRegPos']=$selRegPos;
        $aUsia = $this->global_m->get_stat_usia($selected);
        $aGender = $this->global_m->get_stat_gender($selected);
        $aEdu = $this->global_m->get_stat_edu($selected);
        $aStell = $this->global_m->get_stat_persg($selected);
        $data['externalJS']='<script type="text/javascript" src="' . base_url() . 'assets/morris.js-0.4.3/morris.min.js"></script>';
        $data['externalJS'].='<script type="text/javascript" src="' . base_url() . 'assets/morris.js-0.4.3/raphael-min.js"></script>';
        $data['externalCSS']='<link href="' . base_url() . 'assets/morris.js-0.4.3/morris.css" rel="stylesheet">';
        $data['scriptJS']='
<script>
jQuery(document).ready(function() {
	Morris.Donut({
        element: "stell-donut",
        data: [';
        for($i=0;$i<count($aStell);$i++){
            if($i>0)$data['scriptJS'].=',';
            $data['scriptJS'].='{label: "'.$aStell[$i][0].'", value: '.$aStell[$i][1].' }';
            
        }
        $data['scriptJS'].='],
        formatter: function (y) { return y + "" }
      });
    Morris.Donut({
        element: "gender-donut",
        data: [';
        for($i=0;$i<count($aGender);$i++){
            if($i>0)$data['scriptJS'].=',';
            $data['scriptJS'].='{label: "'.$aGender[$i][0].'", value: '.$aGender[$i][1].' }';
            
        }
        $data['scriptJS'].='],
        formatter: function (y) { return y + "" }
      });
      Morris.Donut({
        element: "usia-donut",
        data: [';
        for($i=0;$i<count($aUsia);$i++){
            if($i>0)$data['scriptJS'].=',';
            $data['scriptJS'].='{label: "'.$aUsia[$i][0].'", value: '.$aUsia[$i][1].' }';
            
        }
        $data['scriptJS'].='
        ],
        formatter: function (y) { return y + "" }
      });
      Morris.Donut({
        element: "edu-donut",
        data: [';
        for($i=0;$i<count($aEdu);$i++){
            if($i>0)$data['scriptJS'].=',';
            $data['scriptJS'].='{label: "'.$aEdu[$i][0].'", value: '.$aEdu[$i][1].' }';
            
        }
        $data['scriptJS'].='
        ],
        formatter: function (y) { return y + "" }
      });
});
</script>
';
        return $data;
    }

    public function admin_mgr() {
        $data['base_url'] = $this->config->item('base_url');
        $data['view'] = 'dashboard_manager';
        $data["userid"] = $this->session->userdata('username');
        return $data;
    }

}