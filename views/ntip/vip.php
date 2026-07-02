<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Tabs;
use dosamigos\chartjs\ChartJs; // ใช้ Chart.js เพื่อสร้างกราฟ

use yii\data\ActiveDataProvider;

$this->title = 'VIP พิเศษ';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ntip/index']];
$this->params['breadcrumbs'][] = 'ntip ncd nap';
?>
<style>
    .cid-link {
        color: green;
    }
</style>
<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0', ['position' => \yii\web\View::POS_HEAD]);


$this->registerCss("
    .nav-tabs > li > a {
        background-color: #f7f7f7; /* สีหาอ่อน */
        color: #333; /* สีตัวหนังสือ */
        border: 1px solid #ddd; /* เส้นขอบ */
        margin-right: 2px; /* ระยะห่างระหว่างแท็บ */
        text-align: center;
        transition: background-color 0.3s ease; /* เพิ่มแอนิเมชัน */
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li > a:hover {
        background-color: #e0f7fa; /* สีเมื่อคลิกหรือ hover */
        color: #00796b; /* สีตัวหนังสือเมื่อ active */
        border-color: #00796b; /* สีขอบเมื่อ active */
    }
    .tab-content {
        padding: 15px;
        border: 1px solid #ddd;
        border-top: none; /* ซ่อนเส้นขอบด้านบน */
        background-color: #ffffff; /* สีพื้นหลังของเนื้อหา */
    }
");

?>
<style>
    .cid-link {
        color: green;
    }
	
	.btn-custom {
    background-color: #B7E1CD;        /* สีฟ้า */
    color: green;                     /* ตัวอักษรสีขาว */
    border: 2px solid white;          /* ขอบขาว */
    padding: 12px 24px;               /* ขยาย padding เล็กน้อย */
    font-size: 18px;                  /* ✅ ขนาดตัวอักษรใหญ่ขึ้น */
    font-weight: bold;
    border-radius: 8px;               /* มุมโค้ง */
    box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3); /* เงาแบบ 3 มิติ */
    transition: 0.3s ease;
    text-decoration: none;
}

.btn-custom:hover {
    background-color: #008ecc;
    box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    transform: translateY(1px);
}

</style>

<br>
    <div style="background-color: #126F30; padding: 10px; border: 0px solid #D4F1F5;">
    <h4><a style="color: white;" href="#">ผู้มารับบริการแผนก VIP-พิเศษ  ที่มีการ X-ray และได้รับการตรวจ Anti-HCV(069)</h4>
</div>

<div class='well' style="background-color: #B7E1CD;color: darkgreen;">
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
        <button class='btn btn-danger'> ตกลง </button>
        <?php $form = ActiveForm::begin([ ]);
    // echo Html::a('แยกรายเดือน', ['thaimed/u_9007712month'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']);
  
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>

<!-- Tabs -->
<?php
$dailyContent = GridView::widget([
    'dataProvider' => $dataProvider,
     'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'regdate',
                        'header' => 'วันรับบริการ',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 15vw; overflow: hidden;'],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'visit_id',
                        'header' => 'visit',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'hn',
                        'header' => 'hn',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
						'attribute' => 'cid',
						'label' => 'เลขประชาชน',
						'contentOptions' => [
							'style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden; font-size: 14px;',
							'ondblclick' => 'copyToClipboard(this);',
						],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
					],
					
					[
                        'attribute' => 'fullname',
                        'header' => 'ชื่อ-สกุล',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 15vw; overflow: hidden;'],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'age',
                        'header' => 'อายุ',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'unit_name',
                        'header' => 'แผนกรับบริการ',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'unit_reg',
                        'header' => 'รหัสแผนก',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    [
                        'attribute' => 'Diag',
                        'header' => 'รหัสโรค',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    
					[
                        'attribute' => 'lab_result',
                        'header' => 'Anti_HCV',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'film_used',
                        'header' => 'ฟิมล์',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'hosp_name',
                        'header' => 'สถานบริการ',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					
                  
    ],
]);

$monthlyContent = GridView::widget([
    'dataProvider' => $monthlyDataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'month',
            'label' => 'เดือน',
            'headerOptions' => ['style' => 'background-color:#B7E1CD'],
        ],
        [
            'attribute' => 'total_visits',
            'label' => 'จำนวนผู้รับบริการ',
            'headerOptions' => ['style' => 'background-color:#B7E1CD'],
        ],
        [
            'attribute' => 'total_patients',
            'label' => 'จำนวนผู้ป่วย',
            'headerOptions' => ['style' => 'background-color:#B7E1CD'],
        ],
        [
            'attribute' => 'total_xray',
            'label' => 'จำนวนฟิล์ม X-ray',
            'headerOptions' => ['style' => 'background-color:#B7E1CD'],
        ],
        [
            'attribute' => 'total_lab',
            'label' => 'ผลตรวจ Anti-HCV',
            'headerOptions' => ['style' => 'background-color:#B7E1CD'],
        ],
    ],
]);
$chartContent = ChartJs::widget([
    'type' => 'bar',
    'options' => [
        'height' => 200,
        'width' => 400,
        'plugins' => [
            'datalabels' => [
                'display' => true,
                'color' => '#000', // สีของตัวเลข
                'align' => 'center', // การจัดตำแหน่งตัวเลข
                'anchor' => 'end', // การจัดการการวางตำแหน่ง
                'formatter' => function($value) {
                    return number_format($value); // รูปแบบตัวเลข
                }
            ]
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true, // เริ่มที่ 0
            ],
        ],
    ],
    'data' => [
        'labels' => array_column($yearlyMonthlyDataProvider->allModels, 'month_name'), // ชื่อเดือน
        'datasets' => [
            [
                'label' => "จำนวนผู้รับบริการ",
                'backgroundColor' => '#B7E1CD', // กำหนดสีของแท่งกราฟ
                'borderColor' => '#B7E1CD', // กำหนดสีของกรอบแท่ง
                'borderWidth' => 1,
                'data' => array_column($yearlyMonthlyDataProvider->allModels, 'total_visits'), // ข้อมูลจำนวนผู้รับบริการ
            ],
        ],
    ],
]);

