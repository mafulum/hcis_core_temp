<p>Dear All,</p>
<p></p>
<p>Berikut List Monitoring Of Task <?php echo $date; ?> dengan detail sebagai berikut :</p>
<table border="1" cellpadding="0" cellspacing="0">
    <tr>
        <th>Nopeg</th>
        <th>Nama</th>
        <th>Unit</th>
        <th>Position</th>
        <th>Reminder Code</th>
        <th>Reminder Type</th>
        <th>Reminder Date</th>
        <th>Begin Date</th>
        <th>End date</th>
        <th>Payroll Area</th>
        <th>Company</th>
    </tr>
    <?php
    foreach($rows as $row){
    ?>
    <tr>
        <td><?php echo $row['PERNR'];?></td>
        <td><?php echo (!empty($emp[$row['PERNR']]['CNAME']))?$emp[$row['PERNR']]['CNAME'] : "";?></td>
        <td><?php echo (!empty($emp[$row['PERNR']]['O_STEXT']))?$emp[$row['PERNR']]['O_STEXT']:"";?> ( <?php echo (!empty($emp[$row['PERNR']]['O_SHORT']))?$emp[$row['PERNR']]['O_SHORT']:"";?> ) </td>
        <td><?php echo (!empty($emp[$row['PERNR']]['S_STEXT']))?$emp[$row['PERNR']]['S_STEXT']:"";?></td>
        <td><?php echo (!empty($row['REMINDER_TYPE']))?$row['REMINDER_TYPE']:"";?></td>
        <td><?php echo (!empty($abbrev[$row['REMINDER_TYPE']]['text']))?$abbrev[$row['REMINDER_TYPE']]['text']:"";?></td>
        <td><?php echo (!empty($row['REMINDER_DATE']))?$row['REMINDER_DATE']:"";?></td>
        <td><?php echo (!empty($row['BEGDA']))?$row['BEGDA']:"";?></td>
        <td><?php echo (!empty($row['ENDDA']))?$row['ENDDA']:"";?></td>
        <td><?php echo (!empty($row['PERNR']['ABKRS']))?$row['PERNR']['ABKRS']:"";?></td>
        <td><?php echo (!empty($row['PERNR']['WERKS']))?$row['PERNR']['WERKS']:"";?></td>
    </tr>
    <?php
    }
    ?>
</table>
<p>Demikian disampaikan terima kasih atas perhatiannya</p>
<p></p>
<p>Salam</p>
<p>Beyond Care</p>