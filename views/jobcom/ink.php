<?php
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->title = 'OPERATION';

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->params['breadcrumbs'][] = 'รายงานนับหัตถการแพทย์ทางเลือก';
?>
<br>
<b style="color:blue">รายงานนับหัตถการแพทย์ทางเลือกในโรงพยาบาล</b>
<div class='well'>
    <?php $form = ActiveForm::begin(); ?>
     ระหว่างวันที่:
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
        <?php $form = ActiveForm::begin([ ]);
    echo Html::a('รายเดือน', ['thaimed/op_count'], ['class' => 'btn btn-success', 'id'=>'modalButton','target'=>'_blank']);
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>

<?php Pjax::begin(); ?>
<?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานนับหัตถการแพทย์ทางเลือกในโรงพยาบาล '
            ],
    ]
  );
        ?>
        <?php Pjax::end() ?>
    </div>