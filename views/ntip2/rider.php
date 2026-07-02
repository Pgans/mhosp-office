<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Tabs;
use dosamigos\chartjs\ChartJs; // ใช้ Chart.js เพื่อสร้างกราฟ
use dosamigos\datepicker\DatePicker;
use yii\data\ActiveDataProvider;

$this->title = 'Rider(NCD-สีเขียว)';
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
        background-color: #faf0fc; /* ม่วงอ่อน */
        color: #4a235a; /* ม่วงเข้มอ่านง่าย */
        border: 1px solid #e0cfe7; /* ขอบม่วงอ่อน */
        margin-right: 2px;
        text-align: center;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li > a:hover {
        background-color: #faf7f7; /* ม่วงอ่อนเข้มขึ้นเวลา hover */
        color: #4a148c; /* ม่วงเข้มเวลา active */
        border-color: #8e44ad; /* ขอบม่วงเข้ม */
    }
    .tab-content {
        padding: 15px;
        border: 1px solid #e0cfe7;
        border-top: none;
        background-color: #ffffff; /* เนื้อหาข้างในสีขาวตัดกัน */
    }
");
?>
<style>
    .cid-link {
        color: #6a1b9a; /* ม่วงเข้มแทนเขียว */
    }
	
    .btn-custom {
        background: linear-gradient(45deg, #e1bee7, #ba68c8); /* ไล่สีม่วง */
        color: white;
        border: 2px solid #faf0fc;
        padding: 12px 24px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        transition: 0.3s ease;
        text-decoration: none;
    }

    .btn-custom:hover {
        background: linear-gradient(45deg, #faf7f7, #9c27b0); /* ม่วงเข้มขึ้น */
        box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        transform: translateY(1px);
    }
</style>


<br>
    <div style="background: linear-gradient(135deg, #a547ff, #faf0fc); 
            padding: 10px; 
            border: 0px solid #b39dfa;">

    <h4><a style="color: white;" href="#">ผู้มารับบริการ Rider NCD-สีเขียว</h4>
</div>

<div class='well' style="background-color: #faf0fc; color: #000; padding: 15px; border-radius: 15px;">
    <?= Html::beginForm(['rider'], 'get', ['class' => 'd-flex align-items-center justify-content-between flex-wrap']) ?>
        <div class="form-inline d-flex align-items-center">
            <label class="mr-2 mb-0" style="color: #000; font-weight: bold;">วันที่:</label>
            <?= DatePicker::widget([
                'name' => 'date1',
                'value' => $date1,
                'language' => 'th',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                ],
                'options' => [
                    'class' => 'form-control shadow-sm',
                    'placeholder' => 'เริ่ม',
                    'style' => '
                        font-size: 1.2rem;
                        padding: 10px 15px;
                        background-color: #ffffff;
                        border: 2px solid #87cefa;
                        border-radius: 20px;
                        color: #000; /* สีตัวอักษรดำ */
                    ',
                ],
            ]) ?>

            <label class="mr-2 mb-0 ml-3" style="color: #000; font-weight: bold;">ถึง:</label>
            <?= DatePicker::widget([
                'name' => 'date2',
                'value' => $date2,
                'language' => 'th',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                ],
                'options' => [
                    'class' => 'form-control shadow-sm',
                    'placeholder' => 'สิ้นสุด',
                    'style' => '
                        font-size: 1.2rem;
                        padding: 10px 15px;
                        background-color: #ffffff;
                        border: 2px solid #b0e0e6;
                        border-radius: 20px;
                        color: #000; /* สีตัวอักษรดำ */
                    ',
                ],
            ]) ?>

            <?= Html::submitButton('🔍 ค้นหา', [
                'class' => 'btn btn-outline-dark shadow ml-3',
                'style' => '
                    font-size: 1.2rem;
                    font-weight: bold;
                    padding: 10px 20px;
                    border-radius: 20px;
                    background-color: #d4c8fa;
                    transition: all 0.3s;
                    color: #000;
                '
            ]) ?>
        </div>
    <?= Html::endForm() ?>
</div>


