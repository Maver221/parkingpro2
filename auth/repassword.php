<?php
    require '../includes/db.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['username'];
        $recovery = $_POST['recovery_code'];
        $newpass  = $_POST['new_password'];

        // เช็ก user + recovery_code
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND recovery_code = ?");
        $stmt->execute([$username, $recovery]);
        $user = $stmt->fetch();

        if ($user) {
            // แฮชรหัสใหม่
            $hash = password_hash($newpass, PASSWORD_DEFAULT);

            // อัปเดตรหัสผ่านอย่างเดียว
            $up = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $up->execute([$hash, $user['id']]);

            // รีผ่าน → เด้งไปหน้า login
            header("Location: login.php?reset=success");
            exit;
        } else {
            $err = "Username หรือ Recovery Code ไม่ถูกต้อง";
        }
    }
?>


<!DOCTYPE html>
<html lang="th">
<head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
        include '../includes/navbar.php'; 
    ?>

<div class="container mt-5 d-flex justify-content-center">
    <div class="card-custom col-md-6">
        <h3 class="text-center">กู้คืนรหัสผ่าน</h3>

        <?php if(isset($msg)) echo "<div class='alert alert-success text-center'>$msg</div>"; ?>
        <?php if(isset($err)) echo "<div class='alert alert-danger text-center'>$err</div>"; ?>

        <form method="post">
            <input name="username" class="form-control mb-3" placeholder="Username" required>

            <input name="recovery_code" class="form-control mb-3" placeholder="Recovery Code" required>

            <input type="password" name="new_password" class="form-control mb-3" placeholder="New Password" required>

            <button class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
</div>

</body>
</html>
