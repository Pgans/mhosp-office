<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Jobcom */



$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ระบบแจ้งซ่อม'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-info">
                        <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> แจ้งการส่งซ่อม</h4></div>
                        <div class="panel-body">
                        <div class="row">
<div class="jobcom-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
