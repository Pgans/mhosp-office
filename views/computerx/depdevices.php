<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use frontend\models\Departments;

$this->title = "DEP_DEVICES";
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['reg/index']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุปกรณ์แยกตามแผนก';
?>
<b style = "color:blue">รายงานอุปกรณ์คอมพิวเตอร์แยกตามแผนก</b>
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
        <?php $form = ActiveForm::begin([ ]);
    echo Html::a('ทั้งหมด', ['computer/depdevice_all'], ['class' => 'btn btn-warning','target'=>'_blank']);
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>
        
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue ">รายงานอุปกรณ์คอมพิวเตอร์แยกตามแผนก</b><b style="color: red">(เครื่อง)</b>',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
    ]
  );

        ?>
        <div class="alert alert-danger">
            <?=$sql?>
        </div>
