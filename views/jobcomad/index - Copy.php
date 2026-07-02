<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JobcomadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Jobcomads');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobcomad-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Jobcomad'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            //'repair_at',
            //'repair_service',
            //'repair_cost',
            //'device_id',
            //'jstatus_id',
            //'type_id',
            //'dep_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
