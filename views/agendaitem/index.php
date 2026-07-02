<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AgendaitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Agendaitems');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendaitem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Agendaitem'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'agenda_id',
            'meeting_agenda_id',
           // 'ref',
            'topic',
            'discription:ntext',
            //'covenant',
            //'docs',
            'create_date',
            //'view',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
