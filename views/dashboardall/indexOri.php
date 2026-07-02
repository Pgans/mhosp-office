<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\View;
use yii\data\ArrayDataProvider;
use miloschuman\highcharts\Highcharts;
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js'); // ✅
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2'); // ✅

$this->registerCss('
.dashboard-wrapper {
    width: 90%;
    margin: 0 auto;
}
.table-top10 th {
    background-color: #e3f2fd;
    font-weight: bold;
    text-align: center;
}
.table-top10 td {
    text-align: center;
}
.card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 30px;
    height: 100%;
}
.card h3 {
    font-size: 1.5rem;
    color: #0069d9;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 15px;
}
th, td {
    border-bottom: 1px solid #eee;
    padding: 8px;
    text-align: center;
}
th {
    background-color: #e9f5ff;
    font-weight: bold;
    color: #007bff;
}
table tr:hover {
    background-color: #e0f0ff !important; /* สีฟ้า/น้ำเงินอ่อน */
    cursor: pointer;
}
.card-modern {
    background: linear-gradient(to bottom right, #f0f8ff, #e3f2fd);
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    padding: 20px;
    margin-bottom: 20px;
}
h3 {
  line-height: 1.2;
}
');
?>

<style>
.sidebar,
.main-sidebar,
.sidebar-menu {
    display: none !important;
}
.content-wrapper, .content {
    margin-left: 0 !important;
}
</style>

<!-- ✅ ครอบทั้งหมดด้วย .dashboard-wrapper -->
<div class="dashboard-wrapper">
<!-- แถวบน: 2 สดมภ์ -->

<?php

// ✅ เตรียมข้อมูล
$years = [];
$opdVisits = [];
$opdPersons = [];
$ipdVisits = [];
$ipdPersons = [];

foreach ($opdData5 as $row) {
    if (is_array($row) && isset($row['fiscal_year'])) {
        $years[] = $row['fiscal_year'];
        $opdVisits[] = isset($row['total_visit']) ? (int)$row['total_visit'] : 0;
        $opdPersons[] = isset($row['total_person']) ? (int)$row['total_person'] : 0;
    }
}
foreach ($ipdData5 as $row) {
    if (is_array($row)) {
        $ipdVisits[] = isset($row['total_visit']) ? (int)$row['total_visit'] : 0;
        $ipdPersons[] = isset($row['total_person']) ? (int)$row['total_person'] : 0;
    }
}

// ✅ Encode JSON ปลอดภัย
$yearsJson = json_encode($years, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
$opdVisitsJson = json_encode($opdVisits);
$opdPersonsJson = json_encode($opdPersons);
$ipdVisitsJson = json_encode($ipdVisits);
$ipdPersonsJson = json_encode($ipdPersons);
?>


<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; width: 100%;">
    <h3 style="margin: 0; padding: 0;">📊 กราฟผู้ป่วย OPD / IPD</h3>

    <div class="d-flex justify-content-end align-items-center mb-3" style="gap: 10px;">
        <?= Html::a('🔄 Update', ['dashboardall/update'], [
            'class' => 'btn btn-warning',
            'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;',
            'data' => [
                'confirm' => 'คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('🔄 UpTop10', ['dashboardall/updatex'], [
            'class' => 'btn btn-warning',
            'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;',
            'data' => [
                'confirm' => 'คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?',
                'method' => 'post',
            ],
        ]) ?>
		 <?= Html::a('🔄 Up-phr', ['dashboardall/updatephr'], [
            'class' => 'btn btn-warning',
            'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;',
            'data' => [
                'confirm' => 'คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?',
                'method' => 'post',
            ],
        ]) ?>

        <button class="btn btn-info shadow"
        style="font-weight: bold; border-radius: 25px;
               padding: 10px 20px; font-size: 1.2rem;">
        <?= $amountx ?>
    </button>
    </div>
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
            'attribute' => 'admit',
            'label' => 'Admit',
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
            'attribute' => 'lr',
            'label' => 'LR',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        [
            'attribute' => 'homeward',
            'label' => 'HomeWard',
            'format' => 'integer',
            'pageSummary' => true,
        ],
        [
            'attribute' => 'ward5',
            'label' => 'Ward5',
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
            <h3>💥 10 อันดับโรค (OPD) ปีงบประมาณ 2568</h3>
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
            <h3>💥 10 อันดับโรค (IPD) ปีงบประมาณ 2568</h3>
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
            <h4 style="font-weight: bold; color: #007bff;">💥 10 อันดับโรค Refer (OPD) ปีงบประมาณ 2568</h4>
            <canvas id="refOpdChart" height="300"></canvas>
        </div>
    </div>

    <!-- กราฟ Refer IPD -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm rounded" style="min-height: 400px;">
            <h4 style="font-weight: bold; color: #d63384;">💥 10 อันดับโรค Refer (IPD) ปีงบประมาณ 2568</h4>
            <canvas id="refIpdChart" height="300"></canvas>
        </div>
    </div>
</div>

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
<div class="col-md-6">
    <div class="card p-3 shadow-sm rounded">
        <h4 class="text-center text-primary mb-3">
            <i class="fas fa-chart-bar"></i> สถิติรวมของโรค X60–X84 แยกตามปี
        </h4>
        <canvas id="x60Chart" style="height: 100px;"></canvas> <!-- ✅ ใช้ CSS แทน -->
    </div>
</div>

<?php
$this->registerJs("
const ctx = document.getElementById('x60Chart').getContext('2d');

// Gradient fill สำหรับ Visits
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, '#42a5f5');
gradient.addColorStop(1, '#bbdefb');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: " . json_encode($years) . ",
        datasets: [
            {
                label: 'จำนวนครั้ง (Visits)',
                data: " . json_encode($totalVisits) . ",
                backgroundColor: gradient,
                borderRadius: 10,
                barThickness: 30
            },
            {
                label: 'จำนวนคน (Persons)',
                data: " . json_encode($totalPersons) . ",
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                borderRadius: 10,
                barThickness: 30
            }
        ]
    },
    options: {
        responsive: true,
        // ❌ เอา maintainAspectRatio ออก เพื่อให้ chart เคารพขนาดจาก style
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: { font: { size: 13 } }
            },
            title: {
                display: true,
                text: 'กราฟจำนวนผู้ป่วย X60-X84 แยกตามปีงบ (คน / ครั้ง)',
                font: { size: 16 }
            },
            datalabels: {
                anchor: 'end',
                align: 'top',
                font: { weight: 'bold', size: 12 },
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
});
");
?>
 
<div class="col-md-6">
   <div class="card p-3 shadow-sm rounded">
    <h4 class="text-center text-primary mb-3">
        <i class="fas fa-chart-pie"></i> สัดส่วนจำนวนผู้ป่วย X60–X84 รายเดือน (ปีงบ 2568)
    </h4>
    <div style="max-width: 900px; margin: 0 auto;"> <!-- ✅ ลดขนาด -->
        <canvas id="x60PieChart" style="height: 500px;"></canvas> <!-- ✅ กำหนดความสูง -->
    </div>
	


<?php
$colors = [
    '#f44336', '#e91e63', '#9c27b0', '#3f51b5',
    '#2196f3', '#03a9f4', '#00bcd4', '#009688',
    '#4caf50', '#8bc34a', '#ffc107', '#ff5722'
];

// คำนวณ total รวม
$totalAll = array_sum($values);

$this->registerJs("
function getBrightness(hexColor) {
    // แปลง hex color เป็น RGB
    const c = hexColor.substring(1); // ตัด #
    const rgb = parseInt(c, 16);
    const r = (rgb >> 16) & 0xff;
    const g = (rgb >> 8) & 0xff;
    const b = rgb & 0xff;
    // สูตรคำนวณความสว่าง (brightness)
    return (r * 299 + g * 587 + b * 114) / 1000;
}

new Chart(document.getElementById('x60PieChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: " . json_encode($labels) . ",
        datasets: [{
            data: " . json_encode($values) . ",
            backgroundColor: " . json_encode($colors) . ",
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 14 },
                    boxWidth: 14
                }
            },
            datalabels: {
                font: {
                    weight: 'bold',
                    size: 11
                },
                color: function(context) {
                    // ดึงสีของ slice นั้นๆ
                    const bgColor = context.chart.data.datasets[0].backgroundColor[context.dataIndex];
                    // ถ้า brightness ต่ำกว่า 128 ถือว่าเข้ม ให้ใช้สีขาว
                    return getBrightness(bgColor) < 128 ? '#fff' : '#000';
                },
                formatter: function(value, context) {
                    const label = context.chart.data.labels[context.dataIndex];
                    return label + '\\n' + value.toLocaleString() + ' ราย';
                }
            },
            title: {
                display: true,
                text: 'จำนวนรวมทั้งหมด: " . number_format($totalAll) . " ราย',
                font: { size: 16, weight: 'bold' },
                padding: { top: 10, bottom: 20 }
            }
        }
    },
    plugins: [ChartDataLabels]
});
");
?>
    </div>
