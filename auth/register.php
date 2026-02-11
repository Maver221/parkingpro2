<?php require '../includes/db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             $pdo->prepare("INSERT INTO users (username, email, password, recovery_code) VALUES (?,?,?,?)")
             ->execute([$_POST['username'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['recovery_code']]);
             header("Location: login.php");
        }
        $path = '../';
    ?>
    <!DOCTYPE html>
    <html lang="th">
     <head>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css">
     </head>
     <body>
          <?php include '../includes/navbar.php'; ?>
          <div class="container mt-5 d-flex justify-content-center">
               <div class="card-custom col-md-6">
                    <h3 class="text-center">สมัครสมาชิก</h3>
                    <form method="post" class="mt-3">
                         <input name="username" class="form-control mb-3" placeholder="Username" required>
                         <input name="email" class="form-control mb-3" placeholder="Email" required>
                         <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                         <input name="recovery_code" class="form-control mb-3" placeholder="Recovery Code (6 digits)" required>
                         <button class="btn btn-primary-custom w-100">Register</button>
                    </form>
               </div>
          </div>
     </body>
     </html>