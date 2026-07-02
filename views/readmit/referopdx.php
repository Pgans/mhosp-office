<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = "Refer-ER";
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['reg/index']];
$this->params['breadcrumbs'][] = 'รายงานเวชระเบียน';
?>
<b style = "color:blue">Refers-ER</b>
<div class='well'>
     <?php $form = ActiveForm::begin([
    'method' => 'POST',
    'action' => ['readmit/referopd'],
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
		<input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">

    <?php ActiveForm::end(); ?>
	
</div>
<?php //echo $sql;?>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<b style="color:blue ">Refers-ER มากว่า 2 ชั่วโมงและตัด Visit ผู้ป่วยในออก</b><b style="color: red">()</b>',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
    ]
  );

        ?>
        <div class="alert alert-warning">
            <?=$sql?>
        </div>
