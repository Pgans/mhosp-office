<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\models\Agendaitem;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MeetingagendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'E-Meetings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <div class="box box-default box-solid" >
    <div class="box-header" id="grad01">
        <div class="box-title"><i class="fa fa-users" aria-hidden="true"></i> E-Meeting<small> MuangSamSib Hospital</small></div>
    </div>
    <div class="box-body">

     <!-- <p>
        <?= Html::a(Yii::t('app', 'Create Meetingagenda'), ['create'], ['class' => 'btn btn-success']) ?> 
         <?= Html::a('Run SQL File', ['site/run-sql'], ['class' => 'btn btn-primary']) ?> 
        </p> -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '', // กำหนดให้ไม่แสดงข้อความ "Showing"
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'title',
                'format' => 'raw', // Set format to 'raw' for HTML output
                'value' => function ($model) {
                    return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                },
            ],
            'attime',
            'date',
            'time',
            //'user',
            //'create_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
