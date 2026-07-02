<?php
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\jui\DatePicker;
use yii\base\ArrayDataProvider;

$this->title = 'ยาสมุนไพร 10 กลุ่มโรค';

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->params['breadcrumbs'][] = 'ยาสมุนไพร 10 กลุ่มโรค';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);

$this->registerJsFile('https://code.highcharts.com/highcharts.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://code.highcharts.com/highcharts-3d.js', ['position' => \yii\web\View::POS_HEAD]);



?>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #ffe6f0; /* สีชมพูอ่อน */
    }

    .well {
        background-color: #fffafc; /* สีพื้นหลังกล่อง */
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-danger {
        background-color: #ff6f91; /* สีชมพูอ่อน */
        border: none;
        color: white;
        font-weight: bold;
    }

    .btn-danger:hover {
        background-color: #ff5a81; /* สีเข้มขึ้นเมื่อ hover */
    }

    .table-container {
        background-color: #fff; /* สีพื้นหลัง */
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .table th {
        background-color: #fbd9e4; /* สีหัวตาราง */
        color: #000;
        text-align: center;
        padding: 10px;
    }

    .table td {
        text-align: center;
        padding: 10px;
    }

    .badge {
        background-color: #ffe6f0; /* สีพื้นหลังของตัวเลข */
        color: #000;
        font-weight: bold;
    }
	canvas {
    max-width: 100%;
    max-height: 100%;
}

</style>

<br>

<div class='well'>
    <?php $form = ActiveForm::begin(); ?>
     ระหว่างวันที่:
        <?php
            echo yii\jui\DatePicker::widget([
                'name' => 'date1',
                'value' => $date1,
                'language' => 'th',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                ]
            ]);
        ?>
        ถึง:
        <?php
            echo yii\jui\DatePicker::widget([
                'name' => 'date2',
                'value' => $date2,
                'language' => 'th',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                ]
            ]);
        ?>
        <button class='btn btn-danger'> ประมวลผล </button>
		
    <?php ActiveForm::end(); ?>
</div>

<?php Pjax::begin(); ?>

<div class="row">

