<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = 'SERVICE_OUT';

$this->params['breadcrumbs'][] = ['label' => 'รายงานอุปกรณ์คอมพิวเตอร์', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานการจำหน่ายอุปกรณ์ที่ชำรุด';
?>
<b>รายงานการจำหน่ายอุปกรณ์ที่ชำรุด</b>
<div class='well'>
    <?php $form = ActiveForm::begin(); ?>
     วันที่ระหว่าง:
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

    <?php ActiveForm::end(); ?>
</div>
<?php //echo $sql;?>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานการส่งซ่อมภายนอก',
],
    //'hover' => true,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
        ]
        ,
                [
                    'attribute' => 'device_serial',
                    'header' => 'หมายเลขครุภัณฑ์'
                ],
                [
                    'attribute' => 'dep_name',
                    'header' => 'แผนก'
                ],
                [
                    'attribute' => 'category_name',
                    'header' => 'ประเภท'
                ],
               [
                    'attribute' => 'purchase_date',
                    'header'  => 'วันซื้อ'
                ],
                [
                    'attribute' => 'sale_date',
                    'header'  => 'วันจำหน่าย'
                ],
                [
                    'attribute' => 'price',
                    'header' => 'ราคา'
                ],
                [
                    'attribute' => 'spec',
                    'header' => 'รุ่น/ยี่ห้อ'
                ],
                
            ]
        ]);
        ?>
