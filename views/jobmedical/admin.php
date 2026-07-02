<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'รายการส่งซ่อมเครื่องมือแพทย์');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobmedical-index">
<div class="box box-info  box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

   
    
    <p>
       
		<?= Html::button(Yii::t('app', 'เพิ่มรายการส่งซอม'), [
    'class' => 'btn btn-success btn-lg',
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
            'dateline',
            'send_by',
            'send_at',
            //'repair_by',
			[
                    'attribute' => 'updater.firstname', 
                    'filter' => false
                ],
            //'repair_at',
            //'repair_service',
            //'repair_cost',
            //'device_id',
            [
                    'attribute' => 'jstatus.status',
                    'format' => 'raw',
                    'label' => 'สถานะ',
                    'value' => function ($model) {
                        return '<span class="badge" style="background-color:' . $model->jstatus->color . '">' . $model->jstatus->status . '</span>'; 
                       // return empty($model->department) ? null : $model->department->dep_name;  
                    },
                    
                ],
            //'type_id',
            //'dep_id',

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
			 <a  class='btn btn-warning btn-ms' href="localhost/mhosp-office/web/index.php?r=rental/calendar"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าปฏิทิน</a>
            <a  class='btn btn-primary btn-ms' href="localhost/mhosp-office/web/index.php?r=rental/index"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าจอง</a>
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
