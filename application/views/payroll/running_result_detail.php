<section class="wrapper">
    <div class="col-lg-12">
        
        <section class="panel" id="panelConfirmation">
            <header class="panel-heading">
                Payroll Running Result - Detail
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="<?php if(isset($prr['by_confirm'])){ echo "#"; }else{ echo base_url(); }?>index.php/payroll/running_result/action">
                    <input type="hidden" name="id" value="<?php echo $prr['id'];?>" id="id"/>
                    <input type="hidden" name="formCodePayroll" value="<?php echo $prr['time_running'];?>" id="id"/>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Code Payroll</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['time_running'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Name of This Process</label>
                        <?php if(isset($prr['name_of_process'])){ ?>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['name_of_process'];?></label>
                        <?php }else{ ?>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="forNameProcess" name="forNameProcess" value="" style="padding: 0.5px 0px;">
                        </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Regular(On) / Off-Cycle(Off)</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['is_offcycle'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Date Off-Cycle</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['date_offcycle'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Period of Regular</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['periode_regular'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">PERNR</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['pernr'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Employee Group</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['persg'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Employee SubGroup</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['persk'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Payroll Area</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['abkrs'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Draft</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['by_draft'];?> / <?php echo $prr['time_draft'];?></label>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Confirm</label>
                        <label class="col-lg-2 col-sm-2 control-label"><?php echo $prr['by_confirm'];?> / <?php echo $prr['time_confirm'];?></label>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <a href="<?php echo base_url();?>index.php/payroll/running_result" class="btn btn-default"/>Back</a>
                            <?php if(isset($prr['by_draft'])==false and isset($prr['by_confirm'])==false ){ ?>
                            <button type="submit" class="btn btn-warning" name="draft" value="draft">Save as Draft</button>
                            <?php } if(isset($prr['by_confirm'])==false){ ?>
                            <button type="submit" class="btn btn-success" name="confirm" value="confirm">Confirm</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Management Summary
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-table">
                        <thead>
                            <tr>
                                <th class="all">Company/Customer</th>
                                <th class="all">Salary</th>
                                <th class="all">BPJS-TK</th>
                                <th class="all">BPJS-Kes</th>
                                <th class="all">Tax</th>
                                <th class="all">Pihak Ke-3</th>
                                <th class="all">Employee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>6</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Pihak Ke3 - Management Summary
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-pihak3-table">
                        <thead>
                            <tr>
                                <th class="all">Name</th>
                                <th class="all">BankPayee</th>
                                <th class="all">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Bank Transfer - Management Summary
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-bank-table">
                        <thead>
                            <tr>
                                <th class="all">BankName</th>
                                <th class="all">Amount</th>
                                <th class="all">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Employee Data Result
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                        <thead>
                            <tr>
                                <th class="all">Nopeg</th>
                                <th class="all">Nama</th>
                                <th class="all">Gender</th>
                                <th class="all">Tgl Lahir</th>
                                <th class="all">Begda EmpOrg</th>
                                <th class="all">Endda EmpOrg</th>
                                <th class="all">PLANS</th>
                                <th class="all">ORGEH</th>
                                <th class="all">PERSG</th>
                                <th class="all">PERSK</th>
                                <th class="all">Payroll Area</th>
                                <th class="all">org short</th>
                                <th class="all">org stext</th>
                                <th class="all">pos stext</th>
                                <th class="all">NPWP</th>
                                <th class="all">DEPND</th>
                                <th class="all">BPJS_TK</th>
                                <th class="all">INSTY</th>
                                <th class="all">PRCTE</th>
                                <th class="all">PRCTC</th>
                                <th class="all">MAXRE</th>
                                <th class="all">MAXRC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>0</td>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>0</td>
                                <td>1</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Simulation - Payroll Employee Slip
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table-payroll-slip">
                        <thead>
                            <tr>
                                <th class="all">Persg</th>
                                <th class="all">Persk</th>
                                <th class="all">Nopeg</th>
                                <th class="all">WGTYP</th>
                                <th class="all">LGTXT</th>
                                <th class="all">PRTYP</th>
                                <th class="all">TNAME</th>
                                <th class="all">WAMNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Simulation - Payroll Employee
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table-payroll-emp">
                        <thead>
                            <tr>
                                <th class="all">Persg</th>
                                <th class="all">Persk</th>
                                <th class="all">Nopeg</th>
                                <th class="all">Begda</th>
                                <th class="all">ENdda</th>
                                <th class="all">WGTYPE</th>
                                <th class="all">LGTXT</th>
                                <th class="all">PRTYP</th>
                                <th class="all">TNAME</th>
                                <th class="all">WAMNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Simulation - Payroll BPJS
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table-payroll-bpjs">
                        <thead>
                            <tr>
                                <th class="all">Nopeg</th>
                                <th class="all">Begda</th>
                                <th class="all">ENdda</th>
                                <th class="all">WGTYPE</th>
                                <th class="all">LGTXT</th>
                                <th class="all">PRTYP</th>
                                <th class="all">TNAME</th>
                                <th class="all">WAMNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Simulation - Payroll Tax
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table-payroll-tax">
                        <thead>
                            <tr>
                                <th class="all">Nopeg</th>
                                <th class="all">Begda</th>
                                <th class="all">ENdda</th>
                                <th class="all">WGTYPE</th>
                                <th class="all">LGTXT</th>
                                <th class="all">PRTYP</th>
                                <th class="all">TNAME</th>
                                <th class="all">WAMNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Simulation - Payroll Accrued
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table-payroll-accrued">
                        <thead>
                            <tr>
                                <th class="all">Nopeg</th>
                                <th class="all">Begda</th>
                                <th class="all">ENdda</th>
                                <th class="all">WGTYPE</th>
                                <th class="all">LGTXT</th>
                                <th class="all">PRTYP</th>
                                <th class="all">TNAME</th>
                                <th class="all">WAMNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Simulation - Payroll Base
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table-payroll-base">
                        <thead>
                            <tr>
                                <th class="all">Nopeg</th>
                                <th class="all">Begda</th>
                                <th class="all">ENdda</th>
                                <th class="all">WGTYPE</th>
                                <th class="all">LGTXT</th>
                                <th class="all">PRTYP</th>
                                <th class="all">TNAME</th>
                                <th class="all">WAMNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Bank Transfer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="transfer-bank-table">
                        <thead>
                            <tr>
                                <th class="all">PERSG</th>
                                <th class="all">PERSK</th>
                                <th class="all">PERNR</th>
                                <th class="all">BankName</th>
                                <th class="all">BankAccount</th>
                                <th class="all">BankPayee</th>
                                <th class="all">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</section>