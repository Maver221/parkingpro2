<?php
    require '../includes/db.php';
    if ($_SESSION['role'] != 'admin') header("Location: ../adminhome.php");
    $path = '../';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="/parkingpro/css/navbarheader.css">
</head>
<body>
        <?php include '../includes/navbar.php'; ?>
        <?php include 'admin_nav.php'; ?>
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">

                <!-- เนื้อหา -->
                <div class="col-md-9 p-4">
                    <h2>Admin Dashboard</h2>
                    <p>ยินดีต้อนรับแอดมิน 🧠</p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
