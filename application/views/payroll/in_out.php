<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - InOut / Advance Payment  List
                <span class="tools pull-right">
                    <a class="fa fa-plus-square" href="<?php echo base_url()."index.php/payroll/in_out/process";?>"></a>
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-table">
                        <thead>
                            <tr>
                                <th class="all">TimeRunnng</th>
                                <th class="all">Date</th>
                                <th class="all">Name</th>
                                <th class="all">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($io)){ 
                                foreach($io as $row){
                                ?>
                            <tr>
                                <td><?php echo $row['time_running'];?></td>
                                <td><?php echo $row['date_inout']; ?></td>
                                <td><?php echo $row['name_inout']; ?></td>
                                <td><!--<a href="<?php echo base_url()."index.php/payroll/in_out/view/".$row['id'];?>">view</a>--></td>
                            </tr>
                                <?php }
                            }else {
                                ?><tr><td colspan="4">no-data</td></tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</section>