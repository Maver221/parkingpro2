<?php
    require 'includes/db.php';
    session_start();

    $err = $msg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /* ===== LOGIN ===== */
        if (isset($_POST['login'])) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($u && password_verify($_POST['password'], $u['password'])) {
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['role'] = $u['role'];
                header("Location: home/home.php");
                exit;
            } else {
                $err = "Username หรือ Password ผิด";
            }
        }

        /* ===== REGISTER ===== */
        if (isset($_POST['register'])) {
            $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $check->execute([$_POST['username']]);

            if ($check->rowCount() > 0) {
                $err = "Username นี้มีคนใช้แล้ว";
            } else {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, recovery_code, role)
                    VALUES (?, ?, ?, ?, 'user')
                ");
                $stmt->execute([
                    $_POST['username'],
                    $_POST['email'],
                    $hash,
                    $_POST['recovery_code']
                ]);

                $msg = "สมัครสำเร็จ กดเข้าสู่ระบบได้เลย";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ParkingPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h1 class="text-center fw-bold mb-4">PARKINGPRO</h1>

                <!-- BUTTON SWITCH -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="topup-option" id="btn-login" onclick="switchTab('login')">
                            <h3>เข้าสู่ระบบ</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="topup-option" id="btn-register" onclick="switchTab('register')">
                            <h3>สมัครสมาชิก</h3>
                        </div>
                    </div>
                </div>

                <div class="card-custom p-4">

                    <?php if ($msg): ?>
                        <div class="alert alert-success text-center"><?= $msg ?></div>
                    <?php endif; ?>

                    <?php if ($err): ?>
                        <div class="alert alert-danger text-center"><?= $err ?></div>
                    <?php endif; ?>

                    <!-- LOGIN -->
                    <div id="login-section" class="d-none">

                        <h4 class="mb-3 text-primary">
                            <i class="fas fa-money-bill-wave"></i> เข้าสู่ระบบ
                        </h4>

                        <div class="alert bg-opacity-10 border-secondary text-light">
                            <h5 class="fw-bold">เข้าสู่ระบบด้วยรหัสผ่านที่ท่านได้สมัครไว้</h5>
                            <p class="mb-0">ชื่อบัญชี เช่น Parkingpro</p>
                            <p class="mb-0">รหัสผ่าน เช่น Parkingpro111</p>
                            <p class="mb-0">ลืมรหัสผ่านกด Repassword</p>
                        </div>

                        <h3 class="text-center my-3">เข้าสู่ระบบ</h3>

                        <form method="post">
                            <input name="username" class="form-control mb-3" placeholder="Username" required>
                            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

                            <button name="login" class="btn btn-primary-custom w-100">
                                Login
                            </button>
                        </form>

                        <a href="auth/repassword.php" class="btn btn-primary-custom w-100 mt-3">
                            Repassword
                        </a>
                    </div>

                    <!-- REGISTER -->
                    <div id="register-section" class="d-none">

                        <h4 class="mb-3 text-primary">
                            <i class="fas fa-money-bill-wave"></i> สมัครสมาชิก
                        </h4>

                        <div class="alert bg-opacity-10 border-secondary text-light">
                            <h5 class="fw-bold">สมัครสมาชิกโปรดตั้ง username และ password ที่ท่านจำได้</h5>
                            <p class="mb-0">username เช่น Parkingpro</p>
                            <p class="mb-0">email ของท่าน</p>
                            <p class="mb-0">password เช่น Parkingpro111</p>
                            <p class="mb-0">recovery_code 6 หลัก</p>
                        </div>

                        <h3 class="text-center my-3">สมัครสมาชิก</h3>

                        <form method="post">
                            <input name="username" class="form-control mb-3" placeholder="Username" required>
                            <input name="email" class="form-control mb-3" placeholder="Email" required>
                            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                            <input name="recovery_code" class="form-control mb-3" placeholder="Recovery Code (6 digits)" required>

                            <button name="register" class="btn btn-primary-custom w-100">
                                Register
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
    function switchTab(tab) {
        document.getElementById('btn-login').classList.remove('active');
        document.getElementById('btn-register').classList.remove('active');
        document.getElementById('login-section').classList.add('d-none');
        document.getElementById('register-section').classList.add('d-none');

        if (tab === 'login') {
            document.getElementById('btn-login').classList.add('active');
            document.getElementById('login-section').classList.remove('d-none');
        } else {
            document.getElementById('btn-register').classList.add('active');
            document.getElementById('register-section').classList.remove('d-none');
        }
    }
    </script>

</body>
</html>
