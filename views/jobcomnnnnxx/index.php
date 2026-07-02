<?php

//use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use app\models\Jobtype;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\Jstatus;
use app\models\departmentjob;//
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JobcomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ระบบการแจ้งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-info">
                        <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> ระบบการแจ้งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา</h4></div>
                        <div class="panel-body">
    <p>
        <?= Html::a(Yii::t('app', 'แจ้งส่งซ่อมคอมและโสตทัศนศึกษา'), ['create'], ['class' => 'btn btn-success']) ?>
   
        <?= Html::a(Yii::t('app','เมื่อบันทึกระบบจะส่งเข้าไลน์ถึงผู้รับผิดชอบทันที....ขอบคุณครับ'))?>
    </p>
    <?php Modal::begin([
        'id' => 'modal',
        'header' => '<h4><a color-blue>CREATE JOBCOM</a></h4>',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
        ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showPageSummary' => true,
     //'pjax' => true,
    // 'striped' => true,
    // 'hover' => true,
    // 'panel' => ['type' => 'info', 'heading' => '<i class="glyphicon glyphicon-user"></i>ระบบการแจ้งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา'],
    // 'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id', 
                'filter' => false
            ],
            [
                'attribute' => 'detail', 
                'filter' => false
            ],
            [
                'attribute' => 'send_by', 
                'format' => 'raw',
                'label'=>'ผู้แจ้งซ่อม',
                'value' => function ($model) {
                    return "<font class='text-primary'>" . $model->send_by . '</font>';
                    
             },
                'filter' => false

            ],
            
            
           
             [
                 'attribute' => 'department.dep_id',
                 'value'=> 'department.dep_name',
                // 'filter' => Html::activeDropDownList($searchModel, 'dep_id',
               //  ArrayHelper::map(Departmentjob::find()->all(), 'dep_id', 'dep_name'),
                // ['class' => 'form-control'])
               ],
            //   [
            //     'attribute' => 'dateline', 
            //     'format' => 'raw',
            //     'label'=>'วันที่ต้องการ',
            //     'value' => function ($model) {
            //        // return '<font class="text-info">' . $model->dateline . '</font>';
            //        return '<stype  class="badge" style="background-color:#999900">' . $model->dateline . '</stype>';
            //  },
            //     'filter' => false

            // ],
            //   [
            //     'attribute' => 'send_at',
            //     'format' => 'raw',
            //     'label'=>'วันที่แจ้ง',
            //     'value' => function ($model) {
            //         return '<stype  class="badge" style="background-color:#009999">' . $model->send_at . '</stype>';
            //     },
            //     'filter'=> false
            // ],
            [
                'attribute' => 'dateline', 
                'format' => 'raw',
                'label'=>'วันที่ต้องการ',
                'value' => function ($model) {
                    return "<font class='text-warning'>" . $model->dateline . '</font>';
                    
             },
                'filter' => false

            ],
            [
                'attribute' => 'send_at', 
                'format' => 'raw',
                'label'=>'วันทีแจ้ง',
                'value' => function ($model) {
                    return "<font class='text-success'>" . $model->send_at . '</font>';
                    
             },
                'filter' => false

            ],
              
            'updater.firstname',
            //'repair_by',
            //'repair_at',
            //'repair_service',
            //'repair_cost',
            //'device.device_name',
            [
                'attribute' => 'jstatus.status',
                'format' => 'raw',
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return '<span class="badge" style="background-color:' . $model->jstatus->color . '">' . $model->jstatus->status . '</span>';
                    
                },
            ],
            // [ // แสดงข้อมูลออกเป็นสีตามเงื่อนไข
            //     'attribute' => 'jstatus_id',
            //     'format'=>'html',
            //     'value'=>function($model, $key, $index, $column){
            //       return $model->jstatus_id ? "<span  style=\"color:#008800;\">ซ่อมเสร็จ</span>":"<span style=\"color:#3366ff;\">แจ้งซ่อม</span>";
            //       //  :"<span style=\"color:#3366ff;\"></span>";
            //     },
            //     'filter'=> false
            //   ],
            //'jstatus.status',
            'type.type',

          //  ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end() ?> 
</div>





