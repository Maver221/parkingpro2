<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit;
}

// เพิ่ม
if (isset($_POST['add'])) {
    $pdo->prepare("INSERT INTO vehicle_types (name) VALUES (?)")
        ->execute([$_POST['name']]);
    header("Location: vehicle_types.php");
    exit;
}

// ลบ
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM vehicle_types WHERE id=?")
        ->execute([$_GET['del']]);
    header("Location: vehicle_types.php");
    exit;
}

$types = $pdo->query("SELECT * FROM vehicle_types ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - Vehicle Types</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/parkingpro/css/admin.css">
    <link rel="stylesheet" href="/parkingpro/css/navbarheader.css">
</head>

<body>

<?php include '../includes/navbar.php'; ?>
<?php include 'admin_nav.php'; ?>

<div class="main-content">

    <!-- TITLE -->
    <h2 class="page-title mb-1">จัดการประเภทรถ</h2>
    <p class="text-secondary mb-4">
        เพิ่ม / ลบ ประเภทรถที่รองรับในระบบ
    </p>

    <!-- ADD FORM -->
    <div class="card-dark mb-4">
        <form method="post" class="d-flex gap-2">
            <input
                name="name"
                class="form-control"
                placeholder="ชื่อประเภทรถ"
                required
            >
            <button name="add" class="btn btn-success">
                เพิ่ม
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card-dark">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>ชื่อประเภทรถ</th>
                    <th width="120">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($types) === 0): ?>
                <tr>
                    <td colspan="3" class="text-center text-secondary py-4">
                        ยังไม่มีประเภทรถ
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($types as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td>
                        <a
                            href="?del=<?= $t['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('ลบประเภทรถนี้ใช่หรือไม่?')"
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
