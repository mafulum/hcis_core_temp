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
    </tr>
    <?php
    foreach($rows as $row){
    ?>
    <tr>
        <td><?php echo $row['PERNR'];?></td>
        <td><?php echo $emp[$row['PERNR']]['CNAME'];?></td>
        <td><?php echo $emp[$row['PERNR']]['O_STEXT'];?> ( <?php echo $emp[$row['PERNR']]['O_SHORT'];?> ) </td>
        <td><?php echo $emp[$row['PERNR']]['S_STEXT'];?></td>
        <td><?php echo $row['REMINDER_TYPE'];?></td>
        <td><?php echo $abbrev[$row['REMINDER_TYPE']]['text'];?></td>
        <td><?php echo $row['REMINDER_DATE'];?></td>
        <td><?php echo $row['BEGDA'];?></td>
        <td><?php echo $row['ENDDA'];?></td>
    </tr>
    <?php
    }
    ?>
</table>
<p>Demikian disampaikan terima kasih atas perhatiannya</p>
<p></p>
<p>Salam</p>
<p>Beyond Care</p>