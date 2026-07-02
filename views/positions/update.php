<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Positions */

$this->title = Yii::t('app', 'แก้ไข: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ตำแหน่ง'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="box box-wraning box-solid">
<div class="positions-update">
<div class="well">
   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
