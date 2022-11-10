<?php $aModule = $this->common->get_module_access(); ?>
<div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
		<?php if($aModule[1] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'dashboard') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/dashboard"; ?>">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo base_url() . "index.php/dashboard"; ?>">Demography Dashboard</a></li>
                <!--<li><a href="<?php echo base_url() . "index.php/dashboard/manager"; ?>">Manager Dashboard</a></li>-->
            </ul>
        </li>
		<?php } ?>
		<?php if($aModule[2] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'employee') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/employee/master"; ?>" >
                <i class="fa fa-laptop"></i>
                <span>Empl. Master Data</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[3] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'orgchart') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/orgchart/tree"; ?>" >
                <i class="fa fa-sitemap"></i>
                <span>Organization Structure</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[4] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'tprofile') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/tprofile/search"; ?>" >
                <i class="fa fa-cogs"></i>
                <span>Talent Profile</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[5] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'ecs') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/ecs/search"; ?>" >
                <i class="fa fa-book"></i>
                <span>Empl. Competency Summary</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[6] == 1){ ?>
        <li class="sub-menu">
			<a <?php if($this->uri->segment(1) == 'srank') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/srank/search"; ?>" >
                <i class="fa fa-tasks"></i>
                <span>Succession Rank</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[7] == 1){ ?>
        <li class="sub-menu">
            <a <?php if($this->uri->segment(1) == 'pmatch') echo 'class="active"'; ?> href="<?php echo base_url() . "index.php/pmatch/search"; ?>" >
                <i class="fa fa-th"></i>
                <span>Profile Matchup</span>
            </a>
        </li>
		<?php } ?>
		<?php if($aModule[8] == 1){ ?>
        <?php
        //    if($this->global_m->get_user_type()==$this->global_m->ADMIN_USER_TYPE){
                ?>
          
        <li class="sub-menu">
            <a  href="#">
                <i class="fa fa-dashboard"></i>
                <span>Config Menu</span>
            </a>
            <ul class="sub">
                <li><a  href="<?php echo base_url() . "index.php/admin/config"; ?>">Config</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/abbrev"; ?>">Abbrev</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/user"; ?>">User</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/perusahaan"; ?>">Perusahaan</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/matrix_js"; ?>">Matrix Job Score</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/mperformance"; ?>">Master Performance</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/mpotential"; ?>">Master Potential</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/mreadiness"; ?>">Master Readiness</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/talentdesc"; ?>">Talent Description</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/cr_readiness"; ?>">Criteria Readiness</a></li>
                <li><a href="<?php echo base_url() . "index.php/admin/m_competency"; ?>">Master Competency</a></li>
            </ul>

        </li>
              <?
            }
        ?>
    </ul>
    <!-- sidebar menu end-->
</div>