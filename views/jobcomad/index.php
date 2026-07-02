<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Jobstatus;
use app\models\departmentjob;
use kartik\widgets\Select2;
use yii\base\Model;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\models\Jobtype;

$this->title = Yii::t('app', 'รายการส่งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา  Admin');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobcom-index">
<div class="box box-info  box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

   
    
    <p>
       
		<?= Html::button(Yii::t('app', 'เพิ่มรายการส่งซอม Admin'), [
    'class' => 'btn btn-danger btn-lg',
    'id' => 'createButton'
]) ?>

    </p>
	<?php
Modal::begin([
    'header' => '<h4>เพิ่มรายการส่งซอม</h4>',
    'id' => 'createModal',
    'size' => 'modal-lg',
]);

echo "<div id='createContent'></div>";

Modal::end();
?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'detail',
            //'dateline',
            'send_by',
            
            //'repair_by',
			[
                    'attribute' => 'updater.firstname', 
                    'filter' => false
                ],
            //'repair_at',
            //'repair_service',
            //'repair_cost',
            //'device_id',
			'send_at',
			'repair_at',
		    'repair_service',
			  [
                     'attribute' => 'type_id',
                     'value'=> 'type.type',
                     'filter' => Html::activeDropDownList($searchModel, 'type_id',
                    ArrayHelper::map(Jobtype::find()->all(), 'id', 'type'),
                     ['class' => 'form-control'])
                  ],
			 
			 [
                    'attribute' => 'jstatus.id',
                    'value'=> 'jstatus.status',
                    'filter' => Html::activeDropDownList($searchModel, 'jstatus_id',
                    ArrayHelper::map(jobstatus::find()->all(), 'id', 'status'),
                    ['class' => 'form-control'])
                  ],
				  
				  /*
            [
                    'attribute' => 'jstatus.status',
                    'format' => 'raw',
                    'label' => 'สถานะ',
                    'value' => function ($model) {
                        return '<span class="badge" style="background-color:' . $model->jstatus->color . '">' . $model->jstatus->status . '</span>'; 
                       // return empty($model->department) ? null : $model->department->dep_name;  
                    },
                    
                ],
				*/
            //'type_id',
            //'dep_id',
			 [
            'label' => 'ใช้เวลา',
            'value' => function ($model) {
                if ($model->repair_at && $model->send_at) {
                    $repairAt = new DateTime($model->repair_at);
                    $sendAt = new DateTime($model->send_at);
                    $interval = $repairAt->diff($sendAt);
                    return $interval->format('%d วัน %h ชั่วโมง %i นาที');
                }
                return null;
            },
        ],
           // ['class' => 'yii\grid\ActionColumn'],
		    ['class' => 'yii\grid\ActionColumn',
            'header'=>'คลิกดู',
            'headerOptions' => ['style' => 'width:15%'],
            'template'=>'<div class="btn-group btn-group-sm text-center" role="group"> {detail} {edit} {del} </div>',
            'buttons'=>[
                'detail' => function($url,$model,$key){
                    return Html::a('View',
                        ['view', 'id' => $model->id],
                        ['class' => 'btn btn-warning'],
                        $url);
                },
                'edit' => function($url,$model,$key){
                    return Html::a('Edit',
                        ['update', 'id' => $model->id],
                        ['class' => 'btn btn-success'],
                        $url);
                },
                // 'del' => function($url,$model,$key){
                //     return Html::a('ลบ',
                //         ['delete', 'id' => $model->id],
                //         ['class' => 'btn btn-danger'],
                //         $url);
                // },
            ],
			],
        ],
    ]); ?>
</div>
<p>
             <?= Html::a('<class="box-title"><i class="glyphicon glyphicon-print"></i> พิมพ์เอกสาร', ['print', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
			 <a  class='btn btn-warning btn-ms' href="localhost/mhosp-office/web/index.php?r=jobcom/calendar"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าปฏิทิน</a>
            <a  class='btn btn-primary btn-ms' href="localhost/mhosp-office/web/index.php?r=jobcom/index"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าจอง</a>
            </p>
			
			<?php
			$this->registerJs("
				$(function() {
					$('#createButton').click(function() {
						$('#createModal').modal('show')
							.find('#createContent')
							.load('" . \yii\helpers\Url::to(['create']) . "');
					});
				});
			");
			?>
