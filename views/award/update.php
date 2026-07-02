<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\award */

$this->title = 'แก้ไขรางวัล: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'รางวัลAward', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="award-create">
    <div class="panel panel-success">
                        <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> แก้ไข</h4></div>
                        <div class="panel-body">
                        <div class="row">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
