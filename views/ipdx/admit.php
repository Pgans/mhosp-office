<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'ADMITT';

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ipd/index']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ป่วยเบาหวานทั้้งหมดในเขตรับผิดชอบ';
?>

<?php            
echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'panel' => [
        //     'before'=>'รายงานผู้ป่วยนอนโรงพยาบาล(Admitt)',
        //     'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
        //     ],
        'columns'=>[
           // ['class'=>'yii\grid\SerialColumn'],
        [
            'attribute'=>'WARD',
            'label'=>'ตึกผู้ป่วย',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['WARD'] . "</font>"; 
                       },
        ],
		[
            'attribute'=>'HN',
            'label'=>'HN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
        [
            'attribute'=>'ADM_ID',
            'label'=>'AN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		[
            'attribute'=>'NAME',
            'label'=>'ชื่อ-สกุล',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
        [
            'attribute'=>'ADMITT',
            'label'=>'Admitt',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='FF9C33'>" . $model['ADMITT'] . "</font>"; 
                    }, 
        ],
        [
            'attribute'=>'DSC',
            'label'=>'Discharge',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='FF9C33'>" . $model['DSC'] . "</font>"; 
                    },
                    
        ],
		/*
        [
            'attribute'=>'P_DIAG',
            //'label'=>'ตึกผู้ป่วย',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'contentOptions' => ['style'=>'max-width:300px;'],
        ],
		*/
        [
            'attribute'=>'STATUS-Admit',
            'label'=>'สถานะ',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        ],
        [
            'attribute'=>'สมณะ',
            'label'=>'สมณะ',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        ],
        [
            'attribute'=> 'BED',
            'label'=>'เตียง',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        ],
        ]
    ]
  );

        ?>
        <div class="alert alert-info">
            <?=$sql?>
        </div>
