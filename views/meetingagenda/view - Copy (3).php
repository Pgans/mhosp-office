
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
        color: #0FB179; /* Replace with your desired color value */#007C80
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
   
<!-- <h3>Agenda Items:</h3> -->

<!-- <?php foreach ($meetingAgenda->uploadfiles as $uploadfile) : ?>
        <li>
            <h2><?= Html::encode($uploadfile->topics)  ?>: </h2><h4><?= Html::encode($uploadfile->description)  ?>:</h4>
        <!-- <h2><?= Html::encode($agendaItem->topic)  ?>: </h2><h3><?= Html::encode($agendaItem->discription)  ?>: </h3> -->
           <h4><?= Html::encode($uploadfile->key_point)  ?>: </h4> <h5><?= Html::encode($uploadfile->show_work) ?>:</h5>
            
            <?php if (!empty($uploadfile->filename)) : ?>
                <?= Html::a('เอกสารประกอบ', $uploadfile->filename, ['target' => '_blank']) ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>  -->

<?php foreach ($meetingAgenda->agendasubs as $uploadfile) : ?>
        <li>
            <h2><?= Html::encode($uploadfile->topics)  ?>: </h2><h4><?= Html::encode($uploadfile->description)  ?>:</h4>
        <!-- <h2><?= Html::encode($agendaItem->topic)  ?>: </h2><h3><?= Html::encode($agendaItem->discription)  ?>: </h3> -->
           <h4><?= Html::encode($uploadfile->key_point)  ?>: </h4> <h5><?= Html::encode($uploadfile->show_work) ?>:</h5>
            
            <?php if (!empty($uploadfile->filename)) : ?>
                <?= Html::a('เอกสารประกอบ', $uploadfile->filename, ['target' => '_blank']) ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul> 