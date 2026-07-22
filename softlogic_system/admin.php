<?php 

session_start();


$admin_username = "";
$admin_password = "";

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin.php");
    exit();
}

$login_error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['is_admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $login_error = "Invalid Username or Password!";
    }
}


if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Softlogic Life</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fa; font-family: 'Segoe UI', system-ui, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { max-width: 400px; width: 100%; border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .brand-header { background: linear-gradient(135deg, #4a154b 0%, #2c0630 100%); color: white; border-bottom: 4px solid #ff6600; text-align: center; padding: 25px; }
        .btn-login { background-color: #ff6600; border: none; color: white; font-weight: bold; transition: all 0.2s; }
        .btn-login:hover { background-color: #e05500; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2); }
        .logo-box { background-color: #ffffff; padding: 6px 12px; border-radius: 8px; display: inline-flex; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
        .logo-img { max-height: 45px; width: auto; mix-blend-mode: multiply; }
    </style>
</head>
<body>
<div class="login-card card">
    <div class="brand-header">
        <div class="logo-box">
            <img src="images/logo.jpg" alt="Logo" class="logo-img">
        </div>
        <h4 class="mb-0 fw-bold">Admin Dashboard Login</h4>
    </div>
    <div class="card-body p-4 bg-white">
        <?php if(!empty($login_error)): ?>
            <div class="alert alert-danger text-center fw-bold small py-2"><?php echo $login_error; ?></div>
        <?php endif; ?>
        <form action="admin.php" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold" style="color: #2c0630;">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Enter username" required autocomplete="off">
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold" style="color: #2c0630;">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-login btn-lg w-100 py-2.5 rounded-3">SECURE LOGIN</button>
        </form>
    </div>
</div>
</body>
</html>
<?php 
exit();
endif; 


include 'db.php'; 


if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM daily_records WHERE id = $delete_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: admin.php?status=deleted");
        exit();
    } else {
        header("Location: admin.php?status=error");
        exit();
    }
}

$sql = "SELECT * FROM daily_records ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softlogic Life - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7fa; font-family: 'Segoe UI', system-ui, sans-serif; }
        .card { border: none; border-radius: 16px; overflow: hidden; }
        .brand-header { background: linear-gradient(135deg, #4a154b 0%, #2c0630 100%); color: white; border-bottom: 4px solid #ff6600; padding: 18px 24px !important; }
        .admin-logo-box { background-color: #ffffff; padding: 6px 12px; border-radius: 8px; margin-right: 16px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
        .admin-logo-img { max-height: 52px; width: auto; mix-blend-mode: multiply; }
        .table-responsive { border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .table-header th { background-color: #4a154b !important; color: #ffffff !important; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 8px !important; border: 1px solid #5d205e !important; }
        .table tbody tr { transition: all 0.2s; }
        .table tbody tr:hover { background-color: #fbf8fc !important; }
        .btn-excel { background-color: #107c41; color: white; font-weight: 600; border: none; padding: 8px 18px; border-radius: 8px; transition: all 0.2s ease-in-out; display: inline-flex; align-items: center; gap: 6px; }
        .btn-excel:hover { background-color: #0a5c30; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 124, 65, 0.3); }
        .btn-new-entry { background-color: #ff6600; color: white; font-weight: 600; border: none; padding: 8px 18px; border-radius: 8px; transition: all 0.2s ease-in-out; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-new-entry:hover { background-color: #e05500; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(255, 102, 0, 0.3); }
        .btn-logout { background-color: transparent; border: 1px solid #ffffff; color: white; font-weight: 600; padding: 8px 15px; border-radius: 8px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-logout:hover { background-color: #ffffff; color: #4a154b; }
        .btn-delete { background-color: #fff5f5; color: #e53e3e; border: 1px solid #fed7d7; padding: 6px 14px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-delete:hover { background-color: #e53e3e; color: white; border-color: #e53e3e; box-shadow: 0 2px 6px rgba(229, 62, 62, 0.2); }
    </style>
</head>
<body>

<div class="container-fluid mt-4 mb-5 px-4">
    <div class="card shadow-lg">
        
        <div class="card-header brand-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="admin-logo-box">
                    <img src="images/logo.jpg" alt="Softlogic Life Logo" class="admin-logo-img">
                </div>
                <h4 class="mb-0 fw-bold tracking-wide">Sales Activity Admin Dashboard</h4>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button onclick="exportTableToExcel('sales_table', 'Sales_Activity_Report')" class="btn btn-excel shadow-sm">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export to Excel
                </button>
                <a href="index.php" class="btn btn-new-entry shadow-sm">
                    <i class="bi bi-plus-circle-fill"></i> New Entry
                </a>
                <a href="admin.php?action=logout" class="btn btn-logout shadow-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>

        <div class="card-body p-4 bg-white">
            
            <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
                <div class="alert alert-success fw-bold text-center shadow-sm py-2 rounded-3 border-0" style="background-color: #def7ec; color: #03543f;">
                    <i class="bi bi-check-circle-fill me-1"></i> Record Successfully Deleted!
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="sales_table" class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead>
                        <tr class="table-header">
                            <th>ID</th>
                            <th>Adv Code</th>
                            <th>Name Coll.</th>
                            <th>Qual. Prospect</th>
                            <th>Cust. Visit</th>
                            <th>Referrals</th>
                            <th>Make Appt.</th>
                            <th>NSI</th>
                            <th>Quotation</th>
                            <th>F/Up Visit</th>
                            <th>NOP</th>
                            <th>ANBP</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // ඩේටාබේස් එකේ වෙලාව තියෙනවද චෙක් කරලා පෙන්වීම
                                if(!empty($row['created_at']) && $row['created_at'] != '0000-00-00 00:00:00') {
                                    // කියවන්න ලේසි ක්‍රමයකට වෙලාව සකස් කිරීම
                                    $submitted_at = date("Y-m-d h:i A", strtotime($row['created_at']));
                                } else {
                                    $submitted_at = 'N/A';
                                }
                                
                                echo "<tr>";
                                echo "<td class='fw-bold text-secondary'>" . $row['id'] . "</td>";
                                echo "<td class='fw-bold text-dark' style='font-size: 1.05rem;'>" . $row['adv_code'] . "</td>";
                                echo "<td>" . $row['name_collection'] . "</td>";
                                echo "<td>" . $row['qualified_prospecting'] . "</td>";
                                echo "<td>" . $row['customers_visit'] . "</td>";
                                echo "<td>" . $row['referrals_collected'] . "</td>";
                                echo "<td>" . $row['making_appointment'] . "</td>";
                                echo "<td>" . $row['nsi'] . "</td>";
                                echo "<td>" . $row['quotation_presentation'] . "</td>";
                                echo "<td>" . $row['f_up_visit'] . "</td>";
                                echo "<td>" . $row['nop'] . "</td>";
                                echo "<td class='fw-bold' style='color: #4a154b; font-size: 1.05rem;'>" . number_format($row['anbp']) . "</td>";
                                echo "<td class='small text-muted fw-bold'>" . $submitted_at . "</td>";
                                echo "<td>
                                        <a href='admin.php?delete_id=" . $row['id'] . "' 
                                           class='btn-delete' 
                                           onclick=\"return confirm('Are you sure you want to delete this record?');\">
                                           <i class='bi bi-trash3-fill'></i> Delete
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='14' class='text-muted py-5 fw-bold fs-5'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    filename = filename?filename+'.xls':'excel_data.xls';
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + '\ufeff' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>
</body>
</html>