<?php 
require '../includes/db.php'; 
if(!isset($_SESSION['user_id'])) header("Location: ../auth/login.php");

// ส่วนจัดการ Logic การเติมเงิน
$msg = ""; $err = "";

// 1. Logic เติมโค้ด
if(isset($_POST['redeem_code'])) {
    $chk = $pdo->prepare("SELECT * FROM topup_codes WHERE code = ? AND is_used = 0");
    $chk->execute([$_POST['code']]);
    if($res = $chk->fetch()) {
        $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")->execute([$res['amount'], $_SESSION['user_id']]);
        $pdo->prepare("UPDATE topup_codes SET is_used = 1 WHERE id = ?")->execute([$res['id']]);
        $msg = "เติมเงินสำเร็จ {$res['amount']} บาท";
    } else {
        $err = "โค้ดไม่ถูกต้อง หรือถูกใช้ไปแล้ว";
    }
}

// 2. Logic แจ้งโอนเงิน (จำลอง)
if(isset($_POST['bank_transfer'])) {
    // ในระบบจริงต้องมีการอัปโหลดไฟล์ แต่ตรงนี้ทำแจ้งเตือนจำลองไว้ก่อน
    $msg = "ส่งข้อมูลแจ้งโอนเรียบร้อย! (ระบบจำลอง: เงินยังไม่เข้าจริง ต้องรอแอดมินอนุมัติ)";
}

$path = '../';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เติมเงิน - ParkingPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <h3 class="mb-4 text-center fw-bold"><i class="fas fa-wallet"></i> เลือกช่องทางเติมเงิน</h3>

                <div class="row mb-4">
                    <div class="col-6">
                        <div class="topup-option active" id="btn-bank" onclick="switchTab('bank')">
                            <div class="topup-icon"><i class="fas fa-university"></i></div>
                            <div>โอนผ่านธนาคาร</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="topup-option" id="btn-code" onclick="switchTab('code')">
                            <div class="topup-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div>กรอกโค้ดส่วนลด</div>
                        </div>
                    </div>
                </div>

                <div class="card-custom">
                    <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?=$msg?></div><?php endif; ?>
                    <?php if($err): ?><div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?=$err?></div><?php endif; ?>

                    <div id="section-bank">
                        <h4 class="mb-3 text-primary"><i class="fas fa-money-bill-wave"></i> โอนเงินผ่านบัญชี</h4>
                        <div class="alert bg-opacity-10 border-secondary text-light">
                            <h5 class="fw-bold">ธนาคารกสิกรไทย (K-Bank)</h5>
                            <p class="mb-0">เลขบัญชี: <strong>123-4-56789-0</strong></p>
                            <p class="mb-0">ชื่อบัญชี: <strong>บจก. ปาร์คกิ้งโปร (จำลอง)</strong></p>
                        </div>
                        
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">จำนวนเงินที่โอน</label>
                                <input type="number" class="form-control" placeholder="เช่น 100" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่และเวลาโอน</label>
                                <div class="row">
                                    <div class="col-6"><input type="date" class="form-control" required></div>
                                    <div class="col-6"><input type="time" class="form-control" required></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">แนบสลิปโอนเงิน (เลือกไฟล์)</label>
                                <input type="file" name="slip_file" class="form-control" required>
                                <small class="text-secondary">* รองรับไฟล์ .jpg, .png</small>
                            </div>
                            <button type="submit" name="bank_transfer" class="btn btn-primary-custom w-100 py-2 mt-2">
                                <i class="fas fa-paper-plane"></i> แจ้งโอนเงิน
                            </button>
                        </form>
                    </div>

                    <div id="section-code" class="d-none">
                        <h4 class="mb-3 text-warning"><i class="fas fa-star"></i> แลกรับรางวัล/เติมเงิน</h4>
                        <p class="text-secondary">กรอกโค้ดที่คุณได้รับจากกิจกรรม หรือคูปองส่วนลดจากร้านค้า</p>
                        
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">กรอกโค้ดที่นี่</label>
                                <input type="text" name="code" class="form-control form-control-lg text-center" placeholder="เช่น Px100" required>
                            </div>
                            <button type="submit" name="redeem_code" class="btn btn-primary-custom w-100 py-2 mt-2">
                                <i class="fas fa-check"></i> ใช้งานโค้ดเลย
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <br><br>

    <script>
        function switchTab(tab) {
            // เอา active ออกจากปุ่มทั้งหมด
            document.getElementById('btn-bank').classList.remove('active');
            document.getElementById('btn-code').classList.remove('active');
            
            // ซ่อนเนื้อหาทั้งหมด
            document.getElementById('section-bank').classList.add('d-none');
            document.getElementById('section-code').classList.add('d-none');

            // เปิดเฉพาะตัวที่เลือก
            if (tab === 'bank') {
                document.getElementById('btn-bank').classList.add('active');
                document.getElementById('section-bank').classList.remove('d-none');
            } else {
                document.getElementById('btn-code').classList.add('active');
                document.getElementById('section-code').classList.remove('d-none');
            }
        }
    </script>
</body>
</html>