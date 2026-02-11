<?php
require '../includes/db.php';

/* โหลดข้อมูล */
$zones = $pdo->query("SELECT * FROM zones")->fetchAll(PDO::FETCH_ASSOC);
$types = $pdo->query("SELECT * FROM vehicle_types")->fetchAll(PDO::FETCH_ASSOC);

$slots = $pdo->query("
    SELECT s.*, 
           z.name AS zone_name, 
           z.type AS zone_type, 
           vt.name AS vehicle_name
    FROM slots s
    JOIN zones z ON s.zone_id = z.id
    JOIN vehicle_types vt ON s.vehicle_type_id = vt.id
    ORDER BY s.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* เพิ่ม slot */
if (isset($_POST['add_slot'])) {
    $prefix = strtoupper(trim($_POST['slot_prefix']));
    $count  = (int)$_POST['slot_count'];
    $zoneId = $_POST['zone_id'];
    $vehicleTypeId = $_POST['vehicle_type_id'];

    $stmt = $pdo->prepare("
        INSERT INTO slots (slot_number, zone_id, vehicle_type_id, status)
        VALUES (?, ?, ?, 'available')
    ");

    for ($i = 1; $i <= $count; $i++) {
        $stmt->execute([
            $prefix . $i,
            $zoneId,
            $vehicleTypeId
        ]);
    }

    header("Location: editslot.php");
    exit;
}

/* ลบ slot */
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM slots WHERE id=?")->execute([$_GET['delete']]);
    header("Location: editslot.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="/parkingpro/css/navbarheader.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include 'admin_nav.php'; ?>

<div class="main-content">

    <h2 class="page-title">จัดการช่องจอด</h2>

    <!-- เพิ่มช่อง -->
    <div class="card-dark">
        <form method="post" class="row g-2">
            <div class="col-md-2">
                <input name="slot_prefix" class="form-control" placeholder="ตัวอักษร (A, B)" required>
            </div>

            <div class="col-md-2">
                <input type="number" name="slot_count" class="form-control" placeholder="จำนวนช่อง" min="1" required>
            </div>

            <div class="col-md-3">
                <select name="zone_id" class="form-select" required>
                    <option value="">เลือกโซน</option>
                    <?php foreach($zones as $z): ?>
                        <option value="<?= $z['id'] ?>">
                            <?= $z['name'] ?> (<?= strtoupper($z['type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="vehicle_type_id" class="form-select" required>
                    <option value="">ประเภทรถ</option>
                    <?php foreach($types as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <button name="add_slot" class="btn btn-success w-100">
                    เพิ่มช่อง
                </button>
            </div>
        </form>
    </div>

    <!-- ตัวกรอง -->
    <div class="card-dark">
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filterZone" class="form-select">
                    <option value="all">แสดงทุกโซน</option>
                    <?php foreach($zones as $z): ?>
                        <option value="<?= $z['id'] ?>"><?= $z['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select id="filterVehicle" class="form-select">
                    <option value="all">แสดงทุกประเภทรถ</option>
                    <?php foreach($types as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- ตาราง -->
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ช่อง</th>
                    <th>โซน</th>
                    <th>ประเภท</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($slots as $s): ?>
                <tr 
                    data-zone="<?= $s['zone_id'] ?>" 
                    data-vehicle="<?= $s['vehicle_type_id'] ?>"
                >
                    <td><?= $s['id'] ?></td>
                    <td>
                        <?= $s['zone_type']=='vip' ? 'VIP ' : '' ?>
                        <?= $s['slot_number'] ?>
                    </td>
                    <td><?= $s['zone_name'] ?></td>
                    <td><?= $s['vehicle_name'] ?></td>
                    <td>
                        <span class="badge <?= $s['status']=='available'?'bg-success':'bg-danger' ?>">
                            <?= $s['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="?delete=<?= $s['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('ลบช่องนี้?')">
                           ลบ
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
const filterZone = document.getElementById('filterZone');
const filterVehicle = document.getElementById('filterVehicle');

function applyFilter() {
    const zoneVal = filterZone.value;
    const vehicleVal = filterVehicle.value;

    document.querySelectorAll('tbody tr').forEach(row => {
        const matchZone =
            zoneVal === 'all' || row.dataset.zone === zoneVal;

        const matchVehicle =
            vehicleVal === 'all' || row.dataset.vehicle === vehicleVal;

        row.style.display = (matchZone && matchVehicle) ? '' : 'none';
    });
}

filterZone.addEventListener('change', applyFilter);
filterVehicle.addEventListener('change', applyFilter);
</script>

</body>
</html>
