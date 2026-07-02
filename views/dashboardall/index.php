<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;
$this->title = Yii::t('app', 'ITA ปีงบประมาณ 2569 หน่วยงาน:::โรงพยาบาลม่วงสามสิบ จังหวัดอุบลราชธานี');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ITA</title>
<script src="script.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script>
    window.onload = function() {
        var editUrl = sessionStorage.getItem('editUrl');
        if (editUrl) {
            sessionStorage.removeItem('editUrl');
            var targetDivIds = ['model1', 'model2', 'model3'];
            targetDivIds.forEach(function(targetDivId) {
                var targetDiv = document.getElementById(targetDivId);
                if (targetDiv) {
                    targetDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    console.error('ไม่พบ <div> ที่ ID เป็น ' + targetDivId + ' ในหน้า index.php');
                }
            });
        }
    };
</script>
<style>
    /* พื้นหลังหน้าเว็บ */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px 0;
        font-family: 'Sarabun', 'Helvetica Neue', Arial, sans-serif;
    }

    /* หัวข้อหลัก */
    h1, .page-title {
        color: #2c3e50;
        text-align: center;
        padding: 20px 0;
        font-weight: bold;
        font-size: 28px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Container */
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* กล่องหลัก 3 กล่อง */
    .info-box-container {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .info-box {
        flex: 1;
        min-width: 300px;
        border-radius: 15px;
        padding: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    }

    /* กล่องที่ 1 - สีชมพูอ่อนสดใส */
    .info-box-1 {
        background: linear-gradient(135deg, #ffa8d5 0%, #ff6ec7 100%);
    }

    /* กล่องที่ 2 - สีชมพูพีช */
    .info-box-2 {
        background: linear-gradient(135deg, #ffb7d5 0%, #ffc3a0 100%);
    }

    /* กล่องที่ 3 - สีชมพูสดใสแดงอมส้ม */
    .info-box-3 {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    }

    /* หัวข้อในกล่อง */
    .info-box h3 {
        color: white;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(255,255,255,0.3);
    }

    /* รายการในกล่อง */
    .info-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-box li {
        padding: 8px 0;
        padding-left: 25px;
        position: relative;
        color: white;
        font-size: 15px;
    }

    .info-box li:before {
        content: "✓";
        position: absolute;
        left: 0;
        font-weight: bold;
        font-size: 18px;
    }

    /* ป้ายกำกับ MOIT */
    .moit-label {
        background: rgba(255,255,255,0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: bold;
        margin-right: 5px;
        display: inline-block;
    }

    /* Panel สำหรับ Model */
    .panel {
        width: 100%;
        margin-bottom: 25px;
        border: none;
        border-radius: 15px;
        padding: 25px;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .panel:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    /* หัวข้อ Panel */
    .panel-heading {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 15px 20px;
        font-weight: bold;
        font-size: 20px;
        margin: -25px -25px 20px -25px;
        text-align: center;
    }

    /* ตาราง GridView */
    .grid-view table {
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
    }

    .grid-view th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: bold;
        border: none;
        padding: 15px 10px;
        text-align: center;
    }

    .grid-view td {
        padding: 12px 10px;
        border-bottom: 1px solid #e8e8e8;
        vertical-align: middle;
    }

    .grid-view tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .grid-view tr:hover {
        background-color: #f0f4ff;
        transition: background-color 0.3s ease;
    }

    /* ปุ่มต่างๆ */
    .btn {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: bold;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    }

    .btn-danger:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(235, 51, 73, 0.4);
    }

    .btn-info {
        background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
    }

    .btn-info:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(6, 190, 182, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        color: white;
    }

    /* DataTables Styling */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        border: 2px solid #667eea;
        padding: 8px 15px;
        margin-left: 10px;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 20px;
        border: 2px solid #667eea;
        padding: 5px 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        border: none;
        border-radius: 5px;
    }

    /* Pagination */
    .pagination > .active > a,
    .pagination > .active > span {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .pagination > li > a {
        color: #667eea;
        border-radius: 5px;
        margin: 0 3px;
        border: 1px solid #ddd;
    }

    .pagination > li > a:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }

    /* Form Control */
    .form-control {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    /* Badge/Label */
    .label {
        border-radius: 12px;
        padding: 5px 12px;
        font-weight: bold;
    }

    .label-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .label-danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    }

    .label-info {
        background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
    }

    /* Modal */
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 5px 5px 0 0;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-box {
            min-width: 100%;
        }
        
        .panel {
            padding: 15px;
        }
        
        .panel-heading {
            margin: -15px -15px 15px -15px;
            font-size: 18px;
        }
    }
</style>
</head>
<body>


<style>
/* ===== Canva Modern Button Style ===== */

/* กลุ่มปุ่มตรงกลาง */
.btn-group-modern {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    margin-top: 60px;
    flex-wrap: wrap;
}

/* ปุ่มหลักแนว Canva */
.btn-modern {
    padding: 16px 42px;
    font-size: 18px;
    border: none;
    border-radius: 18px;
    color: #fff;
    font-weight: 600;
    letter-spacing: 0.3px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.35s ease;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

/* เพิ่มเอฟเฟกต์ไฮไลต์เมื่อ hover */
.btn-modern::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.25);
    transition: left 0.4s ease;
}
.btn-modern:hover::after {
    left: 0;
}

/* ปุ่มปีงบ 2568 */
.btn-refers {
    background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
}

/* ปุ่มปีงบ 2569 */
.btn-opd {
    background: linear-gradient(135deg, #89f7fe, #66a6ff);
}

/* เอฟเฟกต์ hover */
.btn-modern:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* เอฟเฟกต์กด */
.btn-modern:active {
    transform: scale(0.97);
}

/* ไอคอน */
.btn-modern i {
    font-size: 20px;
}
</style>

<div class="btn-group-modern">
    <a href="<?= Url::to(['/dashboardall68/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> ปีงบ 2568
    </a>
    <a href="<?= Url::to(['/dashboardall/index']) ?>" class="btn-modern btn-opd">
        <i class="fa fa-check-square-o"></i> ปีงบ 2569
    </a>
</div>


<!-- ✅ ครอบทั้งหมดด้วย .dashboard-wrapper -->
<div class="dashboard-wrapper">
<!-- แถวบน: 2 สดมภ์ -->

<?php
// ✅ ปีที่ต้องการแสดง
$targetYears = range(2565, 2569);

// ✅ เตรียมข้อมูลพื้นฐาน
$years = $targetYears;
$opdVisits = array_fill(0, count($targetYears), 0);
$opdPersons = array_fill(0, count($targetYears), 0);
$ipdVisits = array_fill(0, count($targetYears), 0);
$ipdPersons = array_fill(0, count($targetYears), 0);

// ✅ เติมข้อมูล OPD
foreach ($opdData5 as $row) {
    if (is_array($row) && isset($row['fiscal_year'])) {
        $year = (int)$row['fiscal_year'];
        $index = array_search($year, $targetYears);
        if ($index !== false) {
            $opdVisits[$index] = isset($row['total_visit']) ? (int)$row['total_visit'] : 0;
            $opdPersons[$index] = isset($row['total_person']) ? (int)$row['total_person'] : 0;
        }
    }
}

// ✅ เติมข้อมูล IPD
foreach ($ipdData5 as $row) {
    if (is_array($row) && isset($row['fiscal_year'])) {
        $year = (int)$row['fiscal_year'];
        $index = array_search($year, $targetYears);
        if ($index !== false) {
            $ipdVisits[$index] = isset($row['total_visit']) ? (int)$row['total_visit'] : 0;
            $ipdPersons[$index] = isset($row['total_person']) ? (int)$row['total_person'] : 0;
        }
    }
}

// ✅ แปลงเป็น JSON
$yearsJson = json_encode($years, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
$opdVisitsJson = json_encode($opdVisits);
$opdPersonsJson = json_encode($opdPersons);
$ipdVisitsJson = json_encode($ipdVisits);
$ipdPersonsJson = json_encode($ipdPersons);
?>

<?php

// ตรวจสอบสิทธิ์การใช้งานปุ่ม Update
$canUpdate = false;
if (!Yii::$app->user->isGuest && Yii::$app->user->identity) {
    $canUpdate = Yii::$app->user->identity->username === '3341400051241';
}
?>
<div class="card" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fb 100%); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06); border-left: 5px solid #c844c8;">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <h3 style="margin: 0; padding: 0; color: #c844c8; font-weight: 600; font-size: 1.5rem;">
            📊 กราฟผู้ป่วย OPD / IPD
        </h3>
        
        <div class="d-flex justify-content-end align-items-center mb-3" style="gap: 10px;">
    
            <!-- Update Button -->
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['dashboardall/update'],
                'method' => 'post',
                'options' => ['style' => 'display: inline-block;']
            ]); ?>
                <?= \yii\helpers\Html::submitButton('🔄 Update', [
                    'class' => 'btn btn-warning',
                    'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;' . (!$canUpdate ? ' opacity: 0.5; cursor: not-allowed;' : ''),
                    'disabled' => !$canUpdate,
                    'onclick' => $canUpdate ? 'return confirm("คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?");' : 'return false;',
                    'title' => !$canUpdate ? 'คุณไม่มีสิทธิ์ใช้งานปุ่มนี้' : ''
                ]) ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
            
            <!-- UpTop10Refer Button -->
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['dashboardall/updatex'],
                'method' => 'post',
                'options' => ['style' => 'display: inline-block;']
            ]); ?>
                <?= \yii\helpers\Html::submitButton('🔄 UpTop10Refer', [
                    'class' => 'btn btn-warning',
                    'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;' . (!$canUpdate ? ' opacity: 0.5; cursor: not-allowed;' : ''),
                    'disabled' => !$canUpdate,
                    'onclick' => $canUpdate ? 'return confirm("คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?");' : 'return false;',
                    'title' => !$canUpdate ? 'คุณไม่มีสิทธิ์ใช้งานปุ่มนี้' : ''
                ]) ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
            
            <!-- Up-phr Button -->
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['dashboardall/updatephr'],
                'method' => 'post',
                'options' => ['style' => 'display: inline-block;']
            ]); ?>
                <?= \yii\helpers\Html::submitButton('🔄 Up-phr', [
                    'class' => 'btn btn-warning',
                    'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;' . (!$canUpdate ? ' opacity: 0.5; cursor: not-allowed;' : ''),
                    'disabled' => !$canUpdate,
                    'onclick' => $canUpdate ? 'return confirm("คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?");' : 'return false;',
                    'title' => !$canUpdate ? 'คุณไม่มีสิทธิ์ใช้งานปุ่มนี้' : ''
                ]) ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
            
            <!-- Up-Tele Button -->
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['dashboardall/updatetelemed'],
                'method' => 'post',
                'options' => ['style' => 'display: inline-block;']
            ]); ?>
                <?= \yii\helpers\Html::submitButton('🔄 Up-Tele', [
                    'class' => 'btn btn-warning',
                    'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;' . (!$canUpdate ? ' opacity: 0.5; cursor: not-allowed;' : ''),
                    'disabled' => !$canUpdate,
                    'onclick' => $canUpdate ? 'return confirm("คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?");' : 'return false;',
                    'title' => !$canUpdate ? 'คุณไม่มีสิทธิ์ใช้งานปุ่มนี้' : ''
                ]) ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
            
            <!-- Up-ncd Button -->
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['dashboardall/updatencd'],
                'method' => 'post',
                'options' => ['style' => 'display: inline-block;']
            ]); ?>
                <?= \yii\helpers\Html::submitButton('🔄 Up-ncd', [
                    'class' => 'btn btn-warning',
                    'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;' . (!$canUpdate ? ' opacity: 0.5; cursor: not-allowed;' : ''),
                    'disabled' => !$canUpdate,
                    'onclick' => $canUpdate ? 'return confirm("คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?");' : 'return false;',
                    'title' => !$canUpdate ? 'คุณไม่มีสิทธิ์ใช้งานปุ่มนี้' : ''
                ]) ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
            
            <!-- Up-Dent Button -->
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['dashboardall/updatedent'],
                'method' => 'post',
                'options' => ['style' => 'display: inline-block;']
            ]); ?>
                <?= \yii\helpers\Html::submitButton('🔄 Up-Dent', [
                    'class' => 'btn btn-warning',
                    'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;' . (!$canUpdate ? ' opacity: 0.5; cursor: not-allowed;' : ''),
                    'disabled' => !$canUpdate,
                    'onclick' => $canUpdate ? 'return confirm("คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?");' : 'return false;',
                    'title' => !$canUpdate ? 'คุณไม่มีสิทธิ์ใช้งานปุ่มนี้' : ''
                ]) ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
            
            <button class="btn btn-info shadow"
                style="font-weight: bold; border-radius: 25px; padding: 10px 20px; font-size: 1.2rem;">
                <?= $amountx ?>
            </button>
        </div>

    </div>
    
    <span style="background: linear-gradient(135deg, #c844c8, #e75ce7); color: white; padding: 0.5rem 1.25rem; border-radius: 25px; font-size: 1rem; font-weight: 500; box-shadow: 0 3px 10px rgba(200, 68, 200, 0.3);">
        ปีงบประมาณ 2569
    </span>
