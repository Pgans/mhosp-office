<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */

$this->title = Yii::t('app', 'แก้ไข รายการส่งซ่อม: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobcoms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="แก้ไข รายการส่งซ่อม">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
