<section class="wrapper">
    <div class="col-lg-12">
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Confirm Uploaded
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th>Nopeg</th>
                                <th>Nama</th>
                                <th>ABKRS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($uploaded)) { ?>
                            <tr><td colspan="3">No Data</td></tr>
                            <?php }else { 
                                foreach($uploaded as $row){ ?>
                            <tr><td><?php echo $row->PERNR;?></td><td><?php echo $row->CNAME;?></td><td><?php echo $row->ABKRS;?></td></tr>
                                <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Conflict
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form method="POST" action="<?php echo base_url();?>index.php/payroll/running_result/action_confirm">
                    <input type="hidden" name="formCodePayroll" value="<?php echo $time_running;?>"/>
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th>CheckBox</th>
                                    <th>Nopeg</th>
                                    <th>Nama</th>
                                    <th>id Running Payroll</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($conflict)) { ?>
                                <tr><td colspan="4">No Data</td></tr>
                                <?php }else { 
                                    foreach($conflict as $row){ ?>
                                <tr><td><input type="checkbox" name="conflict[]" value="<?php echo $row->PERNR."_".$row->id_payroll_running;?>"/></td><td><?php echo $row->PERNR;?></td><td><?php echo $row->CNAME;?></td><td><?php echo $row->id_payroll_running;?></td></tr>
                                    <?php }
                                    } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success" name="confirm" value="confirm">Confirm</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Conflict Already Lock
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th>Nopeg</th>
                                <th>Nama</th>
                                <th>id Running Payroll</th>
                                <th>id Bank Transfer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($conflict_lock)) { ?>
                            <tr><td colspan="4">No Data</td></tr>
                            <?php }else { 
                                foreach($conflict_lock as $row){ ?>
                            <tr><td><?php echo $row->PERNR;?></td><td><?php echo $row->CNAME;?></td><td><?php echo $row->id_payroll_running;?></td><td><?php echo $row->id_bank_transfer;?></td></tr>
                                <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</section>