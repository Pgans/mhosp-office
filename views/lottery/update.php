<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lottery */

$this->title = 'Update Lottery: ' . $model->lottery_id;
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lottery_id, 'url' => ['view', 'id' => $model->lottery_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lottery-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
