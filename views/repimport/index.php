<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RepimportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Repimports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="repimport-index">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Repimport', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'auto_id',
            'rep',
            //'id',
            'train_id',
            'hn',
            'an',
            'pid',
            'fullname',
            'main',
            'regdate',
           // 'discharge',
            //'ins',
            //'pp',
            //'errorcode',
            'sub',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
