<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;


$this->title = 'DHF';

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['dhf/index']];
$this->params['breadcrumbs'][] = 'รายงานdhf-lepto';
?>

<?php            
echo GridView::widget([
        'dataProvider' => $dhfdataProvider,
         'panel' => [
             'before'=>'รายงานผู้ป่วยนอนโรงพยาบาล(Admitt)',
             'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
             ],
        'columns'=>[
           // ['class'=>'yii\grid\SerialColumn'],
        [
            'attribute'=>'regdate',
            'label'=>'วันมา',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['regdate'] . "</font>"; 
                       },
        ],
        [
            'attribute'=>'diag',
            'label'=>'โรค',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='ff4136'>" . $model['diag'] . "</font>"; 
                       },
             'contentOptions' => ['style'=>'max-width:50px;'],

        ],
        [
            'attribute'=>'fullname',
            'label'=>'ชื่อ-สกุล',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['fullname'] . "</font>"; 
                       },
        ],
        [
            'attribute'=>'hn',
           // 'label'=>'AN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		[
            'attribute'=>'home',
            'label'=>'เลขที่',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		[
            'attribute'=>'บ้าน',
           // 'label'=>'AN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		[
            'attribute'=>'ตำบล',
           // 'label'=>'AN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		[
            'attribute'=>'อำเภอ',
           // 'label'=>'AN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		[
            'attribute'=>'จังหวัด',
           // 'label'=>'AN',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            
        ],
		/*
        [
            'attribute'=>'ที่อยู่ตามบัตรประชาชน',
            //'label'=>'Admitt',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'contentOptions' => ['style'=>'max-width:300px;'],
            'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='FF9C33'>" . $model['ที่อยู่ตามบัตรประชาชน'] . "</font>"; 
                    }, 
        ],
		*/
        [
            'attribute'=>'ที่อยู่ญาติ',
            //'label'=>'Admitt',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'contentOptions' => ['style'=>'max-width:300px;'],
        ],
        [
            'attribute'=>'สถานะรับบริการ',
            'label'=>'แผนก',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,         
        ],
        [
            'attribute'=>'Admit',
            //'label'=>'ตึกผู้ป่วย',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
           // 'contentOptions' => ['style'=>'max-width:300px;'],
        ],
        [
            'attribute'=>'Discharge',
            //'label'=>'สถานะ',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        ],
        [
            'attribute'=>'Refer',
            //'label'=>'สมณะ',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        ],
        [
            'attribute'=> 'สิทธิ์การรักษา',
           // 'label'=>'เตียง',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'contentOptions' => ['style'=>'max-width:200px;'],
        ],
        ]
    ]);

        ?>
        <!-- <div class="alert alert-info">
            <?=$sql?>
        </div> -->
