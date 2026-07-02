<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Import */

$this->title = 'แก้ไขไฟล์';
$this->params['breadcrumbs'][] = ['label' => 'Imports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box box-info box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="import-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
