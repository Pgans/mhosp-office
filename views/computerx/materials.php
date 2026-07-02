<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = "MATERIALS";
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['reg/index']];
$this->params['breadcrumbs'][] = 'รายการซื้อหมึก-วัสดุคอมพิวเตอร์';
?>
<b style = "color:blue">รายการซื้อหมึก-วัสดุคอมพิวเตอร์</b>
<div class='well'>
     <?php $form = ActiveForm::begin([
    'method' => 'POST',
    'action' => ['computer/materials'],
]); ?>
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
            'before'=>'<b style="color:blue ">รายการซื้อหมึก-วัสดุคอมพิวเตอร์</b><b style="color: red">(ชิ้น)</b>',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
    ]
  );

        ?>
        <div class="alert alert-danger">
            <?=$sql?>
        </div>
