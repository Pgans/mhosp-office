<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DriversSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'พนักงานขับรถ');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="drivers-index">
<div class="box box-primary box-solid">
        <div class="box-header" id="grad1">
            <h3 class="box-title"><i class="fa fa-user"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'เพิ่มข้อมูลพนักงานขับรถ'), ['create'], ['class' => 'btn btn-success btn-lg']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

           // 'driver_id',
            'driver_name',
            'telephone',
           // ['class' => 'yii\grid\ActionColumn'],
           ['class' => 'yii\grid\ActionColumn',
           'header'=>'คลิกดู',
           'headerOptions' => ['style' => 'width:15%'],
           'template'=>'<div class="btn-group btn-group-sm text-center" role="group"> {detail} {edit} {del} </div>',
           'buttons'=>[
               'detail' => function($url,$model,$key){
                   return Html::a('View',
                       ['view', 'id' => $model->driver_id],
                       ['class' => 'btn btn-warning'],
                       $url);
               },
               'edit' => function($url,$model,$key){
                   return Html::a('Edit',
                       ['update', 'id' => $model->driver_id],
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

        ],
    ]); ?>
</div>
