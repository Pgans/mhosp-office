<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agendasubs */

$this->title = Yii::t('app', 'Update วาระการประชุม: {name}', [
    'name' => $model->sub_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendasubs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sub_id, 'url' => ['view', 'id' => $model->sub_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="box box-default box-solid" >
    <div class="box-header" id="grad8">
        <div class="box-title"> E-Meeting<small> MuangSamSib Hospital</small></div>
    </div>
    <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
