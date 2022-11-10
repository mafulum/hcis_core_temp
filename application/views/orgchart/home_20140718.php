<section class="wrapper">
    <!-- page start-->
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    Organization Structure
                    <span class="pull-right">
                        <?php if ($sStaff == 'Y') { ?>
                            <a class="btn btn-shadow btn-info btn-xs" href="<?php echo base_url() . "index.php/orgchart/tree/N"; ?>" data-toggle="modal">Staff Assignments</a>
                        <?php } else { ?>
                            <a class="btn btn-shadow btn-default btn-xs" href="<?php echo base_url() . "index.php/orgchart/tree/Y"; ?>" data-toggle="modal">Staff Assignments</a>
                        <?php } ?>
                    </span>
                </div>
                <div class="panel-body">
                    <div id="FlatTree" class="tree tree-solid-line">
                        <div class = "tree-folder" style="display:none;">
                            <div class="tree-folder-header">
                                <i class="fa fa-folder"></i>
                                <div class="tree-folder-name"></div>
                            </div>
                            <div class="tree-folder-content"></div>
                            <div class="tree-loader" style="display:none"></div>
                        </div>
                        <div class="tree-item" style="display:none;">
                            <i class="tree-dot"></i>
                            <div class="tree-item-name"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="divEditO" class="col-md-6" style="display:none">

            <section class="panel">
                <header class="panel-heading tab-bg-dark-navy-blue">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#obasic" data-toggle="tab">
                                <i class="fa fa-user"></i> Basic 
                            </a>
                        </li>
                        <li class="" id="toRelationship">
                            <a href="#orelationship" data-toggle="tab">
                                <i class="fa fa-sitemap"></i> Relationship
                            </a>
                        </li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content tasi-tab">
                        <div class="tab-pane active profile-nav" id="obasic">
                            <div class="panel">
                                <div id="headOrg" class="panel-heading">
                                    Edit Organization
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form" id="fOrg" action="<?php echo $base_url; ?>index.php/orgchart/update_org" method="post">
                                        <input type="hidden" id="iOrg" name="iOrg" value="-1">
                                        <input type="hidden" id="iParentOrg" name="iParentOrg" value="-1">
                                        <div class="form-group">
                                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Periode</label>
                                            <div class="col-lg-10">
                                                <div class="input-group input-large" data-date-format="dd/mm/yyyy">
                                                    <input id="dOrgBegda" type="text" class="form-control dpd1" value="" name="begda">
                                                    <span class="input-group-addon">To</span>
                                                    <input id="dOrgEndda" type="text" class="form-control dpd2" value="" name="endda">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Abbreviation</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cOrgShort" name="cOrgShort" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Text</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cOrgText" name="cOrgText" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Sort</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cOrgPriox" name="cOrgPriox" value="">
                                            </div>
                                        </div>
                                    </form>
                                    <a class="btn btn-default" onClick="closeUpdOrg();" data-toggle="modal">Close</a>
                                    <a class="btn btn-success" onClick="confirmUpdOrg();" data-toggle="modal">Save</a>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane profile-nav" id="orelationship">

                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div id="divEditS" class="col-md-6" style="display:none">
            <section class="panel">
                <header class="panel-heading tab-bg-dark-navy-blue">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#basic" data-toggle="tab">
                                <i class="fa fa-user"></i> Basic 
                            </a>
                        </li>
                        <li class="" id="tCompetency">
                            <a href="#competency" data-toggle="tab">
                                <i class="fa fa-sitemap"></i> Competency
                            </a>
                        </li>
                        <li class="">
                            <a href="#prelationship" data-toggle="tab">
                                <i class="fa fa-sitemap"></i> Relationship
                            </a>
                        </li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content tasi-tab">
                        <div class="tab-pane active profile-nav" id="basic">

                            <div class="panel">
                                <div id="headPos" class="panel-heading">
                                    Add Position
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" id="fPos" role="form"  action="<?php echo $base_url; ?>index.php/orgchart/update_pos" method="post">
                                        <input type="hidden" id="iPosition" name="iPosition" value="-1">
                                        <input type="hidden" id="iParentPos" name="iParentPos" value="-1">
                                        <div class="form-group">
                                            <label for="input1" class="col-lg-2 col-sm-2 control-label">Periode</label>
                                            <div class="col-lg-10">
                                                <div class="input-group input-large" data-date-format="dd/mm/yyyy">
                                                    <input id="dPosBegda" type="text" class="form-control dpd1" value="" name="begda">
                                                    <span class="input-group-addon">To</span>
                                                    <input id="dPosEndda" type="text" class="form-control dpd2" value="" name="endda">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input2" class="col-lg-2 col-sm-2 control-label">Abbreviation</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cPosShort" name="cPosShort" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input3" class="col-lg-2 col-sm-2 control-label">Text</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cPosText" name="cPosText" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input4" class="col-lg-2 col-sm-2 control-label">Sort</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cPosPriox" name="cPosPriox" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input5" class="col-lg-2 col-sm-2 control-label">Job Level</label>
                                            <div class="col-lg-10">
                                                <SELECT class="form-control" id="cStell" name="cStell" style="padding: 3px 0px;">
                                                    <?
                                                    for ($i = 0; $i < count($aStell); $i++) {
                                                        echo "<option value='" . $aStell[$i]['SHORT'] . "'>" . $aStell[$i]['STEXT'] . "</option>";
                                                    }
                                                    ?>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input6" class="col-lg-2 col-sm-2 control-label">Job Group</label>
                                            <div class="col-lg-10">
                                                <SELECT class="form-control" id="cFam" name="cFam" style="padding: 3px 0px;">
                                                    <?
                                                    for ($i = 0; $i < count($aFam); $i++) {
                                                        echo "<option value='" . $aFam[$i]['SHORT'] . "'>" . $aFam[$i]['STEXT'] . "</option>";
                                                    }
                                                    ?>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="input5" class="col-lg-2 col-sm-2 control-label">Score</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="cScore" name="cScore" value="">
                                            </div>
                                        </div>
                                    </form>
                                    <a class="btn btn-default" onClick="closeUpdPos();" data-toggle="modal">Close</a>
                                    <a class="btn btn-success" onClick="confirmUpdPos();" data-toggle="modal">Save</a>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane profile-nav" id="competency">

                        </div>
                        <div class="tab-pane profile-nav" id="prelationship">

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- page end-->
</section>