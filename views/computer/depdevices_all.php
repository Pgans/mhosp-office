<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use frontend\models\Departments;

$this->title = "DEP_DEVICES";
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['reg/index']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุปกรณ์แยกตามแผนกทั้งหมด';
?>
<b style = "color:blue">รายงานอุปกรณ์คอมพิวเตอร์ทั้งหมด</b>
<div class='well'>
     <?php $form = ActiveForm::begin([
    'method' => 'POST',
    'action' => ['computer/depdevices'],
]); ?>
        <?php
        $items = ArrayHelper::map(Departments::find()->all(), 'dep_id', 'dep_name');
        echo Html::dropDownList('depid', $depid, $items, ['prompt' => '--- หน่วยบริการ ---']);
        ?>
        <button class='btn btn-success'> ตกลง </button>
      <?php ActiveForm::end(); ?>
</div>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue ">รายงานอุปกรณ์คอมพิวเตอร์ทั้งหมด</b><b style="color: red">(เครื่อง)</b>',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
    ]
  );

        ?>
        <div class="alert alert-danger">
            <?=$sql?>
        </div>
