<?php
require '../includes/db.php';

/* =========================
   โหลดข้อมูล
========================= */
$types = $pdo
    ->query("SELECT * FROM vehicle_types")
    ->fetchAll(PDO::FETCH_ASSOC);

$zones = $pdo
    ->query("SELECT * FROM zones")
    ->fetchAll(PDO::FETCH_ASSOC);

$slots = $pdo
    ->query("
        SELECT s.*, z.type AS zone_type
        FROM slots s
        JOIN zones z ON s.zone_id = z.id
    ")
    ->fetchAll(PDO::FETCH_ASSOC);


/* =========================
   Logic การจอง
========================= */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_SESSION['user_id'])
) {

    $slot_id = $_POST['slot_id'];

    /* ดึงเงินผู้ใช้ */
    $user = $pdo->prepare(
        "SELECT balance FROM users WHERE id=?"
    );
    $user->execute([$_SESSION['user_id']]);
    $balance = $user->fetchColumn();

    /* เช็คโซนของช่อง */
    $zoneTypeStmt = $pdo->prepare("
        SELECT z.type
        FROM slots s
        JOIN zones z ON s.zone_id = z.id
        WHERE s.id=?
    ");
    $zoneTypeStmt->execute([$slot_id]);
    $zoneType = $zoneTypeStmt->fetchColumn();

    /* ราคาตามโซน */
    $price = ($zoneType === 'vip') ? 100 : 50;

    if ($balance >= $price) {

        /* เปลี่ยนสถานะช่อง */
        $pdo->prepare(
            "UPDATE slots SET status='booked' WHERE id=?"
        )->execute([$slot_id]);

        /* หักเงิน */
        $pdo->prepare(
            "UPDATE users SET balance = balance - ? WHERE id=?"
        )->execute([
            $price,
            $_SESSION['user_id']
        ]);

        /* บันทึกการจอง */
        $pdo->prepare("
            INSERT INTO bookings
            (
                user_id,
                slot_id,
                vehicle_type_id,
                license_plate,
                start_time,
                end_time
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ")->execute([
            $_SESSION['user_id'],
            $slot_id,
            $_POST['vehicle_type'],
            $_POST['plate'],
            $_POST['start_time'],
            $_POST['end_time']
        ]);

        echo "<script>
                alert('จองสำเร็จ!');
                location='home.php';
              </script>";
        exit;

    } else {

        echo "<script>
                alert('เงินไม่พอ!');
                location='../topup/index.php';
              </script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ParkingPro</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="row">

        <!-- LEFT -->
        <div class="col-md-4 mb-4">
            <div class="card-custom">

                <h4 class="fw-bold mb-3">
                    <i class="fas fa-cog"></i>
                    ตั้งค่าการจอง
                </h4>

                <form method="post">

                    <div class="mb-3">
                        <label>ประเภทรถ</label>

                        <select
                            name="vehicle_type"
                            id="vehicleType"
                            class="form-select"
                        >
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t['id'] ?>">
                                    <?= $t['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>โซน</label>

                        <select
                            id="zoneSelect"
                            class="form-select"
                        >
                            <?php foreach ($zones as $z): ?>
                                <option
                                    value="<?= $z['id'] ?>"
                                    data-type="<?= $z['type'] ?>"
                                >
                                    <?= $z['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>ทะเบียน</label>

                        <input
                            name="plate"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <input
                                type="datetime-local"
                                name="start_time"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-6">
                            <input
                                type="datetime-local"
                                name="end_time"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <input
                        type="hidden"
                        name="slot_id"
                        id="selectedSlotId"
                    >

                    <div
                        id="selectedSlotDisplay"
                        class="alert alert-info d-none mt-3"
                    >
                        เลือกช่อง:
                        <b id="slotName"></b>
                        <br>
                        ราคา:
                        <b>
                            <span id="slotPrice"></span> บาท
                        </b>
                    </div>

                    <button class="btn btn-primary-custom w-100 mt-3">
                        จองช่อง
                    </button>

                </form>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-md-8">
            <div class="card-custom h-100">
                <div class="slot-container">

                    <?php foreach ($slots as $s): ?>
                        <div
                            class="slot-box
                                <?= $s['status'] === 'booked'
                                    ? 'slot-booked'
                                    : (
                                        $s['zone_type'] === 'vip'
                                        ? 'slot-vip'
                                        : 'slot-normal'
                                    )
                                ?>"
                            data-vehicle="<?= $s['vehicle_type_id'] ?>"
                            data-zone-type="<?= $s['zone_type'] ?>"
                            data-base="<?= $s['slot_number'] ?>"
                            onclick="selectSlot(this, <?= $s['id'] ?>)"
                        >
                            <span class="slot-emoji">🚗</span>

                            <span class="slot-name">
                                <?= $s['zone_type'] === 'vip'
                                    ? 'VIP ' . $s['slot_number']
                                    : $s['slot_number']
                                ?>
                            </span>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
const vehicleSel = document.getElementById('vehicleType');
const zoneSel    = document.getElementById('zoneSelect');

function emojiByVehicle(id) {
    if (id == 2) return '🏍️';
    if (id == 3) return '🏎️';
    return '🚗';
}

function updateSlots() {
    const vehicleId = vehicleSel.value;
    const zoneType  =
        zoneSel.options[zoneSel.selectedIndex].dataset.type;

    document.querySelectorAll('.slot-box').forEach(slot => {

        if (
            slot.dataset.vehicle !== vehicleId ||
            slot.dataset.zoneType !== zoneType
        ) {
            slot.style.display = 'none';
            return;
        }

        slot.style.display = 'flex';
        slot.querySelector('.slot-emoji').innerText =
            emojiByVehicle(vehicleId);
    });

    clearSelection();
}

function clearSelection() {
    document
        .getElementById('selectedSlotDisplay')
        .classList.add('d-none');

    document.getElementById('selectedSlotId').value = '';

    document
        .querySelectorAll('.slot-box')
        .forEach(e => e.classList.remove('slot-selected'));
}

function selectSlot(el, id) {
    if (el.classList.contains('slot-booked')) return;

    clearSelection();

    el.classList.add('slot-selected');
    document.getElementById('selectedSlotId').value = id;

    document.getElementById('slotName').innerText =
        el.querySelector('.slot-name').innerText;

    document.getElementById('slotPrice').innerText =
        el.dataset.zoneType === 'vip' ? 100 : 50;

    document
        .getElementById('selectedSlotDisplay')
        .classList.remove('d-none');
}

vehicleSel.addEventListener('change', updateSlots);
zoneSel.addEventListener('change', updateSlots);
updateSlots();
</script>

</body>
</html>