<?php
$totalแจ้งหนี้ = array_sum(array_column($dataProvider->allModels, 'แจ้งหนี้'));
$totalยอดใบเสร็จ = array_sum(array_column($dataProvider->allModels, 'ยอดออกใบเสร็จ'));
$totalยอดเคลม = array_sum(array_column($dataProvider->allModels, 'ยอดเคลมชดเชย'));
?>

<!-- แสดง Page Summary ด้านนอก Scrollbar -
<div class="page-summary" style="margin-bottom:10px; font-weight:bold; font-size:14px; color: #800080;">
    แจ้งหนี้: <?= number_format($totalแจ้งหนี้) ?> |
    ยอดออกใบเสร็จ: <?= number_format($totalยอดใบเสร็จ) ?> |
    ยอดเคลมชดเชย: <?= number_format($totalยอดเคลม) ?>
</div>
-->

<div style="max-height: 650px; overflow-y: auto; border: 1px solid #ddd; border-radius: 10px; padding: 5px;">
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
		 'summary' => false, // 🔕 ตัดข้อความ Total x items
    'showPageSummary' => true,
	  'floatHeader' => true,          // 🔹 เปิดใช้งานตรึงหัวตาราง
    'floatHeaderOptions' => [
        'scrollingTop' => 0,        // ระยะห่างจาก top ของ container
        'position' => 'absolute',   // ใช้ absolute positioning
        'zIndex' => 10,
        'backgroundColor' => '#faf0fc', // สีพื้นหัวตาราง
    ],
    'tableOptions' => [
            'class' => 'table table-striped table-hover',
            'style' => 'color: #000; font-family: Arial, sans-serif; font-size: 13px;', // ฟอนต์สีดำ
        ],
		 'panel' => [
            'before'=>'<b style="color:green">RIDER</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
     'columns' => [
   [
    'header' => 'ลำดับ',
    'format' => 'raw',
    'value' => function ($model, $key, $index) use ($dataProvider) {
        $page = $dataProvider->pagination->page ?? 0;
        $pageSize = $dataProvider->pagination->pageSize ?? $dataProvider->count;
        return $dataProvider->totalCount - ($page * $pageSize + $index);
    },
],

                    [
                        'attribute' => 'regdate',
                        'header' => 'วันรับบริการ',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 15vw; overflow: hidden;'],
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					
					[
                        'attribute' => 'hn',
                        'header' => 'HN',
						
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					
				
					[
                        'attribute' => 'fullname',
                        'header' => 'ชื่อ-สกุล',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 15vw; overflow: hidden;'],
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					[
                        'attribute' => 'age',
                        'header' => 'อายุ',
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					[
					'attribute' => 'unit_name',
					'header' => 'แผนกรับบริการ',
					'headerOptions' => [
						'style' => 'background-color:#faf0fc; color:green; font-weight:bold;'
					],
					'contentOptions' => [
						'style' => 'color:green; font-weight:bold;'
					],
				    ],

				   [
					'attribute' => 'inscl',
					'header' => 'สิทธิ์การรักษา',
					'headerOptions' => [
						'style' => 'background-color:#faf0fc; color:darkblue; font-weight:bold;'
					],
					'contentOptions' => [
						'style' => 'color:darkblue; font-weight:bold;'
					],
					],

                    [
                        'attribute' => 'Diagx',
                        'header' => 'รหัสโรค',
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
                    [
                        'attribute' => 'comore',
                        'header' => 'โรคร่วม',
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					[
                        'attribute' => 'SBP',
                        'header' => 'SBP',
						
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
						
                    ],
					[
                        'attribute' => 'DBP',
						'header' => 'DBP',
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					[
                        'attribute' => 'FBS',
                        'header' => 'FBS',
						
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					[
                        'attribute' => 'HomeMed',
                        'header' => 'HomeMed',
						
						'headerOptions' => ['style' => 'background-color:#faf0fc'],
                    ],
					[
					'attribute' => 'claimcode',
					'header' => 'authen',
					//'pageSummary' => true,
					'headerOptions' => [
						'style' => 'background-color:#faf0fc; color:orange; font-weight:bold;'
					],
					'contentOptions' => [
						'style' => 'color:orange; font-weight:bold;'
					],
				],
					  
			],
		]);

		?>
		</div>
		</br>
						<p>
			<?= Html::a('⏪ กลับหน้าหลัก', ['referopd/index3'], [
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