</div>

    




    <div class="row">
        <div class="col-md-6">
            <div class="card-modern">
                <h4 class="text-center">📊 ผู้ป่วยนอก (OPD)</h4>
                <canvas id="opdChart" width="600" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-modern">
                <h4 class="text-center">🏥 ผู้ป่วยใน (IPD)</h4>
                <canvas id="ipdChart" width="600" height="250"></canvas>
            </div>
        </div>
    </div>



<?php
$this->registerJs("
const years = $yearsJson;
const opdVisits = $opdVisitsJson;
const opdPersons = $opdPersonsJson;
const ipdVisits = $ipdVisitsJson;
const ipdPersons = $ipdPersonsJson;

function createGradient(ctx, colorStart, colorEnd) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, colorStart);
    gradient.addColorStop(1, colorEnd);
    return gradient;
}

function createChartConfig(canvasId, title, labelVisit, labelPerson, dataVisit, dataPerson, colorStart1, colorEnd1, colorStart2, colorEnd2) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    const gradient1 = createGradient(ctx, colorStart1, colorEnd1);
    const gradient2 = createGradient(ctx, colorStart2, colorEnd2);

    return {
        type: 'bar',
        data: {
            labels: years,
            datasets: [
                {
                    label: labelVisit,
                    data: dataVisit,
                    backgroundColor: gradient1,
                    borderRadius: 10,
                    barThickness: 30
                },
                {
                    label: labelPerson,
                    data: dataPerson,
                    backgroundColor: gradient2,
                    borderRadius: 10,
                    barThickness: 30
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 14 } }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    font: { weight: 'bold', size: 11 },
                    color: '#000',
                    formatter: (value) => value.toLocaleString()
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    };
}

