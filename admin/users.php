<?php
    require '../includes/db.php';
    if ($_SESSION['role'] != 'admin') header("Location: ../home.php");

    $users = $pdo->query("SELECT id, username, email, role FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/parkingpro/css/admin.css">
    <link rel="stylesheet" href="/parkingpro/css/navbarheader.css">
</head>
<body>
    <?php   include '../includes/navbar.php'; ?>
    <?php include 'admin_nav.php'; ?>
    <div class="main-content">

        <h2 class="page-title mb-1">จัดการผู้ใช้</h2>
        <p class="text-secondary mb-4">
            จัดการบัญชีผู้ใช้งานในระบบ
        </p>

        <div class="card-dark">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th width="120">Role</th>
                        <th width="120">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?= $u['role']=='admin'?'bg-warning':'bg-secondary' ?>">
                                <?= $u['role'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="user_edit.php?id=<?= $u['id'] ?>"
                            class="btn btn-warning btn-sm">
                            แก้ไข
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
