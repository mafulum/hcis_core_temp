<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Running
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="fPeriod" class="col-lg-2 col-sm-2 control-label">Date Of Payment</label>
                        <div class="col-lg-10">
                            <select id="fDateOfPayment" name="fDateOfPayment">
                            <option value="" selected>None (For OffCycle Purpose)</option>
                            <option value="26">26</option>
                            <option value="28">28</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fIsReg" class="col-lg-2 col-sm-2 control-label">Regular(On) / Off-Cycle(Off)</label>
                        <div class="col-lg-10">
                            <input type="checkbox" data-toggle="switch" id="fIsReg" name="fIsReg" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fDateOffCycle" class="col-lg-2 col-sm-2 control-label">Date Off-Cycle</label>
                        <div class="col-md-3 col-xs-11">
                            <input class="form-control form-control-inline input-medium "  size="16" type="text" value="" id="fDateOffCycle" name="fDateOffCycle" />
                            <span class="help-block">Select date</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Period of Regular</label>
                        <div class="col-md-3 col-xs-11">
                            <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="yyyy-mm" data-date=""  class="input-append date dpMonths" id="cPeriodRegular">
                                <input type="text" value="" size="16" class="form-control" id="fPeriodRegular" name="fPeriodRegular">
                                    <span class="input-group-btn add-on">
                                      <button class="btn btn-danger" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                            </div>
                            <span class="help-block">Select month only</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name/Nopeg</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fnik" name="fnik" value="" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fPERSG" class="col-lg-2 col-sm-2 control-label">Employee Group</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fPERSG" name="fPERSG" value="" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fPERSK" class="col-lg-2 col-sm-2 control-label">Employee SubGroup</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fPERSK" name="fPERSK" value="" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fABKRS" class="col-lg-2 col-sm-2 control-label">Payroll Area</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="fABKRS" name="fABKRS" value="" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-default" name="" id="fProcess">Process</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll - Management Summary <span id="title"></span>
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
                                <th class="all">PERSG</th>
                                <th class="all">PERSK</th>
                                <th class="all">ABKRS</th>
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
                                <td>9</td>
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
                                <th class="all">PERSG</th>
                                <th class="all">PERSK</th>
                                <th class="all">ABKRS</th>
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
                                <td>1</td>
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
                                <th class="all">ABKRS</th>
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
                                <th class="all">ABKRS</th>
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
                                <th class="all">ABKRS</th>
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
                                <th class="all">ABKRS</th>
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
        
        <section class="panel" id="panelConfirmation">
            <header class="panel-heading">
                Payroll - Confirmation Running Payroll
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="<?php echo base_url();?>index.php/payroll/running_result/action">
                    <div class="form-group">
                        <label for="f" class="col-lg-2 col-sm-2 control-label">Code Payroll</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelCodePayroll">-</label>
                        <input type="hidden" name="formCodePayroll" value="" id="formCodePayroll"/>
                    </div>
                    <div class="form-group">
                        <label for="f" class="col-lg-2 col-sm-2 control-label">Name of This Process</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="forNameProcess" name="forNameProcess" value="" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="f" class="col-lg-2 col-sm-2 control-label">Regular(On) / Off-Cycle(Off)</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelIsReg">-</label>
                        <input type="hidden" name="formIsReg" value="" id="formIsReg"/>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Date Off-Cycle</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelDateOffCycle">-</label>
                        <input type="hidden" name="formDateOffCycle" value="" id="formDateOffCycle"/>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Period of Regular</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelPeriodRegular">-</label>
                        <input type="hidden" name="formPeriodRegular" value="" id="formPeriodRegular"/>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name/NIK</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelPernrs">-</label>
                        <input type="hidden" name="formPernrs" value="" id="formPernrs"/>
                    </div>
                    <div class="form-group">
                        <label for="fPERSG" class="col-lg-2 col-sm-2 control-label">Employee Group</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelPersgs">-</label>
                        <input type="hidden" name="formPersgs" value="" id="formPersgs"/>
                    </div>
                    <div class="form-group">
                        <label for="fPERSK" class="col-lg-2 col-sm-2 control-label">Employee SubGroup</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelPersks">-</label>
                        <input type="hidden" name="formPersks" value="" id="formPersks"/>
                    </div>
                    <div class="form-group">
                        <label for="fABKRS" class="col-lg-2 col-sm-2 control-label">Payroll Area</label>
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label" id="labelAbkrs">-</label>
                        <input type="hidden" name="formAbkrs" value="" id="formAbkrs"/>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-warning" name="draft" value="draft">Save as Draft</button>
                            <button type="submit" class="btn btn-success" name="confirm" value="confirm">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</section>