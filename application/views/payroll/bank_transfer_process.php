<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Bank Transfer Process
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="<?php echo base_url()."index.php/payroll/bank_transfer/act_process/";?>">
                    <input type="hidden" name="id_bank_transfer" value="<?php echo $id_bank_transfer;?>"/>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Regular(On) / Off-Cycle(Off)</label>
                        <?php if(empty($bt)){ ?>
                        <div class="col-lg-10">
                            <input type="checkbox" data-toggle="switch" id="fIsReg" name="fIsReg" value="on" <?php if(!empty($is_reg)){echo "checked";} ?> />
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($bt['is_offcycle'])?  "Off-Cycle(Off)": "Regular(On)" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Date Off-Cycle</label>
                         <?php if(empty($bt)){ ?>
                        <div class="col-md-3 col-xs-11">
                            <input class="form-control form-control-inline input-medium " size="16" type="text" value="<?php if(!empty($date_offcycle)){echo $date_offcycle;}?>" id="fDateOffCycle" name="fDateOffCycle" />
                            <span class="help-block">Select date</span>
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($bt['date_offcycle'])?  $bt['date_offcycle'] : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Period of Regular</label>
                         <?php if(empty($bt)){ ?>
                        <div class="col-md-3 col-xs-11">
                            <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="yyyy-mm" data-date=""  class="input-append date dpMonths" id="cPeriodRegular">
                                <input type="text" size="16" class="form-control" id="fPeriodRegular" name="fPeriodRegular" value="<?php if(!empty($period_regular)){echo $period_regular;}?>">
                                    <span class="input-group-btn add-on">
                                      <button class="btn btn-danger" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                            </div>
                            <span class="help-block">Select month only</span>
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($bt['periode_regular'])?  $bt['periode_regular'] : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name of Bank Transfer</label>
                         <?php if(empty($bt)){ ?>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="name" value="<?php if(!empty($name)){echo $name;}?>" style="padding: 0.5px 0px;">
                        </div>
                        <?php }else{ ?>
                        <label for="fnik" class="col-lg-10 col-sm-2 control-label"> <?php echo ($bt['name'])?  $bt['name'] : "-" ; ?> </label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name of Stage</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="name_stage" value="<?php if(!empty($name_stage)){echo $name_stage;}?>" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fABKRS" class="col-lg-2 col-sm-2 control-label">Payroll Area</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fABKRS" name="fABKRS" value="<?php if(!empty($abkrs)){echo $abkrs;}?>" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Percentage Transfer</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" name="percentage"value="<?php if(!empty($percentage)){echo $percentage;}?>" style="padding: 0.5px 0px;">
                        </div>
                        <label for="fnik" class="col-lg-8 col-sm-2 control-label">% (left it empty if 100% or full remain of processed percentage)</label>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <a href="<?php echo base_url()."index.php/payroll/bank_transfer";?>" class="btn btn-default"/>Back</a>
                            <button type="submit" class="btn btn-default" name="review" value="review" id="fReview">Review</button>
                            <button type="submit" class="btn btn-default" name="confirm" value="confirm" id="fConfirm">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Confirmed Stage Process for info Off-Cycle / Regular at Date Off-Cycle / Month Year
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="bt_stage">
                        <thead>
                            <tr>
                                <th class="all">STAGE_ID</th>
                                <th class="all">STAGE_NAME</th>
                                <th class="all">Payroll Area</th>
                                <th class="all">Percentage</th>
                                <th class="all">Confirmed at</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($bts)){ ?>
                            <tr>
                                <td colspan="5">no data</td>
                            </tr>                            
                            <?php }else{
                                foreach($bts as $row){
                                ?>
                            <tr>
                                <td><?php echo $row['id'];?></td>
                                <td><?php echo $row['name'];?></td>
                                <td><?php echo $row['ABKRS'];?></td>
                                <td><?php echo $row['percentage_emp'];?></td>
                                <td><?php echo $row['created_at'];?></td>
                            </tr>
                                <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section class="panel">
            <header class="panel-heading">
                Payroll - SUMMARY Review with Payroll Area [<span id="PRPayrollArea"></span>] and Percentage [<span id="PRpercentage"></span>] Review / Confirmed At : 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="bt_summary">
                        <thead>
                            <tr>
                                <th class="all">Payroll Area / Customer</th>
                                <th class="all">Employee Transfer</th>
                                <th class="all">BPJS-TK</th>
                                <th class="all">BPJS-Kes</th>
                                <th class="all">Tax</th>
                                <th class="all">Pihak Ke-3</th>
                                <th class="all">Employee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($bto)){ 
                                foreach($bto as $abkrs => $con){
                                ?>
                            <tr>
                                <td><?php echo $abkrs;?></td>
                                <td><?php echo $bto[$abkrs]['sum_transfer'];?></td>
                                <td><?php echo $bto[$abkrs]['bpjs_tk'];?></td>
                                <td><?php echo $bto[$abkrs]['bpjs_jkn'];?></td>
                                <td><?php echo $bto[$abkrs]['tax'];?></td>
                                <td><?php echo $bto[$abkrs]['pihak_3'];?></td>
                                <td><?php echo $bto[$abkrs]['an_emp'];?></td>
                            </tr>
                                <?php }
                            }else if(empty($run_payroll) || empty($run_payroll['abkrs'])){
                                ?><tr><td colspan="7">no-data</td></tr><?php
                            }else{ 
                                $colname_summary = array('sum_transfer','bpjs_tk','bpjs_jkn','tax','pihak_3','an_emp');
                                $aABKRS=array();
                                foreach($colname_summary as $colname){
                                    if(!empty($run_payroll[$colname])){
                                        foreach($run_payroll[$colname] as $abkrs=>$wamnt){
                                            if(!in_array($abkrs,$aABKRS)){
                                                $aABKRS[]=$abkrs;
                                            }
                                        }
                                    }
                                }
                                foreach($aABKRS as $abkrs){
                                ?><tr><td><?php echo $abkrs;?></td>
                                    <td><?php if(!empty($run_payroll['sum_transfer'][$abkrs])){ echo number_format($run_payroll['sum_transfer'][$abkrs]); } ?></td>
                                    <td><?php if(!empty($run_payroll['bpjs_tk'][$abkrs])){ echo number_format($run_payroll['bpjs_tk'][$abkrs]); } ?></td>
                                    <td><?php if(!empty($run_payroll['bpjs_jkn'][$abkrs])){ echo number_format($run_payroll['bpjs_jkn'][$abkrs]); } ?></td>
                                    <td><?php if(!empty($run_payroll['tax'][$abkrs])){ echo number_format($run_payroll['tax'][$abkrs]); } ?></td>
                                    <td><?php if(!empty($run_payroll['pihak_3'][$abkrs])){ echo number_format($run_payroll['pihak_3'][$abkrs]); } ?></td>
                                    <td><?php if(!empty($run_payroll['an_emp'][$abkrs])){ echo number_format($run_payroll['an_emp'][$abkrs]); } ?></td>
                                </tr> <?php
                            } 
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel" id="panelReview">
            <header class="panel-heading">
                Payroll - Confirmed All (Before In Just Confirmed): 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="bt_emp">
                        <thead>
                            <tr>
                                <th class="all">ABKRS</th>
                                <th class="all">Nopeg</th>
                                <th class="all">Bank Order</th>
                                <th class="all">Bank Name</th>
                                <th class="all">Bank Payee</th>
                                <th class="all">Bank Account</th>
                                <th class="all">Percentage</th>
                                <th class="all">Payroll Amount</th>
                                <th class="all">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($bte)){ 
                                foreach($bte as $row){
                                ?>
                            <tr>
                                <td><?php echo $row['ABKRS'];?></td>
                                <td><?php echo $row['PERNR'];?></td>
                                    <td><?php echo $row['BANK_ORDER']; ?></td>
                                    <td><?php echo $row['BANK_NAME']; ?></td>
                                    <td><?php echo $row['BANK_PAYEE']; ?></td>
                                    <td><?php echo $row['BANK_ACCOUNT']; ?></td>
                                    <td><?php echo $row['sum_percentage']; ?></td>
                                    <td><?php echo number_format($row['avg_tfmnt']); ?></td>
                                    <td><?php echo number_format($row['sum_wamnt']); ?></td>
                            </tr>
                                <?php }
                            }else {
                                ?><tr><td colspan="9">no-data</td></tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel" id="panelReview">
            <header class="panel-heading">
                Payroll - Review/Just Confirmed 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="bt_emp">
                        <thead>
                            <tr>
                                <th class="all">ABKRS</th>
                                <th class="all">Nopeg</th>
                                <th class="all">Bank Order</th>
                                <th class="all">Bank Name</th>
                                <th class="all">Bank Payee</th>
                                <th class="all">Bank Account</th>
                                <th class="all">Percentage Rule</th>
                                <th class="all">Percentage</th>
                                <th class="all">Payroll Amount</th>
                                <th class="all">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(empty($run_payroll) || empty($run_payroll['transfer'])){
                                ?><tr><td colspan="9">no-data</td></tr><?php
                            }else{ foreach($run_payroll['transfer'] as $row){
                                if(empty($row['WAMNT'])){
                                    continue;
                                }
                                ?><tr><td><?php echo $row['ABKRS'];?></td>
                                <td><?php echo $row['PERNR'];?></td>
                                    <td><?php echo $row['BANK_ORDER']; ?></td>
                                    <td><?php echo $row['BANK_NAME']; ?></td>
                                    <td><?php echo $row['BANK_PAYEE']; ?></td>
                                    <td><?php echo $row['BANK_ACCOUNT']; ?></td>
                                    <td><?php echo $row['percentage_rule']; ?></td>
                                    <td><?php echo $row['percentage']; ?></td>
                                    <td><?php echo $row['TFMNT']; ?></td>
                                    <td><?php echo $row['WAMNT']; ?></td>
                                </tr> <?php
                            } 
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
    </div>
</section>