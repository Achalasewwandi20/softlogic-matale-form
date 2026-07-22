<?php 
include 'db.php'; 
$message = "";


$current_month_year = date("F Y"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adv_code = intval($_POST['adv_code']);
    $name_collection = intval($_POST['name_collection']);
    $qualified_prospecting = intval($_POST['qualified_prospecting']);
    $customers_visit = intval($_POST['customers_visit']);
    $referrals_collected = intval($_POST['referrals_collected']);
    $making_appointment = intval($_POST['making_appointment']);
    $nsi = intval($_POST['nsi']);
    $quotation_presentation = intval($_POST['quotation_presentation']);
    $f_up_visit = intval($_POST['f_up_visit']);
    $nop = intval($_POST['nop']);
    $anbp = intval($_POST['anbp']);

    $sql = "INSERT INTO daily_records (adv_code, name_collection, qualified_prospecting, customers_visit, referrals_collected, making_appointment, nsi, quotation_presentation, f_up_visit, nop, anbp) 
            VALUES ('$adv_code', '$name_collection', '$qualified_prospecting', '$customers_visit', '$referrals_collected', '$making_appointment', '$nsi', '$quotation_presentation', '$f_up_visit', '$nop', '$anbp')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success text-center fw-bold shadow-sm' style='border-left: 5px solid #28a745; border-radius: 8px;'>✓ Data Successfully Submitted!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center fw-bold shadow-sm'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softlogic Life - Sales Activity Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fa; font-family: 'Segoe UI', system-ui, sans-serif; }
        .card { border: none; border-radius: 16px; overflow: hidden; }
        
        
        .logo-container { 
            background-color: #f3ebf6; 
            padding: 12px 10px; 
            text-align: center; 
        }
       
        .logo-img { 
            max-width: 140px; 
            height: auto; 
            display: block; 
            margin: 0 auto;
            mix-blend-mode: multiply; 
        }
        
        .brand-header { background: linear-gradient(135deg, #4a154b 0%, #2c0630 100%); color: white; border-bottom: 4px solid #ff6600; }
        .form-label { color: #2c0630; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: #ff6600; box-shadow: 0 0 0 0.25rem rgba(255, 102, 0, 0.15); }
        .btn-submit { background-color: #ff6600; border: none; color: white; font-weight: bold; transition: all 0.2s; letter-spacing: 0.5px; }
        .btn-submit:hover { background-color: #e05500; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2); }
        
        
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
<div class="container mt-4 mb-5" style="max-width: 600px;">
    <div class="card shadow-lg">
        
        <div class="logo-container border-bottom text-center">
            <img src="images/logo.jpg" alt="Softlogic Life Logo" class="logo-img">
        </div>
        
        <div class="card-header brand-header text-center py-4">
            <h3 class="mb-1 fw-bold">Sales Activity - Matale Central</h3>
            <p class="mb-0 text-warning fw-bold text-uppercase tracking-wider small"><?php echo $current_month_year; ?></p>
        </div>

        <div class="card-body p-4 bg-white">
            <?php echo $message; ?>
            <form action="index.php" method="POST">
                
                <div class="mb-4">
                    <label class="form-label fw-bold">1. Adv code *</label>
                    <select name="adv_code" class="form-select form-select-lg" required>
                        <option value="" disabled selected>Choose Advisor Code...</option>
                        <?php for($i=100; $i<=190; $i++) { echo "<option value='$i'>$i</option>"; } ?>
                    </select>
                </div>

                <?php
                $fields = [
                    'name_collection' => '2. Name Collection',
                    'qualified_prospecting' => '3. Qualified Prospecting',
                    'customers_visit' => '4. Customers Visit',
                    'referrals_collected' => '5. Referrals Collected',
                    'making_appointment' => '6. Making Appointment',
                    'nsi' => '7. NSI',
                    'quotation_presentation' => '8. Quotation Presentation',
                    'f_up_visit' => '9. F/Up Visit',
                    'nop' => '10. NOP',
                    'anbp' => '11. ANBP'
                ];
                foreach($fields as $name => $label) {
                    echo "
                    <div class='mb-4'>
                        <label class='form-label fw-bold'>$label *</label>
                        <input type='number' name='$name' class='form-control form-control-lg' placeholder='Enter your answer' min='0' required>
                    </div>";
                }
                ?>

                <button type="submit" class="btn btn-submit btn-lg w-100 py-3 mt-2 shadow-sm rounded-3 fs-5">SUBMIT ACTIVITY</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>