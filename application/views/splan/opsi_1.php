<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Variant Selection
                        <span class="tools pull-right">
                        <a class="btn btn-danger btn-xs pull-right" href="<?=base_url();?>index.php/splan/manage_var" data-toggle="modal"> <i class="fa fa-gear"></i> </a>
                    </span>
                    </div>
                    <div class="panel-body" id="divForm2">
                        
                            
                        <div class="form-group">
                            
                            <label for="fprsh2" class="col-lg-2 col-sm-2 control-label">Variant Selection : </label>
                            <div class="col-lg-10">
                                <select id="variant" name="variant" class="form-control" >
                                    <option value="-1" selected>No Selected Variant</option>
                               <?
                               if(!empty($param)){
                            for($i=0;$i<count($param);$i++){
                            ?>
                                    <option value="<? echo $param[$i]['id'];?>"><? echo $param[$i]['name'];?></option>
                            <?
                            }
                            }
                            ?>
                                </select>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        <form class="form-horizontal" role="form"  action="<?php echo $base_url; ?>index.php/splan/view_1" method="post">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Header Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="fprsh2" class="col-lg-2 col-sm-2 control-label">Position</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="position" name="position" style="padding: 3px 0px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Concern Item
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label"> Concern Item</label>
                            <div class="col-lg-10">
                                <textarea name="concern" id="concern" rows="5" cols="120"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="divBase">
                <div class="panel">
                    <div class="panel-body">
                        <div class="col-md-12" id="divSearch">
                            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 1 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                
                                <input type="text" class="form-control" id="emp1" name="emp1" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist1" id="hist1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 2 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp2" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp2" name="emp2" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist2" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist2" id="hist2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 3 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp3" name="emp3" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist3" id="hist3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 4 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp4" name="emp4" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist4" id="hist4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 5 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp5" name="emp5" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist5" id="hist5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 6 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp6" name="emp6" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist6" id="hist6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 7 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp7" name="emp7" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist7" id="hist7"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 8 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp8" name="emp8" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist8" id="hist8"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 9 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp9" name="emp9" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist9" id="hist9"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        Emp 10 Selection
                    </div>
                    <div class="panel-body" id="divForm2">
                        <div class="form-group">
                            <label for="emp1" class="col-lg-2 col-sm-2 control-label">Emp</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="emp10" name="emp10" style="padding: 3px 0px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hist1" class="col-lg-2 col-sm-2 control-label">History</label>
                            <div class="col-lg-10">
                                <textarea name="hist10" id="hist10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- page end-->
</section>