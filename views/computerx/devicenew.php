
<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->params['breadcrumbs'][] = ['label' => 'รายงานอุปกรณ์คอมพิวเตอร์', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุปกรณ์คอมพิวเตอร์';
?>
<b>รายการอุปกรณ์คอมพิวเตอร์</b>

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
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานจำนวนอุปกรณ์คอมพิวเตอร์',
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
                    'attribute' => 'due_date',
                    'header'  => 'วันครบกำหนด'
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