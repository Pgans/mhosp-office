<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Permits */

$this->title = 'แก้ไขการยืมเวชระเบียน: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Permits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="box box-warning box-solid">
  <div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-home"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<!--<div class="permits-update">

    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>