<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

$this->title = 'AFB-TB';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ntip/index']];
$this->params['breadcrumbs'][] = 'ntip ncd nap';
?>
<style>
    /* Scrollable table container */
    .table-container {
        max-height: 500px; /* ตั้งค่าความสูงสูงสุด */
        overflow-y: auto;  /* เพิ่มการเลื่อนในแนวตั้ง */
        display: block;
        border: 1px solid #ddd; /* กรอบให้กับ container */
        border-radius: 4px;
    }

    /* Table styling */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Header Style */
    .table th {
        background-color: #630992;
        color: white;
        position: sticky;
        top: 0;
        z-index: 2;
        text-align: center;
    }

    /* Table Cells Style */
    .table td, .table th {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd; /* กรอบรอบๆ เซลล์ */
    }

    /* Row style for alternating colors */
    .table tbody tr:nth-child(odd) {
        background-color: #f4f4f4;
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    /* Hover effect for rows */
    .table tbody tr:hover {
        background-color: #feecfe; /* เปลี่ยนสีเมื่อ hover */
        cursor: pointer;
    }

    /* Button Style */
    .btn {
        background-color: #630992;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn-danger {
        background-color: #d9534f;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .btn-danger:hover {
        opacity: 0.8;
    }

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
<br>
    <div style="background-color: #630992; padding: 10px; border: 0px solid #D4F1F5;">
    <h4><a style="color: white;" href="#"><i class="fa fa-hospital"></i>  305-AFBตลับ1,306-AFBตลับ2 ,332-TB-DNA</h4>
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
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:green">CD4, Viral Load , Creatinine, AFB</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'lab_request_date',
                        'header' => 'วันรับบริการ',
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
                        'attribute' => 'AFB_1',
                        'header' => 'AFB_1',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'AFB_2',
                        'header' => 'AFB_2',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'TBDNA',
                        'header' => 'TB-DNA',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'AntiHIV_result',
                        'header' => 'AntiHIV',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'AFB_result',
                        'header' => 'AFB',
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