// สร้างเนื้อหาสำหรับแท็บ
$yearlyContent = '<div class="row">
    <div class="col-md-6">
        ' . GridView::widget([
            'dataProvider' => $yearlyMonthlyDataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'year',
                    'label' => 'ปี',
					'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                ],
                [
                    'attribute' => 'month_name',
                    'label' => 'เดือน',
					'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                ],
                [
                    'attribute' => 'total_visits',
                    'label' => 'จำนวนผู้รับบริการ',
					'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                ],
                [
                    'attribute' => 'total_patients',
                    'label' => 'จำนวนผู้ป่วย',
					'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                ],
                [
                    'attribute' => 'total_xray',
                    'label' => 'จำนวนฟิล์ม X-ray',
					'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                ],
                [
                    'attribute' => 'total_lab',
                    'label' => 'ผลตรวจ Anti-HCV',
					'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                ],
            ],
        ]) . '
    </div>
    <div class="col-md-6">
        ' . $chartContent . '
    </div>
</div>';



// หากมีส่วนรายเดือน ให้สร้างตัวแปร $monthlyContent เหมือนตัวอย่างก่อนหน้า
echo Tabs::widget([
    'items' => [
        [
            'label' => 'ข้อมูลรายวัน',
            'content' => $dailyContent,
            'active' => true,
        ],
         [
             'label' => 'ข้อมูลรายเดือน',
             'content' => $monthlyContent, // ใช้ข้อมูลที่เตรียมใน controller
         ],
		  [
             'label' => 'ข้อมูลปีงบ2568',
             'content' => $yearlyContent, // ใช้ข้อมูลที่เตรียมใน controller
         ],
    ],
]);
?>
                <p>
			<?= Html::a('⏪ กลับหน้าหลัก', ['ntip/index3'], [
				'class' => 'btn btn-custom'
			]) ?>
			</p> 
                    
                   <!-- <div class="alert alert-info"><?=$sql?> </div>-->

<script>
function copyToClipboard(element) {
    // Create a textarea
    var textArea = document.createElement("textarea");
    textArea.value = element.innerText;
    document.body.appendChild(textArea);

    // Select and copy the text
    textArea.select();
    document.execCommand('copy');

    // Hide the textarea instead of removing it
    textArea.style.display = 'none';

    // Change the text color to indicate that it has been copied
    var originalColor = element.style.color;
    element.style.color = 'green'; // Change this to the desired color
    setTimeout(function() {
        element.style.color = originalColor; // Restore the original color after a delay
    }, 800000); // Adjust the delay time as needed
}

</script>