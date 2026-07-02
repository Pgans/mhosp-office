<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
//use app\models\Oils;
use app\models\Mooban;
use app\models\Hospanamai;
use app\models\District;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderoilsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orderoils');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-info">
                        <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> ระบบบันทึกการเบิกน้ำมันเชื้อเพลิงควบคุมโรคทีมสอบสวนเคลื่อนที่เร็ว(SRRT)</h4></div>
                        <div class="panel-body">
                        <div class="row"> 

        <!--<?= Html::a(Yii::t('app', 'เพิ่มรายการจ่ายน้ำมันเชื้อเพลิง'), ['create'], ['class' => 'btn btn-success']) ?>  -->

       <?= Html::a('<i class="glyphicon glyphicon-plus"></i> เพิ่มรายการจ่ายน้ำมันเชื้อเพลิง', ['orderoils/create'], ['class' => 'btn btn-success btn-lg', 'id' => 'modalButton']) ?>

        
        <!-- <?= Html::a('PDF', ['pdf'], ['class' => 'btn btn-danger']) ?> -->
        
         <?php Modal::begin([
        'id' => 'modal',
        'header' => '<h4><a color-blue>CREATE ORDER_OILS</a></h4>',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-warning" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
        ?>
        
        <?php Pjax::begin()?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
       // 'columns' => $gridColumns,
        'pjax' => true,
        'striped' => true,
        'hover' => true,
        'panel' => ['type' => 'info', 'heading' => '<i class="glyphicon glyphicon-user"></i>ระบบบันทึกการเบิกน้ำมันเชื้อเพลิงควบคุมโรคทีมสอบสวนเคลื่อนที่เร็ว(SRRT)'],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        // 'panel'=>[
        //     'before'=>' '
        //         ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'oilorder_id', 
                'format' => 'raw',
                'label'=>'เลขสั่งจ่าย',
                'value' => function ($model) {
                    return "<font class='text-primary'>" . $model->oilorder_id . '</font>';
                    
             },
                'filter' => false

            ],
            // [
            //     'attribute'=> 'oilorder_id',
            //     'headerOptions' => ['style' => 'color:green'],
            //     'header' =>'รหัสใบสั่งจ่าย',
            //     'filter'=> false,
            // ],
            // [
            //     'attribute' => 'created_at',
            //     'format' => 'raw',
            //     'label'=>'วันที่จ่าย',
            //     'value' => function ($model) {
            //         return '<stype  class="badge" style="background-color:#009999">' . $model->created_at . '</stype>';
            //     },
            //     'filter'=> false
            // ],
    
            [
                'attribute'=>'created_at',
                'header'=>'วันที่จ่าย',
                'headerOptions' => ['style' => 'color:green'],
                'filter'=> false,
            ],
            
            [
                'attribute'=>'spray.spray_name',
                'label'=>'กิจกรรม',
                'headerOptions' => ['style' => 'color:green'],
                'filter' => false,
            ],
            [
                'attribute'=>'oils',
                'filter'=> false,
                'value'=>function($model){
                  return $model->oilsName;
                }
            ],
            [
                'attribute' => 'fullname', 
                'format' => 'raw',
                'label'=>'ชื่อ-สกุล',
                'value' => function ($model) {
                    return "<font class='text-warning'>" . $model->fullname . '</font>';
                    
             },
                'filter' => false

            ],
            // [
            //     'attribute' => 'diagnosis', 
            //     'format' => 'raw',
            //     'label'=>'รหัสโรค',
            //     'value' => function ($model) {
            //         return "<font class='text-info'>" . $model->diagnosis . '</font>';
                    
            //  },
            //     'filter' => false

            // ],
            [
                'attribute' => 'diagnosis',
                'format' => 'raw',
                'label'=>'รหัสโรค',
                'value' => function ($model) {
                    return '<stype  class="badge" style="background-color:#D2691E">' . $model->diagnosis . '</stype>';
                },
                'filter'=> false
            ],
            [
                'attribute'=>'province.PROVINCE_NAME',
                'headerOptions' => ['style' => 'color:green'],
                'filter' => false,
            ],
            
            [
                'attribute'=>'amphur.AMPHUR_NAME',
                'headerOptions' => ['style' => 'color:green'],
                'filter' => false,
            ],
            
            [
                'attribute'=>'district.DISTRICT_NAME',
                'headerOptions' => ['style' => 'color:green'],
                'filter' => false,
            ],
            [
                'attribute' => 'mooban_id',
                'label' => 'หมู่บ้าน',
                'value' => function($model) {
                        return empty($model->mooban) ? null : $model->mooban->mooban_name;
                 },
                'filter' => ArrayHelper::map(mooban::find()->asArray()->all(), 'mooban_id', 'mooban_name'),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => '<-ค้นหา->'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width'=>'100%'
                    ],
                ],
            ],
            // [
            //     'attribute' => 'mooban.mooban_id',
            //     'value'=> 'mooban.mooban_name',
            //     'filter' => Html::activeDropDownList($searchModel, 'mooban_id',
            //     ArrayHelper::map(mooban::find()->all(), 'mooban_id', 'mooban_name'),
            //     ['class' => 'form-control'])
            //   ],
            
            // [
            //     'attribute'=>'mooban_id',
            //     'value'=>'mooban.mooban_name',
            //     'header'=>'ค้นหาหมู่บ้าน',
            //     'headerOptions' => ['style' => 'color:red'],
            //     //'filter' => false,
            // ],
            [
                'attribute'=>'anamai.hospname',
                'headerOptions' => ['style' => 'color:green'],
                'filter' => false,
            ],
            [
                'attribute'=>'creater.firstname',
                'headerOptions' => ['style' => 'color:green'],
                'filter' => false,
            ],
            // [
            //     'attribute'=>'value',
            //     'format'=>'html',
            //     'value'=>function($model){
            //       return Html::a('<i class="glyphicon glyphicon-print"></i>',['report1', 'id' => $model->oilorder_id],['class'=>'btn-pdfprint btn btn-default','data-pjax'=>'0']);
            //     }
            //   ],
            //'d_update',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'options'=>['style'=>'width:150px;'],
                'buttonOptions'=>['class'=>'btn btn-default'],
                'template'=>'<div class="btn-group btn-group-sm text-center" role="group">{print}  {update}  </div>',
                'buttons'=>[
                  'print'=>function($url,$model){
                   // return Html::a('<i class="glyphicon glyphicon-print"></i>',['pdf/url'],['class'=>'btn-pdfprint btn btn-default','data-pjax'=>'0']);
              
                    return Html::a('<i class="glyphicon glyphicon-print"></i>', ['report1', 'id' => $model->oilorder_id],['class'=>'btn-pdfprint btn btn-default','data-pjax'=>'0','target'=>'_blank']);
                  }
                ]
              ],
          ],
      ]); ?>

           

    <? Pjax::end() ?>
</div>
