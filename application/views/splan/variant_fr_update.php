<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Variant Update
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/splan/variant_upd" method="post">
                <div class="panel-body">
                    <input type="hidden" name="id" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->global_m->get_array_data($frm, "name"); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
            </form>

        </section>
        <section class="panel">
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/splan/variantd_upd" method="post">
                <input type="hidden" name="idm" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                <header class="panel-heading">
                    Variant Detail
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>                
                
                <div class="panel-body" >
                    <label for="input5" class="col-lg-2 col-sm-2 control-label">Nopeg</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="iNopeg" name="nopeg" value=""style="padding: 3px 0px;">
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">ADD Nopeg</button>
                    </div>
                </div>
                
            </form>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Variant Detail</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($table_detail) == 0) {
                            ?>
                            <tr>
                                <td colspan="4">No Variant Maintain</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($table_detail); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table_detail[$i], "objid")." / ". $this->global_m->get_array_data($table_detail[$i], "CNAME"); ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/splan/variant_del_maintain/<?php echo $this->global_m->get_array_data($table_detail[$i], 'idd'); ?>/<?php echo $this->global_m->get_array_data($frm, "id"); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
                
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>

                <div class="modal-body">
                    <p>You are about to delete, this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnNo">Cancel</button>
                    <a href="#" class="btn btn-danger danger" id="btnYes">Yes</a>
                </div>
            </div>
        </div>
    </div>
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/splan/manage_var"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
</section>