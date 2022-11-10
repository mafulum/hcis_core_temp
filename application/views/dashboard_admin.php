 
<script src="<?= $base_url; ?>/themes/js/jquery.min.js"></script> 
<script>window.jQuery||document.write('<script src="<?= $base_url; ?>/themes/js/libs/jquery-1.6.4.min.js"><\/script>');</script> 
<script src="<?= $base_url; ?>/themes/js/jquery-ui.min.js"></script>
<script>window.jQuery.ui||document.write('<script src="<?= $base_url; ?>/themes/js/libs/jquery-ui-1.8.16.min.js"><\/script>');</script> 
<script defer src='<?= $base_url; ?>/themes/js/009eff1.js'></script>

<!-- popup -->
<link rel="stylesheet" href="<?= $base_url; ?>/themes/js/colorbox/colorbox.css" type="text/css"/>
<script type="text/javascript" src="<?= $base_url; ?>/themes/js/colorbox/jquery.colorbox-min.js"></script>

<script type="text/javascript">
    var base_url="<?= $base_url; ?>"
    $(function() {
        $("#chgpass").click(function(){
            $.fn.colorbox({width:"80%", height:"80%", iframe:true,href:base_url+"/home/changepass"});
        });
        $(".btnAct").click(function(){
            var iID = $(this).attr('id').substring(1,8);
            var iTipe = $(this).attr('id').substring(0,1);
            if(iTipe=='O'){
                $.fn.colorbox({width:"80%", height:"80%", iframe:true,href:base_url+"espkl_sup/approve/"+iID,
                    onClosed: function (message) {
                        document.location='dashboard';
                    }});
            }
        });
        $(".btnApp").click(function(){

            var iID = $(this).attr('id').substring(2,9);
            var iX = $(this).attr('id').substring(1,2);
            var iTipe = $(this).attr('id').substring(0,1);
            if(iX=="V"){
                if(iTipe == 'A'){
                    $("#dialog-confirm").attr('title','Approve Hadir Manual');
                    $("#dialog-confirm").dialog( "option", "title", "Approve Hadir Manual" )
                    iTipe = 1;
                }else{
                    $("#dialog-confirm").attr('title','Reject Hadir Manual');
                    $("#dialog-confirm").dialog( "option", "title", "Reject Hadir Manual" )
                    iTipe = 0;
                }

                $("#dialog-confirm").dialog({
                    resizable: false,
                    height:160,
                    modal: true,
                    open: function(event, ui){
                        $("#fKet").val("");
                    },
                    buttons: {
                        "Save": function() {
                            $(".ui-dialog-buttonset").hide();
                            var Ket = $("#fKet").val();
                            if(Ket==''){
                                alert('Alasan mohon diisi');
                                $(".ui-dialog-buttonset").show();
                            }else{
                                $.ajax({
                                    url: "<?= $base_url; ?>hadir_manual/approve",
                                    type: 'POST',
                                    data: {'id': iID,'apv':iTipe,'ket':Ket},
                                    success: function(data){
                                        alert(data);
                                        $(".ui-dialog-buttonset").show();
                                        $("#dialog-confirm").dialog("close");
                                        document.location='dashboard';
                                    }
                                });
                            }
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    }
                });
            }else if(iX=='L'){
                if(iTipe == 'A'){
                    $("#dialog-confirm").attr('title','Approve Leave');
                    $("#dialog-confirm").dialog( "option", "title", "Approve Leave" )
                    iTipe = 1;
                }else{
                    $("#dialog-confirm").attr('title','Reject Leave');
                    $("#dialog-confirm").dialog( "option", "title", "Reject Leave" )
                    iTipe = 0;
                }

                $("#dialog-confirm").dialog({
                    resizable: false,
                    height:160,
                    modal: true,
                    open: function(event, ui){
                        $("#fKet").val("");
                    },
                    buttons: {
                        "Save": function() {
                            $(".ui-dialog-buttonset").hide();
                            var Ket = $("#fKet").val();
                            if(Ket==''){
                                alert('Alasan mohon diisi');
                                $(".ui-dialog-buttonset").show();
                            }else{
                                $.ajax({
                                    url: "<?= $base_url; ?>leave/approve",
                                    type: 'POST',
                                    data: {'id': iID,'apv':iTipe,'ket':Ket},
                                    success: function(data){
                                        alert(data);
                                        $(".ui-dialog-buttonset").show();
                                        $("#dialog-confirm").dialog("close");
                                        document.location='dashboard';
                                    }
                                });
                            }
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    }
                });
            }
                        
    			
            return false;
    			
        });
    });
</script>
<div id="dialog-confirm" title="Approve Leave ?" style="display: none;">
    <span><b>Alasan / Pesan * :</b> <input class="input-edit" id="fKet" name="fKet" type="text" value=""></span>
</div>
<div role="main" class="container_12" id="content-wrapper"> 

    <div id="main_content"> 
        <div class="grid_12">
            <div class="box"> 
                <div class="header"> 
                    <img src="<?= $base_url; ?>/themes/img/icons/packs/fugue/16x16/shadeless/table.png" width=16 height=16> 
                    <h3>Information Message</h3><span></span> 
                </div> 
                <div class="content"> 
                    <? if (!empty($msg)) { ?>
                        <table class="table" id="tblTime"> 						
                            <thead> 
                                <tr>								
                                    <th>No.</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr> 
                            </thead> 
                            <tbody>
                                <? foreach ($msg as $i => $oMsg) { ?>
                                    <tr>
                                        <td class="viewemp" style="cursor:pointer;"	id="C0<?= $oMsg->msg_id; ?>"><span><?= ($i + 1); ?></span></td>
                                        <td class="viewemp" style="cursor:pointer;"	id="C1<?= $oMsg->msg_id; ?>"><span><?= $oMsg->subject; ?></span></td>
                                        <td class="viewemp" style="cursor:pointer;"	id="C3<?= $oMsg->msg_id; ?>"><span><?= $oMsg->msg; ?></span></td>
                                        <td class="viewemp" style="cursor:pointer;"	id="C6<?= $oMsg->msg_id; ?>"><span><?= $oMsg->t_input; ?></span></td>
                                        <td>
                                            <?
                                            $objType = substr($oMsg->objid, 0, 1);
                                            $needAction = false;
                                            $msg = "";
                                            if ($objType == 'O') {
                                                $ci = & get_instance();
                                                $ci->load->model('espkl_m');
                                                $status = $ci->espkl_m->get_overtime_status_ot($oMsg->objid);
                                                if ($status == 2)
                                                    $needAction = true;
                                                if ($status == 0)
                                                    $msg = 'Rejected';
                                                else if ($status == 1)
                                                    $msg = 'Approved';
                                            }else if ($objType == 'L') {
                                                $ci = & get_instance();
                                                $ci->load->model('leave_m');
                                                $status = $ci->leave_m->get_leave_status($oMsg->objid);
                                                if ($status == 4)
                                                    $needAction = true;
                                                if ($status == 0)
                                                    $msg = 'Rejected IDH';
                                                else if ($status == 1)
                                                    $msg = 'Approved IDH';
                                                else if ($status == 2)
                                                    $msg = 'Rejected Sup';
                                                else if ($status == 3)
                                                    $msg = 'Approved Sup';
                                                else if ($status == 5)
                                                    $msg = 'Cancelled';
                                            }else if ($objType == 'V') {
                                                $ci = & get_instance();
                                                $ci->load->model('hadir_m');
                                                $status = $ci->hadir_m->get_status($oMsg->objid);
                                                if ($status == 2)
                                                    $needAction = true;
                                                if ($status == 0)
                                                    $msg = 'Rejected';
                                                else if ($status == 1)
                                                    $msg = 'Approved';
                                            }
                                            if ($needAction) {
                                                if ($objType == "O") {
                                                    echo "<button style=\"display: inline;\" class=\"btnAct\" id=\"" . $oMsg->objid . "\">Action</button>";
                                                } else if ($objType == "L" || $objType == "V") {
                                                    echo"<span><button style=\"display: inline;\" class=\"btnApp\" id=\"A" . $oMsg->objid . "\">Approve</button>";
                                                    echo"| <button style=\"display: inline;\" class=\"btnApp\" id=\"R" . $oMsg->objid . "\">Reject</button></span>";
                                                }
                                            } else {
                                                $this->global_m->update_read_objid($oMsg->objid);
                                                echo $msg;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody> 
                        </table>
                    <? } else { ?>
                        <h3>No Message Found </h3><span></span>
                    <? } ?>
                </div> 
                <div class="clear"></div> 
            </div> </div>
    </div>
    <div class="push clear"></div> </div> 