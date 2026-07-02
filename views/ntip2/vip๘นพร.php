<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Tabs;

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
<?php

//use yii\bootstrap\Tabs;

// ข้อมูลรายเดือน
$monthlyContent = '<table class="table table-bordered"><tr><th>เดือน</th><th>ค่า</th></tr>';
foreach ($monthlyData as $row) {
    $monthlyContent .= "<tr><td>{$row['month']}</td><td>{$row['value']}</td></tr>";
}
$monthlyContent .= '</table>';

// ข้อมูลรายวัน
$dailyContent = '<table class="table table-bordered"><tr><th>วันที่</th><th>ค่า</th></tr>';
foreach ($dailyData as $row) {
    $dailyContent .= "<tr><td>{$row['date']}</td><td>{$row['value']}</td></tr>";
}
$dailyContent .= '</table>';

// สร้าง Tabs
echo Tabs::widget([
    'items' => [
        [
            'label' => 'ข้อมูลรายเดือน',
            'content' => $monthlyContent,
            'active' => true, // เปิด tab นี้เป็นค่าเริ่มต้น
        ],
        [
            'label' => 'ข้อมูลรายวัน',
            'content' => $dailyContent,
        ],
    ],
]);

?>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:green">VIP-พิเศษ(74)</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
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
					
                    ]
                    ]
                    );
                    
                    ?>
                
                    
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