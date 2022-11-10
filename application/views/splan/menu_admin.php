<!-- START MENU -->
<nav id="header_main">
    <div class="container_12">
        <ul id="nav_main">
            <li <? if ($this->uri->segment(1) == 'dashboard') echo "class=current"; ?>> <a href="<?= $base_url; ?>dashboard"> <img src="<?= $base_url; ?>themes/img/icons/25x25/dark/computer-imac.png" width="25" height="25" alt=""> Dashboard</a>
<!--		<ul>
		<li><a href="<?= $base_url; ?>doc/emp.pdf">Donwload Employee Document Manual</a></li>

            <?php if ($this->session->userdata('s_man') == 1) { ?>
<li><a href="<?= $base_url; ?>doc/sup">Donwload Superior Document Manual</a></li>
	    <? } ?>
		</ul>-->
</li>
            <li <? if (($this->uri->segment(1) == 'employee') || ($this->uri->segment(1) == 'espkl') || ($this->uri->segment(1) == 'leave' && ($this->uri->segment(2) == 'index' || $this->uri->segment(2) == '')) || ($this->uri->segment(1) == 'hadir_manual' && ($this->uri->segment(2) == 'index' || $this->uri->segment(2) == ''))) echo "class=current"; ?>> <a href="#"> <img src="<?= $base_url; ?>themes/img/icons/25x25/dark/user.png" width="25" height="25" alt=""> Employee</a>
                <ul>
                    <li <? if ($this->uri->segment(1) == 'employee') echo "class=current"; ?>><a href="<?= $base_url; ?>employee">Time Data</a></li>
                    <? if ($this->session->userdata('can_espkl') == 1) { ?>
                        <li <? if ($this->uri->segment(1) == 'espkl') echo "class=current"; ?>><a href="<?= $base_url; ?>espkl">e-SPKL</a></li>
                    <? } ?>
                    <li <? if ($this->uri->segment(1) == 'leave' && ($this->uri->segment(2) == 'index' || $this->uri->segment(2) == '')) echo "class=current"; ?>><a href="<?= $base_url; ?>leave">Leave</a></li>
                    <li <? if ($this->uri->segment(1) == 'hadir_manual' && ($this->uri->segment(2) == 'index' || $this->uri->segment(2) == '')) echo "class=current"; ?>><a href="<?= $base_url; ?>hadir_manual">Manual Attandance</a></li>
                </ul>
            </li>
            <?php if ($this->session->userdata('s_man') == 1) { ?>
                <li <? if (($this->uri->segment(1) == 'superior') || ($this->uri->segment(1) == 'validasi') || ($this->uri->segment(1) == 't_report') || ($this->uri->segment(1) == 'cuti_tangguh') || ($this->uri->segment(1) == 'espkl_sup') || ($this->uri->segment(1) == 'delegasi' && $this->uri->segment(2) == 'indexs') || ($this->uri->segment(1) == 'leave' && $this->uri->segment(2) == 'indexs') || ($this->uri->segment(1) == 'hadir_manual' && $this->uri->segment(2) == 'indexs')) echo "class=current"; ?>> <a href="#"> <img src="<?= $base_url; ?>themes/img/icons/25x25/dark/users-2.png" width="25" height="25" alt="">Superior</a>
                    <ul>
                        <li <? if ($this->uri->segment(1) == 'superior') echo "class=current"; ?>><a href="<?= $base_url; ?>superior">Sub.s Time Data</a></li>
                        <li <? if ($this->uri->segment(1) == 'espkl_sup') echo "class=current"; ?>><a href="<?= $base_url; ?>espkl_sup">e-SPKL</a></li>
                        <li <? if ($this->uri->segment(1) == 'leave' && $this->uri->segment(2) == 'indexs') echo "class=current"; ?>><a href="<?= $base_url; ?>leave/indexs">Leave App.</a></li>
                        <li <? if ($this->uri->segment(1) == 'hadir_manual' && $this->uri->segment(2) == 'indexs') echo "class=current"; ?>><a href="<?= $base_url; ?>hadir_manual/indexs">Manual Att.</a></li>
                        <li <? if ($this->uri->segment(1) == 'cuti_tangguh' && $this->uri->segment(2) == 'indexs') echo "class=current"; ?>><a href="<?= $base_url; ?>cuti_tangguh/indexs">Leave Repl.</a></li>
                        <li <? if ($this->uri->segment(1) == 'validasi') echo "class=current"; ?>><a href="<?= $base_url; ?>validasi">Validation</a></li>
                        <? if ($this->session->userdata('s_lakhar') == 0) { ?>						
                            <li <? if ($this->uri->segment(1) == 'delegasi' && $this->uri->segment(2) == 'indexs') echo "class=current"; ?>><a href="<?= $base_url; ?>delegasi/indexs">TMS Delegation</a></li>
                        <? } ?>
                            <? if ($this->session->userdata('isVP') == 1) { ?>						
                            <li <? if ($this->uri->segment(1) == 't_report' && $this->uri->segment(2) == 'spkl_paid') echo "class=current"; ?>><a href="<?= $base_url; ?>t_report/spkl_paid">e-SPKL paid</a></li>
                        <? } ?>
                    </ul>
                </li>
            <?php } if ($this->session->userdata('isTAdmin') == 1) { ?>
                <li <? if ($this->uri->segment(1) == 'e_outsource' || ($this->uri->segment(1) == 't_report' && $this->session->userdata('s_man')==0 ) || $this->uri->segment(1) == 'tadmin' || $this->uri->segment(1) == 'fastmove' || $this->uri->segment(1) == 'spkl' || $this->uri->segment(1) == 'report_cuti') echo "class=current"; ?>> <a href="#"> <img src="<?= $base_url; ?>themes/img/icons/25x25/dark/cog-5.png" width="25" height="25" alt="">Time Admin</a>
                    <ul>
                        <li <? if ($this->uri->segment(1) == 'tadmin' && $this->uri->segment(2) <> 'reproses') echo "class=current"; ?>><a href="<?= $base_url; ?>tadmin">Employee(s) Time Data</a></li>
                        <li <? if ($this->uri->segment(1) == 'tadmin' && ($this->uri->segment(2) == 'reproses' || $this->uri->segment(2) == 'reproses_save')) echo "class=current"; ?>><a href="<?= $base_url; ?>tadmin/reproses">Reproses</a></li>
                        <li <? if ($this->uri->segment(1) == 'tadmin' && ($this->uri->segment(2) == 'upload_timedata' || $this->uri->segment(2) == 'do_upload_timedata')) echo "class=current"; ?>><a href="<?= $base_url; ?>tadmin/upload_timedata">Upload</a></li>
                        <li <? if ($this->uri->segment(1) == 'e_outsource') echo "class=current"; ?>><a href="<?= $base_url; ?>e_outsource">Maintain Outsourcing</a></li>
                        <li <? if ($this->uri->segment(1) == 'spkl') echo "class=current"; ?>><a href="<?= $base_url; ?>spkl">Report e-spkl</a></li>
                        <li <? if ($this->uri->segment(1) == 'report_cuti') echo "class=current"; ?>><a href="<?= $base_url; ?>report_cuti">Report Cuti</a></li>
                        <li <? if ($this->session->userdata('s_man')==0 && $this->uri->segment(1) == 't_report' && $this->uri->segment(2) == 'cuti_tangguh') echo "class=current"; ?>><a href="<?= $base_url; ?>t_report/cuti_tangguh">Report Cuti Replacement</a></li>
                        <li <? if ($this->session->userdata('s_man')==0 && $this->uri->segment(1) == 't_report' && $this->uri->segment(2) == 'non_keterangan') echo "class=current"; ?>><a href="<?= $base_url; ?>t_report/non_keterangan">Report Non Keterangan</a></li>

    <!--	<li <? if ($this->uri->segment(1) == 'fastmove') echo "class=current"; ?>><a href="<?= $base_url; ?>fastmove">Maintain Fast Moving</a></li>-->
                    </ul>
                </li>
            <?php } if ($this->session->userdata('isSAdmin') == 1) { ?>
                <li <? if ($this->uri->segment(1) == 'sadmin' || $this->uri->segment(1) == 'admin' || $this->uri->segment(1) == 'excel'|| $this->uri->segment(1) == 'cespkl'|| $this->uri->segment(1) == 'cleave'|| $this->uri->segment(1) == 'cmanatt') echo "class=current"; ?>> <a href="#"> <img src="<?= $base_url; ?>themes/img/icons/25x25/dark/robot.png" width="25" height="25" alt=""> Super Admin</a>
                    <ul>
                        <li <? if ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'userlist') echo "class=current"; ?>><a href="<?= $base_url; ?>admin/userlist">Master User</a></li>
                        <li <? if ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'update_dws') echo "class=current"; ?>><a href="<?= $base_url; ?>admin/maintain_dws">Maintain DWS</a></li>
                        <li <? if ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'maintain_holiday') echo "class=current"; ?>><a href="<?= $base_url; ?>admin/maintain_holiday">Maintain Holiday</a></li>
                        <li <? if ($this->uri->segment(1) == 'excel' && $this->uri->segment(2) == 'index') echo "class=current"; ?>><a href="<?= $base_url; ?>excel/index">excel</a></li>
                        <li <? if ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'unlock') echo "class=current"; ?>><a href="<?= $base_url; ?>admin/unlock">Lock / Unlock Transaction</a></li>
                        <li <? if ($this->uri->segment(1) == 'cespkl') echo "class=current"; ?>><a href="<?= $base_url; ?>cespkl">Create ESPKL</a></li>
                        <li <? if ($this->uri->segment(1) == 'cleave') echo "class=current"; ?>><a href="<?= $base_url; ?>cleave">Create Leave</a></li>
                        <li <? if ($this->uri->segment(1) == 'cmanatt') echo "class=current"; ?>><a href="<?= $base_url; ?>cmanatt">Create Man.Att.</a></li>
                        </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<div id="nav_sub"></div>
<!-- END OF MENU -->