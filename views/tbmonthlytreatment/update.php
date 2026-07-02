<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tbmonthlytreatment */

$this->title = Yii::t('app', 'แก้ไขข้อมูล สูตรคำนวณยา: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbmonthlytreatments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<style>
/* ปิด Left Menu และขยายพื้นที่ content */
.sidebar,
.main-sidebar,
.sidebar-menu {
    display: none !important;
}

.content-wrapper, .content {
    margin-left: 0 !important;
}
</style>
<div class="tbmonthlytreatment-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
