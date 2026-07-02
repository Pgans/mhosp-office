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
    <div class="box-header" id="grad8">
	<div class="box-title">
    <i class="fa fa-users"></i>  
    <span style="color: white; font-size: 20px;">E-Meeting</span>
    <small style="color: white; font-size: 18px;">MuangSamSib Hospital</small>
</div>
        
    </div>
    <div class="box-body">

     <p>
        <?= Html::a(Yii::t('app', '<i class="fa fa-plus" aria-hidden="true"></i></i> 1.เพิ่มหัวข้อประชุม'), ['create'], ['class' => 'btn btn-success btn-lg']) ?>
        <?= Html::a('<i class="fa fa-hourglass-end" aria-hidden="true"></i> 2.เพิ่มวาระการประชุม', ['meetingagenda/run-query'], ['class' => 'btn btn-primary btn-lg']) ?>  
        <?= Html::a('<i class="fa fa-hourglass-end" aria-hidden="true"></i> 3.เพิ่มหัวข่อย่อยการประชุม', ['meetingagenda/run-agendasubx'], ['class' => 'btn btn-warning btn-lg']) ?> 
        </p>
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
                    return Html::a(Html::encode($model->title), ['view_admin', 'id' => $model->id]);
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
