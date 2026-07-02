<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;

$this->title = "RE-ADMIT";
$this->params['breadcrumbs'][] = 'รายงานเวชระเบียน';

$dateNow = date('Y-m-d'); // วันที่ปัจจุบัน
?>

<style>
    .date-box {
        border: 2px solid #9fa8da;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(103, 58, 183, 0.2);
        margin-bottom: 20px;
    }

    .date-box label {
        font-weight: bold;
        color: #3f51b5;
    }

    .date-picker-input {
        width: 150px;
        padding: 6px 10px;
        border: 1px solid #b39ddb;
        border-radius: 6px;
        box-shadow: 0 0 8px rgba(103, 58, 183, 0.1);
    }

    .btn-modern {
        border-radius: 8px;
        padding: 8px 20px;
        margin-right: 10px;
    }
</style>
<style>
.table-header-custom th {
    background-color: #7c90fc !important; /* สีม่วง-ฟ้า */
    color: white !important;
    text-align: center;
    font-weight: bold;
    border-color: #5c6bc0;
}
/* สี hover ของแถวใน GridView */
.table-hover tbody tr:hover {
    background-color: #b5c1ff !important;
}
</style>
<style>
/* กล่องช่วงเวลา */
.date-box {
    border: 2px solid #9fa8da;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 2px 8px rgba(103, 58, 183, 0.2);
    margin-bottom: 20px;
}

/* เส้นขอบ panel GridView สีเดียวกับ .date-box */
.custom-panel {
    border: 2px solid #9fa8da !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(103, 58, 183, 0.15);
}
</style>
<h3 class="text-primary mb-3"><i class="fas fa-hospital"></i> <b>Re-Admit28</b></h3>

<div class="date-box">
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'action' => ['readmit/readmit'],
    ]); ?>

    <div class="form-group d-flex align-items-center">
        <label class="mr-2">📅 วันที่ระหว่าง:</label>
        <?= DatePicker::widget([
            'name' => 'date1',
            'value' => $date1 ?? $dateNow,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'date-picker-input']
        ]) ?>

        <label class="ml-3 mr-2">ถึง:</label>
        <?= DatePicker::widget([
            'name' => 'date2',
            'value' => $date2 ?? $dateNow,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'date-picker-input']
        ]) ?>

        <?= Html::submitButton('🔍 ตกลง', ['class' => 'btn btn-danger btn-modern ml-3']) ?>
        <button type="button" class="btn btn-primary btn-modern" onclick="window.print();">🖨️ Print Results</button>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => '',
    'headerRowOptions' => ['class' => 'table-header-custom'],
    'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => '<b class="text-primary">Re-Admit</b> <b class="text-danger">(น้อยกว่า 28 วัน)</b>',
        'footer' => 'ประมวลผล: <code>' . date('Y-m-d H:i:s') . '</code>',
        'options' => ['class' => 'custom-panel'],  // ใส่ class สำหรับ panel container ตรงนี้
    ],
]);
?>

