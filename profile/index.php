<?php require '../includes/db.php'; 
        if(!isset($_SESSION['user_id'])) header("Location: ../auth/login.php");
        $u = $pdo->query("SELECT * FROM users WHERE id={$_SESSION['user_id']}")->fetch();
        $hist = $pdo->query("SELECT * FROM bookings b JOIN slots s ON b.slot_id=s.id WHERE user_id={$_SESSION['user_id']} ORDER BY b.id DESC");
        $path = '../';
    ?>
    <!DOCTYPE html><html lang="th"><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/style.css"></head>
    <body><?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card-custom">
                    <h5>ข้อมูลส่วนตัว</h5>
                    <p>User: <?= $u['username'] ?><br>Email: <?= $u['email'] ?><br>Balance: <span class="text-success"><?= number_format($u['balance']) ?></span> บ.</p>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-custom">
                    <h5>ประวัติการจอง</h5>
                    <table class="table table-dark table-hover mt-3">
                        <thead>
                            <tr>
                                <th>ช่อง</th>
                                <th>เวลาจอง</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php while($r=$hist->fetch()): ?>
                                <tr>
                                    <td><?= $r['slot_number'] ?></td>
                                    <td><?= $r['booking_date'] ?></td>
                                </tr>
                                <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>