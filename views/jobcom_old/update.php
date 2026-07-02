<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Jobcom */

$this->title = 'แก้ไขแจ้งซ่อม:';
$this->params['breadcrumbs'][] = ['label' => 'ระบบแจ้งซ่อม', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jobcom-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
