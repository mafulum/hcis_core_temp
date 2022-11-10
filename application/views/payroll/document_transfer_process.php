<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Document Transfer Process
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="<?php echo base_url()."index.php/payroll/document_transfer/act_process/";?>">
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name of Document Transfer</label>
                         <?php if(empty($dt)){ ?>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="name" value="<?php if(!empty($name)){echo $name;}?>" style="padding: 0.5px 0px;">
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($dt['name'])?  $dt['name'] : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fBTS" class="col-lg-2 col-sm-2 control-label">Bank Transfer Stage</label>
                        <?php if(empty($dt)){ ?>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fBTS" name="fBTS" value="<?php if(!empty($bts_code)){echo $bts_code;}?>" style="padding: 0.5px 0px;">
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($stages)?  $stages : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <a href="<?php echo base_url()."index.php/payroll/document_transfer";?>" class="btn btn-default"/>Back</a>
                        <?php if(empty($confirm) && empty($dt)){ ?>
                            <button type="submit" class="btn btn-default" name="confirm" value="confirm" id="fConfirm">Confirm</button>
                        <?php }else{ ?>
                            <a href="<?php echo base_url()."index.php/payroll/slip_gaji/generate_slip_regular/".$dt['id'];?>" class="btn btn-success" name="genSlipDocTransfer" value="genSlipDocTransfer" id="fGenSlipDocTransfer">Generate Slip Gaji</a>
                        <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <?php if(!empty($confirm) || !empty($review) || !empty($dt)) { ?>
        <?php if(!empty($confirm)|| !empty($dt)){
            $found_=false;
            foreach($aIDHostbank as $key=>$value){
                if(!empty($aDocBankContent[$key])){ 
                if($key==1){
                    $found_=true;
                }    
                ?>
                <section class="panel">
            <header class="panel-heading">
                Payroll - <?php echo (!empty($confirm))? strtoupper($confirm):""; ?>Document Transfer <?php echo $key."|".$value['name']; ?>
                <span class="tools pull-right">
                    <?php if($key=="1") { ?>
                    <a class="fa fa-download" href="<?php echo base_url()."index.php/payroll/document_transfer/download_bank_tranfer_cms/".$dt['id']."/".$key;?>"> Download for CMS</a>
                    <?php }if($key!='others') { ?>
                    <a class="fa fa-download" href="<?php echo base_url()."index.php/payroll/document_transfer/download_document_tranfer/".$dt['id']."/".$key;?>"> Download</a>
                    <?php }else if($found_==false) { ?>
                        <a class="fa fa-download" href="<?php echo base_url()."index.php/payroll/document_transfer/download_document_tranfer/".$dt['id']."/1";?>"> Download</a>
                    <?php } ?>
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="bt_stage_<?php echo $key;?>">
                        <thead>
                            <tr>
                                <th class="all">PERNR</th>
                                <th class="all">Payroll Area</th>
                                <th class="all">Bank Name</th>
                                <th class="all">Bank Payee</th>
                                <th class="all">Bank Account</th>
                                <th class="all">AMOUNT</th>

                            </tr>
                        </thead>
                        <tbody>
<?php                    foreach($aDocBankContent[$key] as $row){ 
                        $pernr="";
                        $abkrs="";
                        $mapid=$row['BANK_ID']."_".$row['BANK_ACCOUNT'];
                        if(!empty($kv_eta) && !empty($kv_eta[$mapid])){
                            $pernr=$kv_eta[$mapid]['PERNR'];
                            $abkrs=$kv_eta[$mapid]['ABKRS'];
                        }
?>
                         <tr>
                                <td><?php echo $pernr;?></td>
                                <td><?php echo $abkrs;?></td>
                                <td><?php echo $row['BANK_NAME'];?></td>
                                <td><?php echo $row['BANK_PAYEE'];?></td>
                                <td><?php echo $row['BANK_ACCOUNT'];?></td>
                                <td><?php echo number_format($row['WAMNT']);?></td>
                            </tr>
<?php                    } ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </section>
                            
<?php                }
            }
        ?>
        <?php } ?>
        <?php if(!empty($emp_transfer_abkrs)){ ?>
        <section class="panel">
            <header class="panel-heading">
                Payroll - <?php echo (!empty($confirm))? strtoupper($confirm):""; ?>Bank Transfer Report
                <span class="tools pull-right">
                    <a class="fa fa-download" href="<?php echo base_url()."index.php/payroll/document_transfer/download_bank_tranfer/".$dt['id'];?>"> Download for Report Only</a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="bt_report">
                        <thead>
                            <tr>
                                <th class="all">ABKRS</th>
                                <th class="all">PERNR</th>
                                <th class="all">Bank Name</th>
                                <th class="all">Bank Payee</th>
                                <th class="all">Bank Account</th>
                                <th class="all">AMOUNT</th>

                            </tr>
                        </thead>
                        <tbody>
<?php                    foreach($emp_transfer_abkrs as $row){ ?>
                         <tr>
                                <td><?php echo $row['ABKRS'];?></td>
                                <td><?php echo $row['PERNR'];?></td>
                                <td><?php echo $row['BANK_NAME'];?></td>
                                <td><?php echo $row['BANK_PAYEE'];?></td>
                                <td><?php echo $row['BANK_ACCOUNT'];?></td>
                                <td><?php echo number_format($row['SUM_WAMNT']);?></td>
                            </tr>
<?php                    } ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </section>
        <?php } ?>
        <?php } ?>
    </div>
</section>