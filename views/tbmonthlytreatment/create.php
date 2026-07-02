<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tbmonthlytreatment */

$this->title = Yii::t('app', 'Create Tbmonthlytreatment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbmonthlytreatments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbmonthlytreatment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
