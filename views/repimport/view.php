<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Repimport */

$this->title = $model->auto_id;
$this->params['breadcrumbs'][] = ['label' => 'Repimports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repimport-view">

    <?php $form = ActiveForm::begin() ?>

<div class="table-responsive">

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
           // 'label' => 'คำนำหน้า',
            'value' => function ($model) {
                return $model[0][0];
            }
        ],
        [
           // 'label' => 'ชื่อ นามสกุล',
            'value' => function ($model) {
                return $model[0][1];
            }
        ],
        [
           // 'label' => 'เพศ',
            'value' => function ($model) {
                return $model[0][2];
            }
        ],
        [
            'label' => 'สัญชาติ',
            'value' => function ($model) {
                return $model[0][3];
            }
        ],
        [
            'label' => 'หมายเลขบัตรประชาชน',
            'value' => function ($model) {
                return $model[0][4];
            }
        ],
        [
            'label' => 'วันเดือนปีเกิด',
            'value' => function ($model) {
                return $model[0][5];
            }
        ],
        [
            'label' => 'อายุ',
            'value' => function ($model) {
                return $model[0][6];
            }
        ],
        [
            'label' => 'หน่วยอายุ(ปีเดือนวัน)',
            'value' => function ($model) {
                return $model[0][7];
            }
        ],
        [
            'label' => 'HN',
            'value' => function ($model) {
                return $model[0][8];
            }
        ],
        [
            'label' => 'AN',
            'value' => function ($model) {
                return $model[0][9];
            }
        ],
        [
            'label' => 'Ward',
            'value' => function ($model) {
                return $model[0][10];
            }
        ],
        [
            'label' => 'Doctor',
            'value' => function ($model) {
                return $model[0][11];
            }
        ],
        [
            'label' => 'Clinical Diagnosis',
            'value' => function ($model) {
                return $model[0][12];
            }
        ],
        [
            'label' => 'Collected at',
            'value' => function ($model) {
                return $model[0][13];
            }
        ],
        [
            'label' => 'สถานะ',
            'format' => 'raw',
            'value' => function($model){
                //Implement later
                return '<span class="label label-success">ข้อมูลสมบูรณ์</span>';
            }
        ],
        [
            'label' => 'ต้องการส่ง',
            'format' => 'raw',
            'value' => function ($model){
                return Html::dropDownList('register[]', null,
                    [
                        'CS' => 'ส่งปรึกษา (Consult (CS))',
                        'SN' => 'ส่งตรวจชิ้นเนื้อ (Surgical (SN))',
                        'PN' => 'ส่งตรวจวิเคราะห์เซลล์ (PAP)',
                        'FN' => 'ส่งตรวจวิเคราะห์เซลล์ (Non-Gyn)',
                        'EX' => 'ส่งย้อมพิเศษ (EX)',
                        'FI' => 'ส่งตรวจ FISH',
                        'FL' => 'ส่งตรวจ FLOW',
                        'MP' => 'ส่งตรวจ Molecular',
                    ],
                    ['class' => 'form-control', 'prompt' => 'เลือกรายการส่งตรวจ']);
            }
        ],
    ]
]) ?>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> กลับ', ['/repimport/index'], ['class' => 'btn btn-danger'])?>
    </div>
    <div class="col-sm-6 text-right">
        <?= Html::submitButton('<i class="glyphicon glyphicon-arrow-up"></i> นำเข้าข้อมูล', ['class' => 'btn btn-warning btn-lg']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>

<h2>รายการที่มีการนำเข้าแล้ว</h2>
<div class="table-responsive">
    <?=GridView::widget([
        'dataProvider' => $dataProviderImport,
        'columns' => [
            'auto_id',
            'rep',
            'id',
            'hn',
            'pid',
            'an',
            'ins',
            'fullname',
            'pp',
            'ins',
            'main',
            'sub',
            //'clinical_diagnosis',
           //'collected_at',
           // 'regist_type',
            //'created_at:datetime',
        ]
    ])?>
</div>

<div class="alert alert-info">
    <i class="glyphicon glyphicon-info-sign"></i> ประเภทการส่งตรวจ
    <ul>
        <li>SN ส่งตรวจชิ้นเนื้อ (Surgical)</li>
        <li>PN ส่งวิเคราะห์เซลล์ (PAP Smear)</li>
        <li>FN ส่งวิเคราะห์เซลล์ (Non-Gyn)</li>
        <li>CS ส่งปรึกษา (Consult)</li>
        <li>FI ส่งวิเคราะห์ FISH</li>
        <li>FL ส่งวิเคราะห์ FLOW</li>
        <li>MP ส่งวิเคราะห์ Molecular</li>
        <li>FS ส่งวิเคราะห์ Frozen Section</li>
        <li>EM ส่งตรวจ Electro Microscopy</li>
    </ul>
</div>