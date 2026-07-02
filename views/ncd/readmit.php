<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

use yii\data\ActiveDataProvider;

$this->title = 'NCD';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ntip/index']];
$this->params['breadcrumbs'][] = 'Readmit';
?>
<style>
    .cid-link {
        color: green;
    }
</style>

<br>
    <div style="background-color: #BA55D0; padding: 10px; border: 0px solid #D4F1F5;">
    <h4><a style="color: white;" href="#">Readmitภายใน 28 วัน รหัสโรคเดียวกัน</h4>
</div>

<div class='well' style="background-color: #E6E6FA;color: darkgreen;">
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
		<input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">

        <?php $form = ActiveForm::begin([ ]);
    // echo Html::a('แยกรายเดือน', ['thaimed/u_9007712month'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']);
  
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>

<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<a style="color:green">Readmit 28 วัน ด้วยรหัสโรคเดียวกัน</a>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

					[
                        'attribute' => 'hn',
                        'header' => 'hn',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
						'attribute' => 'cid',
						'label' => 'เลขประชาชน',
						'contentOptions' => [
							'style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden; font-size: 14px;',
							'ondblclick' => 'copyToClipboard(this);',
						],
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
					],

					[
                        'attribute' => 'fullname',
                        'header' => 'ชื่อ-สกุล',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'age',
                        'header' => 'อายุ',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'adm1',
                        'header' => 'date_adm1',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'time1',
                        'header' => 'time1',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'an2',
                        'header' => 'an2',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					
                    [
                        'attribute' => 'icd1',
                        'header' => 'รหัสโรค1',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
                     
					[
                        'attribute' => 'adm2',
                        'header' => 'date_adm2',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'time2',
                        'header' => 'time2',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'an2',
                        'header' => 'an2',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					
                    [
                        'attribute' => 'icd2',
                        'header' => 'รหัสโรค2',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
                    ],
					[
                        'attribute' => 'revisit_days',
                        'header' => 'วัน',
						'headerOptions' => ['style' => 'background-color:#E6E6FA'],
						'format' => 'raw', // จำเป็นสำหรับแสดง HTML
						'value' => function ($model) {
							return '<button class="btn btn-primary" style="background-color:#28b463 ; color:white;">' . $model['revisit_days'] . '</button>';
						},
					],
                    
					[
					'attribute' => 'revisit_hours',
					'header' => 'ชั่วโมง',
					'headerOptions' => ['style' => 'background-color:#E6E6FA'],
					 'format' => 'raw', // จำเป็นสำหรับแสดง HTML
					'value' => function ($model) {
						return '<button class="btn btn-primary" style="background-color:#147FF1; color:white;">' . $model['revisit_hours'] . '</button>';
						},
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