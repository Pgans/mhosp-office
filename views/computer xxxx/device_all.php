
<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = 'DEVICE-ALL';

$this->params['breadcrumbs'][] = ['label' => 'รายงานอุปกรณ์คอมพิวเตอร์', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุกรณ์ซื้อใหม่';
?>
<b>รายงานจำนวนอุกรณ์ซื้อใหม่</b>
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
            'before'=>'รายงานจำนวนอุกรณ์ซื้อใหม่',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'catid',
            'header' => 'รหัสประเภท',
        ],
        [
            'attribute' => 'category_name',
            'format' => 'raw',
            'value' => function($model) {
                $catid = $model['catid'];
                $name = $model['category_name'];
                return Html::a(Html::encode($name), ['computer/devicelist59', 'catid' => $catid],
                ['target' => '_blank']);
            }
                ],
                        [
                    'attribute' => 'amount',
                    'header' => 'จำนวน(เครื่อง)'
                ],
                        ]
       ] )


        ?>
            
         