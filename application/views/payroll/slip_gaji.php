<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Slip Gaji
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Period</label>
                        <div class="col-md-3 col-xs-11">
                            <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="yyyy-mm" data-date=""  class="input-append date dpMonths" id="cPeriodRegular">
                                <input type="text" value="" size="16" class="form-control" id="fPeriodRegular" name="fPeriodRegular">
                                    <span class="input-group-btn add-on">
                                      <button class="btn btn-danger" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fnik" class="col-lg-2 col-sm-2 control-label">Nopeg</label>
                        <div class="col-md-3 col-xs-11">
                            <input type="text" class="form-control" id="fnik" name="fnik" value="" style="padding: 0.5px 0px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-default" id="fProcess">Check</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <section class="panel">
            <header class="panel-heading">
                Payroll Sip Gaji 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div id="pdf_content" style="height: 800px;">
                </div>
            </div>
        </section>
    </div>
</section>