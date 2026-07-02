<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Orderoils */

$this->title = Yii::t('app', 'Create Orderoils');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orderoils'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orderoils-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
