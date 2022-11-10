<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Payroll - Document Transfer List
                <span class="tools pull-right">
                    <a class="fa fa-plus-square" href="<?php echo base_url()."index.php/payroll/document_transfer/process";?>"></a>
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="summary-table">
                        <thead>
                            <tr>
                                <th class="all">Name Document Transfer</th>
                                <th class="all">Created At</th>
                                <th class="all">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($adt)){ 
                                foreach($adt as $row){
                                ?>
                            <tr>
                                <td><?php echo $row['name'];?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><a href="<?php echo base_url()."index.php/payroll/document_transfer/process/".$row['id'];?>">view</a></td>
                            </tr>
                                <?php }
                            }else {
                                ?><tr><td colspan="3">no-data</td></tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</section>