<div class="col-md-12" style="padding: 10px; flex: 1;">
        <div class="table-container">
            <?php 
            $gridColumns = [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'report_month_name',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'เดือน',
                ],
				 [
                    'attribute' => 'report_year',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ปี',
					'pageSummary' => 'รวมทั้งหมด',
                ],
                [
                    'attribute' => '0664_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'เถาวัลย์เปรียง',
                   'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
                [
                    'attribute' => '2358_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'สหัสธารา',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2486_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ครีมไพล',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
                [
                    'attribute' => '2486_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ครีมไพล',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ], 
				
				[
                    'attribute' => '0491_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ลูกประคบ',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '0262_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ฟ้าทะลายโจร',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2443_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'มะขามป้อม',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '1392_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'อบเชยแคบ',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2439_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ธาตุอบเชย',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '0263_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ขมิ้นชัน',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2314_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ธรณีสัณฑฆาต',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '0261_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'เพชรสังฆาต',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2363_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'นวโกฐ',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2466_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'พระสุเมรุ',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2419_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'พญายอ',
                   'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				
				[
                    'attribute' => '2295_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'เทพจิตร',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],[
                    'attribute' => '2051_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'น้ำมันกัญชา',
					 'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '0262_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ฟ้าทะลายโจร',
                   'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2289_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'มะระขี้นก',
                   'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '0266_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'น้ำมันไพล',
                   'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				[
                    'attribute' => '2362_visits',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ปรายชมพูทวีป',
                   'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
				
            ];



            echo GridView::widget([
                'tableOptions' => [
                    'class' => 'table table-striped table-hover',
                    'width'=>'100%',
                    'cellspacing'=> '0'
                ],
                'dataProvider' => $monthProvider,
                'panel' => [
                    'before' => '<b style="color:#ff6f91;">รายงานเดือนยาสมุนไพร 10 กลุ่มโรค (ครั้ง)</b>',
                    'after'=>'<a>ประมวลผลจากวันที่</a> '.$date1 .'<a>ถึงวันที่</a>' .$date2 
                ],
                'autoXlFormat' => true,
                'export' => [
                    'fontAwesome' => true,
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK
                ],
                'columns' => $gridColumns,
                'resizableColumns' => true,
                'showPageSummary' => true,
            ]);
            ?>
          <h3>🌿 รายงานสมุนไพร (Smonpai)</h3>

<div class="table-responsive" style="max-height:220px; overflow-y:auto;">
    <?= GridView::widget([
        'dataProvider' => $smonpaiProvider,
        'summary' => false,
        'tableOptions' => [
            'class' => 'table table-bordered table-striped table-condensed table-hover',
            'style' => 'width:100%; border-collapse:collapse;'
        ],
		'panel' => [
            'before'=>'การใช้ยาสมุนไพร '
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'ลำดับ'],
            [
                'attribute' => 'didstd',
                'label' => 'รหัสมาตรฐานยา',
            ],
            [
                'attribute' => 'drug_id',
                'label' => 'รหัสยา',
            ],
            [
                'attribute' => 'herb_id',
                'label' => 'รหัสสมุนไพร',
            ],
            [
                'attribute' => 'group_id',
                'label' => 'รหัสกลุ่มสมุนไพร',
            ],
            [
                'attribute' => 'group_name',
                'label' => 'ชื่อกลุ่มสมุนไพร',
            ],
            [
                'attribute' => 'DRUG_NAME',
                'label' => 'ชื่อยาเต็ม',
            ],
            [
                'attribute' => 'UNIT',
                'label' => 'รหัสหน่วย',
            ],
            [
                'attribute' => 'UNIT_PACKING',
                'label' => 'หน่วยบรรจุ',
            ],
            [
                'attribute' => 'DRUGPRICE',
                'label' => 'ราคา',
            ],
            [
                'attribute' => 'DRUGCOST',
                'label' => 'ต้นทุน',
            ],
			[
                'attribute' => 'reg_datetime',
                'label' => 'วันบริการ',
            ],
            [
                'attribute' => 'visits',
                'label' => 'จำนวน visit',
            ],
			[
                'attribute' => 'hn',
                'label' => 'hn',
            ],
            [
                'attribute' => 'amount',
                'label' => 'จำนวนใช้',
            ],
            [
                'attribute' => 'code_thai',
                'label' => 'รหัสไทย',
            ],
        ],
    ]); ?>
</div>

<?php
$this->registerCss("
.table-responsive thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f2f2f2;
}
.table-responsive {
    max-height: 220px; /* ปรับความสูงประมาณ 5 แถว */
    overflow-y: auto;
}
");
?>



        </div>
    </div>
	
	
    <div class="col-md-6" style="padding: 10px; flex: 1;">
        <div class="table-container">
            <?php 
            $gridColumns = [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'group_name',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'กลุ่มอาการ',
                ],
                [
                    'attribute' => 'drug_name',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ชื่อยา',
                    'pageSummary' => 'รวมทั้งหมด',
                ],
                [
                    'attribute' => 'visits',
                    'headerOptions' => ['style' => 'background-color:#fbd9e4; text-align:center;'],
                    'header' => 'ครั้ง',
                    'format' => ['decimal', 0],
                    'pageSummary' => true,
                    'contentOptions' => [
                        'class' => 'badge',
                        'style' => 'background-color: #ffe6f0; color: #000; text-align: center; padding: 5px 10px; font-size: 14px;'
                    ],
                ],
                [
                    'attribute' => 'amount',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'จำนวนยา',
                    'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'unit_packing',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'หน่วยนับ',
                ],
                [
                    'attribute' => 'total',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'ราคารวม',
                    'format' => ['decimal', 0],
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'code_thai',
                    'headerOptions'=>[ 'style'=>'background-color:#fbd9e4'] ,
                    'header' => 'รหัสโรค',
                ],
            ];

            echo GridView::widget([
                'tableOptions' => [
                    'class' => 'table table-striped table-hover',
                    'width'=>'100%',
                    'cellspacing'=> '0'
                ],
                'dataProvider' => $dataProvider,
                'panel' => [
                    'before' => '<b style="color:#ff6f91;">ยาสมุนไพร 10 กลุ่มโรค</b>',
                    'after'=>'<a>ประมวลผลจากวันที่</a> '.$date1 .'<a>ถึงวันที่</a>' .$date2 
                ],
                'autoXlFormat' => true,
                'export' => [
                    'fontAwesome' => true,
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK
                ],
                'columns' => $gridColumns,
                'resizableColumns' => true,
                'showPageSummary' => true,
            ]);
            ?>
            <div>หมายเหตุ *** จำนวนครั้งนับตามการจ่ายรายการแต่ละประเภทตามวันที่มารับบริการ</div>
			<div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>

        </div>
    </div>

   <div class="col-md-6" style="padding: 10px; flex: 1;">
    <div class="table-container">
        <!-- ขยายขนาดพื้นที่ของกราฟ -->
        <div style="width: 100%; height: 600px; padding: 20px;">
            <canvas id="drugChart"></canvas>

            <?php
           
// ข้อมูลสำหรับกราฟ
$labels = [];
$visitsData = [];
$amountData = [];
$drugNames = [];

foreach ($rawData as $row) {
    $labels[] = $row['drug_name']; // ชื่อยา
    $visitsData[] = (int)$row['visits']; // จำนวนครั้งที่ใช้ยา
    $amountData[] = (int)$row['amount']; // จำนวนยา
}
?>

<!-- ส่วนสำหรับกราฟ -->
<div style="width: 100%; height: 400px; padding: 20px;">
    <canvas id="drugChart"></canvas>
</div>

<script>
    var ctx = document.getElementById('drugChart').getContext('2d');
    var drugChart = new Chart(ctx, {
        type: 'bar', // กราฟแท่งแบบกลุ่ม
        data: {
            labels: <?= json_encode($labels) ?>, // ชื่อยา
            datasets: [
                {
                    label: 'จำนวนครั้งที่ใช้ยา',
                    data: <?= json_encode($visitsData) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // สีน้ำเงินโปร่งแสง
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'จำนวนยา',
                    data: <?= json_encode($amountData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)', // สีแดงโปร่งแสง
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // เปลี่ยนแกนให้เป็นแนวนอน
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString(); // ตัวคั่นหลักพัน
                        }
                    },
                    title: {
                        display: true,
                        text: 'จำนวน',
                        font: {
                            size: 14
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'ชื่อยา',
                        font: {
                            size: 14
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw.toLocaleString(); // ตัวคั่นหลักพันใน tooltip
                        }
                    }
                },
                datalabels: {
                    anchor: 'end', // วางตัวเลขที่ปลายแท่ง
                    align: 'end', // ชิดขอบแท่ง
                    color: '#000',
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    formatter: (value) => value.toLocaleString() // ตัวคั่นหลักพัน
                }
            },
            title: {
                display: true,
                text: 'กราฟแสดงข้อมูลการใช้ยา (แนวนอน)',
                font: {
                    size: 18
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

   
