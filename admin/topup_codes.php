<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// เพิ่ม
if (isset($_POST['add'])) {
    $pdo->prepare("INSERT INTO topup_codes (code, amount) VALUES (?, ?)")
        ->execute([$_POST['code'], $_POST['amount']]);
    header("Location: topup_codes.php");
    exit;
}

// ลบ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM topup_codes WHERE id=?")
        ->execute([$_GET['del']]);
    header("Location: topup_codes.php");
    exit;
}

$codes = $pdo->query("SELECT * FROM topup_codes ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - Topup Codes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="/parkingpro/css/navbarheader.css">
</head>

<body>

<?php include '../includes/navbar.php'; ?>
<?php include 'admin_nav.php'; ?>

<div class="main-content">

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- เนื้อหา (ของมึงยังอยู่ครบ) -->
            <div class="col-12 p-0">
                <!-- TITLE -->
                <h2 class="page-title mb-1">จัดการโค้ดเติมเงิน</h2>
                <p class="text-secondary mb-4">
                    เพิ่ม / ลบ โค้ดเติมเงินสำหรับผู้ใช้งานในระบบ
                </p>

                <!-- FORM -->
                <div class="card-dark mb-4">
                    <form method="post" class="row g-2 mb-0">
                        <div class="col-md-4">
                            <input
                                name="code"
                                class="form-control"
                                placeholder="โค้ดเติมเงิน"
                                required
                            >
                        </div>

                        <div class="col-md-4">
                            <input
                                name="amount"
                                type="number"
                                class="form-control"
                                placeholder="จำนวนเงิน"
                                required
                            >
                        </div>

                        <div class="col-md-2">
                            <button name="add" class="btn btn-success w-100">
                                เพิ่มโค้ด
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="card-dark">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Code</th>
                                <th width="150">Amount</th>
                                <th width="120">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(count($codes) === 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">
                                    ยังไม่มีโค้ดเติมเงิน
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach($codes as $c): ?>
                            <tr>
                                <td><?= $c['id'] ?></td>
                                <td><?= htmlspecialchars($c['code']) ?></td>
                                <td><?= number_format($c['amount']) ?></td>
                                <td>
                                    <a
                                        href="?del=<?= $c['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('ลบโค้ดนี้?')"
                                    >
                                        ลบ
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

</body>
</html>