</div>


<div class="col-md-6">
    <div class="card p-3 shadow-sm rounded">
        <h4 class="text-center text-primary mb-3">
            <i class="fas fa-chart-bar"></i> การบริการสาธารณสุขทางไกล (TELEMED)
        </h4>
       

    <!-- 🔷 กราฟแท่ง -->
   <?= Highcharts::widget([
    'scripts' => ['highcharts-more'],
    'options' => [
        'chart' => [
            'type' => 'column',
            'height' => 540, // 👈 เพิ่มความสูงตรงนี้ (เช่น 500px)
            'style' => ['fontFamily' => 'Kanit']
        ],
        'title' => [
            'text' => 'ยอดการส่งข้อมูล Telemed รายเดือน',
            'style' => ['fontSize' => '20px']
        ],
        'xAxis' => [
            'categories' => array_column($rows, 'month_name'),
            'title' => ['text' => 'เดือน']
        ],
        'yAxis' => [
            'min' => 0,
            'title' => ['text' => 'จำนวนรายการ'],
        ],
        'legend' => ['enabled' => true],
        'plotOptions' => [
            'column' => [
                'dataLabels' => [
                    'enabled' => true,
                    'style' => ['fontSize' => '11px']
                ]
            ]
        ],
        'series' => [
            [
                'name' => 'จำนวนทั้งหมด',
                'data' => array_map('intval', array_column($rows, 'total_sent')),
                'color' => [
                    'linearGradient' => ['x1' => 0, 'y1' => 0, 'x2' => 0, 'y2' => 1],
                    'stops' => [
                        [0, '#8cedfa'],
                        [1, '#0097a7']
                    ]
                ],
            ],
             [
                'name' => 'ผ่านการตรวจสอบ',
                'data' => array_map('intval', array_column($rows, 'passed')),
                'color' => [
                    'linearGradient' => [ 'x1' => 0, 'y1' => 0, 'x2' => 0, 'y2' => 1 ],
                    'stops' => [
                        [0, '#4caf50'],
                        [1, '#2e7d32']
                    ]
                ],
                'dataLabels' => [
                    'enabled' => true, // ✅ แสดงเฉพาะตัวเลขใน series นี้
                    'style' => ['fontSize' => '13px', 'fontWeight' => 'bold', 'color' => 'black']
                ],
            ],
        ],
        'credits' => ['enabled' => false],
    ]
]) ?>

  </div>  
  </div> 
