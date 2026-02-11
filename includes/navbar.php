<?php
define('BASE_URL', '/parkingpro/');
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top main-navbar">
  <div class="container">
    <a class="navbar-brand brand-text" href="<?= BASE_URL ?>home/home.php">
      <i class="fas fa-parking me-2"></i> ParkingPro
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-2">

        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>home/home.php">หน้าหลัก</a>
        </li>

        <?php if(isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>topup/index.php">เติมเงิน</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>contact/index.php">ติดต่อเรา</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>profile/index.php">โปรไฟล์</a>
          </li>

          <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link admin-link" href="<?= BASE_URL ?>admin/adminhome.php">
                จัดการหลังบ้าน
              </a>
            </li>
          <?php endif; ?>

          <li class="nav-item ms-2">
            <a class="btn btn-logout btn-sm" href="<?= BASE_URL ?>auth/logout.php">
              ออก
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>auth/login.php">เข้าสู่ระบบ</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-register ms-2" href="<?= BASE_URL ?>auth/register.php">
              สมัครสมาชิก
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<div class="navbar-spacer"></div>
