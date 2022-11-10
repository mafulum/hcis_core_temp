
<section class="wrapper">
    <!--state overview start-->
    <div class="row state-overview">
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol terques">
                    <i class="fa fa-user"></i>
                </div>
                <div class="value">
                    <h1 class="count">
                        <? echo $this->global_m->get_count_master_emp(); ?>
                    </h1>
                    <p>on Master Employee</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol red">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="value">
                    <h1 class=" count2">
                        <? $pos = $this->global_m->get_count_all_position();
                        echo $pos; ?>
                    </h1>
                    <p>Registered Position</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol yellow">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="value">
                    <h1 class=" count3">
<? $fil = $this->global_m->get_fill_registered_position();
echo $fil; ?>
                    </h1>
                    <p>Filled Position</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol blue">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="value">
                    <h1 class=" count4">
<? echo number_format(($fil * 100 / $pos), 0) . " %"; ?>
                    </h1>
                    <p>All Position</p>
                </div>
            </section>
        </div>
    </div>
    
    <!--state overview end-->
    <div class="row">
        <div class="col-lg-12">
            <!--work progress start-->
            <section class="panel">
                <div class="panel-body progress-panel">
                    <div class="task-progress">
                        <h1>Holding & Company Overview</h1>
                    </div>
                </div>
                <table class="table table-hover personal-task">
                    <tbody>
                        <tr>
                            <th>Number</th>
                            <th>Company</th>
                            <th>Emp</th>
                            <th>Position</th>
                            <th style="text-align: center;">Percentage Fill Position</th>
                        </tr>
                        <?
                        $aCompany=$this->global_m->get_company_profile();
                        for($i=0;$i<count($aCompany);$i++){
                            echo "<tr>";
                            echo "<td>".($i+1)."</td>";
                            echo "<td>".$aCompany[$i]['SHORT']." - ".$aCompany[$i]['STEXT']."</td>";
                            echo "<td>".$aCompany[$i]['nEmp']."</td>";
                            echo "<td>".$aCompany[$i]['nPos']."</td>";
                            echo "<td style=\"text-align:center;\"><span class=\"badge bg-important\">".(empty($aCompany[$i]['nPos'])?"0":number_format($aCompany[$i]['nPosFil']*100/$aCompany[$i]['nPos'],0))."%</span></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
            <!--work progress end-->
        </div>
    </div>
</section>