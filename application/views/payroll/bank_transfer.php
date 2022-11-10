<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Bank Transfer List
                <span class="tools pull-right">
                    <a class="fa fa-plus-square" href="<?php echo base_url()."index.php/payroll/bank_transfer/process";?>"></a>
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-table">
                        <thead>
                            <tr>
                                <th class="all">Name Bank Transfer</th>
                                <th class="all">isOFFCycle</th>
                                <th class="all">Date OffCycle</th>
                                <th class="all">Periode Regular</th>
                                <th class="all">Created At</th>
                                <th class="all">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($abt)){ 
                                foreach($abt as $row){
                                ?>
                            <tr>
                                <td><?php echo $row['name'];?></td>
                                    <td><?php echo $row['is_offcycle']; ?></td>
                                    <td><?php echo $row['date_offcycle']; ?></td>
                                    <td><?php echo $row['periode_regular']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><a href="<?php echo base_url()."index.php/payroll/bank_transfer/process/".$row['id'];?>">view</a></td>
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