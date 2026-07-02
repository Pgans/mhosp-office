
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf">
<meta name="csrf-token" content="H8fGBExE4kDAPPaxO6jt_Wc55fBYTgWJJxw7MRLe1MZUo7E2fw60FqhwgMdp4oyRCGqAlzkebdYKUVR0Z-uZrg==">
   
 <!-- Font Awesome -->
 <link rel="stylesheet" href="../components/fontawesome-5.11.2/css/all.min.css">
<!-- <link href="/web/assets/b46b78dc/css/bootstrap.css" rel="stylesheet"> -->
<!-- Theme style -->
<link rel="stylesheet" href="../require/css/Themes.css">
<!-- <link href="/web/css/site.css" rel="stylesheet"></head> -->
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
    
        color: #1289D6; /* Replace with your desired color value */
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
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Meetingagenda;
use app\models\Agendaitem; // ต้องเชื่อมโยง Model "Agenda"
use app\models\Agendasubx; // ต้องเชื่อมโยง Model "Subagenda"

$title = $meetingAgenda->title;
$attime = $meetingAgenda->attime;
$date= $meetingAgenda->date;
$time = $meetingAgenda->time;
$ttt = $subagenda->sub_topic;

?>

<div style="text-align: center;">
    <img src="<?= Yii::getAlias('@web') ?>/uploads/Capture.PNG" alt="Image" style="width: 1300px; height: auto;">
</div>
<h1 align="center"><?= Html::encode($title) ?></h1> </br>
<h3 align="center">ครั้งที่:: <?= Html::encode($attime) ?></h3> </br>
<h3 align="center">วันที่:: <?= Html::encode($date) ?>  เวลา:: <?= Html::encode($time)?> </h3> 
<h6>*****************************************************************************************************************************</h6>
 

<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $meetingAgenda->getAgendaItems(),
    ]),
    'summary' => '', // กำหนดให้ไม่แสดงข้อความ "Showing"
    //'showHeader' => false, // ไม่แสดงส่วนหัวของ GridView
    'columns' => [
        [
            'attribute' => 'topic',
            'format' => 'raw',

            'value' => function ($model) {
                return '<span style="font-weight: bold; font-size: 20px; color: orange;">' . $model->topic . '</span>';
            },
        ],
        [
           // 'attribute' => 'discription',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width: 170px;'], // กำหนดความกว้าง
            'value' => function ($model) {
                return '<span style="font-weight: bold; font-size: 20px; color: #2E86C1 ;">' . $model->discription . '</span>';
            },
        ],
        [
            'format' => 'raw',
            'value' => function ($model) use ($meeting) {
                if ($model->agen_id === 1 || $model->agen_id === 2 || $model->agen_id === 3 || $model->agen_id === 4 || $model->agen_id === 5) {
                    $dataProvider = new \yii\data\ActiveDataProvider([
                        'query' => agendasubx::find()->where(['agenda_id' => $model->agen_id,'meeting_id' => $model->meetingAgenda->id]),
                    ]);
        
                    return \yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary' => '', // กำหนดให้ไม่แสดงข้อความ "Showing"
                        'showHeader' => false, // ไม่แสดงส่วนหัวของ GridView
                        'columns' => [
                            [
                                'attribute' => 'sub_topic',
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width: 300px;'], // กำหนดความกว้าง
                                'value' => function ($model) {
                                    return '<span style="font-weight; font-size: 16px; color: #1C2833;">' . $model->sub_topic . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'sub_description',
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width: 990px;'], // กำหนดความกว้าง
                                'value' => function ($model) {
                                    return '<span style="font-weight; font-size: 16px; color: #8E44AD;">' . $model->sub_description . '</span>';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {extra}', // เพิ่มเติมคอลัมน์ 'extra'
                                'buttons' => [
								
                                    'view' => function ($url, $model) {
                                        $extension = pathinfo($model->filename, PATHINFO_EXTENSION); // นำมาใช้ในการดึงนามสกุลไฟล์
                                        if (!empty($model->filename)) {
										// มีการแนบไฟล์ (สีเขียว)
										return Html::a('เอกสาร ' . $extension, ['agendasubx/view-file', 'id' => $model->sub_id], ['class' => 'btn btn-success', 'target' => '_blank']);
									} else {
										// ยังไม่มีการแนบไฟล์ (สีเหลือง)
										return Html::a('เอกสาร', null, ['class' => 'btn btn-warning disabled', 'target' => '_blank']);
									}
								},
                                    'extra' => function ($url, $model, $key) {
                                        return '<div class="btn-group float-right">
                                                    <a class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                        <span class="glyphicon glyphicon-option-vertical"></span>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                                        <!--' . Html::a('เอกสาร', ['agendasubx/view-file', 'id' => $model->sub_id], ['class' => 'btn btn-defualt', 'target'=>'_blank']) . ' </br> -->
                                                        ' . Html::a('แนบไฟล์', ['agendasubx/update', 'id' => $model->sub_id], ['class' => 'btn btn-defualt', 'target'=>'_blank']) . '
                                                        <!-- Add more submenu items as needed -->
                                                    </div>
                                                </div>';
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
]) ?>