<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agendaitem */

$this->title = Yii::t('app', 'Update Agendaitem: {name}', [
    'name' => $model->agenda_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendaitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->agenda_id, 'url' => ['view', 'id' => $model->agenda_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agendaitem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
