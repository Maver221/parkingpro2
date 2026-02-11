<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit;
}

// เพิ่มโซน
if (isset($_POST['add_zone'])) {
    $stmt = $pdo->prepare("INSERT INTO zones (name) VALUES (?)");
    $stmt->execute([$_POST['name']]);

    header("Location: zones.php");
    exit;
}

// ลบโซน
if (isset($_GET['del_zone'])) {
    $pdo->prepare("DELETE FROM zones WHERE id=?")
        ->execute([$_GET['del_zone']]);

    header("Location: zones.php");
    exit;
}

$zones = $pdo->query("SELECT * FROM zones ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - Zones</title>

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
    <h2 class="page-title mb-1">จัดการโซน</h2>
    <p class="text-secondary mb-4">
        เพิ่ม / ลบ โซนสำหรับจัดการพื้นที่จอดรถ
    </p>

    <!-- ADD FORM -->
    <div class="card-dark mb-4">
        <form method="post" class="row g-2">
            <div class="col-md-8">
                <input
                    name="name"
                    class="form-control"
                    placeholder="ชื่อโซน"
                    required
                >
            </div>
            <div class="col-md-4">
                <button name="add_zone" class="btn btn-success w-100">
                    เพิ่มโซน
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
                    <th>ชื่อโซน</th>
                    <th width="120">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($zones) === 0): ?>
                <tr>
                    <td colspan="3" class="text-center text-secondary py-4">
                        ยังไม่มีโซนในระบบ
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($zones as $z): ?>
                <tr>
                    <td><?= $z['id'] ?></td>
                    <td><?= htmlspecialchars($z['name']) ?></td>
                    <td>
                        <a
                            href="?del_zone=<?= $z['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('ลบโซนนี้ใช่หรือไม่?')"
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