<div class="col-md-6">
   <div class="card p-3 shadow-sm rounded">
    <h4 class="text-center text-primary mb-3">
        <i class="fas fa-chart-pie"></i> การบริการสาธารณสุขทางไกล (TELEMED) (ปีงบ 2568)
    </h4>
    
	<?php
	$provider = new ArrayDataProvider([
    'allModels' => $rows,
    'pagination' => false,
]);

echo GridView::widget([
    'dataProvider' => $provider,
    'showFooter' => true,
	 'footerRowOptions' => ['style' => 'background-color: #fff9c4; font-weight: bold;'], // ✅ พื้นหลังสีเหลืองอ่อน
    'hover' => true,
    'bordered' => true,
    'striped' => true,
    'summary' => '',
    'columns' => [
        [
            'attribute' => 'month_name',
            'label' => 'เดือน',
            'footer' => 'รวมทั้งหมด',
        ],
        [
            'attribute' => 'total_sent',
            'label' => 'ทั้งหมด',
            'format' => ['decimal', 0],
            'footer' => number_format(array_sum(array_column($rows, 'total_sent'))),
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'passed',
            'label' => 'จำนวนส่ง',
            'format' => ['decimal', 0],
            'footer' => number_format(array_sum(array_column($rows, 'passed'))),
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'claim',
            'label' => 'ค่าเรียกเก็บ (฿)',
            'format' => ['decimal', 2],
            'footer' => number_format(array_sum(array_column($rows, 'claim')), 2),
            'contentOptions' => ['class' => 'text-right text-success'],
        ],
        [
            'attribute' => 'compensate',
            'label' => 'ยอดชดเชย (฿)',
            'format' => ['decimal', 2],
            'footer' => number_format(array_sum(array_column($rows, 'compensate')), 2),
            'contentOptions' => ['class' => 'text-right text-danger'],
        ],
    ],
]);
	?>





    </div>
