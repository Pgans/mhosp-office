<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

$this->title = '📊 รายงานโรคกลุ่ม 506 ปีงบประมาณ 2568';

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2');

$this->registerCss("

.dashboard-wrapper {
    width: 85%;
    margin: 0 auto;
}
.card-modern {
    background: linear-gradient(135deg, #e6f3f5 0%, #faf7fa 100%);
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(140,197,252,0.5);
    padding: 25px;
    margin-bottom: 30px;
}
.progress {
    height: 22px;
    background: #d0e4fd;
    border-radius: 12px;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.1);
}
.progress-bar {
    background: linear-gradient(90deg, #6a11cb, #87f8fa);
    font-weight: 600;
    color: #fff;
    line-height: 22px;
    border-radius: 12px;
    transition: width 0.6s ease;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f0f7ff;
}
table th, table td {
    vertical-align: middle !important;
}
.table-monthly {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    border-radius: 12px;
    overflow: hidden;
}
.table-monthly thead th {
    background: linear-gradient(45deg, #40daf5, #357ABD);
    color: white;
    font-weight: 600;
    padding: 10px 15px;
    border-bottom: 2px solid #2c6bb2;
    text-align: center;
}
.table-monthly tbody tr:nth-child(even) {
    background-color: #f5faff;
}
.table-monthly tbody tr:nth-child(odd) {
    background-color: #ffffff;
}
.table-monthly tbody tr:hover {
    background-color: #d1e7ff !important;
    cursor: pointer;
}
.table-monthly td, .table-monthly th {
    border-right: 1px solid #e0e6ef;
    border-bottom: 1px solid #e0e6ef;
    padding: 8px 12px;
    text-align: center;
    transition: background-color 0.3s ease;
}
.table-monthly td:last-child, .table-monthly th:last-child {
    border-right: none;
}
#monthChart {
    background: linear-gradient(135deg, #e6f7ff, #e0f0ff);
    border-radius: 12px;
    padding: 10px;
}

");

$labelsDay = [];
$valuesDay = [];
foreach ($dayData as $row) {
    $labelsDay[] = $row['โรค'];
    $valuesDay[] = $row['จำนวนเคส'];
}

$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];

$datasetsMonth = array_map(function($row) {
    return [
        'label' => $row['โรค'],
        'data' => [
            (int)$row['2025-01'], (int)$row['2025-02'], (int)$row['2025-03'],
            (int)$row['2025-04'], (int)$row['2025-05'], (int)$row['2025-06'], (int)$row['2025-07'],
        ],
        'fill' => false,
        'borderColor' => '#' . substr(md5($row['โรค']), 0, 6),
        'tension' => 0.3,
    ];
}, array_slice($monthData, 0, 20));
$targetDiseases = [
    'Dengue fever',
    'Mushroom poisoning',
    'Leptospirosis',
    'Hand foot and mouth disease',
    'Anthrax',
    'Coronavirus disease 2019'
];

$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];

$datasetsMonth = [];

foreach ($monthData as $row) {
    if (in_array($row['name_eng'], $targetDiseases)) {
        $datasetsMonth[] = [
            'label' => $row['name_eng'],
            'data' => [
                (int)$row['2025-01'], (int)$row['2025-02'], (int)$row['2025-03'],
                (int)$row['2025-04'], (int)$row['2025-05'], (int)$row['2025-06'], (int)$row['2025-07'],
            ],
            'fill' => false,
            'borderColor' => '#' . substr(md5($row['name_eng']), 0, 6),
            'tension' => 0.3,
            'pointBackgroundColor' => '#' . substr(md5($row['name_eng'] . 'point'), 0, 6),
        ];
    }
}

$this->registerJs("
const ctxDay = document.getElementById('dayChart').getContext('2d');
new Chart(ctxDay, {
    type: 'bar',
    data: {
        labels: " . Json::encode($labelsDay) . ",
        datasets: [{
            label: 'จำนวนเคส',
            data: " . Json::encode($valuesDay) . ",
            backgroundColor: function(context) {
                const ctx = context.chart.ctx;
                const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
                gradient.addColorStop(0, '#2575fc');
                gradient.addColorStop(1, '#34ddf7');  
                return gradient;
            },
            borderRadius: 12,
            borderSkipped: false,
            maxBarThickness: 40
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            datalabels: {
                anchor: 'end',
                align: 'right',
                color: '#333',
                font: { weight: 'bold', size: 14 },
                formatter: function(value) { return value; }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { precision: 0, color: '#555', font: { size: 12 } },
                grid: { color: '#eee' }
            },
            y: {
                ticks: { color: '#555', font: { size: 14 } },
                grid: { drawOnChartArea: false }
            }
        }
    },
    plugins: [ChartDataLabels]
});

const ctxMonth = document.getElementById('monthChart').getContext('2d');
new Chart(ctxMonth, {
    type: 'line',
    data: {
        labels: " . Json::encode($monthLabels) . ",
        datasets: " . Json::encode($datasetsMonth) . "
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { mode: 'index', intersect: false }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },
        scales: {
            x: {
                display: true,
                title: { display: true, text: 'เดือน' },
                grid: { color: '#eee' }
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'จำนวนเคส' },
                grid: { color: '#eee' }
            }
        }
    }
});
");
?>
<style>
canvas {
    font-family: 'Arial', sans-serif !important;
}

.chartjs-render-monitor {
    font-smooth: always;
    -webkit-font-smoothing: antialiased;
}
</style>

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
<div class="dashboard-wrapper">
    
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; width: 100%;">
    <h2 class="mb-4 text-primary"><i class="fa fa-bar-chart"></i> รายงานโรคกลุ่มโรคระบาดวิทยา 506 ปีงบประมาณ 2568</h2>
   <div class="d-flex justify-content-end align-items-center mb-3" style="gap: 10px;">
    <?= Html::a('🔄 Update', ['d506/update'], [
        'class' => 'btn btn-warning',
        'style' => 'font-weight: bold; border-radius: 8px; padding: 6px 15px;',
        'data' => [
            'confirm' => 'คุณต้องการอัปเดตข้อมูลตอนนี้หรือไม่?',
            'method' => 'post',
        ],
    ]) ?>

    <button class="btn btn-info" style="font-weight: bold; border-radius: 20px; padding: 6px 15px; font-size: 1.1rem;">
        <?= $amount ?>
    </button>
</div>
</div>

  <style>
.input-glow {
    border: 2px solid #00e676;
    box-shadow: 0 0 10px #00e676;
    border-radius: 8px;
    padding: 6px 12px;
}
</style>

<div class="row mb-3">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'method' => 'POST',
            'action' => ['d506/index'],
        ]); ?>

        <div class="form-group">
            <label>📌 เลือกช่วงวัน</label><br>
            <?= Html::input('date', 'start_date', $startDate, ['class' => 'input-glow']) ?>
            ถึง
            <?= Html::input('date', 'end_date', $endDate, ['class' => 'input-glow']) ?>
            <?= Html::submitButton('📊 ดูรายงาน', ['class' => 'btn btn-success ml-2']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card-modern">
            <h4>📅 รายงานระหว่างวันที่  
                <span style="color: #f73eeb; font-weight: bold;">
                    <?= date('d/m/Y', strtotime($startDate)) ?> - <?= date('d/m/Y', strtotime($endDate)) ?>
                </span>
            </h4>

            <canvas id="dayChart" style="height: 320px;"></canvas>

            <table class="table table-striped mt-3">
                <thead>
                    <tr><th>โรค</th><th>จำนวนเคส</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($dayData as $row): ?>
                        <tr>
                            <td><?= Html::encode($row['โรค']) ?></td>
                            <td style="min-width: 200px;">
                                
                                <div class="progress mt-1">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: <?= min($row['จำนวนเคส'] * 5, 100) ?>%"
                                        aria-valuenow="<?= (int)$row['จำนวนเคส'] ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= (int)$row['จำนวนเคส'] ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>


       <div class="col-md-6">
    <div class="card-modern">
        <h4>📊 รายงานรายเดือน</h4>
        <p style="color:#888; font-size:14px;">
            ข้อมูลอัปเดตล่าสุด:
            <span style="color: #d4af37; font-weight:bold;">
                <?= Yii::$app->formatter->asDatetime($lastUpdatedAt, 'php:d/m/Y H:i') ?>
            </span>
        </p>

        <canvas id="monthChart" style="height: 320px;"></canvas>

        <table class="table table-bordered table-hover table-monthly mt-3 text-sm">
            <thead class="bg-light text-center text-dark">
                <tr>
                    <th>รหัสโรค</th>
                    <th>ชื่อโรค (ENG)</th>
                    <th>Oct</th><th>Nov</th><th>Dec</th>
                    <th>Jan</th><th>Feb</th><th>Mar</th>
                    <th>Apr</th><th>May</th><th>Jun</th><th>Jul</th>
					<th>Aug</th><th>Sept</th>
                    <th><b>รวม</b></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthData as $m): ?>
                    <tr class="text-center">
                        <td class="text-left"><?= Html::encode($m['diag']) ?></td>
                        <td class="text-left"><?= Html::encode($m['name_eng']) ?></td>
                        <td><?= (int)$m['2024-10'] ?></td>
                        <td><?= (int)$m['2024-11'] ?></td>
                        <td><?= (int)$m['2024-12'] ?></td>
                        <td><?= (int)$m['2025-01'] ?></td>
                        <td><?= (int)$m['2025-02'] ?></td>
                        <td><?= (int)$m['2025-03'] ?></td>
                        <td><?= (int)$m['2025-04'] ?></td>
                        <td><?= (int)$m['2025-05'] ?></td>
                        <td><?= (int)$m['2025-06'] ?></td>
                        <td><?= (int)$m['2025-07'] ?></td>
						<td><?= (int)$m['2025-08'] ?></td>
						 <td><?= (int)$m['2025-09'] ?></td>
                        <td><b><?= (int)$m['total_case'] ?></b></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

