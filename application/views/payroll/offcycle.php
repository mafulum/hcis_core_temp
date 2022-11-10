<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - OffCycle Payment  List
                <span class="tools pull-right">
                    <a class="fa fa-plus-square" href="<?php echo base_url()."index.php/payroll/offcycle/process";?>"></a>
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-table">
                        <thead>
                            <tr>
                                <th class="all">TimeRunnng</th>
                                <th class="all">BEGDA</th>
                                <th class="all">EVTDA</th>
                                <th class="all">Name</th>
                                <th class="all">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($oc)){ 
                                foreach($oc as $row){
                                ?>
                            <tr>
                                <td><?php echo $row['time_running'];?></td>
                                <td><?php echo $row['begda']; ?></td>
                                <td><?php echo $row['evtda']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td>
                                    <!--<a href="<?php echo base_url()."index.php/payroll/offcycle/view/".$row['id'];?>">view</a>-->
                                    <a href="<?php echo base_url()."index.php/payroll/offcycle/gen_slip/".$row['id'];?>">gen-slip</a>
                                </td>
                            </tr>
                                <?php }
                            }else {
                                ?><tr><td colspan="5">no-data</td></tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</section>