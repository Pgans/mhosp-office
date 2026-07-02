<?php
use yii\helpers\Html;

$currentDate = date('Y-m-d');

$icons = [
    'OPD' => ['fa-hospital-user', '#7e57c2'],          // ม่วงเข้ม
    'ERดึก' => ['fa-moon', '#9575cd'],                // ม่วงอ่อน
    'ERเช้า' => ['fa-sun', '#7986cb'],                // น้ำเงินม่วง
    'ERบ่าย' => ['fa-cloud-sun', '#64b5f6'],          // ฟ้า
    'ANC' => ['fa-baby', '#ba68c8'],                   // ม่วงชมพู
    'THAIMED' => ['fa-spa', '#ab47bc'],                // ม่วงสด
    'DENT' => ['fa-tooth', '#9fa8da'],                  // ฟ้าม่วงอ่อน
    'NCD' => ['fa-heartbeat', '#8e24aa'],               // ม่วงเข้ม
    'ARI' => ['fa-lungs', '#ce93d8'],                   // ม่วงอ่อนอมชมพู
    'VIP' => ['fa-user-tie', '#5e35b1'],                // ม่วงเข้ม
    'PHISICAL' => ['fa-dumbbell', '#7e57c2'],
    'TB' => ['fa-bacterium', '#9575cd'],
    'ARV' => ['fa-pills', '#7986cb'],
    'HD' => ['fa-procedures', '#64b5f6'],
    'ACU' => ['fa-leaf', '#ba68c8'],
    'PCU' => ['fa-hospital-alt', '#ab47bc'],
    'elderly' => ['fa-blind', '#9fa8da'],
    'AIDS' => ['fa-virus', '#8e24aa'],
    'Telemed' => ['fa-video', '#ce93d8'],
    'คลินิกวัยใส' => ['fa-child', '#5e35b1'],
    'Teledentistry' => ['fa-teeth', '#7e57c2'],
    'Vipพิเศษ' => ['fa-crown', '#9575cd'],
    'ไวรัสตับอักเสบ' => ['fa-disease', '#7986cb'],
    'อายุรกรรมทั่วไป' => ['fa-user-md', '#64b5f6'],
    'อายุรกรรมโรคไต' => ['fa-kidneys', '#ba68c8'],
    'ORTHER' => ['fa-question', '#ab47bc'],
];

$exclude = ['REGDATE', 'TOTAL'];
?>

<!-- Font + Icons -->
<link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
/* ปิด Left Menu และขยายพื้นที่ content */
.sidebar,
.main-sidebar,
.sidebar-menu {
    display: none !important;
}

.content-wrapper, .content {
    margin-left: 0 !important;
}
</style>
<style>
    body {
        background: linear-gradient(to bottom, #ede7f6, #d1c4e9);
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 20px;
    }

    /* Custom column for 8 cards per row */
    .col-8per-row {
        flex: 0 0 calc(100% / 8);
        max-width: calc(100% / 8);
        padding: 8px;
        box-sizing: border-box;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .col-8per-row {
            flex: 0 0 25%; /* 4 per row */
            max-width: 25%;
        }
    }

    @media (max-width: 768px) {
        .col-8per-row {
            flex: 0 0 50%; /* 2 per row */
            max-width: 50%;
        }
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -8px;  /* compensate padding */
        margin-right: -8px;
    }

    .card-box {
        background: linear-gradient(135deg, #d7caec, #e6dff2); /* โทนม่วงอ่อน */
        border-radius: 20px;
        /* ขอบ 3 มิติด้วยเงา */
        box-shadow:
            4px 4px 10px rgba(110, 81, 153, 0.5),
            -4px -4px 10px rgba(255, 255, 255, 0.7);
        padding: 20px;
        text-align: center;
        height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        user-select: none;
        transition: 0.3s ease;
        color: #311b92; /* ตัวหนังสือม่วงเข้ม */
    }

    .card-box:hover {
        box-shadow:
            6px 6px 14px rgba(110, 81, 153, 0.7),
            -6px -6px 14px rgba(255, 255, 255, 0.9);
        transform: translateY(-3px);
    }

    .card-icon {
        font-size: 36px;
        margin-bottom: 12px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 6px;
        color: #311b92;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        /* สีตัวเลขจะกำหนดแบบ inline ตามแต่ละแผนก */
    }

    .time-box {
        font-size: 20px;
        text-align: center;
        margin-bottom: 25px;
        font-weight: 600;
        color: #311b92;
    }
</style>
<button class="btn btn-info"
    style="position: absolute; top: 10px; right: 10px;
           font-weight: bold; border-radius: 20px;
           padding: 6px 15px; font-size: 1.1rem; z-index: 999;">
    <?= $amount ?>
</button>

<div class="container mt-4">
    <h2 class="text-center mb-3" style="color:#311b92;">
        📊 สรุปจำนวนผู้รับบริการได้รับการตรวจ วินิจฉัย(<?= Html::encode($currentDate) ?>)
    </h2>


    <div class="text-center my-3">
	<button class="btn btn-info shadow"
        style="font-weight: bold; border-radius: 25px;
               padding: 10px 20px; font-size: 1.2rem;">
        <?= $amount ?>
    </button>
    <span class="time-box bg-light px-3 py-2 rounded shadow-sm d-inline-block" style="font-size: 2.1rem;">
        🕒 <span id="currentTime">--:--:--</span>
    </span>
</div>

    <div class="row">
        <!-- รวมทั้งหมด -->
        <div class="col-8per-row">
            <div class="card-box">
                <div class="card-icon" style="color: #ffca28;"><i class="fas fa-users"></i></div>
                <div class="card-title">รวมทั้งหมด</div>
                <div class="card-value" style="color: #f57f17;"><?= Html::encode($r['TOTAL'] ?? 0) ?></div>
            </div>
        </div>

        <!-- แสดง 27 แผนก -->
        <?php
        $count = 0;
        foreach ($r as $key => $val):
            if (in_array($key, $exclude)) continue;
            if ($count >= 27) break;
            $icon = $icons[$key][0] ?? 'fa-notes-medical';
            $color = $icons[$key][1] ?? '#311b92';
            $count++;
        ?>
            <div class="col-8per-row">
                <div class="card-box">
                    <div class="card-icon" style="color: <?= $color ?>"><i class="fas <?= $icon ?>"></i></div>
                    <div class="card-title"><?= Html::encode($key) ?></div>
                    <div class="card-value" style="color: <?= $color ?>;"><?= Html::encode($val) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function updateTime() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2,'0');
        const minutes = now.getMinutes().toString().padStart(2,'0');
        const seconds = now.getSeconds().toString().padStart(2,'0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        document.getElementById('currentTime').textContent = timeString;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // รีเฟรชหน้าอัตโนมัติทุก 1 ชั่วโมง
    setTimeout(() => location.reload(), 3600000);
</script>
