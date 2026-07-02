
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf">
<meta name="csrf-token" content="H8fGBExE4kDAPPaxO6jt_Wc55fBYTgWJJxw7MRLe1MZUo7E2fw60FqhwgMdp4oyRCGqAlzkebdYKUVR0Z-uZrg==">
   
    
<link href="/web/assets/b46b78dc/css/bootstrap.css" rel="stylesheet">
<link href="/web/css/site.css" rel="stylesheet"></head>
<body>
<!-- by toei change color navbar menu -->
<style type="text/css">
    .navbar {
        background-color:#00008B;
        background-image: none;
    }
    h1 
    {
        color: #0A73AD; /* Replace with your desired color value */
    }
    h2 
    {
        background-color: #DCF7D7; /* Replace with your desired background color value */
        color: #0FB179; /* Replace with your desired color value */
    }
    h3
    {
        color: #007C80; /* Replace with your desired color value */
    }
    h4
    {
        color: #7D0552; /* Replace with your desired color value */
    }
    h5
    {
        color: #A954D1; /* Replace with your desired color value */
    }
    h6
    {
        background-color: #D4DAD3; /* Replace with your desired background color value */
        color: #D4DAD3; /* Replace with your desired color value */
    }
</style>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $meetingAgenda->title;
$attime = $meetingAgenda->attime;
$date= $meetingAgenda->date;
$time = $meetingAgenda->time;
//$this->params['breadcrumbs'][] = ['label' => 'Meeting Agendas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<h1 align="center"><?= Html::encode($this->title) ?></h1> </br>
<h3 align="center">ครั้งที่:: <?= Html::encode($attime) ?></h3> </br>
<h3 align="center">วันที่:: <?= Html::encode($date) ?>  เวลา:: <?= Html::encode($time)?> </h3> 
<h6>*****************************************************************************************************************************</h6>

<?php
$agenda3DataProvider = new \yii\data\ActiveDataProvider([
    'query' => $meetingAgenda->getAgendasub()->where(['agenda_id' => 3])->limit(1),
    'sort' => [
        'attributes' => ['meeting_id'],
    ],
]);

$agenda4DataProvider = new \yii\data\ActiveDataProvider([
    'query' => $meetingAgenda->getAgendasub()->where(['agenda_id' => 4]),
    'sort' => [
        'attributes' => ['meeting_id'],
    ],
]);

echo \yii\grid\GridView::widget([
    'dataProvider' => $agenda3DataProvider,
    'summary' => '',
    'columns' => [
        [
            'attribute' => 'วาระการประชุม',
            'format' => 'raw',
            'value' => function ($model) {
                return '<span style="font-weight: bold; font-size: 20px; color: orange;">' . $model->agendaItems->topic . '</span>';
            },
        ],
        [
            'format' => 'raw',
            'value' => function ($model) {
                return '<span style="font-weight: bold; font-size: 20px; color: orange;">' . $model->agendaItems->discription . '</span>';
            },
        ],
        [
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->agenda_id === 3) {
                    $subAgendaDataProvider = new \yii\data\ActiveDataProvider([
                        'query' => agendasubx::find()->where(['agenda_id' => $model->agenda_id, 'meeting_id' => $model->meeting_id]),
                    ]);

                    return \yii\grid\GridView::widget([
                        'dataProvider' => $subAgendaDataProvider,
                        'summary' => '',
                        'columns' => [
                            [
                                'attribute' => 'sub_topic',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight: bold; font-size: 16px; color: #1C2833;">' . $model->sub_topic . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'sub_description',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight: bold; font-size: 16px; color: #8E44AD;">' . $model->sub_description . '</span>';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {download}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        $extension = pathinfo($model->filename, PATHINFO_EXTENSION);
                                        return Html::a('เอกสาร ' . $extension, ['agendasubx/view-file', 'id' => $model->sub_id], ['class' => 'btn btn-success', 'target' => '_blank']);
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('แก้ไข', ['agendasubx/update', 'id' => $model->sub_id], ['class' => 'btn btn-warning']);
                                    },
                                ],
                            ],
                        ],
                    ]);
                } elseif ($model->agenda_id === 4) {
                    $subAgendaDataProvider = new \yii\data\ActiveDataProvider([
                        'query' => agendasubx::find()->where(['agenda_id' => $model->agenda_id, 'meeting_id' => $model->meeting_id]),
                    ]);

                    return \yii\grid\GridView::widget([
                        'dataProvider' => $subAgendaDataProvider,
                        'summary' => '',
                        'columns' => [
                            [
                                'attribute' => 'sub_topic',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight: bold; font-size: 16px; color: #1C2833;">' . $model->sub_topic . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'sub_description',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight: bold; font-size: 16px; color: #8E44AD;">' . $model->sub_description . '</span>';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {download}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        $extension = pathinfo($model->filename, PATHINFO_EXTENSION);
                                        return Html::a('เอกสาร ' . $extension, ['agendasubx/view-file', 'id' => $model->sub_id], ['class' => 'btn btn-success', 'target' => '_blank']);
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('แก้ไข', ['agendasubx/update', 'id' => $model->sub_id], ['class' => 'btn btn-warning']);
                                    },
                                ],
                            ],
                        ],
                    ]);
                } else {
                    return '';
                }
            },
        ],
    ],
]);
?>
