<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = Yii::t('app', 'นัดมารับบริการ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'การนัด'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="-view"><div class="box box-success box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