new Chart(document.getElementById('opdChart'), createChartConfig(
    'opdChart',
    'ผู้ป่วย OPD',
    'จำนวนครั้ง (Visit)',
    'จำนวนคน (Person)',
    opdVisits,
    opdPersons,
    '#42a5f5', '#fae3f6',
    '#26c6da', '#bef8fa'
));

new Chart(document.getElementById('ipdChart'), createChartConfig(
    'ipdChart',
    'ผู้ป่วย IPD',
    'จำนวนครั้ง (Visit)',
    'จำนวนคน (Person)',
    ipdVisits,
    ipdPersons,
    '#ef5350', '#fae3f6',
    '#ffa726', '#bef8fa'
));
");
?>

<!-- ################################################################################ -->
    <!-- แถวบน: 2 สดมภ์ -->
    <div class="row">
        <!-- OPD Summary -->
        <div class="col-md-6">
            <div class="card">
                <h3>📊 ผู้ป่วยนอก (OPD)</h3>
                <p style="color: #888;">
                    อัปเดตล่าสุด: <span style="color:#daa520; font-weight: bold;">
                        <?= Yii::$app->formatter->asDatetime($opdUpdatedAt, 'php:d/m/Y H:i') ?>
                    </span>
                </p>
                <?php
				echo GridView::widget([
    'dataProvider' => $opdData,
    'summary' => false, // 🔕 ตัดข้อความ Total x items
    'showPageSummary' => true,
    'bordered' => true,
    'striped' => true,
    'hover' => true,
    'responsive' => true,
	/*
    'panel' => [
        'type' => 'success',
        'heading' => '🏥 ผู้ป่วยนอก (OPD)',
        'footer' => false,
    ],
	*/
    'columns' => [
        ['attribute' => 'month_name', 'label' => 'เดือน'],
        ['attribute' => 'period_year', 'label' => 'ปี'],
		 ['attribute' => 'visit', 'label' => 'ครั้ง', 'format' => 'integer', 'pageSummary' => true],
        ['attribute' => 'person', 'label' => 'จำนวนคน', 'format' => 'integer', 'pageSummary' => true],
        ['attribute' => 'refers', 'label' => 'Refer Out', 'format' => 'integer', 'pageSummary' => true],
    ],
]);
				?>
            </div>
        </div>

        <!-- IPD Summary -->
        <div class="col-md-6">
            <div class="card">
                <h3>🏥 ผู้ป่วยใน (IPD)</h3>
               <p style="color: #888;">
                    อัปเดตล่าสุด: <span style="color:#daa520; font-weight: bold;">
                        <?= Yii::$app->formatter->asDatetime($ipdUpdatedAt, 'php:d/m/Y H:i') ?>
                    </span>
                </p>
