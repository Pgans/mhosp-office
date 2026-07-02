<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Json;

$this->title = "Refer-ER Dashboard";
$this->params['breadcrumbs'][] = 'รายงานเวชระเบียน';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);

?>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<div class="refer-er-dashboard">
    <h1 style="color: #337ab7;"><?= Html::encode($this->title) ?></h1>
    <p class="text-muted">รายงานการส่งต่อผู้ป่วยที่มาถึงโรงพยาบาลและใช้เวลามากกว่า 2 ชั่วโมง</p>

    <!-- Date Range Form -->
    <div class="well">
        <?php $form = ActiveForm::begin([
            'method' => 'POST',
            'action' => ['readmit/referopd'],
        ]); ?>
        <div class="row">
            <div class="col-md-2">
                <label>วันที่เริ่มต้น:</label>
                <?= DatePicker::widget([
                    'name' => 'date1',
                    'value' => $date1,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-2">
                <label>วันที่สิ้นสุด:</label>
                <?= DatePicker::widget([
                    'name' => 'date2',
                    'value' => $date2,
                    'language' => 'th',
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label><br>
                <button class="btn btn-primary">ค้นหา</button>
                <button class="btn btn-success" onclick="window.print();">พิมพ์รายงาน</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
	 <div class="result-graph">
	  <div class="col-md-6">
    <?php
        // เตรียมข้อมูลกราฟ
        $chartData = [];
        foreach ($monthProvider->getModels() as $model) {
            $chartData[] = [
                'name' => $model['ชื่อเดือน'] . ' ' . $model['ปี'],
                'data' => [
                    (int)$model['จำนวนครั้งโดยรถโรงพยาบาล'],
                    (int)$model['ไปเอง'],
                    (int)$model['จำนวนทั้งหมด'],
                ]
            ];
        }

        // กำหนดค่ากราฟ
        echo Highcharts::widget([
            'options' => [
                'chart' => [
                    'type' => 'bar',  // ใช้กราฟแนวนอน (Bar)
                    'height' => 400,  // ขนาดความสูงของกราฟ
                ],
                'title' => [
                    'text' => 'จำนวนครั้งการใช้งานตามเดือน',  // ชื่อกราฟ
                ],
                'xAxis' => [
                    'categories' => ['รถโรงพยาบาล', 'ไปเอง', 'ทั้งหมด'],  // หมวดหมู่ที่แสดงบนแกน X
                ],
                'yAxis' => [
                    'title' => [
                        'text' => 'จำนวนครั้ง',
                    ],
                ],
                'series' => $chartData,  // ข้อมูลที่ใช้ในการสร้างกราฟ
            ]
        ]);
    ?>
</div>

    <!-- Data Grid -->
	 <div class="col-md-6">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><b>รายงานสรุปการส่งต่อผู้ป่วยงานอุบัติเหตุฉุกเฉิน</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $monthProvider,
                'panel' => [
                    'type' => GridView::TYPE_INFO,
                    'heading' => '<i class="glyphicon glyphicon-stats"></i> สถิติการส่งต่อรายเดือน',
                    'footer' => false,
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
				'showPageSummary' => true, // เปิดการแสดงผลรวม
                 'columns' => [
				 ['class' => 'kartik\grid\SerialColumn'],
                /*
                [
                    'attribute' => 'EnglishMonth',
                    'label' => 'เดือน (English)',
                ],
				*/
                [
                    'attribute' => 'ชื่อเดือน',
                    'label' => 'เดือน (ภาษาไทย)',
                ],
                [
                    'attribute' => 'ปี',
                    'label' => 'ปี พ.ศ.',
					'pageSummary' => 'รวมทั้งหมด',
                ],
				
                [
                    'attribute' => 'ไปเอง (มากกว่า 2 ชั่วโมง)',
                    'label' => 'ไปเอง (>= 2 ชม.)',
                    'format' => ['integer'],
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'โดยรถโรงพยาบาล (มากกว่า 2 ชั่วโมง)',
                    'label' => 'รถรพ. (>= 2 ชม.)',
                    'format' => ['integer'],
					'pageSummary' => true,
                ],
				[
                    'attribute' => 'จำนวนที่ใช้เวลามากกว่า 2 ชั่วโมง',
                    'label' => '(>= 2 ชม.)ทั้งหมด',
                    'format' => ['integer'],
					'pageSummary' => true,
                ],
                [
                    'attribute' => 'จำนวนครั้งโดยรถโรงพยาบาล',
                    'label' => 'รถ รพ.',
                    'format' => ['integer'],
					'pageSummary' => true,
                ],
                [
                    'attribute' => 'ไปเอง',
                    'label' => 'จำนวนไปเอง',
                    'format' => ['integer'],
					'pageSummary' => true,
                ],
                [
                    'attribute' => 'จำนวนทั้งหมด',
                    'label' => 'จำนวนทั้งหมด',
                    'format' => ['integer'],
					'pageSummary' => true,
                ],
                
            ],
        ]) ?>
    </div>
	<p>
    <?= Html::a('⏪ กลับหน้าหลัก', ['referopd/index3'], [
        'class' => 'btn btn-custom'
    ]) ?>
</p>

<?php
$this->registerCss("
    .btn-custom {
        background-color: #3399ff; /* สีฟ้า */
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-custom:hover {
        background-color: #007acc; /* ฟ้าเข้มตอน hover */
        color: white;
        text-decoration: none;
    }
");
?>

</div>
<!--
<div class="chart-container">
    <canvas id="myBarChart" style="width:100%; height: 400px;"></canvas>
</div>

<script>
    var ctx = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar', // เลือกประเภทของกราฟเป็น bar
        data: {
            labels: [<?= implode(', ', array_map(function($row) { return "'".$row['ชื่อเดือน']."'"; }, $monthProvider->models)) ?>], // แท็กชื่อเดือน
            datasets: [{
                label: 'จำนวนครั้งโดยรถโรงพยาบาล',
                data: [<?= implode(', ', array_map(function($row) { return $row['จำนวนครั้งโดยรถโรงพยาบาล']; }, $monthProvider->models)) ?>], // ข้อมูล
                backgroundColor: 'rgba(75, 192, 192, 0.6)', // สีพื้นหลังของแท่ง
                borderColor: 'rgba(75, 192, 192, 1)', // สีขอบของแท่ง
                borderWidth: 1,
            },
            {
                label: 'จำนวนทั้งหมด',
                data: [<?= implode(', ', array_map(function($row) { return $row['จำนวนทั้งหมด']; }, $monthProvider->models)) ?>],
                backgroundColor: 'rgba(153, 102, 255, 0.6)', 
                borderColor: 'rgba(153, 102, 255, 1)', 
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true, // ให้กราฟปรับขนาดตามหน้าจอ
            scales: {
                x: {
                    beginAtZero: true, // เริ่มจาก 0 บนแกน X
                    title: {
                        display: true,
                        text: 'จำนวนครั้ง'
                    }
                },
                y: {
                    beginAtZero: true, // เริ่มจาก 0 บนแกน Y
                    title: {
                        display: true,
                        text: 'เดือน'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        // Custom tooltip to show values in bar
                        label: function(tooltipItem) {
                            return tooltipItem.raw + ' ครั้ง'; // เพิ่มข้อความ "ครั้ง" ใน Tooltip
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value) {
                        return value + ' ครั้ง'; // แสดงจำนวนที่แท่งกราฟ
                    }
                }
            }
        },
    });
</script>
