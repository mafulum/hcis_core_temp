<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Employee Validation
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Regular(On) / Off-Cycle(Off)</label>
                        <div class="col-lg-10">
                            <input type="checkbox" data-toggle="switch" id="fIsReg" name="fIsReg" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Date Off-Cycle</label>
                        <div class="col-md-3 col-xs-11">
                            <input class="form-control form-control-inline input-medium" data-date-format="yyyy-mm-dd"   size="16" type="text" value="" id="fDateOffCycle" name="fDateOffCycle" />
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
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Name/NIK</label>
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
                            <button type="button" class="btn btn-default" id="fProcess">Process</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <section class="panel">
            <header class="panel-heading">
                Payroll - Employee Validation Result
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
    </div>
</section>