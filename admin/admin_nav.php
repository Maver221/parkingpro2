<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar">
    <h5>ADMIN PANEL</h5>

    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a class="nav-link <?= $current=='users.php'?'active':'' ?>" href="users.php">
                👤 จัดการผู้ใช้
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $current=='vehicle_types.php'?'active':'' ?>" href="vehicle_types.php">
                🚗 ประเภทรถ
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $current=='zones.php'?'active':'' ?>" href="zones.php">
                🗺️ โซนจอด
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $current=='topup_codes.php'?'active':'' ?>" href="topup_codes.php">
                💳 โค้ดเติมเงิน
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $current=='editslot.php'?'active':'' ?>" href="editslot.php">
                📦 จัดการ SLOT
            </a>
        </li>

        <li class="nav-item">
            <span class="nav-link disabled">⛔ การจอง (ยังไม่เปิด)</span>
        </li>
    </ul>
</div>
