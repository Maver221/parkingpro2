<?php 
    require '../includes/db.php'; 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]); $u = $stmt->fetch();
            if ($u && password_verify($_POST['password'], $u['password'])) {
                $_SESSION['user_id'] = $u['id']; $_SESSION['role'] = $u['role']; header("Location: ../home/home.php");
            } else $err = "รหัสผิด";
        }
        $path = '../'; 
?>
    <!DOCTYPE html>
    <html lang="th"
    ><head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css">
    </head>

    <?php if(isset($_GET['reset']) && $_GET['reset'] == 'success'): ?>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="loginToast" class="toast show text-bg-success border-0" role="alert">
                <div class="toast-body">
                    ✔ เปลี่ยนรหัสผ่านสำเร็จ เข้าสู่ระบบได้เลย
                </div>
            </div>
        </div>

        <script>
            setTimeout(() => {
                const toast = document.getElementById('loginToast');
                if (toast) toast.classList.remove('show');
            }, 5000);
        </script>
    <?php endif; ?>

    <body>
        <?php 
            include '../includes/navbar.php'; ?>
            <div class="container mt-5 d-flex justify-content-center">
                <div class="card-custom col-md-5">
                    <h3 class="text-center">เข้าสู่ระบบ</h3>
        <?php if(isset($err)) echo "<div class='text-danger text-center'>$err</div>"; ?>
                    <form method="post" class="mt-3">
                        <input name="username" class="form-control mb-3" placeholder="Username" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <button class="btn btn-primary-custom w-100">Login</button>
                        <br><br>
                    </form>
                    
                    <form method="post" class="mt-3">
                        <a href="repassword.php" class="btn btn-primary-custom w-100">
                            Repassword
                        </a>
                    </form>

                </div>
            </div>
    </body>
    </html>