<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="import-index">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('เพิ่มไฟล์', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        ///'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
		   [
                    'attribute' => 'file', 
                    'filter' => false
            ],
            [
                    'attribute' => 'regdate', 
                    'filter' => false
            ],
           // 'regdate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
