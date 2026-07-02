<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Positions */

$this->title = Yii::t('app', 'Create Positions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary box-solid">
<div class="positions-create">
<div class="well">
   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
