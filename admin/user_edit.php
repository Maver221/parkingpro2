<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../home.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: users.php");
    exit;
}

$user = $pdo->prepare("SELECT * FROM users WHERE id=?");
$user->execute([$id]);
$u = $user->fetch();

if (!$u) {
    header("Location: users.php");
    exit;
}

if (isset($_POST['save'])) {
    $sql = "UPDATE users SET username=?, email=?, role=?";
    $data = [$_POST['username'], $_POST['email'], $_POST['role']];

    if (!empty($_POST['password'])) {
        $sql .= ", password=?";
        $data[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $sql .= " WHERE id=?";
    $data[] = $id;

    $pdo->prepare($sql)->execute($data);
    header("Location: users.php");
    exit;
}

if (isset($_POST['delete'])) {
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edit User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <h2 class="page-title mb-1">แก้ไขผู้ใช้</h2>
    <p class="text-secondary mb-4">
        แก้ไขข้อมูลบัญชีผู้ใช้งานในระบบ
    </p>

    <!-- FORM CARD -->
    <div class="card-dark">

        <form method="post">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input
                    type="text"
                    class="form-control"
                    name="username"
                    value="<?= htmlspecialchars($u['username']) ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    name="email"
                    value="<?= htmlspecialchars($u['email']) ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password <span class="text-secondary">(ไม่กรอก = ไม่เปลี่ยน)</span>
                </label>
                <input
                    type="password"
                    class="form-control"
                    name="password"
                    placeholder="รหัสผ่านใหม่"
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Role</label>
                <select class="form-select" name="role">
                    <option value="user" <?= $u['role']=='user'?'selected':'' ?>>user</option>
                    <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>admin</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="save" class="btn btn-success flex-fill">
                    บันทึกการเปลี่ยนแปลง
                </button>
                <a href="users.php" class="btn btn-secondary flex-fill">
                    ยกเลิก
                </a>
            </div>

        </form>

        <hr class="border-secondary my-4">

        <form method="post" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบบัญชีนี้?');">
            <button type="submit" name="delete" class="btn btn-danger w-100">
                ลบบัญชีผู้ใช้
            </button>
        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
