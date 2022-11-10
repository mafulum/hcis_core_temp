<section class="wrapper">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                User Update
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/user_upd" method="post">
                <div class="panel-body">
                    <input type="hidden" name="id" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                    <div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Username</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $this->global_m->get_array_data($frm, "username"); ?>">
                        </div>
                    </div><div class="form-group">
                        <label for="input3" class="col-lg-2 col-sm-2 control-label">Password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $this->global_m->get_array_data($frm, "pwd_raw"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">PERNR</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="pernr" name="pernr" value="<?php echo $this->global_m->get_array_data($frm, "pernr"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input4" class="col-lg-2 col-sm-2 control-label">User Type</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="user_type" name="user_type" style="padding: 3px 0px;">
                                <?
                                $aKey = array_keys($this->admin_m->aUserType);
                                for ($i = 0; $i < count($this->admin_m->aUserType); $i++) {
                                    $selected = "";
                                    if ($aKey[$i] == $this->global_m->get_array_data($frm, "user_type"))
                                        $selected = "selected='selected'";
                                    echo "<option value='" . $aKey[$i] . "' " . $selected . ">" . $this->admin_m->aUserType[$aKey[$i]] . "</option>";
                                }
                                ?>
                            </select>
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
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/userd_upd" method="post">
                <input type="hidden" name="id_user" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                <header class="panel-heading">
                    Organization Handling
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>                
                <?
                if(count($userd_filter)>0){
                ?>
                <div class="panel-body" >
                    <label for="input5" class="col-lg-2 col-sm-2 control-label">Organization</label>
                    <div class="col-lg-10">
                        <select class="form-control" id="org_unit" name="org_unit" style="padding: 3px 0px;">
                            <?
                            for ($i = 0; $i < count($userd_filter); $i++) {
                                echo "<option value='" . $userd_filter[$i]['OBJID'] . "'  >" . $userd_filter[$i]['STEXT'] . "(" . $userd_filter[$i]['SHORT'] . ") </option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">ADD Org maintain</button>
                    </div>
                </div>
                <?
                }
                ?>
            </form>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($table_og) == 0) {
                            ?>
                            <tr>
                                <td colspan="4">No Unit Maintain</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($table_og); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table_og[$i], "STEXT") . " (" . $this->global_m->get_array_data($table_og[$i], "SHORT") . ")"; ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/admin/user_del_maintain/<?php echo $this->global_m->get_array_data($table_og[$i], 'id'); ?>/<?php echo $this->global_m->get_array_data($frm, "id"); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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
         <section class="panel">
            <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/admin/userdm_upd" method="post">
                <input type="hidden" name="id_user" value="<?php echo $this->global_m->get_array_data($frm, "id"); ?>"/> 
                <header class="panel-heading">
                    App Module Handling
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </header>
                <?
                if(count($userdm_filter)>0){
                ?>
                <div class="panel-body" >
                    <label for="input5" class="col-lg-2 col-sm-2 control-label">Menu</label>
                    <div class="col-lg-10">
                        <select class="form-control" id="id_module" name="id_module" style="padding: 3px 0px;">
                            <?
                            for ($i = 0; $i < count($userdm_filter); $i++) {
                                echo "<option value='" . $userdm_filter[$i]['SHORT'] . "'  >" . $userdm_filter[$i]['STEXT'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">ADD Module</button>
                    </div>
                </div>
                <?
                }
                ?>
            </form>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($table_dm) == 0) {
                            ?>
                            <tr>
                                <td colspan="4">No Module Maintain</td>
                            </tr>
                            <?php
                        } else {
                            for ($i = 0; $i < count($table_dm); $i++) {
                                ?>
                                <tr>
                                    <td><?php echo $this->global_m->get_array_data($table_dm[$i], "STEXT") ; ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="#" onclick="confirm_delete('<?= base_url(); ?>index.php/admin/user_del_module/<?php echo $this->global_m->get_array_data($table_dm[$i], 'id_module'); ?>/<?php echo $this->global_m->get_array_data($frm, "id"); ?>');" data-toggle="modal"> <i class="fa fa-trash-o"></i> </a>
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
    <a class="btn btn-default" href="<?php echo base_url() . "index.php/admin/user"; ?>" data-toggle="modal"> <i class=" fa fa-chevron-left"> Back</i> </a>
</section>