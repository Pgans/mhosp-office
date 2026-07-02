<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;

$this->title = 'ไฟล์นำเข้าโปรแกรม Ntip คลินิกหอบหืด (COPD)';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ntip/index']];
$this->params['breadcrumbs'][] = 'ntip ncd nap';
?>
<style>
    .cid-link {
        color: green;
    }
</style>
<style>
    /* Hover สีเขียวอ่อน */
    .kv-grid-table tbody tr:hover {
        background-color: #DFF0D8 !important; /* เขียวอ่อน */
    }

    /* Sticky header */
    .kv-grid-table thead th {
        position: sticky;
        top: 0;
        background: #B7E1CD; /* ให้คงสีเขียวไว้ที่หัว */
        z-index: 1;
    }

    /* Scrollbar container */
    .kv-grid-container {
        max-height: 500px;
        overflow-y: auto;
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
    <div style="background-color: #788704; padding: 10px; border: 0px solid #D4F1F5;">
    <h4><a style="color: white;" href="#">ส่งออกไฟล์ Excel นำเข้า Ntip คลินิกหอบหืด (COPD)</h4>
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
    
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>
<div class="table-responsive-custom">

<?php

echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:green">ไฟล์ Excel สำหรับน่ำเข้าโปแกร Ntip คลินิกหอบหืด (COPD) </b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'RISK_TYPE',
                        'header' => 'RISK_TYPE',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'TITLE_ID',
                        'header' => 'TITLE_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
						'attribute' => 'FNAME',
						'label' => 'FNAME',
						'contentOptions' => [
							'style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden; font-size: 14px;',
							'ondblclick' => 'copyToClipboard(this);',
						],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
					],
					[
                        'attribute' => 'LNAME',
                        'header' => 'LNAME',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'CID',
                        'header' => 'CID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'GENDER',
                        'header' => 'GENDER',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'BORN',
                        'header' => 'BORN',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'ADDR',
                        'header' => 'ADDR',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    [
                        'attribute' => 'MU',
                        'header' => 'MU',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    [
                        'attribute' => 'PROVINCE_ID',
                        'header' => 'PROVINCE_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'AMPHUR_ID',
                        'header' => 'AMPHUR_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'TAMBOL_ID',
                        'header' => 'TAMBOL_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'PEOPLE_TYPE',
                        'header' => 'PEOPLE_TYPE',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'RACE_ID',
                        'header' => 'RACE_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'CONTACT_DATE',
                        'header' => 'CONTACT_DATE',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'SYMPTOM_SCREEN',
                        'header' => 'SYMPTOM_SCREEN',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'CXR_DATE',
                        'header' => 'CXR_DATE',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'CXR_RESULT',
                        'header' => 'CXR_RESULT',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'CXR_ABNORMAL_RESULT',
                        'header' => 'CXR_ABNORMAL_RESULT',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'DX',
                        'header' => 'DX',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'HN',
                        'header' => 'HN',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'HMAIN_ID',
                        'header' => 'HMAIN_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'INSCL_ID',
                        'header' => 'INSCL_ID',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'ICD10',
                        'header' => 'ICD10',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'HbA1C',
                        'header' => 'HbA1C',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'IMMUNNO_DISEASE',
                        'header' => 'IMMUNNO_DISEASE',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'B24',
                        'header' => 'B24',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    ]
                    ]
                    );
                    
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
