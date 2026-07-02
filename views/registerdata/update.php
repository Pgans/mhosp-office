<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Registerdata */

$this->title = 'Update Registerdata: ' . $model->ECLAIM_NO;
$this->params['breadcrumbs'][] = ['label' => 'Registerdatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ECLAIM_NO, 'url' => ['view', 'ECLAIM_NO' => $model->ECLAIM_NO, 'HCODE' => $model->HCODE, 'HN' => $model->HN, 'DATEADM' => $model->DATEADM, 'TIMEADM' => $model->TIMEADM, 'PERSON_TYPE' => $model->PERSON_TYPE, 'PID' => $model->PID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="registerdata-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
