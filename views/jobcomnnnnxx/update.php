<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Jobcom */

$this->title = Yii::t('app', 'แก้ไขการแจ้งซ่อม:', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ระบบแจ้งซ่อม'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="panel panel-info">
                        <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> แจ้งการส่งซ่อม</h4></div>
                        <div class="panel-body">
                        <div class="row">
    <!-- <div class="jobcom-update"> -->

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
