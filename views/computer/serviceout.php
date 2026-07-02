<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;

$this->title = 'SERVICE_OUT';

$this->params['breadcrumbs'][] = ['label' => 'รายงานอุปกรณ์คอมพิวเตอร์', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานการส่งซ่อมภายนอก';
?>
<b>รายงานการส่งซ่อมภายนอก</b>

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
echo \kartik\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    //'summary' => "",
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
    'panel' => [
        'before' => 'รายงานการส่งซ่อมภายนอก (<b style="color: blue">SERVICE_OUT</b>)',
    //'type' => \kartik\grid\GridView::TYPE_SUCCESS,
    ],
    /* 'export' => [
        'showConfirmAlert' => false,
        'target' => GridView::TARGET_BLANK
    ],*/
    'hover' => true,
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
                    'attribute' => 'device_name',
                    'header' => 'ที่ตั้งอุปกรณ์' 
                ],
                [
                    'attribute' => 'category_name',
                    'header' => 'ประเภท'
                ],
                [
                    'attribute' => 'date_sent',
                    'header' => 'วันที่ส่งซ่อม'
                ],
               [
                    'attribute' => 'date_in',
                    'header'  => 'วันที่เสร็จ'
                ],
                [
                    'attribute' => 'price',
                    'header' => 'ราคา'
                ],
                [
                    'attribute' => 'orther',
                    'header' => 'อะไหล่เปลี่ยน'
                ],
                
            ]
        ]);
        ?>


    
 
 