</div>
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
                <h3>🏥 Readmit 28 วัน</h3>
            <?php $form = ActiveForm::begin([
                'method' => 'POST',
                'action' => ['dashboardall/index'],
            ]); ?>

            <div class="form-group d-flex align-items-center flex-wrap">
                <label class="mr-2 mb-2 mb-md-0">📅 วันที่ระหว่าง:</label>
                <?= DatePicker::widget([
                    'name' => 'readmit_date1',
                    'value' => $date1 ?? $dateNow,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'date-picker-input']
                ]) ?>

                <label class="ml-3 mr-2 mb-2 mb-md-0">ถึง:</label>
                <?= DatePicker::widget([
                    'name' => 'readmit_date2',
                    'value' => $date2 ?? $dateNow,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'date-picker-input']
                ]) ?>

                <?= Html::submitButton('🔍 ค้นหา', ['class' => 'btn btn-danger btn-modern ml-3 mb-2 mb-md-0']) ?>
               
            </div>

            <?php ActiveForm::end(); ?>
        


       <!-- Scrollable GridView with sticky header -->
<div class="grid-container">
   <?= GridView::widget([
    'dataProvider' => $reamitProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'headerRowOptions' => ['class' => 'table-header-custom sticky-header'],
         'tableOptions' => ['class' => 'table table-bordered table-striped table-hover sticky-table'],

    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'adm1',
        'time1',
        'hn',
        'an1',
        'icd1',
        'adm2',
		'an2',
        'time2',
        'icd2',
        'revist_time',
        // ❌ ไม่ใส่ 'visit_id' ทั้ง vn1 และ vn2
    ],
]); ?>

</div>

    </div>
   
 </div>
<div class="col-md-6">
            <div class="card">
                <h3>🏥 ReVisit (น้อยกว่า48วัน)</h3>
            <?php $form = ActiveForm::begin([
                'method' => 'POST',
                'action' => ['dashboardall/index'],
            ]); ?>

            <div class="form-group d-flex align-items-center flex-wrap">
                <label class="mr-2 mb-2 mb-md-0">📅 วันที่ระหว่าง:</label>
                <?= DatePicker::widget([
                    'name' => 'revisit_date1',
                    'value' => $date1 ?? $dateNow,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'date-picker-input']
                ]) ?>

                <label class="ml-3 mr-2 mb-2 mb-md-0">ถึง:</label>
                <?= DatePicker::widget([
                    'name' => 'revisit_date2',
                    'value' => $date2 ?? $dateNow,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'date-picker-input']
                ]) ?>

                <?= Html::submitButton('🔍 ค้นหา', ['class' => 'btn btn-danger btn-modern ml-3 mb-2 mb-md-0']) ?>
               
            </div>

            <?php ActiveForm::end(); ?>
        
<!-- Scrollable GridView with sticky header -->
<div class="grid-container">
    <?= GridView::widget([
    'dataProvider' => $revisitProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'headerRowOptions' => ['class' => 'table-header-custom sticky-header'],
     'tableOptions' => ['class' => 'table table-bordered table-striped table-hover sticky-table'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        // ไม่ใส่ 'visit_id'
        'd1',
        'time_1',
        'icdname_1',
        'ptname',
        'd2',
        'time_2',
        'icdname_2',
        'revist_time',
    ],
]); ?>

</div>

    </div>
</div>
<div class="col-md-6">
            <div class="card">
                <h3>Unplan Refer</h3>
            <?php $form = ActiveForm::begin([
                'method' => 'POST',
                'action' => ['dashboardall/index'],
            ]); ?>

            <div class="form-group d-flex align-items-center flex-wrap">
                <label class="mr-2 mb-2 mb-md-0">📅 วันที่ระหว่าง:</label>
                <?= DatePicker::widget([
                    'name' => 'unplan_date1',
                    'value' => $date1 ?? $dateNow,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'date-picker-input']
                ]) ?>

                <label class="ml-3 mr-2 mb-2 mb-md-0">ถึง:</label>
                <?= DatePicker::widget([
                    'name' => 'unplan_date2',
                    'value' => $date2 ?? $dateNow,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'date-picker-input']
                ]) ?>

                <?= Html::submitButton('🔍 ค้นหา', ['class' => 'btn btn-danger btn-modern ml-3 mb-2 mb-md-0']) ?>
               
            </div>

            <?php ActiveForm::end(); ?>
        
<!-- Scrollable GridView with sticky header -->
<div class="grid-container">
    <?= GridView::widget([
    'dataProvider' => $unplanProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'headerRowOptions' => ['class' => 'table-header-custom sticky-header'],
     'tableOptions' => ['class' => 'table table-bordered table-striped table-hover sticky-table'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        // ไม่ใส่ 'visit_id'
        'd1',
        'time_1',
        'icdname_1',
        'ptname',
        'd2',
        'time_2',
        'icdname_2',
        'revist_time',
    ],
]); ?>

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

    </div>
</div>
