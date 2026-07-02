
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
use app\models\Agendasubs; // ต้องเชื่อมโยง Model "Subagenda"

$title = $meetingAgenda->title;
$attime = $meetingAgenda->attime;
$date= $meetingAgenda->date;
$time = $meetingAgenda->time;
$ttt = $subagenda->sub_topic;

?>

<div style="text-align: center;">
    <img src="<?= Yii::getAlias('@web') ?>/images/Capture.PNG" alt="Image" style="width: 1300px; height: auto;">
</div>
<h1 align="center"><?= Html::encode($title) ?></h1> </br>
<h3 align="center">ครั้งที่:: <?= Html::encode($attime) ?></h3> </br>
<h3 align="center">วันที่:: <?= Html::encode($date) ?>  เวลา:: <?= Html::encode($time)?> </h3> 
<h6>*****************************************************************************************************************************</h6>
 

<!-- <h3>Agenda Items:</h3>
<ul>
    <?php foreach ($meetingAgenda->agendaItems as $agendaItems) : ?>
        <li>
            <h2><?= Html::encode($agendaItems->topic) ?>: </h2>
            <h3><?= Html::encode($agendaItems->discription) ?>:</h3>
            <?php if ($meetingAgenda->id === 3): ?>
                <h4>รายการ Subagenda ที่เกี่ยวข้อง:</h4>
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => $agendaItems->getSubagendas()->where(['agenda_id' => 3]),
                    ]),
                    'columns' => [
                        'sub_topic',
                        // เพิ่มคอลัมน์อื่น ๆ ของ subagenda ที่คุณต้องการแสดง
                    ],
                ]) ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul> -->

<!-- <h2>รายการวาระการประชุม</h2> -->

<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $meetingAgenda->getAgendaItems(),
    ]),
    'summary' => '', // กำหนดให้ไม่แสดงข้อความ "Showing"
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
            'value' => function ($model) {
                return '<span style="font-weight: bold; font-size: 20px; color: #2E86C1 ;">' . $model->discription . '</span>';
            },
        ],
        [
           // 'label' => 'รายการ Subagenda',
            'format' => 'raw',
            'value' => function ($model) use ($meeting) {
                if ($model->agenda_id === 3) {
                    $dataProvider = new \yii\data\ActiveDataProvider([
                        'query' => agendasubs::find()->where(['agenda_id' => $model->agenda_id]),
                    ]);

        
                    return \yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary' => '', // กำหนดให้ไม่แสดงข้อความ "Showing"
                        'columns' => [
                            [
                                'attribute' => 'sub_topic',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight; font-size: 16px; color: #1C2833  ;">' . $model->sub_topic . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'sub_description',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight; font-size: 16px; color: #8E44AD ;">' . $model->sub_description . '</span>';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {download}', // เพิ่มปุ่ม download and view
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                    
                                        // ... Existing code for the 'view' button ...
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('แก้ไข', ['agendasubs/update', 'id' => $model->sub_id], ['class' => 'btn btn-warning']);
                                    },
                                    'download' => function ($url, $model) {
                                        $fileExtension = pathinfo($model->filename, PATHINFO_EXTENSION);
                                        $isPdf = strtolower($fileExtension) === 'pdf';
                                        if (!$isPdf) {
                                            $linkText = 'เอกสาร (' . strtoupper($fileExtension) . ')';
                                        
                                            return Html::a($linkText, ['agendasubs/download', 'id' => $model->sub_id, 'filename' => $model->filename], ['class' => 'btn btn-success ']);
                                           
                                        } else {
                                            return Html::a('View', ['agendasubs/view', 'id' => $model->sub_id], ['class' => 'btn btn-primary']) . ' (PDF)';
                                        }
                                    },
                                ],
                            ],
                        ],
                    ]);
                } elseif ($model->agenda_id === 4) {
                    $dataProvider = new \yii\data\ActiveDataProvider([
                        'query' => agendasubs::find()->where(['agenda_id' => $model->agenda_id]),
                    ]);

        
                    return \yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary' => '', // กำหนดให้ไม่แสดงข้อความ "Showing"
                        'columns' => [
                            [
                                'attribute' => 'sub_topic',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight; font-size: 16px; color: #1C2833  ;">' . $model->sub_topic . '</span>';
                                },
                            ],
                            [
                                'attribute' => 'sub_description',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span style="font-weight; font-size: 16px; color: #8E44AD ;">' . $model->sub_description . '</span>';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {download}', // เพิ่มปุ่ม download and view
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        // ... Existing code for the 'view' button ...
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('แก้ไข', ['agendasubs/update', 'id' => $model->sub_id], ['class' => 'btn btn-warning ']);
                                        Html::a('กลับไปยังหน้า Meeting Agenda', ['meetingagenda/view', 'id' => $model->meeting_id], ['class' => 'btn btn-primary']);
                                    },
                                    'download' => function ($url, $model) {
                                        $fileExtension = pathinfo($model->filename, PATHINFO_EXTENSION);
                                        $isPdf = strtolower($fileExtension) === 'pdf';
                                        if (!$isPdf) {
                                            $linkText = 'เอกสาร (' . strtoupper($fileExtension) . ')';
                                            // Use the 'download' action to get the correct URL for downloading other file types
                                            return Html::a($linkText, ['agendasubs/download', 'id' => $model->sub_id, 'filename' => $model->filename], ['class' => 'btn btn-success ']);
                                           // $url = Url::to(['agendasubs/download', 'id' => $model->sub_id, 'filename' => $model->filename]);
                                            //return Html::a('Download File', ['agendasubs/download', 'id' => $model->sub_id, 'filename' => $model->filename], ['class' => 'btn btn-success']);
                                            //return Html::a($model->filename, ['agendasubs/download', 'id' => $model->sub_id, 'filename' => $model->filename], ['class' => 'btn btn-success']);
                                           // return Html::a('เอกสารประกอบ', $url, ['class' => 'btn btn-success']);
                                        } else {
                                            return Html::a('View', ['agendasubs/view', 'id' => $model->sub_id], ['class' => 'btn btn-primary']) . ' (PDF)';
                                        }
                                    },
                                ],
                            ],
                        ],
                       // 'showHeader' => false, // ไม่แสดงส่วนหัวของ GridView
                    ]);
                } else {
                    return '';
                }
            },
        ],
    ],
]) ?>