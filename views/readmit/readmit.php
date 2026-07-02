<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = "RE-ADMIT";
$this->params['breadcrumbs'][] = 'รายงานเวชระเบียน';
?>

<h3 style="color:#007acc; font-weight:bold; text-shadow:1px 1px 2px rgba(0,0,0,0.1);">
    📄 Re-Admit28
</h3>

<div class='well custom-well'>
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'action' => ['readmit/readmit'],
    ]); ?>
    
    <div style="margin-bottom:10px;">
        <strong>วันที่ระหว่าง:</strong>
        <?= yii\jui\DatePicker::widget([
            'name' => 'date1',
            'value' => $date1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]); ?>
        <strong>ถึง:</strong>
        <?= yii\jui\DatePicker::widget([
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

    <button class='btn btn-danger'>ตกลง</button>
      
	<!-- ปุ่มพิมพ์ -->
<input class="btn btn-primary" 
       name="btnButton" 
       type="button" 
       value="🖨 พิมพ์รายงาน" 
       onclick="printGrid();">

<!-- พื้นที่ที่จะพิมพ์ -->
<div id="print-area">
    <?php
        // ใส่ตารางหรือเนื้อหาที่ต้องการพิมพ์
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'before'=>'<b style="color:#007acc;">Re-Admit</b> <b style="color:red"></b>',
                'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
        ]);
    ?>
</div>

<!-- สคริปต์พิมพ์ -->
<script>
function printGrid() {
    var printContents = document.getElementById("print-area").innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = "<html><head><title>Print</title></head><body>" 
        + printContents + "</body>";
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // โหลด DOM เดิมกลับมา
}
</script>

    <?php ActiveForm::end(); ?>
</div>


<p>
    <?= Html::a('⏪ กลับหน้าหลัก', ['referopd/index3'], [
        'class' => 'btn btn-custom'
    ]) ?>
</p>

<?php
$this->registerCss("
    /* กล่อง well */
    .custom-well {
        background: linear-gradient(145deg, #f0f9ff, #cce7ff);
        border-radius: 10px;
        border: 1px solid #b3d7ff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 15px;
    }

    /* ปุ่ม custom */
    .btn-custom {
        background: linear-gradient(to right, #3399ff, #007acc);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-custom:hover {
        background: linear-gradient(to right, #007acc, #005f99);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* ปุ่ม danger และ primary */
    .btn-danger {
        background: linear-gradient(to right, #ff6666, #cc0000);
        border: none;
        font-weight: bold;
    }
    .btn-primary {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        border: none;
        font-weight: bold;
    }
    .btn-danger:hover, .btn-primary:hover {
        opacity: 0.9;
    }
");
?>
