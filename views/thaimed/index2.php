<?php
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
//use dosamigos\datepicker\DatePicker;

$this->title = 'สมุนไพรกัญชา';

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->params['breadcrumbs'][] = 'รายงานการจ่ายยาสมุนไพรกัญชา';
?>
<br>
<b style="color:blue">รายงานการจ่ายยาสมุนไพรกัญชา</b>
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
    <?php ActiveForm::end(); ?>
  
<?php Pjax::begin(); ?>
<?php echo GridView::widget([
		  'tableOptions' => [
			'class' => 'table table-striped table-hover',
			'width'=>'100%',
			'cellspacing'=> '0'
			],
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานการจ่ายยาสมุนไพรกัญชา '
            ],
			'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                  
                    [
                        'attribute' => 'regdate',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'วันรับบริการ',
                    ],
                    [
                        'attribute' => 'hn',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'เลข รพ.',
                    ],
					[
                        'attribute' => 'fullname',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ชื่อ-สกุล',
                    ],
					[
                        'attribute' => 'inscl_name',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'สิทธิ์การรักษา',
                    ],
					[
                        'attribute' => 'Diag_primary',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'รหัสโรคหลัก',
                    ],
					[
                        'attribute' => 'Diag_other',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'รหัสโรคร่วม',
                    ],
					[
                        'attribute' => 'Drug',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'รหัสยา',
                    ],
					[
                        'attribute' => 'drug_name',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ชื่อยา',
                    ],
					]
    ]
  );
        ?>
        <?php Pjax::end() ?>
    </div>
	 <div class="alert alert-info"><?=$sql?> </div>