<?php

echo GridView::widget([
    'dataProvider' => $ipdData,
    'responsive' => true,
    'hover' => true,
    'bordered' => true,
    'striped' => true,
	'summary' => false, // 👈 ตัดการแสดงผล summary
	/*
    'panel' => [
        'heading' => '🏥 ผู้ป่วยใน (IPD)',
        'type' => 'primary',
    ],
	*/
    'showPageSummary' => true,
    'columns' => [
        [
            'attribute' => 'month_name',
            'label' => 'เดือน',
        ],
        [
            'attribute' => 'period_year',
            'label' => 'ปี',
        ],
		[
            'attribute' => 'admit',
            'label' => 'Disharge',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        [
            'attribute' => 'person',
            'label' => 'จำนวนคน',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        [
            'attribute' => 'refers',
            'label' => 'Refer Out',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        
        [
            'attribute' => 'ward1',
            'label' => 'Ward1',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        [
            'attribute' => 'ward2',
            'label' => 'Ward2',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        
        [
            'attribute' => 'homeward',
            'label' => 'Ward3(HW)',
            'format' => 'integer',
            'pageSummary' => true,
        ],
		[
            'attribute' => 'ward4',
            'label' => 'Ward4',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        [
            'attribute' => 'ward5',
            'label' => 'Ward5',
            'format' => 'integer',
            'pageSummary' => true,
        ],
		[
            'attribute' => 'lr',
            'label' => 'LR',
            'format' => 'integer',
            'pageSummary' => true,
        ],
    ],
]);
?>
            </div>
        </div>
    </div>

   <!-- แถวเดียว มี 4 คอลัมน์ -->
<div class="row">
    <!-- Top 10 OPD -->
    <div class="col-md-3">
        <div class="card p-3">
            <h3>💥 10 อันดับโรค (OPD) ปีงบประมาณ 2569</h3>
            <table class="table table-sm table-bordered table-top10">
                <thead>
                    <tr>
                        <th>ICD10</th>
                        <th>ชื่อโรค</th>
                        <th class="text-end">จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top10OpdData as $row): ?>
                    <tr>
                        <td><?= Html::encode($row['icd10_tm']) ?></td>
                        <td style="text-align: left;">
                            <?= Html::encode(mb_strlen($row['nickname']) > 30 ? mb_substr($row['nickname'], 0, 30) . '...' : $row['nickname']) ?>
                        </td>
                        <td class="text-end"><?= number_format($row['total_visit']) ?></td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Top 10 IPD -->
    <div class="col-md-3">
        <div class="card p-3">
            <h3>💥 10 อันดับโรค (IPD) ปีงบประมาณ 2569</h3>
            <table class="table table-sm table-bordered table-top10">
                <thead>
                    <tr>
                        <th>ICD10</th>
                        <th>ชื่อโรค</th>
                        <th class="text-end">จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top10IpdData as $row): ?>
                    <tr>
                        <td><?= Html::encode($row['icd10_tm']) ?></td>
                        <td style="text-align: left;">
                            <?= Html::encode(mb_strlen($row['nickname']) > 30 ? mb_substr($row['nickname'], 0, 30) . '...' : $row['nickname']) ?>
                        </td>
                        <td class="text-end"><?= number_format($row['total_visit']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<!-- โหลด Chart.js และ Plugin ด้านบนเพียงครั้งเดียว -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

<?php
// เตรียมข้อมูล OPD Refer
$refOpdLabels = [];
$refOpdValues = [];
foreach ($top10RefOpdData as $row) {
    $refOpdLabels[] = $row['โรค'];
    $refOpdValues[] = (int)$row['จำนวนครั้ง'];
}

// เตรียมข้อมูล IPD Refer
$refIpdLabels = [];
$refIpdValues = [];
foreach ($top10RefIpdData as $row) {
    $refIpdLabels[] = $row['โรค'];
    $refIpdValues[] = (int)$row['จำนวนครั้ง'];
}
?>

<div class="row">
    <!-- กราฟ Refer OPD -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm rounded" style="min-height: 400px;">
            <h4 style="font-weight: bold; color: #007bff;">💥 10 อันดับโรค Refer (OPD) ปีงบประมาณ 2569</h4>
            <canvas id="refOpdChart" height="300"></canvas>
        </div>
    </div>

    <!-- กราฟ Refer IPD -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm rounded" style="min-height: 400px;">
            <h4 style="font-weight: bold; color: #d63384;">💥 10 อันดับโรค Refer (IPD) ปีงบประมาณ 2569</h4>
            <canvas id="refIpdChart" height="300"></canvas>
        </div>
    </div>
</div>
<!-- ######################################### OPD-DENTAL ####################################################################-->
<div class="col-md-6">
    <div class="card p-3 shadow-sm rounded">
        <h4 class="text-center text-primary mb-3">
            <i class="fas fa-chart-bar"></i> บริการทันตกรรม โรงพยาบาลม่วงสามสิบ(DENTAL)  ปีงบประมาณ 2569
        </h4>
		<h5 class="text-center text-primary mb-3">
            <i class="fas fa-chart-bar"></i> 03-ทันตกรรม (ทั่วไป) ,04-ทันตกรรม (นัด)และ 05-ทันตกรรม (เฉพาะทาง)
        </h5>

       <p style="color: #888;">
                    อัปเดตล่าสุด: <span style="color:#daa520; font-weight: bold;">
                        <?= Yii::$app->formatter->asDatetime($dentUpdatedAt, 'php:d/m/Y H:i') ?>
                    </span>
                </p>
<?php
   $years = array_column($opdDent5, 'fiscal_year');
$visits = array_column($opdDent5, 'total_visit');
$persons = array_column($opdDent5, 'total_person');
$refers = array_column($opdDent5, 'total_refers');
?>



<h3 class="mb-4">📊 กราฟสรุปงานทันตกรรมย้อนหลัง 5 ปี</h3>
<canvas id="dentChart" height="120"></canvas>

<!-- โหลด Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- โหลด Plugin สำหรับแสดงตัวเลข -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
const ctx = document.getElementById('dentChart').getContext('2d');

// ✅ Gradient สี
const gradientVisit = ctx.createLinearGradient(0, 0, 0, 400);
gradientVisit.addColorStop(0, '#f022f0');
gradientVisit.addColorStop(1, '#1e88e5');

const gradientPerson = ctx.createLinearGradient(0, 0, 0, 400);
gradientPerson.addColorStop(0, '#80f2e9');
gradientPerson.addColorStop(1, '#2e7d32');

const gradientRefer = ctx.createLinearGradient(0, 0, 0, 400);
gradientRefer.addColorStop(0, '#ef5350');
gradientRefer.addColorStop(1, '#c62828');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= Json::encode($years) ?>,
        datasets: [
            {
                label: 'จำนวน Visit',
                data: <?= Json::encode($visits) ?>,
                backgroundColor: gradientVisit,
                borderRadius: 10
            },
            {
                label: 'จำนวน Person',
                data: <?= Json::encode($persons) ?>,
                backgroundColor: gradientPerson,
                borderRadius: 10
            },
            {
                label: 'จำนวน Refer',
                data: <?= Json::encode($refers) ?>,
                backgroundColor: gradientRefer,
                borderRadius: 10
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'สรุปงานทันตกรรมย้อนหลัง 5 ปี (ตามปีงบประมาณ)' },
            datalabels: {
                anchor: 'end',
                align: 'end',
                color: '#000',      // สีตัวเลข
                font: {
                    weight: 'bold',
                    size: 12
                },
                formatter: function(value) {
                    return value.toLocaleString(); // แสดงเลขมี , ขั้น
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    },
    plugins: [ChartDataLabels]
});
</script>

<div style="background-color: #e6f2ff; border-radius: 10px; padding: 15px; margin-top: 15px;">
		<p>
        <strong>ร้อยละต่อเดือน</strong><br>
        
       สูตรคำนวณ:
        <code>(980 / 1,000) × 100 = 98.00</code>
    </p>
  </div>  
  </div> 
   </div> 
  
  
<div class="col-md-6">
   <div class="card p-3 shadow-sm rounded">
        <h4 class="text-center text-primary mb-3">
            <i class="fas fa-chart-pie"></i> การบริการทันตกรรม (ปีงบ 2569)
        </h4>
		ทันตกรรม (ทั่วไป) ,ทันตกรรม (นัด)และทันตกรรม (เฉพาะทาง)
        <p style="color: #888;">
            อัปเดตล่าสุด: <span style="color:#daa520; font-weight: bold;">
                <?= Yii::$app->formatter->asDatetime($dentUpdatedAt, 'php:d/m/Y H:i') ?>
            </span>
        </p>
<?= GridView::widget([
            'dataProvider' => $dentProvider,
            'showFooter' => true,
            'footerRowOptions' => ['style' => 'background-color: #fff9c4; font-weight: bold;'],
            'hover' => true,
            'bordered' => true,
            'striped' => true,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'month_year',
                    'label' => 'เดือน/ปี',
                    'footer' => 'รวมทั้งหมด',
                ],
                [
                    'attribute' => 'total_visit',
                    'label' => 'Visit',
                    'format' => ['decimal', 0],
                    'footer' => number_format(array_sum(array_column($dentProvider->allModels, 'total_visit'))),
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'total_person',
                    'label' => 'Person',
                    'format' => ['decimal', 0],
                    'footer' => number_format(array_sum(array_column($dentProvider->allModels, 'total_person'))),
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'total_refers',
                    'label' => 'Refer',
                    'format' => ['decimal', 0],
                    'footer' => number_format(array_sum(array_column($dentProvider->allModels, 'total_refers'))),
                    'contentOptions' => ['class' => 'text-center'],
                ],
            ],
        ]); ?>
		
   </div>
</div>
<div style="background-color: #e6f2ff; border-radius: 10px; padding: 15px; margin-top: 15px;">
    
    
<!-- ##############################################END OPD-DENTAL###############################################################-->	
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 👉 สร้าง gradient จาก context
    function getGradient(ctx, colorStart, colorEnd) {
        const gradient = ctx.createLinearGradient(0, 0, 400, 0); // แนวนอน
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    }

    // -------------------- OPD Chart --------------------
    const refOpdCtx = document.getElementById('refOpdChart').getContext('2d');
    const refOpdGradient = getGradient(refOpdCtx, '#86f3f7', '#42a5f5');  

    new Chart(refOpdCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($refOpdLabels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                label: 'จำนวนครั้ง',
                data: <?= json_encode($refOpdValues) ?>,
                backgroundColor: refOpdGradient,
                borderColor: '#1e88e5',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Refer OPD (ตามจำนวนครั้ง)',
                    font: { size: 16 }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    color: '#000',
                    formatter: (value) => value + ' ครั้ง',
                    font: { weight: 'bold', size: 12 }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: { display: true, text: 'จำนวนครั้ง' }
                },
                y: {
                    ticks: {
                        autoSkip: false,
                        font: { size: 12 }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // -------------------- IPD Chart --------------------
    const refIpdCtx = document.getElementById('refIpdChart').getContext('2d');
    const refIpdGradient = getGradient(refIpdCtx, '#faf2c5', '#ef5350');  

    new Chart(refIpdCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($refIpdLabels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                label: 'จำนวนครั้ง',
                data: <?= json_encode($refIpdValues) ?>,
                backgroundColor: refIpdGradient,
                borderColor: '#d32f2f',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Refer IPD (ตามจำนวนครั้ง)',
                    font: { size: 16 }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    color: '#000',
                    formatter: (value) => value + ' ครั้ง',
                    font: { weight: 'bold', size: 12 }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: { display: true, text: 'จำนวนครั้ง' }
                },
                y: {
                    ticks: {
                        autoSkip: false,
                        font: { size: 12 }
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>



<style>
.grid-container {
    max-height: 600px;
    overflow-y: auto;
}

/* ตรึงหัวตาราง */
.sticky-table thead th {
    position: sticky;
    top: 0;
    background: #f0f0f0;
    z-index: 10;
    box-shadow: 0 2px 2px -1px rgba(0,0,0,0.3);
}

/* ป้องกันตารางล้น */
.sticky-table {
    width: 100%;
    border-collapse: separate;
}
</style>


<style>
.date-picker-input {
    border: 2px solid #d7dbd7;         /* สีเส้นขอบ */
    border-radius: 8px;                /* มุมโค้งมน */
    padding: 6px 12px;                 /* ระยะห่างขอบใน */
    background-color: #f5fff5;         /* สีพื้นหลังอ่อน */
    color: #333;                       /* สีตัวอักษร */
    transition: all 0.3s ease-in-out;  /* แอนิเมชันลื่นไหล */
    box-shadow: none;                  /* ลบเงาเริ่มต้น */
}

.date-picker-input:focus {
    border-color: #2e7d32;             /* สีขอบเมื่อโฟกัส */
    box-shadow: 0 0 5px rgba(46, 125, 50, 0.5); /* แสงเมื่อโฟกัส */
    outline: none;                     /* ลบเส้นขอบฟ้าเริ่มต้น */
    background-color: #ffffff;
}
</style>
<!--############################################################################################-->

   

  

  <!-- 🔷 ตาราง GridView -->
  <div class="col-md-6">
     <div class="card p-3 shadow-sm rounded">
      <h4 class="text-center text-primary mb-3">
          <i class="fas fa-table"></i> ตารางสรุป TELEMED ปีงบ 2568-2569
      </h4>
      <p style="color: #888;">
          อัปเดตล่าสุด: <span style="color:#daa520; font-weight: bold;">
              <?= Yii::$app->formatter->asDatetime($teleUpdatedAt, 'php:d/m/Y H:i') ?>
          </span>
      </p>

      <?php
     
      $provider = new ArrayDataProvider([
          'allModels' => $rows,
          'pagination' => false,
      ]);

      echo GridView::widget([
        'dataProvider' => $provider,
        'showFooter' => true,
        'footerRowOptions' => ['style' => 'background-color: #fff9c4; font-weight: bold;'],
        'hover' => true,
        'bordered' => true,
        'striped' => true,
        'summary' => '',
        'columns' => [
            ['attribute' => 'fiscal_year', 'label' => 'ปีงบประมาณ'],
            ['attribute' => 'month_no', 'label' => 'เดือน', 'footer' => 'รวมทั้งหมด'],
            [
                'attribute' => 'total_visit',
                'label' => 'ทั้งหมด',
                'format' => ['decimal',0],
                'footer' => number_format(array_sum(array_column($rows,'total_visit'))),
                'contentOptions' => ['class'=>'text-center'],
            ],
            [
                'attribute' => 'total_sent',
                'label' => 'จำนวนส่ง',
                'format' => ['decimal',0],
                'footer' => number_format(array_sum(array_column($rows,'total_sent'))),
                'contentOptions' => ['class'=>'text-center'],
            ],
            [
                'attribute' => 'total_claim',
                'label' => 'ค่าเรียกเก็บ (฿)',
                'format' => ['decimal',2],
                'footer' => number_format(array_sum(array_column($rows,'total_claim')),2),
                'contentOptions' => ['class'=>'text-right text-success'],
            ],
            [
                'attribute' => 'total_paid',
                'label' => 'ยอดชดเชย (฿)',
                'format' => ['decimal',2],
                'footer' => number_format(array_sum(array_column($rows,'total_paid')),2),
                'contentOptions' => ['class'=>'text-right text-danger'],
            ],
        ],
      ]);
      ?>
      </div>
  </div>

</div>

<!-- ##################################################################################################################-->
<div class="col-md-6">
    <div class="card p-3 shadow-sm rounded">
        <h4 class="text-center text-primary mb-3">
            <i class="fas fa-chart-bar"></i> ผู้เสียชีวิตแยกตามแผนก ในโรงพยาบาล
        </h4>

        <!-- 🔷 กราฟแท่ง -->
        <canvas id="deathBarChart" height="150"></canvas>

        <!-- 🔷 ตารางประกอบ -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Department</th>
                        <?php foreach ($yearsx as $year): ?>
                            <th>ปี <?= Html::encode($year) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $dept): ?>
                        <tr>
                            <td><?= Html::encode($dept) ?></td>
                            <?php foreach ($yearsx as $year): ?>
                                <td><?= $chartData[$dept][$year] ?? 0 ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- ✅ Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
function createGradient(ctx, color1, color2) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
}

document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('deathBarChart').getContext('2d');

    // กำหนดคู่สี gradient สำหรับแต่ละแผนก (คละสี)
    const gradientColors = [
        ['#ff7e5f', '#fae3d2'],  // สีส้ม → เหลือง
        ['#6a11cb', '#d3e3fe'],  // สีม่วง → น้ำเงิน
        ['#43cea2', '#e0f0ff'],  // สีเขียว → น้ำเงินเข้ม
        ['#f7971e', '#f7f4e6'],  // สีส้มทอง
        ['#00c6ff', '#e1ecfa'],  // สีฟ้า
        ['#f953c6', '#fce1f0'],  // สีชมพูม่วง
        ['#43e97b', '#38f9d7']   // สีเขียวฟ้า
    ];

    const yearsx = <?= json_encode($yearsx) ?>;
    const chartData = <?= json_encode($chartData) ?>;
    const departments = <?= json_encode($departments) ?>;

    const datasets = departments.map((dept, i) => {
        // เลือกสี gradient คู่ที่ i หรือวนกลับถ้าเกิน
        const colors = gradientColors[i % gradientColors.length];
        const gradient = createGradient(ctx, colors[0], colors[1]);

        const data = yearsx.map(year => chartData[dept][year] ?? 0);

        return {
            label: dept,
            data: data,
            backgroundColor: gradient,
            borderRadius: 6,
            barThickness: 25,
        };
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: yearsx,
            datasets: datasets,
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: {
                    display: true,
                    text: 'จำนวนผู้เสียชีวิตแยกตามแผนกย้อนหลัง 3 ปี'
                },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.formattedValue} ราย`
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#444',
                    font: { weight: 'bold', size: 12 },
                    formatter: Math.round,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'จำนวน (ราย)' }
                },
                x: {
                    title: { display: true, text: 'ปี' }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
<!--#######################################################################################-->


<div class="col-md-6">
    <div class="card p-3 shadow-sm rounded">
        <h4 class="text-center text-primary mb-3">
            <i class="fas fa-chart-pie"></i> ข้อมูลการส่ง PHR (ปีงบ 2568)
        </h4>
				<p style="color: #888;">
                    อัปเดตล่าสุด: <span style="color:#daa520; font-weight: bold;">
                        <?= Yii::$app->formatter->asDatetime($phrUpdatedAt, 'php:d/m/Y H:i') ?>
                    </span>
                </p>
       <?php
// ดึง models ตรงจาก dataProvider (ปลอดภัยกว่าใช้ $rows ที่อาจไม่ถูกตั้งค่า)
$models = $phrProvider->getModels(); // Array of arrays or objects

// ฟังก์ชันช่วยดึงค่าอย่างปลอดภัย (รองรับ array / object / ActiveRecord)
$get = function($model, $key) {
    if (is_array($model)) {
        return $model[$key] ?? 0;
    }
    if (is_object($model)) {
        // ActiveRecord มี toArray()
        if (method_exists($model, 'toArray')) {
            $arr = $model->toArray();
            return $arr[$key] ?? 0;
        }
        // fallback cast
        $arr = (array)$model;
        return $arr[$key] ?? 0;
    }
    return 0;
};

// คำนวณค่ารวม / ค่าเฉลี่ย จาก $models
$totalVisitsSum = 0;
$phrSentSum = 0;
$percentMonthSum = 0.0;
$percentOfYearSum = 0.0;
$rowsCount = count($models);

foreach ($models as $m) {
    $tv = (int) $get($m, 'total_visits');
    $ps = (int) $get($m, 'phr_sent');
    $pmonth = (float) $get($m, 'percent_phr_sent');
    $pyear = (float) $get($m, 'percent_of_total_phr');

    $totalVisitsSum += $tv;
    $phrSentSum += $ps;
    $percentMonthSum += $pmonth;
    $percentOfYearSum += $pyear;
}

// ป้องกันหารด้วยศูนย์
$percentMonthAvg = $rowsCount ? ($percentMonthSum / $rowsCount) : 0;

// เตรียมข้อความ footer (format ให้สวย)
$footer_total_visits = number_format($totalVisitsSum);
$footer_phr_sent = number_format($phrSentSum);
$footer_percent_month = number_format($percentMonthAvg, 2);
$footer_percent_of_year = number_format($percentOfYearSum, 2);

// ตอนนี้แสดง GridView โดยเอาค่า footer จากตัวแปรข้างบน
echo GridView::widget([
    'dataProvider' => $phrProvider,
    'showFooter' => true,
    'footerRowOptions' => ['style' => 'background-color: #fff9c4; font-weight: bold;'],
    'hover' => true,
    'bordered' => true,
    'striped' => true,
    'summary' => '',
    'columns' => [
        [
            'attribute' => 'month',
            'label' => 'เดือน',
            'footer' => 'รวมทั้งหมด',
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'total_visits',
            'label' => 'ทั้งหมด',
			 'format' => ['decimal', 0],
            'footer' => $footer_total_visits,
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'phr_sent',
            'label' => 'จำนวนส่ง',
		    'format' => ['decimal', 0],
            'footer' => $footer_phr_sent,
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'percent_phr_sent',
            'label' => 'ร้อยละเดือน',
            //'format' => ['decimal', 2],  
            'footer' => $footer_percent_month,
            'contentOptions' => function ($model) use ($get) {
                $val = (float) $get($model, 'percent_phr_sent');
                $color = $val >= 97.0 ? 'green' : 'red';
                return ['class' => 'text-center', 'style' => "color: {$color};"];
            },
        ],
        [
            'attribute' => 'percent_of_total_phr',
            'label' => 'ร้อยละปี',
            //'format' => ['decimal', 2],
            'footer' => $footer_percent_of_year,
            'contentOptions' => ['class' => 'text-center'],
        ],
    ],
]);
?>
<div style="background-color: #e6f2ff; border-radius: 10px; padding: 15px; margin-top: 15px;">
    <p>
        <strong>ร้อยละต่อเดือน</strong><br>
        ถ้าเดือนมกราคม<br>
        <code>total_visits = 1,000</code><br>
        <code>phr_sent = 980</code>
       สูตรคำนวณ:
        <code>(980 / 1,000) × 100 = 98.00</code>
    </p>
    <p>
        <strong>ร้อยละต่อปี</strong><br>
        ถ้าปีงบประมาณ 2568 ส่ง PHR ทั้งปีรวม = 120,000<br>
        เดือนมกราคม ส่งได้ = 10,000<br>
        สูตรคำนวณ:
        <code>(10,000 / 120,000) × 100 = 8.33</code>
    </p>
</div>
</div>
</div>
<div class="col-md-6">
            <div class="card">
              <h4 class="text-primary mb-3">📊 สรุป Readmit / Revisit / Unplanned Refer รายปี</h4>

    <?php
    $summaryProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $revisitReadmit,
        'pagination' => false,
        'sort' => [
            'attributes' => ['fiscal_year', 'readmit', 'revisit', 'unplan'],
        ],
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $summaryProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
        'columns' => [
            [
                'attribute' => 'fiscal_year',
                'label' => 'ปีงบประมาณ',
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'readmit',
                'label' => '✅ Readmit',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-end text-success'],
            ],
            [
                'attribute' => 'revisit',
                'label' => '🔁 Revisit',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-end text-warning'],
            ],
            [
                'attribute' => 'unplan',
                'label' => '🚑 Unplanned Refer',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-end text-danger'],
            ],
        ],
    ]) ?>
</div>
</div>


<div class="col-md-6">
            <div class="card">
              <h4 class="text-primary mb-3">📊 สรุป Readmit / Revisit / Unplanned Refer รายปี</h4>

    <?php
    $summaryProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $revisitReadmit,
        'pagination' => false,
        'sort' => [
            'attributes' => ['fiscal_year', 'readmit', 'revisit', 'unplan'],
        ],
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $summaryProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
        'columns' => [
            [
                'attribute' => 'fiscal_year',
                'label' => 'ปีงบประมาณ',
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'readmit',
                'label' => '✅ Readmit',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-end text-success'],
            ],
            [
                'attribute' => 'revisit',
                'label' => '🔁 Revisit',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-end text-warning'],
            ],
            [
                'attribute' => 'unplan',
                'label' => '🚑 Unplanned Refer',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-end text-danger'],
            ],
        ],
    ]) ?>
</div>
</div>

    </div>
</div>
