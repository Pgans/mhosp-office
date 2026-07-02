<?php

use yii\helpers\Html;
use kartik\grid\GridView; //composer require kartik-v/yii2-grid "dev-master"
use slavkovrn\prettyphoto\PrettyPhotoWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ห้องประชุม';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('เพิ่มห้องประชุม', ['create'], ['class' => 'btn btn-danger']) ?>
    </p>
    <div class="box box-primary box-solid">
        <div class="box-header">
            <div class="box-title"><?= $this->title ?></div>
        </div>
        <div class="box-body"> 
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'room_id',
                    ['attribute' => 'room_img',
                        'format' => 'html',
                        'value' => function($model) {
                            return PrettyPhotoWidget::widget([
                                        'id' => $model->room_img, // id of plugin should be unique at page
                                        'class' => 'img-thumbnail', // class of plugin to define a style
                                        'width' => '100px',
                                        //'height' => '100px', 
                                        'images' => [
                                            1 => [
                                                'src' => $model->photoViewer,
                                            //'title' => 'Image visible',
                                            ],
                                        /* 2 => [
                                          'src' => $model->photoViewer,
                                          'title' => 'Image visible',
                                          ], */
                                        ]
                            ]);
                        }, 'filter' => FALSE,
                    ],
                    'room_name',
                    //'room_size',
                    'room_seate',
					'is_cancel',
                    // 'room_description:ntext',
                    //'room_img',
                    //['class' => 'yii\grid\ActionColumn'],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}',
                        'buttons'=>[
                                    'view' => function($url,$model,$key){
                                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['room/view','id'=>$model->room_id]);
                                    }
                            ]
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons'=>[
                                    'view' => function($url,$model,$key){
                                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['room/update','id'=>$model->room_id]);
                                    }
                            ]
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
	<p>****0 = ยกเลิก  ****** ******  1 = เปิดให้จองได้*****</p>
</div>
