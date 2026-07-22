<?php
include 'db.php';
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Softlogic_Daily_Records_$date.xls");

$sql = "SELECT * FROM daily_records WHERE date_submitted = '$date' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<table border="1">
    <thead>
        <tr style="background-color: #007bff; color: #ffffff; font-weight: bold;">
            <th>Date</th><th>Adv Code</th><th>Name Collection</th><th>Qualified Prospecting</th><th>Customers Visit</th><th>Referrals Collected</th><th>Making Appointment</th><th>NSI</th><th>Quotation Presentation</th><th>F/Up Visit</th><th>NOP</th><th>ANBP</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['date_submitted']; ?></td>
                    <td><?php echo $row['adv_code']; ?></td>
                    <td><?php echo $row['name_collection']; ?></td>
                    <td><?php echo $row['qualified_prospecting']; ?></td>
                    <td><?php echo $row['customers_visit']; ?></td>
                    <td><?php echo $row['referrals_collected']; ?></td>
                    <td><?php echo $row['making_appointment']; ?></td>
                    <td><?php echo $row['nsi']; ?></td>
                    <td><?php echo $row['quotation_presentation']; ?></td>
                    <td><?php echo $row['f_up_visit']; ?></td>
                    <td><?php echo $row['nop']; ?></td>
                    <td><?php echo $row['anbp']; ?></td>
                </tr>
            <?php } 
        } ?>
    </tbody>
</table>