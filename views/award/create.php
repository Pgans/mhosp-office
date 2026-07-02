<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\award */

$this->title = 'เพิ่มรางวัล';
$this->params['breadcrumbs'][] = ['label' => 'รางวัลAward', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="award-create">
    <div class="panel panel-success">
                        <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> เพิ่มรางวัลดีเด่น ยอดเยี่ยม</h4></div>
                        <div class="panel-body">
                        <div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
