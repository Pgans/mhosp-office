<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agendaitem */

$this->title = Yii::t('app', 'Create Agendaitem');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendaitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendaitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
